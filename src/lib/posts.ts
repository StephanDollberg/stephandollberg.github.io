import type { CollectionEntry } from "astro:content";

type Post = CollectionEntry<"posts">;

function pad(value: number) {
  return String(value).padStart(2, "0");
}

export function comparePosts(a: Post, b: Post) {
  return b.data.pubDate.getTime() - a.data.pubDate.getTime();
}

export function getPostPath(post: Post) {
  const year = post.data.pubDate.getUTCFullYear();
  const month = pad(post.data.pubDate.getUTCMonth() + 1);
  const day = pad(post.data.pubDate.getUTCDate());
  return `/${year}/${month}/${day}/${post.id}/`;
}

export function formatPostDate(date: Date) {
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    timeZone: "UTC",
  });
}

