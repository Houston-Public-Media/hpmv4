<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
// Show In Depth Story on home page starts here
global $ika;
$indepth = false;
$extra = 'card card-medium';
$size = 'thumbnail';

$postClass = get_post_class();
if ( is_home() && in_array( 'category-in-depth', $postClass ) ) {
	$indepth = true;
} ?>
<div class="col-12 col-lg-9">
	<div class="news-slider">
		<div class="row">
			<div class="col-sm-6">
				<div class="news-slider-info">
					<?php echo ( $indepth ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.houstonpublicmedia.org/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
					<h4 class="text-light-gray"><?php echo hpm_top_cat( get_the_ID() ); ?></h4>
					<h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
							if ( is_front_page() ) {
								$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
								if ( !empty( $alt_headline ) ) {
									echo $alt_headline;
								} else {
									the_title();
								}
							} else {
								the_title();
							} ?></a></h2>
					<p><?php $summary = strip_tags( get_the_excerpt() );
					echo $summary;
					?></p>
				</div>
			</div>
			<div class="col-sm-6"><?php if ( has_post_thumbnail() ) { ?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $size ) ?></a>
			<?php } ?></div>
		</div>
	</div>
</div>


