<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
// Show In Depth Story on home page starts here

$indepth = false;
$extra = 'card card-medium';
$size = 'thumbnail';
 ?>
<div class="col-12 col-lg-9">
	<div class="news-slider">
		<div class="row">
			<div class="col-sm-6">
				<div class="news-slider-info">
					<h4 class="text-light-gray"><?php echo hpm_top_cat( 494124 ); ?></h4>
					<h2><a href="<?php echo get_permalink(494124); ?>" rel="bookmark"><?php
							if ( is_front_page() ) {
								$alt_headline = get_post_meta( 494124, 'hpm_alt_headline', true );
								if ( !empty( $alt_headline ) ) {
									echo $alt_headline;
								} else {
                                    echo get_the_title(494124);
								}
							} else {
								echo get_the_title(494124);
							} ?></a></h2>
					<p><?php $summary = strip_tags( get_the_excerpt(494124) );
					echo $summary;
					?></p>
				</div>
			</div>
			<div class="col-sm-6"><?php if ( has_post_thumbnail() ) { ?>
				<a class="post-thumbnail" href="<?php echo get_the_permalink(494124); ?>">
<?php
                    $img = get_the_post_thumbnail_url(494124, $size);

?>
                    <img src="http://localhost/test.png">
                </a>
			<?php } ?></div>
		</div>
	</div>
</div>


