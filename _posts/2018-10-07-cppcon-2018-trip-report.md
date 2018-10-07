---
layout: post
title: "CppCon 2018 trip report"
category: C++
comments: true
---


Overall CppCon was a really nice conference with lots of good talks from lots of different application domains. There is still quite a few talks which I missed because they overlapped with other talks. Will catch up with those once their recordings have been uploaded.

Short review of the talks I watched. List ordered chronologically.

<!-- more -->

## Concepts: The Future of Generic Programming (the future is here) - Bjarne Stroustrup - [link](https://www.youtube.com/watch?v=HddFGPTAmtU)
Opening keynote from Bjarne about Concepts and keeping C++ simple. Nothing major new from this talk. Concepts lite is on track for 20, short concepts syntax will probably not make it.

## The C++ Execution Model - Bryce Adelstein Lelbach - [link](https://www.youtube.com/watch?v=FJIn1YhPJJc)

Covered the C++ execution model. Explained concepts such as "sequence points, "order of evaluation", "happens before relation ship" and "forward progress". It's a good refresher on those points. 

The Forward Progress concept was new to me. Shortly explained (and probably omitting lots of detail) it means that implementations have to guarantee that progress is always guaranteed. Taking a `std::async` implementation based on a threadpool as an example. Let's say the backing threadpool has a single thread and one dispatches a function to it which yet again dispatches functions on the threadpool, the implementation has to assure that in such a scenario no deadlock appears (for example by executing the newly dispatched functions inline if there is no free thread in the thread pool). There is obviously way more details, this talk is a good starter point if interested.

## Contract Programming in C++(20) (part 1 of 2)  - Alisdair Meredith - [link](https://www.youtube.com/watch?v=aAFRxRznVjQ)
First talk of a two parter talk about contracts. This talk mostly focused on general concepts of contracts. It doesn't go much into C++ contracts (that's what the second half is for). Would only recommend this if you care much about type theory. 

Most interesting part about this was that he had a few polls about how people feel when they see certain types in their code (int vs. unsigned, `std::string` vs. `std::string_view`). Results were often very mixed which I found very interesting as it showed big disparities in peoples opinions and the lack of clear guidance which you can find in other more strongly opinionated languages.

## High-Radix Concurrent C++  - Olivier Giroux - [link](https://www.youtube.com/watch?v=75LcDvlEIYw)
Good talk on busting common myths on what GPUs are good for. Very code heavy, builds a trie in a single thread manner, then parallelized and finally on the GPU. Also good Q&A in the end. Recommended watch.

## Expect the Expected - Andrei Alexandrescu - [link](https://www.youtube.com/watch?v=PH4WBuE1BHI)
Good talk from Andrei as usual on a future `std::expected`. If you are following the `std::expected`, `std::outcome` or `std::error` discussions on the mailing lists there is nothing really new in this talk, but it's a still an entertaining talk to watch.

## Grill the Committee  - Marshall Clow, Olivier Giroux, Howard Hinnant, Bjarne Stroustrup, Herb Sutter, Ville Voutilainen - [link](https://www.youtube.com/watch?v=cH0nJPbMFAY)
Evening panel, not sure whether these got recorded at all. Didn't learn to much new form this, lots of ranting and bikeshedding involved.

## 	minidumps: gdb-compatible, software controlled core dumps  - Matthew Fleming - TBA
Only caught the end of this. Some proprietary implementation of doing core dumps which result in smaller dump sizes than the default kernel ones.

## Source Instrumentation for Monitoring C++ in Production  - Steven Simpson - [link](https://www.youtube.com/watch?v=0WgC5jnrRx8)
I liked this talk because it focused on more practical things such as tracing and logging (only application based though) and not on any esoteric new language feature nobody really needs. It showed a few examples of how one can implement application level tracing. Though, I am not sure that is applicable to all application domains and I probably still prefer and on-demand tool based solution.

