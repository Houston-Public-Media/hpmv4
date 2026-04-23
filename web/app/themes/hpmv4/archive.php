<?php
/**
 * @package WordPress
 * @subpackage hpmv4
 * @since hpmv4 1.0
 */
$pod_id = 0;
if ( is_category() ) {
	$cat = get_term_by( 'name', single_cat_title( '', false ), 'category' );
	if ( empty( $wp_query->query_vars['paged'] ) && $cat !== false ) {
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
	global $wpdb;
	$podcast = $wpdb->get_results( "SELECT post_id FROM wp_postmeta WHERE meta_key = 'hpm_pod_cat' AND meta_value = {$cat->term_id}" );
	if ( !empty( $podcast ) ) {
		$pod_id = $podcast[0]->post_id;
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
					} elseif ( !empty( $pod_id ) ) { ?>
					<h1 class="page-title">Podcast: <?PHP echo get_the_title( $pod_id ); ?></h1>
					<div class="station-social" style="margin-block-start: 1rem;">
						<div class="badges-box" style="width: max-content; padding: 1rem;">
							<h3 style="color: white;"><?php echo get_the_content( '', false, $pod_id ); ?></h3>
							<?php echo HPM_Podcasts::show_social( $pod_id, false, '', true ); ?>
						</div>
					</div>
				<?php
					} else {
						the_archive_title( '<h1 class="page-title">', '</h1>' );
					}
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header>
<?php
	if ( is_category() && $cat->term_id === 13766 ) {
		// Full Menu Sponsor ?>
		<aside class="column-right">
			<div class="hpm-promo-wrap"><div id="full-menu-sponsor" class="top-banner"><h4>The Full Menu is sponsored by</h4><a href="https://www.centralmarket.com/?utm_medium=display&utm_source=npr&utm_campaign=fullmenu&utm_content=npr_banner"><img src="https://cdn.houstonpublicmedia.org/assets/images/CM-Logo-300x25016.jpg.webp" alt="Support for the Full Menu comes from Central Market"></a></div></div>
		</aside>
<?php
	}
?>
			<section id="search-results">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'content', get_post_type() );
			}

			if ( is_post_type_archive( [ 'podcasts', 'shows' ] ) ) {
				HPM_Podcasts::list_inactive( $post->post_type );
			} else {
				$max_pages = 0;
				if ( !empty( $cat->max_num_pages ) ) {
					$max_pages = $cat->max_num_pages;
				}
				echo '<div>' . hpm_custom_pagination( $max_pages ) . '<p>&nbsp;</p></div>';
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