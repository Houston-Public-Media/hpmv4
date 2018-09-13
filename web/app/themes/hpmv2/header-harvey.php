<?php
 	global $wp_query;
	if ( !empty( $wp_query ) && !empty( $wp_query->queried_object->ID ) ) :
		$ID = $wp_query->queried_object->ID;
		$head_excerpt = htmlentities( strip_tags( get_excerpt_by_id( $ID ) ), ENT_QUOTES );
		$head_title = $wp_query->queried_object->post_title.' | Houston Public Media';
		$head_perma = get_permalink();
		$attach_id = get_post_thumbnail_id( $ID );
		if ( !empty( $attach_id ) ) :
			$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
			$thumb_meta = [
				'width' => $feature_img[1],
				'height' => $feature_img[2],
				'mime-type' => get_post_mime_type( $attach_id )
			];
			$thumb = $feature_img[0];
		else :
			$thumb = 'https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg';
			$thumb_meta = [
				'width' => 1200,
				'height' => 800,
				'mime-type' => 'image/png'
			];
		endif;
	else :
		$head_excerpt = 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.';
		$head_perma = 'https://www.houstonpublicmedia.org';
		$head_title = 'Houston Public Media';
		$thumb = 'https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg';
		$thumb_meta = [
			'width' => 1200,
			'height' => 800,
			'mime-type' => 'image/jpeg'
		];
	endif;
 	$thumb = str_replace( 'http://', 'https://', $thumb );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime()</script>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="<?PHP echo strip_tags( $head_excerpt ); ?>" />
		<meta name="keywords" content="Houston Public Media,KUHT,TV 8,Houston Public Media Schedule,Educational TV Programs,independent program broadcasts,University of Houston,nonprofit,NPR News,KUHF,Classical Music,Arts & Culture,News 88.7" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="bitly-verification" content="7777946f1a0a"/>
		<meta name="google-site-verification" content="QOrBnMZ1LXDA9tL3e5WmFUU-oI3JUbDRotOWST1P_Dg" />
		<link rel="shortcut icon" href="https://cdn.hpm.io/assets/images/favicon.ico">
		<link rel="icon" type="image/png" href="https://cdn.hpm.io/assets/images/favicon-192x192.png" sizes="192x192">
		<link rel="apple-touch-icon" sizes="180x180" href="https://cdn.hpm.io/assets/images/apple-touch-icon-180x180.png">
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<meta name="apple-itunes-app" content="app-id=530216229" />
		<meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
		<meta property="fb:app_id" content="523938487799321" />
		<meta property="fb:admins" content="37511993" />
		<meta property="fb:pages" content="27589213702" />
		<meta property="fb:pages" content="183418875085596" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $head_title; ?>" />
		<meta property="og:url" content="<?php echo $head_perma; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $head_excerpt; ?>" />
		<meta property="og:image" content="<?php echo $thumb; ?>" />
		<meta property="og:image:url" content="<?php echo $thumb; ?>" />
		<meta property="og:image:height" content="<?php echo $thumb_meta['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $thumb_meta['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $thumb_meta['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo $thumb; ?>" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@houstonpubmedia" />
		<meta name="twitter:creator" content="@houstonpubmedia" />
		<meta name="twitter:title" content="<?php echo $head_title; ?> | Houston Public Media" />
		<meta name="twitter:image" content="<?php echo $thumb; ?>" />
		<meta name="twitter:url" content="<?php echo $head_perma; ?>" />
		<meta name="twitter:description" content="<?php echo $head_excerpt; ?>">
		<meta name="twitter:widgets:link-color" content="#000000">
		<meta name="twitter:widgets:border-color" content="#000000">
		<meta name="twitter:partner" content="tfwp">
		<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>
		<title><?php echo $head_title; ?> | Houston Public Media</title>
<?php 
	if ( WP_ENV == 'development' ) : ?>
		<link rel="stylesheet" id="harvey-css" href="https://local.hpm.io/harvey/css/harvey.css" type="text/css" media="all" />
<?php
	else : ?>
		<link rel="stylesheet" id="harvey-css" href="https://cdn.hpm.io/assets/css/harvey.css" type="text/css" media="all" />
<?php
	endif; ?>
		<!--[if lt IE 10]>
			<link rel="stylesheet" id="ie9-css-css" href="https://cdn.hpm.io/assets/css/ie9.css" type="text/css" media="all" />
		<![endif]-->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script type="text/javascript" src="https://cdn.hpm.io/assets/js/respond.min.js"></script>
		<![endif]-->
<?php 
	if ( WP_ENV == 'development' ) : ?>
		<script type="text/javascript" src="https://local.hpm.io/harvey/js/harvey.js"></script>
<?php
	else : ?>
		<script type="text/javascript" src="https://cdn.hpm.io/assets/js/harvey.js"></script>
<?php
	endif; ?>
		<?php hpm_google_tracker(); ?>
	</head>
	<?php
		$postscript = get_post_meta( $ID, 'postscript_meta', true );
	?>
	<body id="top" class="gradient <?php echo $postscript['class_body']; ?>">
		<?php hpm_fb_sdk(); ?>
		<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
		<header id="masthead" class="site-header<?php echo ( $postscript['class_body'] == 'hah-sub' ? ' active' : '' ); ?>" role="banner">
			<div class="site-branding">
				<a href="/" rel="home" title="Houston Public Media"><img src="https://cdn.hpm.io/assets/images/hpm_service_line@2x.png" alt="Houston Public Media" /></a>
			</div><!-- .site-branding -->
			<div class="site-title">
				<h1><a href="/harvey/">Houston <span class="hah-title">After Harvey</span></a></h1>
			</div>
			<div class="site-nav">
				<div id="mobile-nav"></div>
				<nav id="harvey-navigation" class="header-navigation" role="navigation">
					<div class="menu-harvey-navigation-container">
						<ul id="menu-harvey-navigation" class="nav-menu">
							<li><a href="#" id="stories-nav">Stories</a>
								<ul>
									<li><a href="/harvey/stories-from-the-storm/">Videos</a></li>
									<li><a href="/harvey/stories-from-the-storm/podcast/">Podcast</a></li>
								</ul>
							</li>
							<li><a href="/harvey/hurricane-season/" class="scrollto">Hurricane Season</a></li>
							<li><a href="/harvey/#news" class="scrollto">News</a></li>
						</ul>
					</div>
					<div class="clear"></div>
				</nav>
			</div>
			<div id="hfm-nav">
				<a href="https://www.houstonfloodmuseum.org" title="Houston Flood Museum" target="_blank">
					<img src="https://cdn.hpm.io/assets/images/harvey/houston_flood_museum@2x.png" alt="Houston Flood Museum" />
				</a>
			</div>
		</header><!-- .site-header -->