<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" exclude-result-prefixes="xhtml feedburner" xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0"	xmlns:xhtml="http://www.w3.org/1999/xhtml">
	<xsl:output method="html" doctype-public="HTML" />
	<xsl:variable name="title" select="/rss/channel/title" />
	<xsl:variable name="feedUrl" select="/rss/channel/atom10:link[@rel='self']/@href" xmlns:atom10="http://www.w3.org/2005/Atom" />
	<xsl:template match="/">
		<xsl:element name="html">
			<head>
				<title><xsl:value-of select="$title" /> from Houston Public Media</title>
				<link rel="alternate" type="application/rss+xml" title="{$title}" href="{$feedUrl}" />
				<link href="https://cdn.houstonpublicmedia.org/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
				<link href="/app/themes/hpmv4/style.css" rel="stylesheet" type="text/css" media="all" />
				<xsl:element name="meta">
					<xsl:attribute name="charset">UTF-8</xsl:attribute>
				</xsl:element>
				<xsl:element name="meta">
					<xsl:attribute name="name">viewport</xsl:attribute>
					<xsl:attribute name="content">width=device-width, initial-scale=1, maximum-scale=1</xsl:attribute>
				</xsl:element>
				<xsl:element name="meta">
					<xsl:attribute name="name">description</xsl:attribute>
					<xsl:attribute name="content"><xsl:value-of select="description" /></xsl:attribute>
				</xsl:element>
				<xsl:element name="meta">
					<xsl:attribute name="http-equiv">X-UA-Compatible</xsl:attribute>
					<xsl:attribute name="content">IE=edge,chrome=1</xsl:attribute>
				</xsl:element>
				<xsl:element name="link">
					<xsl:attribute name="rel">shortcut icon</xsl:attribute>
					<xsl:attribute name="href">https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-48.png</xsl:attribute>
				</xsl:element>
				<xsl:element name="link">
					<xsl:attribute name="rel">icon</xsl:attribute>
					<xsl:attribute name="href">https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-192.png</xsl:attribute>
					<xsl:attribute name="type">image/png</xsl:attribute>
					<xsl:attribute name="sizes">192x192</xsl:attribute>
				</xsl:element>
				<xsl:element name="link">
					<xsl:attribute name="rel">apple-touch-icon</xsl:attribute>
					<xsl:attribute name="href">https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-180.png</xsl:attribute>
					<xsl:attribute name="type">image/png</xsl:attribute>
					<xsl:attribute name="sizes">180x180</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://cdn.houstonpublicmedia.org/assets/js/analytics/index.js</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://www.google-analytics.com/analytics.js</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">/app/themes/hpmv4/js/main.js?v=1</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://cdn.houstonpublicmedia.org/assets/js/plyr/plyr.js?v=1</xsl:attribute>
				</xsl:element>
				<style type="text/css">.pod-desc { font: 500 1.125em/1.125em var(--hpm-font-main); color: rgb(142,144,144); } article.card { display: block !important; border-bottom: 1px solid #808080; }</style>
			</head>
			<xsl:apply-templates select="rss/channel" />
		</xsl:element>
	</xsl:template>
	<xsl:template match="channel">
		<body class="page page-template-page-series">
			<header id="masthead" class="site-header" role="banner">
				<div class="header-container">
					<div class="site-branding">
						<div class="site-logo">
							<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston">
								<img src="https://cdn.houstonpublicmedia.org/assets/images/houston-public-media-logo.png" alt="Houston Public Media" rel="Houston Public Media" />
							</a>
						</div>
						<div class="header-highlight-text">
							<a href="/tv8">TV 8</a> | <a href="/news887">News 88.7</a> | <a href="/classical">Classical</a> | <a href="/mixtape">Mixtape</a>
						</div>
					</div>
					<div class="quick-access">
						<ul>
							<li class="nav-top nav-passport"><a href="/support/passport/"><span style="text-indent:-9999px;">PBS Passport</span><!--?xml version="1.0" encoding="utf-8"?--><svg id="pbs-passport-logo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 488.8 80" style="enable-background:new 0 0 488.8 80;" xml:space="preserve" aria-hidden="true"> <style type="text/css"> .st0{fill:#0A145A;} .st1{fill:#5680FF;} .st2{fill:#FFFFFF;} </style> <g> <g> <path class="st0" d="M246.2,18c2.6,1.2,4.8,3.1,6.3,5.5s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2H232v15.6h-11.1V16h15.2C239.5,15.9,243,16.6,246.2,18z M241.1,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.9-2.2-5.2c-1.6-1.3-3.6-1.9-5.7-1.8H232v14.5h3C237.2,39.5,239.4,38.7,241.1,37.2L241.1,37.2z"></path> <path class="st0" d="M284.5,31.4c2.6,2.6,3.9,6.1,3.9,10.7v21.8H280l-1.2-3c-1.3,1.1-2.9,2-4.5,2.6c-1.9,0.7-4,1.1-6.1,1.1 c-3.1,0.1-6.2-0.9-8.5-2.9c-2.2-2.1-3.4-5-3.2-8.1c0-4.2,1.6-7.2,4.7-9c3.6-2,7.6-2.9,11.7-2.8c1.7,0,3.4,0.1,5.1,0.4 c0.1-1.7-0.4-3.4-1.4-4.8c-0.9-1.1-2.8-1.7-5.6-1.7c-1.9,0-3.8,0.2-5.6,0.7c-1.9,0.4-3.8,1.1-5.6,1.9v-8.6c4.2-1.5,8.6-2.3,13-2.3 C278,27.5,281.9,28.8,284.5,31.4z M268.4,55.5c0.9,0.7,2,1.1,3.2,1c2.3-0.1,4.5-0.8,6.3-2.1v-5.7c-1.1-0.1-2.2-0.2-3.3-0.2 c-1.8-0.1-3.6,0.3-5.3,1c-1.3,0.6-2.1,1.9-2,3.4C267.2,53.9,267.6,54.8,268.4,55.5z"></path> <path class="st0" d="M294.5,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.7-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.8,0.5,10,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.2,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C302,64.7,298.1,64,294.5,62.6z"></path> <path class="st0" d="M325.1,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.8-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.7,0.5,9.9,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.1,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C332.5,64.7,328.7,64,325.1,62.6z"></path> <path class="st0" d="M386.9,32.3c3.2,3.2,4.9,7.7,4.9,13.7c0.1,3.4-0.6,6.7-2.1,9.8c-1.3,2.7-3.3,5-5.9,6.6 c-2.7,1.6-5.8,2.4-9,2.3c-2.4,0.1-4.8-0.4-7.1-1.3v15.1h-10.5V30.4c5.2-1.8,10.7-2.8,16.2-2.9C379.1,27.5,383.6,29.1,386.9,32.3z M378.6,52.8c1.5-2.1,2.3-4.6,2.2-7.2c0-3-0.7-5.2-2.1-6.8s-3.5-2.5-5.7-2.4c-1.8,0-3.6,0.3-5.4,0.8v17.1c1.6,0.8,3.3,1.1,5,1.1 C374.9,55.6,377.1,54.6,378.6,52.8z"></path> <path class="st0" d="M404.6,62.4c-2.8-1.5-5.1-3.7-6.6-6.4c-1.7-3.1-2.5-6.5-2.4-10c-0.1-3.5,0.7-6.9,2.4-9.9 c1.5-2.7,3.9-4.9,6.6-6.4c3-1.5,6.3-2.3,9.6-2.2c3.3,0,6.5,0.7,9.4,2.2c2.8,1.4,5.1,3.6,6.7,6.3c1.6,2.9,2.5,6.2,2.4,9.5 c0.1,3.6-0.7,7.1-2.4,10.2c-1.5,2.8-3.8,5.1-6.6,6.6c-3,1.6-6.3,2.3-9.6,2.3C410.8,64.7,407.5,63.9,404.6,62.4z M419.6,53.1 c1.4-1.7,2.1-4.2,2.1-7.4c0.2-2.4-0.6-4.9-2-6.8c-1.3-1.6-3.4-2.6-5.5-2.5c-2.1-0.1-4.2,0.8-5.5,2.4c-1.4,1.6-2.1,4-2.1,7.1 s0.7,5.5,2.1,7.2c2.5,3,6.9,3.4,10,1C419.1,53.8,419.4,53.5,419.6,53.1L419.6,53.1z"></path> <path class="st0" d="M461,28.2v10.1c-0.7-0.2-1.4-0.4-2.1-0.5c-0.8-0.1-1.5-0.2-2.3-0.2c-1.5,0-3.1,0.4-4.4,1.1 c-1.3,0.7-2.3,1.6-3.2,2.8v22.4h-10.6V28.4h9.1l1.3,4.4c0.9-1.5,2.1-2.8,3.6-3.6c1.7-0.9,3.5-1.3,5.4-1.3 C458.9,27.8,460,27.9,461,28.2z"></path> <path class="st0" d="M479.6,36.2v14.5c-0.1,1.4,0.3,2.8,1.1,4c1,1,2.4,1.5,3.8,1.4c1.4,0,2.7-0.2,4-0.6v8c-1,0.4-2.1,0.6-3.1,0.8 c-1.3,0.2-2.7,0.3-4,0.3c-4.1,0-7.2-1-9.3-3.1c-2-2.1-3.1-5.1-3.1-9V36.2h-5.5v-7.8h5.5v-7.7l10.6-2.9v10.6h9.2v7.8H479.6z"></path> </g> <g> <path class="st0" d="M25.3,17.9c2.6,1.2,4.8,3,6.3,5.4s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2h-4.1v15.7H0V16h15.2C18.7,15.9,22.1,16.6,25.3,17.9z M20.2,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.8-2.1-5.1c-1.6-1.3-3.6-1.9-5.7-1.8h-3.3v14.5h3C16.4,39.5,18.6,38.7,20.2,37.2z"></path> <path class="st0" d="M70.1,41.8c2,2.1,3,5,2.9,7.9c0.1,4-1.6,7.8-4.7,10.3s-7.5,3.8-13.2,3.8H38.3V16h15.6c5.2,0,9.1,1,11.9,3 c2.7,2,4.1,5,4.1,9c0.1,2.2-0.5,4.5-1.8,6.3c-1.1,1.7-2.6,3-4.4,3.7C66.1,38.6,68.4,39.9,70.1,41.8z M49.4,24.3v10.8h3.2 c1.7,0.1,3.3-0.4,4.5-1.5c1.1-1.1,1.7-2.6,1.6-4.2c0.1-1.4-0.5-2.8-1.5-3.8c-1.3-1-2.8-1.4-4.4-1.3H49.4z M59.6,53.7 c1.3-1.2,1.9-2.9,1.8-4.6c0.1-1.7-0.6-3.3-1.9-4.4c-1.2-1-3.1-1.6-5.7-1.6h-4.4v12.3h4.4C56.5,55.3,58.4,54.8,59.6,53.7z"></path> <path class="st0" d="M83.3,63.8c-2.1-0.4-4.2-1-6.2-1.9V51.5c2,1,4,1.9,6.2,2.5c2.2,0.7,4.4,1,6.7,1c2,0.1,3.9-0.3,5.7-1.2 c1.2-0.7,1.9-2,1.9-3.4s-0.8-2.8-2-3.5c-2.2-1.5-4.6-2.7-7.1-3.7c-4.1-1.8-7.1-3.8-8.9-6c-1.9-2.3-2.9-5.1-2.8-8.1 c0-2.6,0.8-5.2,2.3-7.3c1.6-2.2,3.8-3.8,6.3-4.8c2.9-1.1,6-1.7,9.1-1.7c2.2,0,4.4,0.1,6.6,0.5c1.7,0.3,3.4,0.7,5.1,1.3v9.7 c-3.3-1.3-6.8-1.9-10.3-1.9c-1.8-0.1-3.7,0.3-5.3,1c-1.2,0.6-2,1.8-2,3.2c0,0.9,0.4,1.7,1,2.3c0.8,0.7,1.6,1.2,2.5,1.7 c1.1,0.5,3.1,1.4,6,2.7c4,1.8,6.8,3.8,8.5,6.1s2.6,5.1,2.5,7.9c0.2,5.6-3.1,10.8-8.3,12.9c-3.2,1.3-6.6,2-10,1.9 C88.1,64.5,85.7,64.3,83.3,63.8z"></path> </g> <g> <circle class="st1" cx="164.9" cy="40" r="40"></circle> <path class="st2" d="M164.8,4.5c-19.8,0-35.8,15.9-35.9,35.7c0,19.6,15.9,35.6,35.5,35.7c19.7,0.1,35.8-15.8,35.9-35.5 C200.4,20.7,184.5,4.6,164.8,4.5z M134.5,40.3L134.5,40.3l23.3,6.8l6.9,23.2C148.1,70.2,134.7,56.9,134.5,40.3z M157.8,33.2 L134.5,40c0.1-16.6,13.6-29.9,30.2-30L157.8,33.2z M164.9,70.3L164.9,70.3l6.9-23.2l23.3-6.8C195,56.9,181.5,70.3,164.9,70.3z M171.8,33.2L165,10c16.6,0,30,13.4,30.1,30l0,0L171.8,33.2z"></path> <polygon class="st2" points="151.3,49.2 146,58.9 155.7,53.6 154.7,50.2"></polygon> <polygon class="st2" points="174.9,30.1 178.3,31.1 183.6,21.5 173.9,26.7"></polygon> <polygon class="st2" points="178.3,49.2 174.9,50.2 173.9,53.6 183.6,58.9"></polygon> <polygon class="st2" points="154.7,30.1 155.7,26.7 146,21.5 151.3,31.1"></polygon></g></g></svg></a></li>
							<li class="nav-top nav-uh"><a target="_blank" href="https://www.uh.edu" rel="noopener">UH</a></li>
						</ul>
						<div id="top-search" aria-expanded="false">
							<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M475.8,420.8l-88-88c-4-4-9.4-6.2-15-6.2h-14.4c24.4-31.2,38.8-70.3,38.8-113c0-101.4-82.2-183.6-183.6-183.6 S30,112.2,30,213.6s82.2,183.6,183.6,183.6c42.6,0,81.8-14.5,113-38.8v14.4c0,5.6,2.2,11,6.2,15l88,88c8.3,8.3,21.7,8.3,29.9,0 l25-25C484,442.5,484,429.1,475.8,420.8z M213.6,326.6c-62.4,0-113-50.5-113-113c0-62.4,50.5-113,113-113c62.4,0,113,50.5,113,113 C326.6,276,276.1,326.6,213.6,326.6z"></path></svg>
							<form role="search" method="get" class="search-form" action="/search/">
								<label class="screen-reader-text">Search for:</label>
								<input type="search" class="search-field" placeholder="Search" value="" name="search" title="Search for:" />
								<button class="search-submit screen-reader-text"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M475.8,420.8l-88-88c-4-4-9.4-6.2-15-6.2h-14.4c24.4-31.2,38.8-70.3,38.8-113c0-101.4-82.2-183.6-183.6-183.6 S30,112.2,30,213.6s82.2,183.6,183.6,183.6c42.6,0,81.8-14.5,113-38.8v14.4c0,5.6,2.2,11,6.2,15l88,88c8.3,8.3,21.7,8.3,29.9,0 l25-25C484,442.5,484,429.1,475.8,420.8z M213.6,326.6c-62.4,0-113-50.5-113-113c0-62.4,50.5-113,113-113c62.4,0,113,50.5,113,113 C326.6,276,276.1,326.6,213.6,326.6z"></path></svg><span class="screen-reader-text">Search</span></button>
							</form>
						</div>
					</div>
				</div>
				<div class="navigation-wrap">
					<div class="header-container">
						<nav id="site-navigation" class="main-navigation" role="navigation">
							<div id="top-mobile-menu" class="nav-button" aria-expanded="false"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M46.1,130.9h419.7c8.9,0,16.1-7.2,16.1-16.1V74.4c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1 v40.4C30,123.7,37.2,130.9,46.1,130.9z M46.1,292.3h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4c0-8.9-7.2-16.1-16.1-16.1H46.1 c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,285.1,37.2,292.3,46.1,292.3z M46.1,453.8h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4 c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,446.5,37.2,453.8,46.1,453.8z"></path></svg><br /><span class="top-mobile-text">MENU</span></div>
							<div id="focus-sink" tab-index="-1" style="position: absolute; top: 0; left: 0;height:1px; width: 1px;"></div>
							<div id="top-mobile-close" class="nav-button"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M341.4,256l128.8-128.8c15.8-15.8,15.8-41.4,0-57.2l-28.6-28.6c-15.8-15.8-41.4-15.8-57.2,0L255.5,170.1 L126.7,41.4c-15.8-15.8-41.4-15.8-57.2,0L40.9,70c-15.8,15.8-15.8,41.4,0,57.2L169.6,256L40.9,384.8C25,400.6,25,426.2,40.9,442 l28.6,28.6c15.8,15.8,41.4,15.8,57.2,0l128.8-128.8l128.8,128.8c15.8,15.8,41.4,15.8,57.2,0l28.6-28.6c15.8-15.8,15.8-41.4,0-57.2 L341.4,256z"></path></svg><br /><span class="top-mobile-text">CLOSE</span></div>
							<div class="nav-wrap">
								<div class="menu-new-header-navigation-container">
									<ul id="menu-new-header-navigation" class="nav-menu">
										<li class="nav-top nav-news"><a href="/news" rel="noopener">News &amp; Information</a></li>
										<li class="nav-top nav-education"><a href="/edu" rel="noopener">Education</a></li>
										<li class="nav-top nav-programs"><a href="/podcasts" rel="noopener">Programs &amp; Podcasts</a></li>
										<li class="nav-top nav-about"><a href="/about" rel="noopener">About</a></li>
										<li class="nav-top nav-support"><a href="/support" rel="noopener">Support</a></li>
									</ul>
								</div>
								<div class="d-flex nav-right">
									<div class="nav-buttons" id="top-listen">
										<button aria-label="Listen Live" data-href="/listen-live" type="button" data-dialog="480:855">
											<img src="https://cdn.houstonpublicmedia.org/assets/images/icon-listen.png" alt="Listen Live" /> Listen
										</button>
										<button aria-label="Watch Live" data-href="/watch-live" type="button" data-dialog="820:850">
											<img src="https://cdn.houstonpublicmedia.org/assets/images/icon-watch.png" alt="Watch Live" /> Watch
										</button>
										<a href="/donate" class="btn-donate">
											<img src="https://cdn.houstonpublicmedia.org/assets/images/icon-donate.png" alt="Donate Now" /> Donate
										</a>
									</div>
									<div class="d-flex social-icon-wrap">
										<div class="social-icon facebook">
											<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M441.4,283.8l12.6-82h-78.7v-53.2c0-22.4,11-44.3,46.2-44.3h35.8V34.5c0,0-32.5-5.5-63.5-5.5 C329,29,286.7,68.3,286.7,139.3v62.5h-72v82h72V482h88.6V283.8H441.4z"></path></svg><span class="screen-reader-text">Facebook</span></a>
										</div>
										<div class="social-icon twitter">
											<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path></svg><span class="screen-reader-text">Twitter</span></a>
										</div>
										<div class="social-icon instagram">
											<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,141.1c-63.6,0-114.9,51.3-114.9,114.9S192.4,370.9,256,370.9S370.9,319.6,370.9,256S319.6,141.1,256,141.1z  M256,330.7c-41.1,0-74.7-33.5-74.7-74.7s33.5-74.7,74.7-74.7s74.7,33.5,74.7,74.7S297.1,330.7,256,330.7L256,330.7z M402.4,136.4 c0,14.9-12,26.8-26.8,26.8c-14.9,0-26.8-12-26.8-26.8s12-26.8,26.8-26.8S402.4,121.6,402.4,136.4z M478.5,163.6 c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9,0c-35.8,1.7-67.6,9.9-93.9,36.1 s-34.4,58-36.2,93.9c-2.1,37-2.1,147.9,0,184.9c1.7,35.9,9.9,67.7,36.2,93.9s58,34.4,93.9,36.2c37,2.1,147.9,2.1,184.9,0 c35.9-1.7,67.7-9.9,93.9-36.2c26.2-26.2,34.4-58,36.2-93.9C480.6,311.4,480.6,200.6,478.5,163.6L478.5,163.6z M430.7,388.1 c-7.8,19.6-22.9,34.7-42.6,42.6c-29.5,11.7-99.5,9-132.1,9s-102.7,2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6 c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7,9-132.1c7.8-19.6,22.9-34.7,42.6-42.6c29.5-11.7,99.5-9,132.1-9s102.7-2.6,132.1,9 c19.6,7.8,34.7,22.9,42.6,42.6c11.7,29.5,9,99.5,9,132.1S442.4,358.7,430.7,388.1z"></path></svg><span class="screen-reader-text">Instagram</span></a>
										</div>
										<div class="social-icon youtube">
											<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M472.5,146.9c-5.2-19.6-20.6-35.1-40-40.3c-35.3-9.5-177-9.5-177-9.5s-141.7,0-177,9.5 c-19.5,5.2-34.8,20.7-40,40.3C29,182.4,29,256.6,29,256.6s0,74.2,9.5,109.7c5.2,19.6,20.6,34.4,40,39.7c35.3,9.5,177,9.5,177,9.5 s141.7,0,177-9.5c19.5-5.2,34.8-20,40.1-39.7c9.5-35.6,9.5-109.7,9.5-109.7S482,182.4,472.5,146.9z M209.2,324V189.3l118.4,67.4 L209.2,324L209.2,324z"></path></svg><span class="screen-reader-text">YouTube</span></a>
										</div>
										<div class="social-icon linkedin">
											<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M130.4,482H36.5V179.6h93.9V482z M83.4,138.3c-30,0-54.4-24.9-54.4-54.9C29,53.4,53.4,29,83.4,29 c30,0,54.4,24.3,54.4,54.4C137.8,113.4,113.4,138.3,83.4,138.3z M481.9,482h-93.7V334.8c0-35.1-0.7-80.1-48.8-80.1 c-48.8,0-56.3,38.1-56.3,77.6V482h-93.8V179.6h90.1v41.3h1.3c12.5-23.8,43.2-48.8,88.9-48.8c95,0,112.5,62.6,112.5,143.9V482H481.9 z"></path></svg><span class="screen-reader-text">Linkedin</span></a>
										</div>
										<div class="social-icon mastodon">
											<a href="https://mastodon.social/@houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M433 179.11c0-97.2-63.71-125.7-63.71-125.7-62.52-28.7-228.56-28.4-290.48 0 0 0-63.72 28.5-63.72 125.7 0 115.7-6.6 259.4 105.63 289.1 40.51 10.7 75.32 13 103.33 11.4 50.81-2.8 79.32-18.1 79.32-18.1l-1.7-36.9s-36.31 11.4-77.12 10.1c-40.41-1.4-83-4.4-89.63-54a102.54 102.54 0 0 1-.9-13.9c85.63 20.9 158.65 9.1 178.75 6.7 56.12-6.7 105-41.3 111.23-72.9 9.8-49.8 9-121.5 9-121.5zm-75.12 125.2h-46.63v-114.2c0-49.7-64-51.6-64 6.9v62.5h-46.33V197c0-58.5-64-56.6-64-6.9v114.2H90.19c0-122.1-5.2-147.9 18.41-175 25.9-28.9 79.82-30.8 103.83 6.1l11.6 19.5 11.6-19.5c24.11-37.1 78.12-34.8 103.83-6.1 23.71 27.3 18.4 53 18.4 175z"></path></svg><span class="screen-reader-text">Mastodon</span></a>
										</div>
									</div>
								</div>
							</div>
						</nav>
					</div>
				</div>
			</header>
			<div id="page" class="hfeed site">
				<div id="content" class="site-content">
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">
							<article>
								<header class="entry-header">
									<h1 class="entry-title">
										<xsl:choose>
											<xsl:when test="link">
												<a href="{link}" title="Link to original website"><xsl:value-of select="$title" /></a>
											</xsl:when>
											<xsl:otherwise>
												<xsl:value-of select="$title" />
											</xsl:otherwise>
										</xsl:choose>
									</h1>
								</header>
								<div class="entry-content">
									<div class="alignleft">
										<xsl:apply-templates select="image" />
									</div>
									<div class="alignleft">
										<h2>Subscribe Now!</h2>
										<p>Do a search for "<strong><xsl:value-of select="$title" /></strong>" or "<strong>Houston Public Media</strong>" in your podcast app of choice</p>
										<p>Or copy/paste this address:</p>
										<p><form><input type="text" value="{$feedUrl}" style="padding: 0.5em; width: 100%; font-family: var(--hpm-font-main);" /></form></p>
									</div>
								</div>
							</article>
							<aside class="column-right">
								<h2>About <xsl:value-of select="$title" /></h2>
								<p><a href="{link}" title="Link to original website">Visit the program homepage</a></p>
								<div class="pod-desc"><xsl:value-of select="description" disable-output-escaping="no" /></div>
							</aside>
							<section id="search-results">
								<xsl:apply-templates select="item" />
							</section>
						</main>
					</div>
				</div>
			</div>
			<footer id="colophon" class="site-footer" role="contentinfo">
				<div class="container">
					<div class="footer-section footer-top">
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Features</h3>
								<div class="menu-footer-features-container">
									<ul id="menu-footer-features" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/news/today-in-houston-newsletter/">Today in Houston Newsletter</a></li>
										<li><a href="https://www.houstonpublicmedia.org/coronavirus/">Coronavirus News and Resources</a></li>
										<li><a href="https://www.houstonpublicmedia.org/news/indepth/">News 88.7 inDepth</a></li>
										<li><a href="https://www.houstonpublicmedia.org/storm-ready/">Storm Ready</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Topic</h3>
								<div class="menu-footer-topics-container">
									<ul id="menu-footer-topics" class="nav-menu">
										<li><a href="/topics/news/houston/">Local News</a></li>
										<li><a href="/news/the-texas-newsroom/">Statewide News</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/news/business/">Business</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/news/energy-environment/">Energy &amp; Environment</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/news/health-science/">Health &amp; Science</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/news/politics/immigration/">Immigration</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Art &amp; Culture</h3>
								<div class="menu-footer-art-culture-container">
									<ul id="menu-footer-art-culture" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/">Arts &amp; Culture</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/classical-music/">Classical Music</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/opera-musical-theater/">Opera &amp; Musical Theater</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/dance/">Dance</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/visual-art/">Visual Art</a></li>
										<li><a href="https://www.houstonpublicmedia.org/news/series/voices-and-verses-a-poem-a-day-series/">Voices and Verses: A Poem-A-Day Series</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Awareness</h3>
								<div class="menu-footer-awareness-container">
									<ul id="menu-footer-awareness" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/black-history-month/">Black History Month</a></li>
										<li><a href="https://www.houstonpublicmedia.org/pride/">Pride Month: Better Together!</a></li>
										<li><a href="https://www.houstonpublicmedia.org/asian-american-pacific-islander-heritage/">Asian American Pacific Islander Heritage</a></li>
										<li><a href="https://www.houstonpublicmedia.org/black-history-month/">Black History</a></li>
										<li><a href="https://www.houstonpublicmedia.org/womens-history-month/">Women’s History</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="footer-section footer-middle">
						<h2>Programs &amp; podcasts</h2>
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Local Programs</h3>
								<div class="menu-footer-local-programs-container">
									<ul id="menu-footer-local-programs" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/shows/party-politics/">Party Politics</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/i-see-u/">I SEE U with Eddie Robinson</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/houston-matters/">Houston Matters with Craig Cohen</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>UH</h3>
								<div class="menu-footer-uh-main-container">
									<ul id="menu-footer-uh-main" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/100-years-of-houston/">100 Years of Houston</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/bauer-business-focus/">Bauer Business Focus</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/briefcase/">Briefcase</a></li>
										<li><a href="https://uh.edu/engines">Engines of our Ingenuity</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/health-matters/">Health Matters</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/uh-moment/">UH Moment</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Education</h3>
								<div class="menu-footer-art-culture-container">
									<ul id="menu-footer-art-culture-1" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/">Arts &amp; Culture</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/classical-music/">Classical Music</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/opera-musical-theater/">Opera &amp; Musical Theater</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/dance/">Dance</a></li>
										<li><a href="https://www.houstonpublicmedia.org/topics/arts-culture/visual-art/">Visual Art</a></li>
										<li><a href="https://www.houstonpublicmedia.org/news/series/voices-and-verses-a-poem-a-day-series/">Voices and Verses: A Poem-A-Day Series</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Podcasts</h3>
								<div class="menu-footer-podcasts-container">
									<ul id="menu-footer-podcasts" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/below-the-waterlines/">Below the Waterlines: Houston After Hurricane Harvey</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/party-politics/">Party Politics</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/skyline-sessions/">Skyline Sessions</a></li>
										<li><a href="https://www.houstonpublicmedia.org/shows/encore-houston/">Encore Houston</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="footer-section footer-middle">
						<h2>Support</h2>
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Membership</h3>
								<div class="menu-footer-support-container">
									<ul id="menu-footer-support" class="nav-menu">
										<li><a href="https://www.callswithoutwalls.com/pledgeCart3/?campaign=15605107-AEB7-4016-898F-9287A03226E9&amp;source=#/home">Update Payment Method</a></li>
										<li><a href="https://www.callswithoutwalls.com/pledgeCart3/?campaign=15605107-AEB7-4016-898F-9287A03226E9&amp;source=#/home">Upgrade your Monthly Gift</a></li>
										<li><a href="https://donate.houstonpublicmedia.org/hpmf/gift-membership">Give a Gift Membership</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Giving Programs</h3>
								<div class="menu-footer-giving-programs-container">
									<ul id="menu-footer-giving-programs" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/support/giving-societies/affinity-council-members/">Affinity Council</a></li>
										<li><a href="https://www.houstonpublicmedia.org/support/giving-societies/">Giving Societies</a></li>
										<li><a href="/support/giving-societies/in-tempore-legacy-society/">In Tempore Legacy Society</a></li>
										<li><a href="/support/innovation-fund/">Innovation Fund</a></li>
										<li><a href="/support">Other Ways to Give</a></li>
										<li><a href="https://houstonpublicmedia.careasy.org/home">Vehicle Donation</a></li>
										<li><a href="/support/company-matching-gifts/">Employee Match Program</a></li>
										<li><a href="/support">More Ways to Give</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Volunteers</h3>
								<div class="menu-footer-volunteers-container">
									<ul id="menu-footer-volunteers" class="nav-menu">
										<li><a href="/about/houston-public-media-foundation/">Foundation Board</a></li>
										<li><a href="/about/young-leaders-council/">Young Leaders Council</a></li>
										<li><a href="/support/mission-ambassador/">Mission Ambassadors</a></li>
									</ul>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Partnerships</h3>
								<div class="menu-footer-partnership-container">
									<ul id="menu-footer-partnership" class="nav-menu">
										<li><a href="https://sponsorhoustonpublicmedia.org/">Corporate Partnership</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<nav id="compliance-foot-navigation" class="footer-navigation" role="navigation">
						<div class="menu-footer-compliance-container">
							<ul id="menu-footer-compliance" class="nav-menu">
								<li><a href="https://www.houstonpublicmedia.org/about/corporation-for-public-broadcasting-cpb-compliance/">CPB Compliance</a></li>
								<li><a href="https://www.houstonpublicmedia.org/about/fcc-station-information/">FCC Station Information</a></li>
								<li><a href="https://www.houstonpublicmedia.org/about/fcc-applications/">FCC Applications</a></li>
								<li><a href="https://publicfiles.fcc.gov/fm-profile/KUHF">KUHF Public File</a></li>
								<li><a href="https://publicfiles.fcc.gov/tv-profile/KUHT">KUHT Public File</a></li>
								<li><a href="https://www.houstonpublicmedia.org/about/ethics-standards/">Ethics and Standards</a></li>
								<li><a href="http://www.uhsystem.edu/privacy-notice/">Privacy Policy</a></li>
								<li><a href="https://www.houstonpublicmedia.org/about/additional-disclosures/">Additional Disclosures</a></li>
							</ul>
						</div>
					</nav>
					<div class="footer-section footer-last">
						<div class="row">
							<div class="col-sm-12 col-lg-7 col-xl-8">
								<div class="menu-footer-navigation-container">
									<ul id="menu-footer-navigation" class="nav-menu">
										<li><a href="https://www.houstonpublicmedia.org/about/">About</a></li>
										<li><a href="https://www.houstonpublicmedia.org/about/careers/">Careers</a></li>
										<li><a href="https://www.uh.edu/president/communications/communicae/20200608-commitment-to-the-city/index.php">Commitment</a></li>
										<li class="foot-button"><a href="https://www.houstonpublicmedia.org/contact-us/">Contact Us</a></li>
										<li><a href="https://www.houstonpublicmedia.org/tv8/">TV</a></li>
										<li><a href="https://www.houstonpublicmedia.org/news887/">Radio</a></li>
										<li><a href="https://www.houstonpublicmedia.org/news/">News</a></li>
										<li><a href="/shows/">Shows</a></li>
									</ul>
								</div>
								<div class="footer-tag">
									<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" rel="noopener" target="_blank">University of Houston</a></p>
									<p>© 2024 Houston Public Media</p>
								</div>
							</div>
							<div class="col-sm-12 col-lg-5 col-xl-4">
								<div class="icon-wrap">
									<div class="service-icon facebook">
										<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M441.4,283.8l12.6-82h-78.7v-53.2c0-22.4,11-44.3,46.2-44.3h35.8V34.5c0,0-32.5-5.5-63.5-5.5 C329,29,286.7,68.3,286.7,139.3v62.5h-72v82h72V482h88.6V283.8H441.4z"></path></svg><span class="screen-reader-text">Facebook</span></a>
									</div>
									<div class="service-icon twitter">
										<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path></svg><span class="screen-reader-text">Twitter</span></a>
									</div>
									<div class="service-icon instagram">
										<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,141.1c-63.6,0-114.9,51.3-114.9,114.9S192.4,370.9,256,370.9S370.9,319.6,370.9,256S319.6,141.1,256,141.1z  M256,330.7c-41.1,0-74.7-33.5-74.7-74.7s33.5-74.7,74.7-74.7s74.7,33.5,74.7,74.7S297.1,330.7,256,330.7L256,330.7z M402.4,136.4 c0,14.9-12,26.8-26.8,26.8c-14.9,0-26.8-12-26.8-26.8s12-26.8,26.8-26.8S402.4,121.6,402.4,136.4z M478.5,163.6 c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9,0c-35.8,1.7-67.6,9.9-93.9,36.1 s-34.4,58-36.2,93.9c-2.1,37-2.1,147.9,0,184.9c1.7,35.9,9.9,67.7,36.2,93.9s58,34.4,93.9,36.2c37,2.1,147.9,2.1,184.9,0 c35.9-1.7,67.7-9.9,93.9-36.2c26.2-26.2,34.4-58,36.2-93.9C480.6,311.4,480.6,200.6,478.5,163.6L478.5,163.6z M430.7,388.1 c-7.8,19.6-22.9,34.7-42.6,42.6c-29.5,11.7-99.5,9-132.1,9s-102.7,2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6 c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7,9-132.1c7.8-19.6,22.9-34.7,42.6-42.6c29.5-11.7,99.5-9,132.1-9s102.7-2.6,132.1,9 c19.6,7.8,34.7,22.9,42.6,42.6c11.7,29.5,9,99.5,9,132.1S442.4,358.7,430.7,388.1z"></path></svg><span class="screen-reader-text">Instagram</span></a>
									</div>
									<div class="service-icon youtube">
										<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M472.5,146.9c-5.2-19.6-20.6-35.1-40-40.3c-35.3-9.5-177-9.5-177-9.5s-141.7,0-177,9.5 c-19.5,5.2-34.8,20.7-40,40.3C29,182.4,29,256.6,29,256.6s0,74.2,9.5,109.7c5.2,19.6,20.6,34.4,40,39.7c35.3,9.5,177,9.5,177,9.5 s141.7,0,177-9.5c19.5-5.2,34.8-20,40.1-39.7c9.5-35.6,9.5-109.7,9.5-109.7S482,182.4,472.5,146.9z M209.2,324V189.3l118.4,67.4 L209.2,324L209.2,324z"></path></svg><span class="screen-reader-text">YouTube</span></a>
									</div>
									<div class="service-icon linkedin">
										<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M130.4,482H36.5V179.6h93.9V482z M83.4,138.3c-30,0-54.4-24.9-54.4-54.9C29,53.4,53.4,29,83.4,29 c30,0,54.4,24.3,54.4,54.4C137.8,113.4,113.4,138.3,83.4,138.3z M481.9,482h-93.7V334.8c0-35.1-0.7-80.1-48.8-80.1 c-48.8,0-56.3,38.1-56.3,77.6V482h-93.8V179.6h90.1v41.3h1.3c12.5-23.8,43.2-48.8,88.9-48.8c95,0,112.5,62.6,112.5,143.9V482H481.9 z"></path></svg><span class="screen-reader-text">LinkedIn</span></a>
									</div>
									<div class="service-icon mastodon">
										<a href="https://mastodon.social/@houstonpublicmedia" rel="noopener me" target="_blank"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M433 179.11c0-97.2-63.71-125.7-63.71-125.7-62.52-28.7-228.56-28.4-290.48 0 0 0-63.72 28.5-63.72 125.7 0 115.7-6.6 259.4 105.63 289.1 40.51 10.7 75.32 13 103.33 11.4 50.81-2.8 79.32-18.1 79.32-18.1l-1.7-36.9s-36.31 11.4-77.12 10.1c-40.41-1.4-83-4.4-89.63-54a102.54 102.54 0 0 1-.9-13.9c85.63 20.9 158.65 9.1 178.75 6.7 56.12-6.7 105-41.3 111.23-72.9 9.8-49.8 9-121.5 9-121.5zm-75.12 125.2h-46.63v-114.2c0-49.7-64-51.6-64 6.9v62.5h-46.33V197c0-58.5-64-56.6-64-6.9v114.2H90.19c0-122.1-5.2-147.9 18.41-175 25.9-28.9 79.82-30.8 103.83 6.1l11.6 19.5 11.6-19.5c24.11-37.1 78.12-34.8 103.83-6.1 23.71 27.3 18.4 53 18.4 175z"></path></svg><span class="screen-reader-text">Mastodon</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<nav id="uh-foot-navigation" class="footer-navigation" role="navigation">
					<div class="menu-footer-uh-container">
						<ul id="menu-footer-uh" class="nav-menu">
							<li><a href="https://www.texas.gov/">Texas.gov</a></li>
							<li><a href="https://gov.texas.gov/organization/hsgd">Texas Homeland Security</a></li>
							<li><a href="https://www.tsl.texas.gov/trail/index.html">TRAIL</a></li>
							<li><a href="https://sao.fraud.texas.gov/ReportFraud/">Fraud Reporting</a></li>
							<li><a href="https://www.uhsystem.edu/fraud-non-compliance/">Fraud &amp; Non-Compliance Hotline</a></li>
							<li><a href="https://www.sos.state.tx.us/linkpolicy.shtml">Linking Notice</a></li>
							<li><a href="https://uhsystem.edu/privacy-notice/">Privacy Notice</a></li>
							<li><a href="https://uhsystem.edu/legal-affairs/general-counsel/public-information-act/">Open Records/Public Information Act</a></li>
							<li><a href="https://apps.highered.texas.gov/resumes/">Institutional Résumé</a></li>
							<li><a href="https://www.uh.edu/finance/pages/State_Report.htm">Required Reports</a></li>
							<li><a href="https://www.uh.edu/equal-opportunity/eir-accessibility/">Electronic &amp; Information Resources Accessibility</a></li>
							<li><a href="https://www.uh.edu/sexual-misconduct-reporting-form/">Discrimination and Sexual Misconduct Reporting and Awareness</a></li>
							<li><a href="https://www.uh.edu/policies/">University Policies</a></li>
						</ul>
					</div>
				</nav>
			</footer>
			<script type="text/javascript">hpm.audioPlayers();var pods = document.querySelectorAll('.pod-desc');if (pods !== null) { Array.from(pods).forEach((p) => {p.innerHTML = p.innerText; }); }</script>
		</body>
	</xsl:template>
	<xsl:template match="item" xmlns:dc="http://purl.org/dc/elements/1.1/">
		<xsl:if test="position() = 1">
			<h2 style="padding-left: 1em;">Current Feed Content</h2>
		</xsl:if>
		<article class="card">
			<header class="entry-header">
				<h2 class="entry-title">
					<xsl:choose>
						<xsl:when test="guid[@isPermaLink='true' or not(@isPermaLink)]">
							<a href="{guid}">
								<xsl:value-of select="title" />
							</a>
						</xsl:when>
						<xsl:when test="link">
							<a href="{link}">
								<xsl:value-of select="title" />
							</a>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="title" />
						</xsl:otherwise>
					</xsl:choose>
				</h2>
			</header>
			<div class="entry-summary">
				<p><span class="posted-on">
					<xsl:if test="count(child::pubDate)=1"><span>Posted:</span>
						<xsl:text> </xsl:text>
						<xsl:value-of select="pubDate" />
					</xsl:if>
					<xsl:if test="count(child::dc:date)=1"><span>Posted:</span>
						<xsl:text> </xsl:text>
						<xsl:value-of select="dc:date" />
					</xsl:if>
				</span></p>
				<xsl:if test="count(child::enclosure)=1">
					<div class="article-player-wrap">
						<audio controls="controls" class="js-player" preload="none">
							<source src="{enclosure/@url}?source=podcast-feed-page" type="audio/mpeg" />
							Your browser does not support the <code>audio</code> element. <a href="{enclosure/@url}">Click here to play.</a>
						</audio>
					</div>
				</xsl:if>
				<div class="pod-desc"><xsl:call-template name="outputContent" /></div>
			</div>
		</article>
	</xsl:template>
	<xsl:template match="image">
		<a href="{link}" title="Link to original website"><img src="{url}" id="feedimage" alt="{title}" /></a>
		<xsl:text />
	</xsl:template>
	<xsl:template name="outputContent">
		<xsl:choose>
			<xsl:when test="xhtml:body">
				<xsl:copy-of select="xhtml:body/*" />
			</xsl:when>
			<xsl:when test="xhtml:div">
				<xsl:copy-of select="xhtml:div" />
			</xsl:when>
			<xsl:when xmlns:content="http://purl.org/rss/1.0/modules/content/" test="content:encoded">
				<xsl:value-of select="content:encoded" disable-output-escaping="yes" />
			</xsl:when>
			<xsl:when test="description">
				<xsl:value-of select="description" disable-output-escaping="yes" />
			</xsl:when>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>