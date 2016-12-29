<?php
    include "credentials/redis";

    session_start();
    $DEBUG = false;

    if ($DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }


    require "lib/predis/autoload.php";
    Predis\Autoloader::register();

    try {
        $redis = new Predis\Client(array("password" => $REDIS_PASS));
    }
    catch (Exception $e) {
        die($e->getMessage());
    }

    function register_user($username, $password) {
        global $redis; 

        $user_id = $redis->hget("users", $username);
        debug("haha". $user_id);
        if (!$user_id) {
            $user_id = $redis->incr("user_idx");
            $redis->hset("users", $username, $user_id);
            $redis->hset("user:$user_id", "username", $username);
            $redis->hset("user:$user_id", "password", $password);
            $redis->hset("user:$user_id", "pic", "profiles/default.jpg");

            $redis->hset("users", $username, $user_id);
            return TRUE;
        }
        return FALSE;
    }

    function verify_password($username, $password) {
        global $redis;

        $user_id = $redis->hget("users", $username);
        if ($user_id) {
            $real_pass = $redis->hget("user:$user_id", "password");
            return $user_id;
        }
        return FALSE;
    }

    function get_user($user_id) {
        global $redis;

        $username = $redis->hget("user:{$user_id}", "username");
        $fname = $redis->hget("user:{$user_id}", "fname");
        $lname = $redis->hget("user:{$user_id}", "lname");
        $bio = $redis->hget("user:{$user_id}", "bio");
        $pic = $redis->hget("user:{$user_id}", "pic");

        return array("username" => $username,
            "fname" => $fname,
            "lname" => $lname,
            "bio" => $bio,
            "pic" => $pic);
    }


    function update_user($fname, $lname, $bio, $pic = NULL) {
        global $redis;

        if (logged_in() && !is_admin()) {
            $redis->hset("user:{$_SESSION["id"]}", "fname", $fname);
            $redis->hset("user:{$_SESSION["id"]}", "lname", $lname);
            $redis->hset("user:{$_SESSION["id"]}", "bio", $bio);
            if ($pic) {
                $redis->hset("user:{$_SESSION["id"]}", "pic", $pic);
            }
        }
    }

    function is_item_owner($item_id) {
        global $redis;

        $owner = $redis->hget("item:{$item_id}", "user_id");
        return ($owner == $_SESSION["id"]);
    }

    function is_admin() {
        return $_SESSION["id"] === "1";
    }

    function is_list_owner($list_id) {
        global $redis;

        $owner = $redis->hget("list:{$list_id}", "user_id");
        return ($owner == $_SESSION["id"]);
    }

    function update_item($name, $details, $item_id) {
        global $redis;

        if (!is_admin() && logged_in() && is_item_owner($item_id)) {
            $redis->hset("item:{$item_id}", "name", $name);
            $redis->hset("item:{$item_id}", "details", $details);

            return TRUE;
        }
        return FALSE;
    }

    function get_lists() {
        global $redis;

        if (logged_in()) {
            $list_ids = $redis->lrange("lists:{$_SESSION["id"]}", 0, -1);

            $results = array();
            foreach($list_ids as $list_id) {
                array_push($results, get_list_details($list_id));
            }

            return $results;
        }
        return FALSE;
    }

    function get_list_details($list_id) {
        global $redis;

        if (logged_in() && is_list_owner($list_id)) {
            $name = $redis->hget("list:{$list_id}", "name");
            $description = $redis->hget("list:{$list_id}", "description");
            $share_link = $redis->hget("list:{$list_id}", "share_link");
            $user_id = $redis->hget("list:{$list_id}", "user_id");

            return array("id" => $list_id, "user_id" => $user_id, "name" =>$name, "description"=>$description, "share_link"=>$share_link);
        }

        return FALSE;
    }

    function resolve_share($share_id) {
        global $redis;

        if (logged_in()) {
            $list_id = $redis->hget("shares", $share_id);
            return $list_id;
        }
        return FALSE;
    }

    function create_list($name, $description) {

        global $redis;

        if (logged_in() && !is_admin()) {
            $list_id = $redis->incr("list_idx");
            $redis->hset("list:{$list_id}", "user_id", $_SESSION["id"]);
            $redis->hset("list:{$list_id}", "name", $name);
            $redis->hset("list:{$list_id}", "description", $description);
            $redis->hset("list:{$list_id}", "share_link", "");

            $redis->rpush("lists:{$_SESSION["id"]}", $list_id);
            return $list_id;
        }
        return FALSE;
    }

    function get_list_items($list_id) {
        global $redis;

        if (logged_in() && is_list_owner($list_id)) {
            $item_ids = $redis->lrange("items:{$list_id}", 0, -1);

            $results = array();
            foreach($item_ids as $item_id) {
                array_push($results, get_item($item_id));
            }

            return $results;
        }
        return FALSE;
    }

    function get_item($item_id) {
        global $redis;

        if (logged_in() && is_item_owner($item_id)) {
            $name = $redis->hget("item:{$item_id}", "name");
            $details = $redis->hget("item:{$item_id}", "details");
            $list_id = $redis->hget("item:{$item_id}", "list_id");

            return array("id" => $item_id, "name" => $name, "details" => $details, "list_id" => $list_id);
        }

        return FALSE;
    }

    function delete_item($item_id) {
        global $redis;

        if (logged_in() && is_item_owner($item_id) && !is_admin()) {
            $list_id = get_item($item_id)["list_id"];
            $redis->hdel("item:{$item_id}", "user_id");
            $redis->hdel("item:{$item_id}", "list_id");
            $redis->hdel("item:{$item_id}", "name");
            $redis->hdel("item:{$item_id}", "details");
            $redis->lrem("items:{$list_id}", 0, $item_id);

            return TRUE;
        }
        return FALSE;
    }

    function update_list($name, $description, $list_id) {
        global $redis;

        if (logged_in() && is_list_owner($list_id) && !is_admin()) {
            $redis->hset("list:{$list_id}", "name", $name);
            $redis->hset("list:{$list_id}", "description", $description);

            return TRUE;
        }

        return FALSE;
    }

    function delete_list($list_id) {
        global $redis;

        if (logged_in() && is_list_owner($list_id) && !is_admin()) {
            $items = get_list_items($list_id);
            foreach($items as $item) {
                delete_item($items);
            }

            $redis->hdel("list:{$list_id}", "name");
            $redis->hdel("list:{$list_id}", "description");
            $redis->hdel("list:{$list_id}", "share_link");
            $redis->hdel("list:{$list_id}", "user_id");
            $redis->lrem("lists:{$_SESSION["id"]}", 0, $list_id);

            return TRUE;
        }
        return FALSE;
    }

    function copy_item($item_id, $target_list) {
        global $redis;

        if (logged_in() && is_list_owner($target_list) && !is_admin()) {
            $item_name = $redis->hget("item:{$item_id}", "name");
            $item_details = $redis->hget("item:{$item_id}", "details");

            return create_list_item($item_name, $item_details, $target_list);
        }
        return FALSE;

    }

    function create_list_item($name, $details, $list_id) {
        global $redis;

        if (logged_in() && is_list_owner($list_id) && !is_admin()) {
            $item_id = $redis->incr("item_idx");
            $redis->hset("item:{$item_id}", "user_id", $_SESSION["id"]);
            $redis->hset("item:{$item_id}", "list_id", $list_id);
            $redis->hset("item:{$item_id}", "name", $name);
            $redis->hset("item:{$item_id}", "details", $details);

            $redis->rpush("items:{$list_id}", $item_id);
            return TRUE;
        }
        return FALSE;
    }

    function debug($str) {
        global $DEBUG;
        if ($DEBUG) {
            echo "<p style='color:red'>{$str}</p>";
        }
    }

    function logged_in() {
        return $_SESSION["logged_in"];
    }

    function require_login() {
        if (!logged_in()) {
            header("Location: /?page=login");
            exit;
        }
    }

    function load_user_details() {
        global $db;
        if (logged_in()) {
            $user = get_user($_SESSION["id"]);

            $_SESSION["username"] = $user["username"];
            $_SESSION["fname"] = $user["fname"];
            $_SESSION["lname"] = $user["lname"];
            $_SESSION["bio"] = $user["bio"];
            $_SESSION["pic"] = $user["pic"];
        }
    }

    function in_cidr($cidr, $ip) {
        list($prefix, $mask) = explode("/", $cidr);

        return 0 === (((ip2long($ip) ^ ip2long($prefix)) >> $mask) << $mask);
    }

    function get_contents($url) {
        $disallowed_cidrs = [ "127.0.0.1/24", "169.254.0.0/16", "0.0.0.0/8" ];

        do {
            $url_parts = parse_url($url);

            if (!array_key_exists("host", $url_parts)) {
                die("<p><h3 style=color:red>There was no host in your url!</h3></p>");
            }

            $host = $url_parts["host"];

            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ip = $host;
            } else {
                $ip = dns_get_record($host, DNS_A);
                if (count($ip) > 0) {
                    $ip = $ip[0]["ip"];
                    debug("Resolved to {$ip}");
                } else {
                    die("<p><h3 style=color:red>Your host couldn't be resolved man...</h3></p>");
                }
            }

            foreach ($disallowed_cidrs as $cidr) {
                if (in_cidr($cidr, $ip)) {
                    die("<p><h3 style=color:red>That IP is a blacklisted cidr ({$cidr})!</h3></p>");
                }
            }

            // all good, curl now
            debug("Curling {$url}");
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 3);
            curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_ALL 
                & ~CURLPROTO_FILE 
                & ~CURLPROTO_SCP); // no files plzzz
            curl_setopt($curl, CURLOPT_RESOLVE, array($host.":".$ip)); // no dns rebinding plzzz

            $data = curl_exec($curl);

            if (!$data) {
                die("<p><h3 style=color:red>something went wrong....</h3></p>");
            }

            if (curl_error($curl) && strpos(curl_error($curl), "timed out")) {
                die("<p><h3 style=color:red>Timeout!! thats a slowass  server</h3></p>");
            }

            // check for redirects
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status >= 301 and $status <= 308) {
                $url = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
            } else {
                return $data;
            }

        } while (1);
    }

    function random_shit_quote() {
        $quotes = array( 
        "<p>The original lists were probably carved in stone and represented longer periods of time. They contained things like 'Get More Clay. Make Better Oven.'</p><footer>David Viscott</footer>",
        "<p>The human animal differs from the lesser primates in his passion for lists.  </p>
        <footer>H. Allen Smith</footer>",
        "<p>I'm controlling, and I want everything orderly, and I need lists. My mind goes a mile a minute. I'm difficult on every single level.</p><footer>Sandra Bullock</footer>",
        "<p>Lists today are a way of trying to get through the day, because we are losing a sense of time.</p><footer>David Viscott</footer>",
        "<p>I'm very much into making lists and breaking things apart into categories.</p><footer>David Byrne</footer>",
        "<p>We like lists because we don't want to die.</p><footer>Umberto Eco</footer>",
        "<p>People now feel time accelerating. Lists allow them to feel some sense of accomplishment.</p><footer>David Viscott</footer>",
        "<p>Lists have always implied social order.</p><footer>David Viscott</footer>");
        return $quotes[array_rand($quotes)];
    }

    function shia() {
        $shia = array(
            "Do it!",
            "Just do it!",
            "Yesterday, you said tomorrow!",
            "Make your dreams come true!",
            "You gonna wake up and stop giving up!",
            "What are you waiting for?",
            "You gonna wake up and work hard at!",
            "Nothing is impossible!"
        );
        return $shia[array_rand($shia)];
    };


    function is_image($img) {
        $info = @getimagesizefromstring($img);
        return ($info !== FALSE) &&
                (($info[2] !== IMAGETYPE_GIF) ||
                ($info[2] !== IMAGETYPE_JPEG) ||
                ($info[2] !== IMAGETYPE_PNG));
    }


    if (!isset($_SESSION["logged_in"])) {
        $_SESSION["logged_in"] = false;
    } 
    load_user_details();

