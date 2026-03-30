<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
	$ColumnClass = "col-sm-6 col-md-4 mb-4";
	if ( post_type_archive_title( '', false ) == "Shows" ) {
		$ColumnClass = "col-sm-12";
	}
?>
<div class="<?php echo $ColumnClass; ?>">
	<div class="episodes-content"> 
		<?php if ( has_post_thumbnail() ) { ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
		<?php } ?>
		<div class="content-wrapper">
			<h4 class="content-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
			<p><?php
				echo get_excerpt_by_id_ShowPages( get_the_ID() );
				 ?></p>
		</div>
	</div>
</div>