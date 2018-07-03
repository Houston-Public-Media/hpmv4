<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header(); ?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<!-- Bloopty boop -->
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<section id="search-results">
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text' => __( '&lt;', 'hpmv2' ),
				'next_text' => __( '&gt;', 'hpmv2' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv2' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>
			</section>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>