<?php
	global $curauth;
	global $author_check;
	$head_perma = get_author_posts_url( $curauth->ID );
	$thumb = 'https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg';
	$thumb_meta = array(
		'width' => 1200,
		'height' => 800,
		'mime-type' => 'image/jpeg'
	);
	$head_title = $curauth->display_name." | Houston Public Media";
	$head_excerpt = 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.';
	$head_keywords = 'Houston Public Media,KUHT,TV 8,Houston Public Media Schedule,Educational TV Programs,independent program broadcasts,University of Houston,nonprofit,NPR News,KUHF,Classical Music,Arts & Culture,News 88.7';
	if ( !empty( $author_check ) ) :
		while ( $author_check->have_posts() ) :
			$author_check->the_post();
			$head_excerpt = htmlentities( strip_tags( get_the_content() ), ENT_QUOTES );
			if ( $head_excerpt == '' || $head_excerpt == '<p>Biography pending.</p>' ) :
				$head_excerpt = 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.';
			endif;
			$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE );
			$attach_id = get_post_thumbnail_id( get_the_ID() );
			if ( !empty( $attach_id ) ) :
				$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
				$thumb_meta = array(
					'width' => $feature_img[1],
					'height' => $feature_img[2],
					'mime-type' => get_post_mime_type( $attach_id )
				);
				$thumb = $feature_img[0];
			else :
				$thumb = 'https://cdn.hpm.io/assets/images/HPM_UH_ConnectivityLogo_OUT.jpg';
				$thumb_meta = array(
					'width' => 1200,
					'height' => 800,
					'mime-type' => 'image/png'
				);
			endif;
			$head_categories = get_the_terms( get_the_ID(), 'staff_category' );
			$keywords = array();
			if ( !empty( $head_categories ) ) :
				foreach( $head_categories as $hcat ) :
					$keywords[] = $hcat->name;
				endforeach;
			endif;
			if ( !empty( $keywords ) ) :
				$head_keywords = implode( ', ', $keywords );
			else :
				$head_keywords = 'Houston Public Media,KUHT,TV 8,Houston Public Media Schedule,Educational TV Programs,independent program broadcasts,University of Houston,nonprofit,NPR News,KUHF,Classical Music,Arts & Culture,News 88.7';
			endif;
			$head_title = $curauth->display_name.", ".$author['title']." | Houston Public Media";
		endwhile;
	endif;
	$thumb = str_replace( 'http://', 'https://', $thumb );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# profile: http://ogp.me/ns/profile#">
	<head>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime()</script>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="<?PHP echo strip_tags( $head_excerpt ); ?>" />
		<meta name="keywords" content="<?PHP echo $head_keywords; ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="bitly-verification" content="7777946f1a0a"/>
		<meta name="google-site-verification" content="QOrBnMZ1LXDA9tL3e5WmFUU-oI3JUbDRotOWST1P_Dg" />
		<link rel="shortcut icon" href="https://cdn.hpm.io/assets/images/favicon.ico">
		<link rel="icon" type="image/png" href="https://cdn.hpm.io/assets/images/favicon-192x192.png" sizes="192x192">
		<link rel="apple-touch-icon" sizes="180x180" href="https://cdn.hpm.io/assets/images/apple-touch-icon-180x180.png">
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<meta name="apple-itunes-app" content="app-id=530216229" />
		<meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
		<meta property="og:image" content="<?php echo $thumb; ?>" />
		<meta property="og:image:url" content="<?php echo $thumb; ?>" />
		<meta property="og:image:height" content="<?php echo $thumb_meta['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $thumb_meta['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $thumb_meta['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo $thumb; ?>" />
		<meta property="og:type" content="profile" />
		<meta property="og:title" content="<?php echo $head_title; ?>" />
		<meta property="og:url" content="<?php echo $head_perma; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $head_excerpt; ?>" />
		<meta property="fb:app_id" content="523938487799321" />
		<meta property="fb:admins" content="37511993" />
		<meta property="fb:pages" content="27589213702" />
		<meta property="fb:pages" content="183418875085596" />
		<meta property="profile:first_name" content="<?php echo $curauth->first_name; ?>">
		<meta property="profile:last_name" content="<?php echo $curauth->last_name; ?>">
		<meta property="profile:username" content="<?php echo $curauth->user_nicename; ?>">
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
		wp_head(); ?>
		<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
		<script>
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
			googletag.cmd.push(function() {
				var dfpWide = window.innerWidth;
				if ( dfpWide > 1000 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [970, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '970px';
				}
				else if ( dfpWide <= 1000 && dfpWide > 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [728, 90], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '728px';
				}
				else if ( dfpWide <= 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [320, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '320px';
				}
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-1').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-2').addService(googletag.pubads());
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