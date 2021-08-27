<?php
/*
Template Name: Series
*/

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
				echo hpm_head_banners( get_the_ID() );
				if ( !empty( get_the_content() ) ) :
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php
				endif;
			endwhile;
			$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
			if ( empty( $cat_no ) ) :
				$cat = new WP_Query( [ 'post_parent' => get_the_ID(), 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1 ] );
			else :
				$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
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
					'posts_per_page' => 15,
					'ignore_sticky_posts' => 1
				];
				if ( !empty( $top ) && $top !== 'None' ) :
					$top_art = new WP_Query( [
						'p' => $top
					] );
					$cat_args['posts_per_page']--;
					$cat_args['post__not_in'] = [ $top ];
				endif;
				$cat = new WP_Query( $cat_args );
			endif; ?>
			<aside>
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
				endif;
				get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<?php
				if ( $cat->have_posts() ) : ?>
			<section class="archive">
		<?php
					if ( !empty( $top_art ) ) :
						if ( $top_art->have_posts() ) :
							while ( $top_art->have_posts() ) : $top_art->the_post();
								get_template_part( 'content', get_post_format() );
							endwhile;
							wp_reset_query();
						endif;
					endif;
					while ( $cat->have_posts() ) : $cat->the_post();
						get_template_part( 'content', get_post_format() );
					endwhile; ?>
			</section>
			<?php
				if ( $cat->found_posts > 15 ) : ?>
			<div class="readmore">
				<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
			</div>
			<?php
				endif;
				wp_reset_postdata();
				if ( !empty( $embeds['bottom'] ) ) :
					echo $embeds['bottom'];
				endif;
			?>
			<?php
				endif; ?>
		</main>
	</div>
<?php get_footer(); ?>
