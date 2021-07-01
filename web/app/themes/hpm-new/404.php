<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>
	<style>
		.search-results-form {
			margin: 1em 0;
			font: 100 1.5em/1em var(--hpm-font-main);
			color: rgb(161,161,162);
		}
		.search-results-form .search-form {
			display: flex;
			margin: 0.25em 0;
			padding: 0 1em 0 0;
		}
		.search-results-form .search-form label {
			flex: 1;
			flex-grow: 2;
			flex-basis: auto;
		}
		.search-results-form .search-form .search-field {
			border: 0;
			outline: 0;
			background-color: rgb(243,244,244);
			color: rgb(142,144,144);
			font: 500 1.125em/1.25em var(--hpm-font-main);
			padding: 0.25em 0.125em;
			width: 100%;
		}
		.search-results-form button.search-submit.screen-reader-text {
			display: block;
			background-color: rgb(180,213,223);
			overflow: initial;
			width: 3.5em;
			height: 3.5em;
			color: #00b0bc;
			clip: initial;
			position: initial !important;
			border: 0;
			outline: 0;
		}
		.search-results-form .fas {
			font-size: 1.5em;
			line-height: 1em;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<article class="error-404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'The page you requested can&rsquo;t be found [error 404]', 'hpmv2test' ); ?></h1>
				</header><!-- .page-header -->

				<div class="entry-content">
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

					$url_find = [ ' ','!','"','#','$','&','\'','(',')',',','-',':','/',';' ];
					$url_replace = [ '%20','%21','%22','%23','%24','%26','%27','%28','%29','%2C','%2D','%3A','%2F','%3B' ]; ?>
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
			</article><!-- .error-404 -->
			<aside class="column-right">
				<?php hpm_top_posts(); ?>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</section>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
