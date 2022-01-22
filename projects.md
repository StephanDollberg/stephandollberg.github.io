---
layout: page
title: Projects
permalink: /projects/
comments: false
author_footer: false
---

Overview of some of my projects. See Github for the full collection.

## [rredis](https://github.com/StephanDollberg/rredis)

Something redis like (very far from it obviously). Some more Rust training and me playing around with io\_uring.

Currently supports GET and SET and also has async command-logging to disk all powered by io\_uring.

## [Yotta](https://github.com/StephanDollberg/yotta)

Yotta is a basic http file server written in C++ and C.

It's main purpose is to get some raw epoll action going with all kinds of things such as graceful upgrades. In addition, it's a performant file server that doesn't need 50 lines of config.

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

