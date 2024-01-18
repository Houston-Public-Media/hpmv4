<?php
/*
Template Name: Series-Tiles
*/

get_header(); ?>
	<style>
		.page #main {
			background-color: transparent;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post();
				$header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
				$show_title = get_the_title();
				$show_content = get_the_content();
				echo hpm_head_banners( get_the_ID(), 'series' );
			} ?>
			<div class="party-politics-page">
				<div class="about-party">
<?php
	if ( $show_title == '#TXDecides' ) { ?>
					<div class="show-content">
						<p>Houston Public Media and its partners across the state are collaborating to provide comprehensive coverage on the important legislation, politics and new laws that will impact all Texans.</p>
					</div>
<?php
	} ?>
					<h2 class="title no-bar"><strong><span>ABOUT <?php echo $show_title; ?></span></strong></h2>
					<div class="show-content">
						<?php echo apply_filters( 'the_content', $show_content ); ?>
					</div>
				</div>
				<aside class="column-right">
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
							</script>
						</div>
					</div>
<?php
			if ( !empty( $embeds['twitter'] ) || !empty( $embeds['facebook'] ) ) { ?>
					<section id="embeds">
<?php
				if ( !empty( $embeds['twitter'] ) ) {
					echo '<h4>Twitter</h4>' . $embeds['twitter'];
				}
				if ( !empty( $embeds['facebook'] ) ) {
					echo '<h4>Facebook</h4>' . $embeds['facebook'];
				} ?>
					</section>
<?php
			} ?>
				</aside>
				<section id="search-results">
		<?php
			$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
			$top = get_post_meta( get_the_ID(), 'hpm_series_top', true );
			$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
			$term = reset( $terms );
			if ( empty( $embeds['order'] ) ) {
				$embeds['order'] = 'ASC';
			}
			$cat_args = [
				'cat' => $cat_no,
				'orderby' => 'date',
				'order' => $embeds['order'],
				'posts_per_page' => 15,
				'ignore_sticky_posts' => 1
			];
			if ( !empty( $top ) && $top !== 'None' ) {
				$top_art = new WP_Query([
					'p' => $top
				]);
				$cat_args['posts_per_page'] = 14;
				$cat_args['post__not_in'] = [ $top ];
				if ( $top_art->have_posts() ) {
					while ( $top_art->have_posts() ) {
						$top_art->the_post();
						$ka = 0;
						get_template_part( 'content', get_post_type() );
					}
					$post_num = 14;
				}
				wp_reset_query();
			}
			$cat = new WP_Query( $cat_args );
			if ( $cat->have_posts() ) {
				if ( isset( $ka ) ) {
					$ka += 2;
				} else {
					$ka = 0;
				}
				while ( $cat->have_posts() ) {
					$cat->the_post();
					get_template_part( 'content', get_post_type() );
					$ka += 2;
				}
			} ?>
				</section>
			</div>
		<?php
			if ( $cat->found_posts > 15 ) { ?>
			<div class="readmore">
				<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
			</div>
		<?php
			}
			if ( !empty( $embeds['bottom'] ) ) {
				echo $embeds['bottom'];
			} ?>
		</main>
	</div>
<?php
	wp_reset_query();
	get_footer(); ?>
