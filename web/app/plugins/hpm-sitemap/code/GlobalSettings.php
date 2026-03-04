<?php
namespace hpmSitemap;

class GlobalSettings {
	public bool $enableImages = false; // add images to the sitemap
	public bool $addRssToHead = true;  // add recent files to Rss header
	public string $urlXmlSitemap = "sitemap.xml";
	public string $urlNewsSitemap = "newssitemap.xml";
	public string $urlRssSitemap = "rsssitemap.xml";
	public string $urlRssLatest = "rsslatest.xml";
	public string $urlHtmlSitemap = "htmlsitemap.htm";
	public int $newsMode = 0; // add news sitemap. 0 = disabled, 1 = all , 2 = selected categories.
}