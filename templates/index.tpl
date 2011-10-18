<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title>My torrent feed</title>
<link>http://public.tamaratemple.com/mytorrentfeed/</link>
<description>RSS feed for shows I watch</description>
{foreach from=$stories item=story}
<item> 
<title>{$story.title}</title>
<link>{$story.link}</link>
<description>{$story.description}</description>
<pubDate>{$story.pubdate}</pubDate>
</item>
{/foreach}
</channel>
</rss>