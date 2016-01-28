---
layout: post
title: "Static Site Hosting with Github Pages and Cloudflare"
category: web
comments: true
---

How I bootstrapped this blog and how you can do it, too. Let's assume you want to setup static hosting on `example.com`:

 1. Register your domain with the registrar of your choice.
 1. Sign in/up for Cloudflare with your domain.
 2. Replace the DNS servers of your registrar with the ones you get from Cloudflare.
 3. In the Cloudflare dashboard under DNS add two entries:
    - `CNAME example.com  yourusername.github.io`
    - `CNAME www          yourusername.github.io`

    Make sure that you click the Cloudflare icon for both of these entries to turn them on.
 4. Get started with Github pages as described on [this](https://pages.github.com/) page.
 5. Add an empty file called `CNAME` containing `example.com` to your repository.
 6. Push.
 7. Profit!

 <!-- more -->

At this point you probably want to get started using [`jekyll`](https://help.github.com/articles/using-jekyll-with-pages/) or start using a ready made [theme](https://github.com/jekyll/jekyll/wiki/Themes) and modify that to your likings.

On the Cloudflare side you can tune some settings. I would recommend to turn on caching via [pages rules](https://blog.cloudflare.com/introducing-pagerules-advanced-caching/) and also turn on minifying under the Speed tab.

Now, you have a static site speed up by Cloudflare which you can use for blogging or just for some personal info.
