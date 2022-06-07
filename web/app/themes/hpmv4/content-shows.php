<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
<?php if ( has_post_thumbnail() ) : ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
	<?php endif; ?>
	<div class="card-content">
		<header class="entry-header">
			<h3>Show</h3>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		</header>
		<div class="entry-summary">
			<p><?php
				$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
				$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true );
				if ( !empty( $show['times'] ) && !empty( $show['hosts'] ) ) :
					echo $show['times']." with ".$show['hosts'];
				elseif ( empty( $show['times'] ) && !empty( $show['hosts'] ) ) :
					echo "With ".$show['hosts'];
				elseif ( !empty( $show['times'] ) && empty( $show['hosts'] ) ) :
					echo $show['times'];
				endif; ?></p>
		</div>
		<footer class="entry-footer">
			<?php
				$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
				if ( $tags_list ) :
					printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
						_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
						$tags_list
					);
				endif;
				edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
	</div>
</article>