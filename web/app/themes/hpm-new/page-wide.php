<?php
/*
Template Name: Full-Width Page
*/
$pagename = get_query_var( 'pagename' );
$anc = get_post_ancestors( get_the_ID() );
get_header();
$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
if ( !empty( $embeds ) ) :
	echo $embeds['bottom'];
endif; ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) :
		the_post();
		echo hpm_head_banners( get_the_ID() ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php
					the_content( sprintf(
						__( 'Continue reading %s', 'hpmv2' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );
				?>
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
	<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>