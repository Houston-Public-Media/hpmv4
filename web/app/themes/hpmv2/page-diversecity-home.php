<?php
/*
Template Name: DiverseCity Home
*/
get_header('diversecity'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) : the_post();
		echo do_shortcode( get_the_content() );
	endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer('diversecity'); ?>