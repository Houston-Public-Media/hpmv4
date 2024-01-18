<?php
/*
Template Name: Full-Width Page
*/
	get_header();
	$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
	if ( !empty( $embeds ) ) {
		echo $embeds['bottom'];
	} ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) {
		the_post();
		echo hpm_head_banners( get_the_ID(), 'page' ); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php echo hpm_head_banners( get_the_ID(), 'entry' ); ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
<?php
	} ?>
		</main>
	</div>

<?php get_footer(); ?>