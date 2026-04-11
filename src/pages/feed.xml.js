import rss from "@astrojs/rss";
import { getCollection } from "astro:content";
import { comparePosts, getPostPath } from "../lib/posts";
import { SITE } from "../site";

export async function GET(context) {
  const posts = (await getCollection("posts")).sort(comparePosts);

  return rss({
    title: SITE.title,
    description: SITE.description,
    site: context.site,
    items: posts.map((post) => ({
      title: post.data.title,
      description: post.data.description,
      pubDate: post.data.pubDate,
      link: getPostPath(post),
      categories: post.data.category ? [post.data.category] : [],
    })),
  });
}

