<?php
/*
Template Name: Series
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			$page_head_class = hpm_head_banners( get_the_ID() );
			while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header<?php echo $page_head_class; ?>">
				<?php
					the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
				</div><!-- .entry-content -->
			</article>
		<?php
			endwhile;
			$series_check = get_query_var( 'pagename' );
			if ( $series_check == 'series' ) :
				$cat = new WP_query( array( 'post_parent' => get_the_ID(), 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
			else :
				$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
				$top =  get_post_meta( get_the_ID(), 'hpm_series_top', true );
				$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
				$term = reset( $terms );
				if ( empty( $embeds['order'] ) ) :
					$embeds['order'] = 'ASC';
				endif;
				$cat_args = array(
					'cat' => $cat_no,
					'orderby' => 'date',
					'order'   => $embeds['order'],
					'posts_per_page' => 15,
					'ignore_sticky_posts' => 1
				);
				if ( !empty( $top ) && $top !== 'None' ) :
					$top_art = new WP_query( array(
						'p' => $top
					) );
					$cat_args['posts_per_page'] = 14;
					$cat_args['post__not_in'] = array( $top );
				endif;
				$cat = new WP_query( $cat_args );
			endif; ?>
			<aside class="column-right">
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
			<section id="search-results">
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
					endwhile;
					if ( $cat->found_posts > 15 ) : ?>
				<div class="readmore">
					<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
				</div>
		<?php
					endif;
					wp_reset_postdata();
					if ( !empty( $embeds['bottom'] ) ) :
						echo $embeds['bottom'];
					endif; ?>
			</section>
			<?php
				endif; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
