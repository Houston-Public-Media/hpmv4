<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
global $ka;
$postClass = get_post_class();
if ( $ka == 0 ) { ?>
<div class="col-8">
	<div class="row">
		<div class="col-5">
			<div class="blue-label">
				<strong>Breaking News</strong>
				<span>LOCAL</span>
			</div>
			<div class="time-category">
				<span>11:12 AM</span>
				<strong class="text-light-gray text-uppercase"><?php echo hpm_top_cat( get_the_ID() ); ?></strong>
			</div>
			<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
					if ( is_front_page() ) {
						$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
						if ( !empty( $alt_headline ) ) {
							echo $alt_headline;
						} else {
							the_title();
						}
					} else {
						the_title();
					} ?></a></h1>
			<p><?php $summary = strip_tags( get_the_excerpt() );
				echo $summary;?></p>
		</div>
		<div class="col-7">
			<div class="box-img breaking-news-img">
				<img src="<?php echo get_template_directory_uri(); ?>/images/news-img.jpg">
			</div>
		</div>
	</div>
</div>
<?php }  ?>
<!-- /.col-8 -->
<div class="col-4">
	<ul class="news-listing">
<?php if ( $ka == 1 ) { echo "tras"; ?>
		<li>
			<div class="d-flex flex-row-reverse">
				<div class="col-5">
					<div class="box-img">
						<?php if ( has_post_thumbnail() ) { ?>
							<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail() ?></a>
						<?php } ?>
					</div>
				</div>
				<div class="col-7">
					<h4><?php echo hpm_top_cat( get_the_ID() ); ?></h4>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</div>
		</li>
<?php } ?>
	</ul>
</div>













































<!--
<article id="post-<?php the_ID(); ?>" <?php post_class( $extra ); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $size ) ?></a>
	<?php } ?>
	<div class="card-content">
		<header class="entry-header">
			<?php echo ( $indepth ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.houstonpublicmedia.org/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
			<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
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
			<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
		</header>
		<div class="entry-summary">
			<p><?php
			$summary = strip_tags( get_the_excerpt() );
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( 'F j, Y' )
			);

			printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Posted on', 'Used before publish date.', 'hpmv4' ),
				$time_string
			);
			echo " &middot; ".$summary; ?></p>
		</div>
		<footer class="entry-footer">
			<?php
				$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
				if ( $tags_list ) {
					printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
						_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
						$tags_list
					);
				}
				edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
	</div>
</article> -->