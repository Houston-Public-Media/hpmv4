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
			<section class="error-404 not-found column-left">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'The page you requested can&rsquo;t be found [error 404]', 'hpmv4' ); ?></h1>
				</header>
				<div class="page-content">
				<?php
					$ref = ( $_SERVER['HTTP_REFERER'] ?? '' );
					$redurl = ( $_SERVER['REQUEST_URI'] ?? '' );
					$domain = ( isset( $_SERVER['SERVER_NAME'] ) ? "https://".$_SERVER['SERVER_NAME'] : '' );
					$time = date( 'm/d/Y  H:i:s' );
					if ( empty( $ref ) ) {
						$ref = 'No referring URL';
						$ref2 = 'No referring URL';
					} else {
						$ref2 = '<a href="'.$ref.'">'.$ref.'</a>';
					}
					$text = "There has been an error reported on your website.  Please rectify this at your earliest convenience:%0A%0AReferring Site/Page: ".$ref."%0APage Requested: ".$domain.$redurl."%0ATime: ".$time;

					$url_find = [ ' ','!','"','#','$','&','\'','(',')',',','-',':','/',';' ];
					$url_replace = [ '%20','%21','%22','%23','%24','%26','%27','%28','%29','%2C','%2D','%3A','%2F','%3B' ]; ?>
					<p><?php _e( '... but we think we can help you.' ,'hpmv4' ); ?></p>
					<p><?php _e( 'You were incorrectly referred to this page by:' ,'hpmv4' ); ?> <?php echo $ref2; ?></p>
					<p><?php _e( 'Try searching our database:' ); ?></p>
					<div class="search-results-form"><?php get_search_form(); ?></div>
					<p><?php _e( 'We suggest you try one of the links below:' ,'hpmv4' ); ?></p>
					<ul>
						<li><a href="/"><?php _e( 'Houston Public Media Homepage' ,'hpmv4' ); ?></a></li>
						<li><a href="/news/"><?php _e( 'HPM News' ,'hpmv4' ); ?></a></li>
						<li><a href="/arts-culture/"><?php _e( 'HPM Arts &amp; Culture' ,'hpmv4' ); ?></a></li>
						<li><a href="/education/"><?php _e( 'HPM Education' ,'hpmv4' ); ?></a></li>
						<li><a href="/tv8/"><?php _e( 'TV 8 Schedule' ,'hpmv4' ); ?></a></li>
						<li><a href="/news887/"><?php _e( 'News 88.7 Schedule' ,'hpmv4' ); ?></a></li>
						<li><a href="/classical/"><?php _e( 'Classical Schedule' ,'hpmv4' ); ?></a></li>
					</ul>
					<h3>Help us to help you ...</h3>
					<p>In order to improve our site, you can inform us that someone else has an incorrect link to our site, or that one of our links is broken. We will do our best to address the issue.</p>
					<p><a href="mailto:webmaster@houstonpublicmedia.org?subject=Page%20Not%20Found&body=<?PHP echo str_replace( $url_find, $url_replace, $text ); ?>">Report this broken link &gt;&gt;</a></p>
				</div>
			</section>
			<aside class="column-right">
				<?php get_template_part( 'sidebar' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>
