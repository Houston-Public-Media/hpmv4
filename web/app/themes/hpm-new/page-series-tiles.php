<?php
/*
Template Name: Series-Tiles
*/

get_header(); ?>
	<style>
		.page-template-page-series-tiles #main {
			background-color: transparent;
		}
		.page-template-page-series-tiles #main > aside {
			background-color: white;
			padding: 1rem;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post();
			$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
			$show_title = get_the_title();
			$show_content = get_the_content();
			echo hpm_head_banners( get_the_ID() ); ?>
		<?php
			endwhile; ?>
			<aside>
<?php
	if ( $show_title == '#TXDecides' ) : ?>
				<div class="show-content">
					<p>Houston Public Media and its partners across the state are collaborating to provide comprehensive coverage on the important legislation, politics and new laws that will impact all Texans.</p>
				</div>
<?php
	endif; ?>
				<h3>About <?php echo $show_title; ?></h3>
				<div class="show-content">
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</div>
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
		<?php
			if ( !empty( $embeds['twitter'] ) || !empty( $embeds['facebook'] ) ) : ?>
				<section id="embeds">
				<?php
					if ( !empty( $embeds['twitter'] ) ) : ?>
						<h4>Twitter</h4>
					<?php
						echo $embeds['twitter'];
					endif;

					if ( !empty( $embeds['facebook'] ) ) : ?>
						<h4>Facebook</h4>
					<?php
						echo $embeds['facebook'];
					endif; ?>
					</section>
			<?php
				endif; ?>
				</aside>
				<section>
		<?php
			$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
			$top = get_post_meta( get_the_ID(), 'hpm_series_top', true );
			$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
			$term = reset( $terms );
			if ( empty( $embeds['order'] ) ) :
				$embeds['order'] = 'ASC';
			endif;
			$cat_args = [
				'cat' => $cat_no,
				'orderby' => 'date',
				'order'   => $embeds['order'],
				'posts_per_page' => 16,
				'ignore_sticky_posts' => 1
			];
			if ( !empty( $top ) && $top !== 'None' ) :
				$top_art = new WP_query( [
					'p' => $top
				] );
				$cat_args['posts_per_page']--;
				$cat_args['post__not_in'] = [ $top ];
				if ( $top_art->have_posts() ) :
					while ( $top_art->have_posts() ) : $top_art->the_post();
						get_template_part( 'content', get_post_format() );
					endwhile;
				endif;
				wp_reset_query();
			endif;
			$cat = new WP_query( $cat_args );
			if ( $cat->have_posts() ) :
				while ( $cat->have_posts() ) : $cat->the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
			endif; ?>
				</section>
		<?php
			if ( $cat->found_posts > 15 ) : ?>
				<div class="readmore">
					<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
				</div>
		<?php
			endif;
			if ( !empty( $embeds['bottom'] ) ) :
				echo $embeds['bottom'];
			endif; ?>
		</main>
	</div>
<?php
	wp_reset_query();
	get_footer(); ?>
