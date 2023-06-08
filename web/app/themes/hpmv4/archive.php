<?php
/**
 * @package WordPress
 * @subpackage hpmv4
 * @since hpmv4 1.0
 */
if ( is_category() ) {
	$cat = get_term_by( 'name', single_cat_title( '', false ), 'category' );
	if ( empty( $wp_query->query_vars['paged'] ) ) {
		if ( $cat->parent == 9 ) {
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
		} elseif ( $cat->parent == 5 ) {
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
		}
		if ( !empty( $args ) ) {
			$series_page = new WP_query( $args );
			if ( $series_page->have_posts() ) {
				while ( $series_page->have_posts() ) {
					$series_page->the_post();
					header( "HTTP/1.1 301 Moved Permanently" );
					header( 'Location: ' . get_the_permalink() );
					exit;
				}
				wp_reset_postdata();
			}
		}
		if ( $cat->term_id == 29328 ) {
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: /news/indepth/' );
			exit;
		}
	}
}
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) { ?>
			<header class="page-header">
				<?php
					if ( is_post_type_archive( [ 'podcasts', 'shows' ] ) ) { ?>
					<h1 class="page-title"><?PHP echo ucwords( get_post_type() ); ?></h1>
				<?php
					} else {
						the_archive_title( '<h1 class="page-title">', '</h1>' );
					}
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header>
			<section id="search-results">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'content', get_post_type() );
			}

			if ( is_post_type_archive( [ 'podcasts', 'shows' ] ) ) {
				HPM_Podcasts::list_inactive( $post->post_type );
			} else {
				the_posts_pagination( [
					'prev_text' => __( '&lt;', 'hpmv4' ),
					'next_text' => __( '&gt;', 'hpmv4' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv4' ) . ' </span>',
				] );
			}

		// If no content, include the "No posts found" template.
		} else {
			get_template_part( 'content', 'none' );
		}
		?>
			</section>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>