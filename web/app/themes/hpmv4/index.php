<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
			if ( have_posts() ) {
				if ( is_home() && ! is_front_page() ) { ?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>
<?php		} ?>
			<section id="search-results">
<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'content', get_post_format() );
			}

			the_posts_pagination([
				'prev_text'          => __( 'Previous page', 'hpmv4' ),
				'next_text'          => __( 'Next page', 'hpmv4' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv4' ) . ' </span>',
			]);

		} else {
			get_template_part( 'content', 'none' );
		} ?>
			</section>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>