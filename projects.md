---
layout: page
title: Projects
permalink: /projects/
comments: false
author_footer: false
---

Overview of my most important personal projects. Headers are links. Feedback or help is welcome on all of those.

## [Dict](https://github.com/stephandollberg/dict)

Cache friendly open addressed hash table implementation in C++. `dict` is supposed to be a drop-in replacement for `std::unordered_map`. Its goal is to offer a performance boost while providing an almost standard conforming interface.

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

## [Yotta](https://github.com/StephanDollberg/yotta)

Yotta is basic http file server. It serves my personal blog [dollberg.xyz](https://dollberg.xyz).

It's main purpose is to build an epoll based event loop with all kinds of gimmicks. In addition, it's a performant file server that doesn't need 50 lines of config.

## [JWT Middleware for Go-Json-Rest](https://github.com/StephanDollberg/go-json-rest-middleware-jwt)

This is a middleware for the Go-JSON-REST framework that provides JSON-Web-Token  authentication. Its goal is to stay simple and not require too much configuration.

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

