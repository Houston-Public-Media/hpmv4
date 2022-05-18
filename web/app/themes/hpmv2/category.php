<?php
	$cat = get_term_by('name', single_cat_title('',false), 'category');
	if ( ( $cat->parent == 9 || $cat->parent == 5 ) && empty( $wp_query->query_vars['paged'] ) ) :
		if ( $cat->parent == 9 ) :
			$args = [
				'post_type' => 'page',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => [[
					'key' => 'hpm_series_cat',
					'compare' => '=',
					'value' => $cat->term_id
				]]
			];
		else :
			$args = [
				'post_type' => 'shows',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => [[
					'key' => 'hpm_shows_cat',
					'compare' => '=',
					'value' => $cat->term_id
				]]
			];
		endif;
		if ( !empty( $args ) ) :
			$series_page = new WP_query( $args );
			if ( $series_page->have_posts() ) :
				while( $series_page->have_posts() ) :
					$series_page->the_post();
					header("HTTP/1.1 301 Moved Permanently");
					header('Location: '.get_the_permalink());
					exit;
				endwhile;
				wp_reset_postdata();
			endif;
		endif;
		if ( $cat->term_id == 29328 ) :
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: /news/indepth/' );
			exit;
		endif;
	endif;
	get_header(); ?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="entry-title"><?php single_cat_title(); ?></h1>
				<?php echo category_description(); ?>
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
