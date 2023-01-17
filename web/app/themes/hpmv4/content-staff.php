<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
$staff = get_post_meta( get_the_ID(), 'hpm_staff_meta', true );
$author_bio = get_the_content();
if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" || $author_bio == '' ) {
	$bio_link = false;
} else {
	$bio_link = true;
} ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
	if ( has_post_thumbnail() ) {
		if ( $bio_link ) { ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
<?php
		} else { ?>
	<div class="post-thumbnail"><?php the_post_thumbnail( 'thumbnail' ); ?></div>
<?php
		}
	} ?>
	<div class="card-content">
		<header class="entry-header">
			<h2 class="entry-title"><?php
				if ( $bio_link ) {
					?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a><?php
				} else {
					the_title();
				} ?></h2>
			<?php // echo ( !empty( $staff['pronouns'] ) ? '<p class="staff-pronouns">(' . $staff['pronouns'] . ')</p>' : '' ) ?>
			<div class="social-wrap">
			<?php
				echo ( !empty( $staff['facebook'] ) ? '<div class="social-icon facebook"><a href="' . $staff['facebook'] . '" target="_blank">' . hpm_svg_output( 'facebook' ) . '</a></div>' : '' );
				echo ( !empty( $staff['twitter'] ) ? '<div class="social-icon twitter"><a href="' . $staff['twitter'] . '" target="_blank">' . hpm_svg_output( 'twitter' ) .'</a></div>' : '' );
				echo ( !empty( $staff['linkedin'] ) ? '<div class="social-icon linkedin"><a href="' . $staff['linkedin'] .'" target="_blank">' . hpm_svg_output( 'linkedin' ) . '</a></div>' : '' );
				echo ( !empty( $staff['email'] ) ? '<div class="social-icon"><a href="mailto:' . $staff['email'] . '" target="_blank">' . hpm_svg_output( 'envelope' ) . '</a></div>' : '' ); ?>
			</div>
		</header>
		<div class="entry-summary">
			<p><?php echo $staff['title']; ?></p>
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
</article>