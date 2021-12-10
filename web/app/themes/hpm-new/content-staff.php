<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
$staff = get_post_meta( get_the_ID(), 'hpm_staff_meta', true );
$author_bio = get_the_content();
if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" || $author_bio == '' ) :
	$bio_link = false;
else :
	$bio_link = true;
endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
	<?php endif; ?>
	<div class="card-content">
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="social-wrap">
<?php
		if (!empty( $staff['facebook'] ) ) : ?>
			<div class="social-icon facebook">
				<a href="<?php echo $staff['facebook']; ?>" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
			</div>
<?php	endif;
		if ( !empty( $staff['twitter'] ) ) : ?>
				<div class="social-icon twitter">
					<a href="<?php echo $staff['twitter']; ?>" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
				</div>
<?php	endif;
		if (!empty( $staff['linkedin'] ) ) : ?>
				<div class="social-icon linkedin">
					<a href="<?php echo $staff['linkedin']; ?>" target="_blank"><span class="fab fa-linkedin-in" aria-hidden="true"></span></a>
				</div>
<?php	endif;
		if ( !empty( $staff['email'] ) ) : ?>
	<div class="social-icon">
		<a href="mailto:<?php echo $staff['email']; ?>" target="_blank"><span class="fas fa-envelope" aria-hidden="true"></span></a>
	</div>
<?php	endif; ?>
			</div>
		</header>
		<div class="entry-summary">
			<p><?php echo $staff['title']; ?></p>
		</div>
		<footer class="entry-footer">
			<?php
				$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
				if ( $tags_list ) :
					printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
						_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
						$tags_list
					);
				endif;
				edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
	</div>
			</article>