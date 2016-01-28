---
layout: page
title: Projects
permalink: /projects/
comments: true
author_footer: false
---

Overview of my most important personal projects. Headers are links. Feedback or help is welcome on all of those.

## [Dict](https://github.com/stephandollberg/dict)

Cache friendly open addressed hash table implementation in C++. `dict` is supposed to be a drop-in replacement for `std::unordered_map`. It's goal is to offer a performance boost while providing an almost standard conforming interface.

Currently, we see a speedup of at least a factor of two in almost all tests (in some even more). The only test in which we perform worse is the case of very heavy clustering and lookup failure.

Ongoing work is looking into advanced hashing techniques such as [Robin Hood Hashing](http://www.sebastiansylvan.com/post/robin-hood-hashing-should-be-your-default-hash-table-implementation/) and other optimizations.

{% highlight cpp %}
#include <iostream>
#include <dict/dict.hpp>

int main() {
  io::dict<std::string, int> worldcups{ {"Germany", 4},
                                        {"Brazil", 5},
                                        {"France", 1}};

  std::cout << worldcups["Germany"] << " stars for Germany!" << std::endl;
}

{% endhighlight %}

Outputs: `4 stars for Germany!`

## [Vyo.be](https://vyo.be)

I was annoyed by constantly getting rickrolled by URL shorteners. `Vyo.be` keeps the domain visible on shortening. So no more rickrolling!

For example, shortening a link to one of the blog posts [`https://dollberg.xyz/web/2016/01/28/Static-Site-Hosting-With-Github-Pages-And-Cloudflare/`](https://dollberg.xyz/web/2016/01/28/Static-Site-Hosting-With-Github-Pages-And-Cloudflare/) results in [`vyo.be/dollberg9cg8p`](vyo.be/dollberg9cg8p). We see that the domain name stays visible and some random chars are appended.

Vyo is written in Go.

## [JWT Middleware for Go-Json-Rest](https://github.com/StephanDollberg/go-json-rest-middleware-jwt)

This is a middleware for the Go-JSON-REST framework that provides JSON-Web-Token  authentication. It's goal is to stay simple and not require too much configuration.

Configuring the middleware is as simple as:

{% highlight go %}
    jwt_middleware := &jwt.JWTMiddleware{
        Key:        []byte("secret key"),
        Realm:      "jwt auth",
        Timeout:    time.Hour,
        MaxRefresh: time.Hour * 24,
        Authenticator: func(userId string, password string) bool {
            return /* auth logic */
        }}
{% endhighlight %}

We also provide ready made handlers for logging in and refreshing tokens so that the user does not have to bother with creating tokens.

Currently, we only support the HMAC alogrithms. There is an update planned for the JWT library that we are using which will probably break some code and at that point we might expand the middleware to also support the asymmetric keys.

