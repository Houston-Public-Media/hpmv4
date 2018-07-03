<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'The page you requested can&rsquo;t be found [error 404]', 'hpmv2test' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
				<?php
					$ref = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
					$redurl = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
					$domain = (isset($_SERVER['SERVER_NAME'])) ? "http://".$_SERVER['SERVER_NAME'] : '';
					$time = date('m/d/Y  H:i:s');
					if (empty($ref)) :
						$ref = 'No referring URL';
						$ref2 = 'No referring URL';
					else :
						$ref2 = '<a href="'.$ref.'">'.$ref.'</a>';
					endif;
					$text = "There has been an error reported on your website.  Please rectify this at your earliest convenience:%0A%0AReferring Site/Page: ".$ref."%0APage Requested: ".$domain.$redurl."%0ATime: ".$time;

					$url_find = array(' ','!','"','#','$','&','\'','(',')',',','-',':','/',';');
					$url_replace = array('%20','%21','%22','%23','%24','%26','%27','%28','%29','%2C','%2D','%3A','%2F','%3B'); ?>
					<p><?php _e( '... but we think we can help you.' ,'hpmv2' ); ?></p>
					<p><?php _e( 'You were incorrectly referred to this page by:' ,'hpmv2' ); ?> <?php echo $ref2; ?></p>
					<p><?php _e( 'Try searching our database:' ); ?></p>
					<div class="search-results-form"><?php get_search_form(); ?></div>
					<p><?php _e( 'We suggest you try one of the links below:' ,'hpmv2' ); ?></p>
					<ul>
						<li><a href="//www.houstonpublicmedia.org/"><?php _e( 'Houston Public Media Homepage' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/news"><?php _e( 'HPM News' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/arts"><?php _e( 'HPM Arts &amp; Culture' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/education"><?php _e( 'HPM Education' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/tv8"><?php _e( 'TV 8 Schedule' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/news887"><?php _e( 'News 88.7 Schedule' ,'hpmv2' ); ?></a></li>
						<li><a href="//www.houstonpublicmedia.org/classical"><?php _e( 'Classical Schedule' ,'hpmv2' ); ?></a></li>
					</ul>
					<h3>Help us to help you ...</h3>
					<p>In order to improve our site, you can inform us that someone else has an incorrect link to our site, or that one of our links is broken. We will do our best to address the issue.</p>
					<p><a href="mailto:webmaster@houstonpublicmedia.org?subject=Page%20Not%20Found&body=<?PHP echo str_replace($url_find,$url_replace,$text); ?>">Report this broken link &gt;&gt;</a></p>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
			<aside class="column-right">
				<?php hpm_top_posts(); ?>
				<div class="sidebar-ad">
					<div id="div-gpt-ad-1394579228932-1">
						<h4>Support Comes From</h4>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
