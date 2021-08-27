<?php
/*
Template Name: Wide with Articles
*/

get_header();
$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
if ( !empty( $embeds ) ) :
	echo $embeds['bottom'];
endif; ?>
	<style>
		@media screen and (min-width: 34rem) {
			#main > article {
    			grid-column: 1 / -1 !important;
				grid-row: -2;
			}
		}
		@media screen and (min-width: 64.25rem) {
			#main > aside {
				grid-row: 2;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
				echo hpm_head_banners( get_the_ID() );
				while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
			<?php
				endwhile;
				$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				if ( !empty( $cat_no ) ) :
					$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
					$term = reset( $terms );
					$cat = new WP_query( array(
						'cat' => $cat_no,
						'orderby' => 'date',
						'order'   => 'DESC',
					) );
					if ( $cat->have_posts() ) : ?>
				<section class="archive">
		<?php
						while ( $cat->have_posts() ) : $cat->the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
						wp_reset_postdata(); ?>
				</section>
				<aside>
					<?php get_template_part( 'sidebar', 'none' ); ?>
				</aside>
				<div class="readmore">
					<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
				</div>
			<?php
					endif;
				endif; ?>
		</main>
	</div>
<?php get_footer(); ?>