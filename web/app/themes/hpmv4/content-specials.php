<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true ); ?>


<div class="col-sm-12">
	<div class="episodes-content">
		<?php if ( has_post_thumbnail() ) { ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
		<?php } ?>
		<div class="content-wrapper"> <span class="date"><?php echo date( 'F j, Y', get_the_date('U')); ?></span>
			<h4 class="content-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
			<p><?php //echo strip_tags( get_the_excerpt() );

				echo get_excerpt_by_id_ShowPages(get_the_ID());
				$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
				$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true );
				if ( !empty( $show['times'] ) && !empty( $show['hosts'] ) ) {
					echo $show['times']." with ".$show['hosts'];
				} elseif ( empty( $show['times'] ) && !empty( $show['hosts'] ) ) {
					echo "With ".$show['hosts'];
				} elseif ( !empty( $show['times'] ) && empty( $show['hosts'] ) ) {
					echo $show['times'];
				} ?></p>
		</div>
	</div>
</div>

