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
					<xsl:attribute name="href">https://cdn.hpm.io/assets/images/favicon/icon-48.png</xsl:attribute>
				</xsl:element>
				<xsl:element name="link">
					<xsl:attribute name="rel">icon</xsl:attribute>
					<xsl:attribute name="href">https://cdn.hpm.io/assets/images/favicon/icon-192.png</xsl:attribute>
					<xsl:attribute name="type">image/png</xsl:attribute>
					<xsl:attribute name="sizes">192x192</xsl:attribute>
				</xsl:element>
				<xsl:element name="link">
					<xsl:attribute name="rel">apple-touch-icon</xsl:attribute>
					<xsl:attribute name="href">https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-180.png</xsl:attribute>
					<xsl:attribute name="type">image/png</xsl:attribute>
					<xsl:attribute name="sizes">180x180</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://cdn.hpm.io/assets/js/analytics/index.js</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://www.google-analytics.com/analytics.js</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://cdn.hpm.io/assets/js/main.js?v=1</xsl:attribute>
				</xsl:element>
				<xsl:element name="script">
					<xsl:attribute name="type">text/javascript</xsl:attribute>
					<xsl:attribute name="src">https://cdn.hpm.io/assets/js/plyr/plyr.js?v=1</xsl:attribute>
				</xsl:element>
				<style type="text/css">.pod-desc { font: 500 1.125em/1.125em var(--hpm-font-main); color: rgb(142,144,144); } article.card { display: block !important; border-bottom: 1px solid #808080; }</style>
			</head>
			<xsl:apply-templates select="rss/channel" />
		</xsl:element>
	</xsl:template>
	<xsl:template match="channel">
		<body class="page page-template-page-series">
			<div class="container">
				<header id="masthead" class="site-header" role="banner">
					<div class="site-branding">
						<div class="site-logo">
							<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true" class="hpm-logo"><text class="hpm-logo-text" x="0" y="68">Houston Public Media</text><text class="hpm-logo-service" x="5" y="130">A SERVICE OF THE UNIVERSITY OF HOUSTON</text><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>
						</div>
						<section>
							<div id="top-schedule">
								<div class="top-schedule-label"><button aria-label="View Schedules" type="button" aria-expanded="false" aria-controls="top-schedule-link-wrap"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M68.6,199.1h373.8c5.8,0,10.6,4.8,10.6,10.6v229.2c0,23.4-19,42.3-42.3,42.3H100.3c-23.4,0-42.3-19-42.3-42.3 V209.7C58,203.8,62.8,199.1,68.6,199.1z M453,160.3v-31.7c0-23.4-19-42.3-42.3-42.3h-42.3V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3 c-5.8,0-10.6,4.8-10.6,10.6v45.8H199.1V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3c-5.8,0-10.6,4.8-10.6,10.6v45.8h-42.3 c-23.4,0-42.3,19-42.3,42.3v31.7c0,5.8,4.8,10.6,10.6,10.6h373.8C448.2,170.9,453,166.1,453,160.3z"></path></svg>Schedules</button></div>
								<div class="top-schedule-link-wrap" id="top-schedule-link-wrap">
									<div class="top-schedule-links"><a href="/tv8">TV 8</a></div>
									<div class="top-schedule-links"><a href="/news887">News 88.7</a></div>
									<div class="top-schedule-links"><a href="/classical">Classical</a></div>
									<div class="top-schedule-links"><a href="/mixtape">Mixtape</a></div>
								</div>
							</div>
							<div id="top-listen"><button aria-label="Listen Live" data-href="/listen-live" data-dialog="480:855"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M256,340.5c46.7,0,84.5-37.9,84.5-84.5V115.1c0-46.7-37.9-84.5-84.5-84.5s-84.5,37.9-84.5,84.5V256 C171.5,302.7,209.3,340.5,256,340.5z M396.9,199.6h-14.1c-7.8,0-14.1,6.3-14.1,14.1V256c0,65.9-56.8,118.7-124,112.2 c-58.6-5.7-101.5-58.4-101.5-117.2v-37.3c0-7.8-6.3-14.1-14.1-14.1h-14.1c-7.8,0-14.1,6.3-14.1,14.1v35.4 c0,78.9,56.3,149.3,133.9,160v30.1h-49.3c-7.8,0-14.1,6.3-14.1,14.1v14.1c0,7.8,6.3,14.1,14.1,14.1h140.9c7.8,0,14.1-6.3,14.1-14.1 v-14.1c0-7.8-6.3-14.1-14.1-14.1h-49.3v-29.7C352.6,399.1,411,334.3,411,256v-42.3C411,205.9,404.7,199.6,396.9,199.6z"></path></svg>Listen</button></div>
							<div id="top-watch"><button aria-label="Watch Live" data-href="/watch-live" data-dialog="820:850"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M448.1,74.7H63.9C45.2,74.7,30,89.9,30,108.6v226c0,18.7,15.2,33.9,33.9,33.9h169.5v22.6H109.1 c-6.2,0-11.3,5.1-11.3,11.3V425c0,6.2,5.1,11.3,11.3,11.3h293.8c6.2,0,11.3-5.1,11.3-11.3v-22.6c0-6.2-5.1-11.3-11.3-11.3H278.6 v-22.6h169.5c18.7,0,33.9-15.2,33.9-33.9v-226C482,89.9,466.8,74.7,448.1,74.7z M436.8,323.3H75.2V119.9h361.6V323.3z"></path></svg>Watch</button></div>
						</section>
						<div id="top-donate"><a href="/donate"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M438.1,85.3c-48.4-41.2-120.3-33.8-164.7,12L256,115.2l-17.4-17.9c-44.3-45.8-116.4-53.2-164.7-12 c-55.4,47.3-58.4,132.2-8.7,183.5L236,445.2c11,11.4,29,11.4,40,0l170.8-176.4C496.5,217.5,493.6,132.6,438.1,85.3L438.1,85.3z"></path></svg><br /><span class="top-mobile-text">Donate</span></a></div>
						<div tabindex="0" id="top-mobile-close" class="nav-button"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M341.4,256l128.8-128.8c15.8-15.8,15.8-41.4,0-57.2l-28.6-28.6c-15.8-15.8-41.4-15.8-57.2,0L255.5,170.1 L126.7,41.4c-15.8-15.8-41.4-15.8-57.2,0L40.9,70c-15.8,15.8-15.8,41.4,0,57.2L169.6,256L40.9,384.8C25,400.6,25,426.2,40.9,442 l28.6,28.6c15.8,15.8,41.4,15.8,57.2,0l128.8-128.8l128.8,128.8c15.8,15.8,41.4,15.8,57.2,0l28.6-28.6c15.8-15.8,15.8-41.4,0-57.2 L341.4,256z"></path></svg><br /><span class="top-mobile-text">CLOSE</span></div>
						<nav id="site-navigation" class="main-navigation" role="navigation">
							<div tabindex="0" id="top-mobile-menu" class="nav-button" aria-expanded="false"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M46.1,130.9h419.7c8.9,0,16.1-7.2,16.1-16.1V74.4c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1 v40.4C30,123.7,37.2,130.9,46.1,130.9z M46.1,292.3h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4c0-8.9-7.2-16.1-16.1-16.1H46.1 c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,285.1,37.2,292.3,46.1,292.3z M46.1,453.8h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4 c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,446.5,37.2,453.8,46.1,453.8z"></path></svg><br /><span class="top-mobile-text">MENU</span></div><div id="focus-sink" tabindex="-1" style="position: absolute; top: 0; left: 0;height:1px; width: 1px;"></div>
							<div class="nav-wrap">
								<div class="menu-main-header-nav-container">
									<ul id="menu-main-header-nav" class="nav-menu">
										<li class="nav-top">
											<a href="/news/" class="nav-item-head-main">News</a>
										</li>
										<li class="nav-top">
											<a href="/arts-culture/" class="nav-item-head-main">Arts/Culture</a>
										</li>
										<li class="nav-top">
											<a href="/education/" class="nav-item-head-main">Education</a>
										</li>
										<li class="nav-top">
											<a href="/shows/" class="nav-item-head-main">Shows</a>
										</li>
										<li class="nav-top">
											<a href="/podcasts/" class="nav-item-head-main">Podcasts</a>
										</li>
										<li class="nav-top">
											<a href="/support/" class="nav-item-head-main">Support</a>
										</li>
										<li class="nav-top nav-donate">
											<a href="/donate" class="nav-item-head-main">Donate</a></li>
										<li class="nav-top nav-passport">
											<a href="/support/passport/" class="nav-item-head-main">Passport</a>
										</li>
										<li class="nav-top nav-uh">
											<a href="https://uh.edu" class="nav-item-head-main">UH</a>
										</li>
										<li class="nav-top nav-top-mobile">
											<a href="/about/" class="nav-item-head-main">About</a>
										</li>
										<li class="nav-top nav-top-mobile">
											<a href="/contact-us/" class="nav-item-head-main">Contact Us</a>
										</li>
									</ul>
								</div>
							</div>
						</nav>
					</div>
				</header>
			</div>
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
				<section>
					<div class="foot-logo">
						<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true" class="hpm-logo"><text class="hpm-logo-text" x="0" y="68">Houston Public Media</text><text class="hpm-logo-service" x="5" y="130">A SERVICE OF THE UNIVERSITY OF HOUSTON</text><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>
					</div>
					<div class="foot-hpm">
						<h3>Houston Public Media</h3>
						<nav id="second-navigation" class="footer-navigation" role="navigation">
							<div class="menu-footer-navigation-container">
								<ul id="menu-footer-navigation" class="nav-menu">
									<li><a href="/about/">About</a></li>
									<li><a href="/about/careers/">Careers</a></li>
									<li><a href="https://www.uh.edu/president/communications/communicae/20200608-commitment-to-the-city/index.php">Commitment</a></li>
									<li><a href="/tv8/">TV</a></li>
									<li><a href="/news887/">Radio</a></li>
									<li><a href="/news/">News</a></li>
									<li><a href="/shows/">Shows</a></li>
								</ul>
							</div>
							<div class="clear"></div>
						</nav>
					</div>
					<div class="foot-comply">
						<h3>Compliance</h3>
						<nav id="third-navigation" class="footer-navigation" role="navigation">
							<div class="menu-footer-compliance-container">
								<ul id="menu-footer-compliance" class="nav-menu">
									<li><a href="/about/corporation-for-public-broadcasting-cpb-compliance/">CPB Compliance</a></li>
									<li><a href="/about/fcc-station-information/">FCC Station Information</a></li>
									<li><a href="/about/fcc-applications/">FCC Applications</a></li>
									<li><a href="https://publicfiles.fcc.gov/fm-profile/KUHF">KUHF Public File</a></li>
									<li><a href="https://publicfiles.fcc.gov/tv-profile/KUHT">KUHT Public File</a></li>
									<li><a href="/about/ethics-standards/">Ethics and Standards</a></li>
									<li><a href="http://www.uhsystem.edu/privacy-notice/">Privacy Policy</a></li>
									<li><a href="/about/additional-disclosures/">Additional Disclosures</a></li>
								</ul>
							</div>
							<div class="clear"></div>
						</nav>
					</div>
					<div class="foot-newsletter">
						<h3>Subscribe to Our Newsletters</h3>
						<h4><a href="https://www.houstonpublicmedia.org/news/today-in-houston-newsletter/">Today in Houston</a></h4>
						<p>Let the Houston Public Media newsroom help you start your day.</p>
						<h4><a href="https://www.houstonpublicmedia.org/support/newslettereguide-signup/">This Week</a></h4>
						<p>Get highlights, trending news, and behind-the-scenes insights from Houston Public Media delivered to your inbox each week.</p>
					</div>
					<div class="foot-contact">
						<p class="foot-button"><a href="/contact-us/">Contact Us</a></p>
						<p>4343 Elgin, Houston, TX 77204-0008</p>
						<div class="social-wrap">
							<div class="social-icon facebook">
								<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M441.4,283.8l12.6-82h-78.7v-53.2c0-22.4,11-44.3,46.2-44.3h35.8V34.5c0,0-32.5-5.5-63.5-5.5 C329,29,286.7,68.3,286.7,139.3v62.5h-72v82h72V482h88.6V283.8H441.4z"></path></svg></a>
							</div>
							<div class="social-icon twitter">
								<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M435.5,163.9c0.3,4,0.3,8,0.3,12c0,122.5-93.2,263.6-263.6,263.6C119.8,439.6,71,424.4,30,398 c7.5,0.9,14.6,1.1,22.4,1.1c43.3,0,83.2-14.6,115-39.6c-40.7-0.9-74.9-27.5-86.6-64.2c5.7,0.9,11.5,1.4,17.5,1.4 c8.3,0,16.6-1.1,24.4-3.2c-42.4-8.6-74.3-45.9-74.3-90.9v-1.1c12.3,6.9,26.7,11.2,41.9,11.8c-25-16.6-41.3-45-41.3-77.2 c0-17.2,4.6-33,12.6-46.7c45.6,56.2,114.1,92.9,191,96.9c-1.4-6.9-2.3-14.1-2.3-21.2c0-51.1,41.3-92.6,92.6-92.6 c26.7,0,50.8,11.2,67.7,29.3c20.9-4,41-11.8,58.8-22.4c-6.9,21.5-21.5,39.6-40.7,51.1c18.6-2,36.7-7.2,53.3-14.3 C469.4,134.4,453.6,150.7,435.5,163.9L435.5,163.9z"></path></svg></a>
							</div>
							<div class="social-icon instagram">
								<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M256,141.1c-63.6,0-114.9,51.3-114.9,114.9S192.4,370.9,256,370.9S370.9,319.6,370.9,256S319.6,141.1,256,141.1z  M256,330.7c-41.1,0-74.7-33.5-74.7-74.7s33.5-74.7,74.7-74.7s74.7,33.5,74.7,74.7S297.1,330.7,256,330.7L256,330.7z M402.4,136.4 c0,14.9-12,26.8-26.8,26.8c-14.9,0-26.8-12-26.8-26.8s12-26.8,26.8-26.8S402.4,121.6,402.4,136.4z M478.5,163.6 c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9,0c-35.8,1.7-67.6,9.9-93.9,36.1 s-34.4,58-36.2,93.9c-2.1,37-2.1,147.9,0,184.9c1.7,35.9,9.9,67.7,36.2,93.9s58,34.4,93.9,36.2c37,2.1,147.9,2.1,184.9,0 c35.9-1.7,67.7-9.9,93.9-36.2c26.2-26.2,34.4-58,36.2-93.9C480.6,311.4,480.6,200.6,478.5,163.6L478.5,163.6z M430.7,388.1 c-7.8,19.6-22.9,34.7-42.6,42.6c-29.5,11.7-99.5,9-132.1,9s-102.7,2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6 c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7,9-132.1c7.8-19.6,22.9-34.7,42.6-42.6c29.5-11.7,99.5-9,132.1-9s102.7-2.6,132.1,9 c19.6,7.8,34.7,22.9,42.6,42.6c11.7,29.5,9,99.5,9,132.1S442.4,358.7,430.7,388.1z"></path></svg></a>
							</div>
							<div class="social-icon youtube">
								<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M472.5,146.9c-5.2-19.6-20.6-35.1-40-40.3c-35.3-9.5-177-9.5-177-9.5s-141.7,0-177,9.5 c-19.5,5.2-34.8,20.7-40,40.3C29,182.4,29,256.6,29,256.6s0,74.2,9.5,109.7c5.2,19.6,20.6,34.4,40,39.7c35.3,9.5,177,9.5,177,9.5 s141.7,0,177-9.5c19.5-5.2,34.8-20,40.1-39.7c9.5-35.6,9.5-109.7,9.5-109.7S482,182.4,472.5,146.9z M209.2,324V189.3l118.4,67.4 L209.2,324L209.2,324z"></path></svg></a>
							</div>
							<div class="social-icon linkedin">
								<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M130.4,482H36.5V179.6h93.9V482z M83.4,138.3c-30,0-54.4-24.9-54.4-54.9C29,53.4,53.4,29,83.4,29 c30,0,54.4,24.3,54.4,54.4C137.8,113.4,113.4,138.3,83.4,138.3z M481.9,482h-93.7V334.8c0-35.1-0.7-80.1-48.8-80.1 c-48.8,0-56.3,38.1-56.3,77.6V482h-93.8V179.6h90.1v41.3h1.3c12.5-23.8,43.2-48.8,88.9-48.8c95,0,112.5,62.6,112.5,143.9V482H481.9 z"></path></svg></a>
							</div>
						</div>
					</div>
				</section>
				<div class="foot-tag">
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" rel="noopener" target="_blank">University of Houston</a></p>
					<p>Copyright © 2022</p>
				</div>
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
						<audio controls="controls" class="js-player">
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