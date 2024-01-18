<?php
/*
	Template Name: Full Width Article
	Template Post Type: post
*/

get_header();
if ( is_preview() ) { ?>
	<div id="preview-warn">You're viewing a preview. Some things might be a little squirrelly. --The Management</div>
<?php } ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) {
		the_post();
		$postClass = get_post_class(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
						echo hpm_pub_time_banner( get_the_time( 'U' ) );
						echo ( in_array( 'category-in-depth', $postClass ) ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.houstonpublicmedia.org/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
					<h3><?php echo hpm_top_cat(get_the_ID()); ?></h3>
					<?php
						the_title('<h1 class="entry-title">', '</h1>');
						the_excerpt();
						$single_id = get_the_ID(); ?>
					<div class="byline-date">
                        <div class="byline-date-text">
					<?PHP
						coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
						$pub = get_the_time( 'U' );
						$mod = get_the_modified_time( 'U' );
						$desc = $mod - $pub;
						$mod_time = get_post_meta( $single_id, 'hpm_no_mod_time', true );
						if ( $desc > 900 && $mod > $pub && $mod_time == 0 ) {
							$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Last Updated: <time class="updated" datetime="%3$s">%4$s</time>)';
						}

						$time_string = sprintf(
							$time_string,
							esc_attr( get_the_date( 'c' ) ),
							get_the_date(),
							esc_attr( get_the_modified_date( 'c' )),
							get_the_modified_date()
						);

						printf(
							'<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x('Posted on', 'Used before publish date.', 'hpmv4'),
							$time_string
						); ?></div>
                    <?php hpm_article_share(); ?>
					</div>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
					<div class="tags-links">
					<?PHP
						$cat_list = get_the_category_list( ' ', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
						if ( $cat_list ) :
							echo $cat_list;
						endif;
						$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
						if ( $tags_list ) :
							echo $tags_list;
						endif;
						edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' );
					?>
					</div>
				</footer>
			</article>
<?php
	} ?>
        <div id="author-wrap">
            <div class="row justify-content-center">
                <div class="col-sm-10 ">
                    <?php echo author_footer( get_the_ID(), "fullwidth" ); ?>
                </div>
            </div>
        </div>
	</main>
</div>
<?php get_footer(); ?>