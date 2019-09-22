---
layout: post
title: "CppCon 2019 trip report"
category: C++
comments: true
---


Another nice CppCon, though I had the feeling there was better talks last year (at least more I was interested in). Also happy to hear that there finally seems to start some initiative to get more aggressive on deprecating features and focusing more on performance over backwards-compatibility.

Short review of the talks I watched. List ordered chronologically.

<!-- more -->

## C++20: C++ at 40 - Bjarne Stroustrup - [link](https://www.youtube.com/watch?v=u_ij0YNkFUs)
Classic Bjarne opening keynote. Key idea is to keep all C++ features coherent. Nothing really new or interesting to see here.

## The C++20 Synchronization Library - Bryce Adelstein Lelbach

Good walkthrough through all the new synchronization features in C++20:
 - `std::jthread`: Thread which joins in the destructor and also supports stop requesting
 - `std::atomic_ref`: Use a non-atomic in an atomic manner (e.g.: if passed in through legacy C API)
 - `std::atomic` API additions: `std::atomic` got a `wait` method added which makes it basically work like a mutex on which you block. Idea is that this might be implementable more efficiently though that is not entirely clear to me yet and it feels like this got mainly added for platforms such as CUDA.
 - `std::latch`/`std::barrier/std::counting_semaphore`: Classic synchronization primitives now in standard C++

## What Every Programmer Should Know About Memory Allocation - Samy Al Bahra, Hannes Sowa, Paul Khuong

Talk about allocators. Goes into various directions comparing glibc malloc, jemalloc and tcmalloc. Covers things like fragmentation, arenas and multithreaded scaling.

Good intro.

## High performance graphics and text rendering on the GPU for any C++ application - Barbara Geller, Ansel Sermersheim

Not really what I am usually into but I finally learned what a shader is so not too bad.

## Preventing Spectre One Branch at a Time: The Desing and Implementation of Fine Grained Spectre v1 Mitigation APIs - Zola Bridges, Devin Jeanpierre

Yet another Spectre talk. Good rehash of the Spectre v1 vulnerability. Also covers mitigation techniques and goes into lower level detail.

Worth watching given that this category of problems will probably keep us busy for quite a while.

## Committee Fireside Chat

Monday night session. Fireside chat with quite a lot of people. A few good questions in there.

