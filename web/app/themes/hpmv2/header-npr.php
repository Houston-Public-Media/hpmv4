<?php
	global $nprdata;
	$head_title = $nprdata['title'];
	$head_perma = $nprdata['permalink'];
	$head_excerpt = htmlentities( strip_tags( $nprdata['excerpt'] ), ENT_QUOTES );
	$keywords = $nprdata['keywords'];
	$thumb = $nprdata['image']['src'];
	$thumb_meta = array(
		'width' => $nprdata['image']['width'],
		'height' => $nprdata['image']['height'],
		'mime-type' => $nprdata['image']['mime-type']
	);
	$publish_date = $nprdata['date'];
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<head>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime()</script>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="<?PHP echo strip_tags( $head_excerpt ); ?>" />
		<meta name="keywords" content="<?PHP echo implode( ', ', $keywords ); ?>" />
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
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?php echo $head_title; ?> | Houston Public Media" />
		<meta property="og:url" content="<?php echo $head_perma; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $head_excerpt; ?>" />
		<meta property="og:image" content="<?php echo $thumb; ?>" />
		<meta property="og:image:url" content="<?php echo $thumb; ?>" />
		<meta property="og:image:height" content="<?php echo $thumb_meta['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $thumb_meta['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $thumb_meta['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo str_replace( 'http://','https://', $thumb ); ?>" />
		<meta property="article:published_time" content="<?php echo $publish_date; ?>" />
		<meta property="article:publisher" content="https://www.facebook.com/houstonpublicmedia/" />
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
<?php
	foreach( $nprdata['keywords'] as $keys ) : ?>
		<meta property="article:tag" content="<?php echo $keys; ?>" />
<?php
	endforeach; ?>
<?php
	wp_head(); ?>
		<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
		<script>
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
			googletag.cmd.push(function() {
				var dfpWide = window.innerWidth;
				if ( dfpWide > 1000 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [970, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width ='970px';
				}
				else if ( dfpWide <= 1000 && dfpWide > 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [728, 90], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '728px';
				}
				else if ( dfpWide <= 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [320, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '320px';
				}
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-1').setCategoryExclusion('External').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-2').setCategoryExclusion('External').addService(googletag.pubads());
				googletag.pubads().collapseEmptyDivs(); 
				googletag.enableServices();
			});
		</script>
		<?php hpm_google_tracker(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'body_open' ); ?>
		<?php hpm_fb_sdk(); ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
		<div class="container">
			<?php hpm_site_header(); ?>
		</div>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<!-- /9147267/HPM_Under_Nav -->
				<div id='div-gpt-ad-1488818411584-0'>
					<script>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
					</script>
				</div>