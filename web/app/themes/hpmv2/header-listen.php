<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
 	global $wp_query;
 	$ID = $wp_query->post->ID;
	$head_excerpt = htmlentities( strip_tags( get_excerpt_by_id( $ID ) ), ENT_QUOTES );
	$head_title = $wp_query->post->post_title.' | Houston Public Media';
	$head_perma = get_permalink();
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
		<meta property="og:image" content="https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg" />
		<meta property="og:image:url" content="https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg" />
		<meta property="og:image:height" content="1200" />
		<meta property="og:image:width" content="800" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:secure_url" content="https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@houstonpubmedia" />
		<meta name="twitter:creator" content="@houstonpubmedia" />
		<meta name="twitter:title" content="<?php echo $head_title; ?> | Houston Public Media" />
		<meta name="twitter:image" content="https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg" />
		<meta name="twitter:url" content="<?php echo $head_perma; ?>" />
		<meta name="twitter:description" content="<?php echo $head_excerpt; ?>">
		<meta name="twitter:widgets:link-color" content="#000000">
		<meta name="twitter:widgets:border-color" content="#000000">
		<meta name="twitter:partner" content="tfwp">
<?php
	wp_head();
	hpm_google_tracker(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'body_open' ); ?>
		<?php hpm_fb_sdk(); ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
		<div class="container">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>">&nbsp;</a>
					</div>
					<div id="top-donate"><a href="/donate" target="_blank">Donate</a></div>
					<div id="top-mobile-menu"><span class="fa fa-bars" aria-hidden="true"></span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<?php
							wp_nav_menu( array(
								'menu_class' => 'nav-menu',
								'menu' => 12244,
								'walker' => new HPMv2_Menu_Walker
							) ); ?>
						<div class="clear"></div>
					</nav><!-- .main-navigation -->
				</div><!-- .site-branding -->
			</header><!-- .site-header -->
		</div>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">