One interesting thing I learned is that there is work by Vittorio Romeo on introducing Rust like editions into C++. This would be quite nice as it would allow us to easier deprecate things. Something I am quite into. See [link](https://vittorioromeo.info/index/blog/fixing_cpp_with_epochs.html) for more info.

## Will Your Code Survive the Attack of the Zombie Pointers? - Michael wong, Paul Mc Kenney, Maged Michael

Interesting talk about yet another stupid feature of C++. Basically the standard says that pointers become invalid as soon as what they point to goes away. For example, goes out of scope or gets freed. This means using the pointers - for comparisons for example - is undefined behaviour.

Example (simple single threaded one, many more concurrent ones exist):

```
auto ptr_before = ptr_;
ptr_ = realloc(...);
if (ptr_ != ptr_before) { // UB
    update_some_state(...)
}
```

Talk outlined different techniques which are currently under discussion of how to potentially fix this.

## Speed Is Found In The Minds of People - Andrei Alexandrescu - [link](https://www.youtube.com/watch?v=FJJTYQYB1JQ)

Second keynote. As always good talk by Andrei and a must-watch. He makes the point that for maximum performance we should write less generic code but also need more customizability points in the language (Reflection!) to get more insight into properties of structs (e.g.: how expensive is comparing, swapping, moving etc.). This again would then allow us to keep our libraries generic at the same time as allowing maximum performance.

Also features a bunch of nice performance tricks (goto, infinite loops and less-branching).

## When C++ Zero-Cost Abstratction fails: how to fix your compiler - Adrien Guinet

Using the example of a C++ code generator shows how clang fails to vectorize some generic C++ code (zip iterators). Definitely a lesson to keep in mind though there is probably better examples to show this.

## C++ Standard Librar "Little Things" - Billy O'Neil

Collection of smaller less talked about additions to C++ in the latest standards. Worth watching.

## There are No Zero-cost Abstractions - Chandler Carruth

Talk by Chandler about abstractions. His point is that an abstraction always costs something, that might be at compile time, runtime or write time.

He goes through three examples. Most prominent one is the `std::unique_ptr` is not zero-overhead one. This was previously explained on llvm-dev in quite detail: https://lists.llvm.org/pipermail/llvm-dev/2017-June/113607.html

To recap this is because of:
 - We don't have destrucive moves (dtor of moved-from class still needs to be called)
 - ABI doesn't allow effective passing (see link above)

While nothing new to me it's something that annoys me a lot as I think this is one of the real problems C++ has and something we should focus on (Rust for example has destructive moves by definition and no specified ABI). Feels good to see that more people finally get annoyed by this.

## Lightning talks

Lightning talks evening session, few funny ones in there.

## SG14 Meeting

Joined (most of) the SG14 meeting on Wednesday again. A few different papers were discussed. There was lots of talk about Linear Algebra which I am less interested in.

One interesting paper draft (D1605)  that was discussed is "Member Layout Control". The feature it wants to standardize is the ability to specify the layout of class data members in memory. Currently members are layed out as they are defined in code (grouped by access specifier).

This can be a problem if you for example want to group related variables in code next to each other to have a common explaining code:

```
class foo {
    // some good comment explaing feature A
    int feature_a_value;
    bool feature_a_flag;

    // some good ocmment explaining feature B

    int feature_b_value;
    bool feature_b_flag;
};
```

This layout has suboptimal size given the padding between the bool values. Moving the bools together would fix this but the comments would no longer be grouped.

The paper proposes an extra layout section in classes which separately would allow to specify how members are layed out in memory.

While this proposal would definitley be useful the author mentioned a related proposal which is currently being discussed and I wasn't aware of - P1112 - "Language support for class layout control Language support for class layout control".

This proposal suggests something related where one would be able to tag a class with attributes such as `[[layout(smallest)]]` or `[[layout(best)]]`. These would signify to the compiler that we are no longer interested in ABI compatibility and that it's allowed to reorder members at its will for better performance or size. This is something which would be quite nice because it could also be driven by PGO data and we could such get optimial layout based on instrumentation data.

## Applied WebAssembly: Copmiling and Running C++ in Your Web Browser - Ben Smith - [link](https://www.youtube.com/watch?v=5N4b-rU-OAA)

Keynote of the day, showed how we can run clang in the browser using WebAssembly similar to using Emscripten and friends.

Might have been nice for a 30 minute talk but not sure how this got selected for a 90 minute keynote. Not really worth watching.

## Abseil's Open Source Hashtables: 2 Years In

Follow on to previous abseil swiss-table talks. Goes through more Hyrum's law cases and improvements that were made since then.

Definitely worth watching if into hash-tables.

## Lightning talks

Second Lightning talks evening session. Not that funny today.

## This Videogame Programmer used the STL and you will never guess what happened - Mathieu Ropert

Talk about how the standard library can actually be used for game dev. Nothing new here really and I also disagree with a few of the mentioned points.

## Better Code: Relationships - Sean Parent - [link](https://www.youtube.com/watch?v=ejF6qqohp3M)

Another kind of boring keynote. Some talk about relationships. Not really sure what to take home from this.

## The C++ ABI for Dummies - Louis Dionne

Good intro talk into the Itanium C++ ABI. My favorite C++ pet peeve as we could have many more nice things if it wasn't for ABI stability. Louis and a few other libc++ guys which I talked to after had a similar sentiment that more often than not we actually don't really need ABI stability so hopefully things improve here in the future.

## Generators, Coroutines and Other Brain Unrolling Sweetness - Adi Shavit

30 minute talk about coroutines. Good refresher on what's coming. Made me aware of a coroutine support library which will definitley be needed given that the standard doesn't ship with any library support at all: https://github.com/lewissbaker/cppcoro

## Path Tracing Three Ways: A Study of C++ Style - Matt Godbolt

Good talk by Matt Godbolt about building a path tracer in three different programming styles (OOP, FP and data-driven). Even if it doesn't show anything new it's still worth watching as it's quite informative (data-driven design wins) and has a few cool pics.

## Lightning Talks

Last evening Lighting talk session. A few funny ones on this one.

## Faster Compile Times and Better Performance: Bringing Just-in-Time Compilation to C++ - Hal Finkel

Not really what I expected. Presenter talks about his JIT library showing performance characteristics. Take away is that a C++ JIT is already possible but more work needed.

## Deprecating Volatile - JF Bastien

Talk by JF about the state of `volatile`. Gives a bit of history of where volatile comes from, what it's supposed to be used for (make the compiler not touch a value) and common misunderstandings. He wants to remove the usecases from the standard that are confusing. Some of which are already getting removed in C++20.

## Modern Linux C++ debugging tools under the covers - Greg Law

Nothing really new here. Run through basic tools. New thing I learned is that the `-g` flag actually also takes a level with `-g2` being the default. `-g3` apparently even allows debugging macros.

## What is C++ - Chandler Carruth, Titus Winters

Talk first gave insight into where C++ is coming from (C obviously) then turned into a critique of the current C++ model of prioritizing backwards compatibility before other things. Instead, they suggest we should focus on performance and simplicity as otherwise C++ might become obsolete.

As mentioned above, this is one of my major concerns with C++ and such I am happy more people are getting the same feeling.

## Defragmenting C++: Making Exceptions and RTTI More Affordable and Usable - Herb Sutter

Closing keynote, Herb presenting his papers about true zero-overhead exceptions (and RTTI). Idea is to make exceptions not be polymorphic via a std::error class.

This would allow implementing exceptions as if one would just return an Expected/Outcome error object. At the same time, the programming style would stay the same and would allow having the best of both worlds - performance of error objects but program flow of exceptions. Mentions a few related papers such as making `std::bad_alloc` terminate by default.

Quite like this one as it solves concrete problems and improves performance instead of adding useless new features. Seems like more people understand now that C++ is becoming bloated and slow.