## Patterns and Techniques Used in the Houdini 3D Graphics Application - Mark Elendt - [link](https://www.youtube.com/watch?v=2YXwg0n9e7E) 
Keynote of that day about Houdini (written in C++) which is a rendering software used in the film industry. It has apparently been used in many Oscar winning movies. 

There is not that much C++ in this talk. Most of it is about how older code bases can be ported to newer standards and some tales about how this was done in Houdini.

## 	Make World: The Most Miserable Place In C++  -  Peter Bindels, Robert Maynard, Isabella Muerte, Jussi Pakkanen - TBA
Panel on build systems (there seem have been lots of talks on build systems this year). I didn't take much out of this other than there not being a single good build system (and there will never be one either).

Mostly random rants on various different build systems. Would only watch if you are really into build systems. 

## Pessimistic Programming  -  Patrice Roy - [link](https://www.youtube.com/watch?v=pnSvUbE1HHk)
This was a well presented talk on the point of "average case vs. worst case". 

It had lots of simplified examples (for example exception throwing cost) which brought the point across that one has to think about which case one is optimizing for. Certainly recommended to watch when interested in low latency application domains.

## Touring the "C++ Tip of the Week" Series  - Jon Cohen, Matt Kulukundis - [link](https://www.youtube.com/watch?v=THDpfWG5T7Y)
This was an entertainingly presented talk on a few topics of the [abseil C++ tips of the week](https://abseil.io/tips/). It's definitely worth watching if not only for entertainment purpose.

The tips which are presented are equally good. Especially liked the one about initialization (`()` vs `{}`) and the gotchas involved. It also showed one of my biggest C++ pet peeves. Getting new features wrong (even after lots of committee consideration) and making the language not more simpler but actually more difficult by introducing new special cases that people have to remember.

## Memory Tagging and how it improves C++ memory safety  - Kostya Serebryany - [link](https://www.youtube.com/watch?v=lLEcbXidK2o)
Another good talk by Kostya (main ASAN guy). Introduction on how memory tagging can be used to implement ASAN like functionality at different trade offs (memory usage vs. precision).

Also showed how future hardware architectures could make memory tagging be a viable production security layer. Definitely worth watching especially if you are into low level stuff.

## The Networking TS in Practice: Testable, Composable Asynchronous I/O in C++  - Robert Leahy - [link]
This was a very fast paced talk on the networking ts and how to effectively test code using the networking ts. It gave a few good examples of how to avoid typical network testing code which is full of sleeps etc. 

It might have been a bit early given that the networking ts is still far out (and apparently will not make it into 20 as it's being bound to the executors ts now). Probably still worth a watch as it gives an outlook of how networking ts code is going to look. I would have hoped for more code showing the coroutine style usage as I think that's very it really shines (well boost::asio already does that). 

## Lightning Talks
Watch worthy, probably not for its C++ content but rather its meme material (#westconstbestconst) and some of the facepalmy parts of C++.

## SG14 Meeting
Attended parts of the SG14 working group (low latency, game dev and embedded) meeting on Wednesday.

Was interesting to gain some insight into how those meetings take place in person. 

Most interesting paper to me was Arthur O'Dwyer's paper on "trivial relocatbility". The idea is that for types that are trivially relocatable move construction can be optimized to a memcpy. Examples of this is when containers need to grow. Instead of having to move all objects they could simply do a memcpy and not explicitly call destructors on the old range. 

See for example (very pseudo cody):

```
void vector<std::unique_ptr>::grow(Iter src, Iter end, Iter dst) {
    std::move(src, end, dst);
    delete src;
}
```

vs.

```
void vector<std::unique_ptr>::grow(Iter src, Iter end, Iter dst) {
    std::memcpy(end, src, dst - size);
    free src;
}
```

The idea is that we don't have to hope that moves will be optimized to a memcpy and also we save the explicit destructor calls. The paper only focuses on attributes / traits of objects so that library writers can make use of them. It doesn't touch on the subject of compilers being able to automatically do the same for objects which are no longer being used after move (e.g.: move an object into a function). From what I understand that's way more complicated in C++ than it is in Rust for example.

Really looking forward for that to hit the standard.

I found that a lot of bikeshedding took place in general especially on the more finished papers. That might just be me preferring "Design by Dictator" over "Design by Committee" though.

## Simplicity: not just for beginners - Kate Gregory - [link](https://www.youtube.com/watch?v=n0Ak6xtVXno) 
Wednesday keynote about "simplicity". Missed the beginning. Didn't like this talk too much. Lots of general "best practice" tips which are well known but in my opinion are hard to apply when having to deal with real world codebases. 

## Understanding Optimizers: Helping the Compiler Help You  - Nir Friedman - [link](https://www.youtube.com/watch?v=8nyq8SNUTSc)
Really good talk on a few real life examples and how the compiler can (not) optimize them.

Key take aways:
 - Compiler can't always inline if it doesn't have enough information (best to check with assembly)
 - There is a paper on the way which makes the commonly used "reinterpret_cast buffer to struct" pattern no longer UB - feature is called `std::bless`
 - `std::variant`/`std::visit` are not being optimized really well yet

## Memory Latency Troubles You? Nano-coroutines to the Rescue! (Using Coroutines TS, of Course)  - Gor Nishanov - [link](https://www.youtube.com/watch?v=j9tlJAqMV7U)
This was my favorite talk, blew my mind. Gor used nano-coroutines (very lightweight coroutines) to hide memory latency.

Given the problem of searching a database index using multiple binary searches, each step in the binary search incurred around ~60ns memory latency in his example.

The idea is to spawn multiple coroutines (for multiple different binary searches) which all use `_mm_prefetch()` to prefetch the memory of the element to which look at next in the binary search. Once `_mm_prefetch()` is called the coroutine returns back to the scheduler. The next time the coroutine gets scheduled and tries to access the current mid element of the binary search it should be already in cache. That process is then repeated. Using this "parallelization" scheme he is able to hide memory latency and increase throughput.

At the same he wrapped all this into the coroutines TS framework with better results than manually rolling it.

As mentioned above, this is a must watch talk IMO. Having only looked at coroutines from the networking side so far it was a novel usecase. Though it makes sense given that's it's just some other form of IO.

## Optimizing Code Speed and Space with Build Time Switches  - Ian Bearman, Chandler Carruth, Xiang Fan, Brett Searles, Michael Wong - TBA
Evening panel, lots of good stuff on optimization. Things that stood out:

 - Definitely use lto
 - `-Os` can often be better than `-O2/O3`
 - PGO is supposed to help a lot if guided with the right training data
 - statically link


## Inside Visual C++' Parallel Algorithms  - Billy O'Neal - [link](https://www.youtube.com/watch?v=nOpwhTbulmk)
Walkthrough through the implementation of a few implementations of the parallel algorithms in msvc. Good talk and discussion if interested in parallel implementations.

## Thoughts on a More Powerful and Simpler C++ (5 of N) - Herb Sutter - [link](https://www.youtube.com/watch?v=80BZxujhY38) 
Keynote by Herb. Split into three parts. 

First part was general talk on how we shouldn't make C++ more complex by adding complexity but then said that with the right features we can make it simpler. I disagreed with a few parts he mentioned here. I think we generally introduce way too many special cases even with simple features (for example see the Tips of C++ talk on list initialization or the whole `auto` vs `decltype(auto)` ([SO link](https://stackoverflow.com/questions/24109737/what-are-some-uses-of-decltypeauto))). 

He also shortly talks about good encapsulation (e.g.: interface vs. definition, public vs. private in classes). However, he is IMO leaving out ABIs here which is a big problem in practice. There is many defects in standard library implementations which can't be fixed because of ABIs (e.g.: stdlibc++ `std::deque` default constructor allocates or rhel7 still not having SSO). IMO fixing those issues would help people more in day to day life than yet another hip language feature.

He then talks about progress on two features he previously spoke about. First is "lifetimes", which seems like a nice feature for compilers/static analyzers to warn about potential lifetime issues. This reminded me a lot about Rust. Herb mentioned that some of them rely on heuristics. That makes me a bit wary as I assume people will just start disabling all lifetime warnings if we see too many false positives. Hopefully there will be a way to avoid those.

Second one was about meta classes. He showed a few examples. I think it can be nice but I fear that it might end up too complex to actually make the language any simpler. Time will tell.

## Design for Performance: Practical Experience  - Fedor Pikus - [link](https://www.youtube.com/watch?v=m25p3EtBua4)
Another performance related talk. Key point was to not make your interfaces too restrictive to lock you into bad design choices. Good example of this is `std::unordered_map` and its bucket interface which nobody uses but enforces a certain type of implementation.

## Effective replacement of dynamic polymorphism with std::variant  - Mateusz Pusz - [link](https://www.youtube.com/watch?v=gKbORJtnVu8)
Talk about avoiding virtual function calls in a inheritance hierarchy of a state machine by using a `std::variant` instead. Interesting talk with good ideas, sometimes hard to follow though as the code can get a little bit tricky without context.

## Liberating the Debugging Experience with the GDB Python API  - Jeff Trull - [link](https://www.youtube.com/watch?v=ck_jCH_G7pA)
Showed a few really nice things you can do with the gdb python API to make debugging easier.

I had written a few gdb plugins previously (way simpler than what he showed)  so this rang home.

## Concepts in 60: Everything you need to know and nothing you don't  - Andrew Sutton - [link](https://www.youtube.com/watch?v=ZeU6OPaGxwM/li) 
Concepts talk by the man himself. He gives a basic introduction to concepts. Nothing too new here. 

He warns though that writing good Concepts will be hard especially if you have to guarantee API stability. Though at the same using it as a simple interface in application code is considered good practice.


## 	Lightning Talks
Thursday evening lightning talks, again lots of entertaining talks.


## C++ Modules and Large-Scale Development  - John Lakos - [link](https://www.youtube.com/watch?v=K_fTl_hIEGY)
I had the feeling I had already seen this talk which Lakos confirmed as the slides are apparently 10 years old according to him.

It was also a more general talk on modules as a concept.

He also stated that we will still need macros for things like writing a nice logging library (as in macros are not going away).

## 	The Bits Between the Bits: How We Get to main()  - Matt Godbolt - [link](https://www.youtube.com/watch?v=dOfucXtyEsU)
Good talk about what happens before the entry to main() on linux. Usually people don't speak much about the linker or loader so this is a good change. Gets pretty low level so definitely recommended if you are into that.

Also warns against dynamic linking for anything performance critical.

## 	Standard Library Compatibility Guidelines  - Titus Winters - [link](https://www.youtube.com/watch?v=BWvSSsKCiAw)
Explains what users shouldn't do to stay compatible with future changes of the standard library. Mostly sensible things such as not opening `std::`. 

Most interesting recommendation was to always use qualified calls even in your own namespace because of ADL screwing you up.

For example:

```
namespace foo {
    void split(std::string, char);

    void some_code() {
        ...
        split(some_string, some_char);
    }
}

// later C++ adds
namespace std {
    void split(std::string_view, char);
}
```

In case the standard ever introduced a split function with the same interface then your code might silently start calling that function instead of your own version as ADL now finds that function. Hence he recommends to always qualify even internal calls (or not use snake_case). 

## 	Spectre: Secrets, Side-Channels, Sandboxes, and Security - Chandler Carruth - [link](https://www.youtube.com/watch?v=_f7O3IfIR2k) 
Very good talk from Chandler about Spectre and friends. He gives a good summary of the different variations of Spectre. Also shows (and runs) some code examples which make use of spectre style attacks or code patterns which are susceptible.

Finally also gives advice in which scenarios one should be concerned and when not.

He also mentions that more attacks of this pattern will come out in the next months and years.

## Closing Panel: Spectre  - Chandler Carruth, Jon Masters, Matt Miller - [link](https://www.youtube.com/watch?v=mt_ULMnQ4_A)
Panel continuation of the above. Lots of good questions. Recommend watching.
