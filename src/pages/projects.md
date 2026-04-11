---
layout: ../layouts/PageLayout.astro
title: Projects
description: A short overview of Stephan Dollberg's personal projects.
---

Overview of my most important personal projects. Headers are links. Feedback or help is welcome on all of those.

## [Dict](https://github.com/stephandollberg/dict)

Cache friendly open addressed hash table implementation in C++. `dict` is supposed to be a drop-in replacement for `std::unordered_map`. Its goal is to offer a performance boost while providing an almost standard conforming interface.

Currently, we see a speedup of at least a factor of two in almost all tests, and in some even more. The only test in which we perform worse is the case of very heavy clustering and lookup failure.

Ongoing work is looking into advanced hashing techniques such as [Robin Hood Hashing](http://www.sebastiansylvan.com/post/robin-hood-hashing-should-be-your-default-hash-table-implementation/) and other optimizations.

```cpp
#include <iostream>
#include <dict/dict.hpp>

int main() {
  io::dict<std::string, int> worldcups{{"Germany", 4},
                                       {"Brazil", 5},
                                       {"France", 1}};

  std::cout << worldcups["Germany"] << " stars for Germany!" << std::endl;
}
```

Outputs: `4 stars for Germany!`

## [Yotta](https://github.com/StephanDollberg/yotta)

Yotta is a basic HTTP file server. It serves my personal blog [dollberg.xyz](https://dollberg.xyz).

Its main purpose is to build an epoll-based event loop with all kinds of gimmicks. In addition, it is a performant file server that does not need 50 lines of config.

## [JWT Middleware for Go-Json-Rest](https://github.com/StephanDollberg/go-json-rest-middleware-jwt)

This is a middleware for the Go-JSON-REST framework that provides JSON Web Token authentication. Its goal is to stay simple and not require too much configuration.

Configuring the middleware is as simple as:

```go
jwt_middleware := &jwt.JWTMiddleware{
    Key:        []byte("secret key"),
    Realm:      "jwt auth",
    Timeout:    time.Hour,
    MaxRefresh: time.Hour * 24,
    Authenticator: func(userId string, password string) bool {
        return /* auth logic */
    },
}
```

We also provide ready made handlers for logging in and refreshing tokens so that the user does not have to bother with creating tokens.

Currently, we only support the HMAC algorithms. There is an update planned for the JWT library that we are using which will probably break some code and at that point we might expand the middleware to also support asymmetric keys.

