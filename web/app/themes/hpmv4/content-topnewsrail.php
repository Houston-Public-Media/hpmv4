<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
global $ka;
$postClass = get_post_class();
?>
<li>
	<h4 class="text-light-gray text-center" style="color:#237bbd;"><?php echo hpm_top_cat( get_the_ID() ); ?></h4>
	<h3><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
</li>