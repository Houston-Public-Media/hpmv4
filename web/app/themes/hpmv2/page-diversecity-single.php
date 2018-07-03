<?php
/*
Template Name: DiverseCity Article
Template Post Type: post
*/

get_header('diversecity-single'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
					<?php
						the_title( '<h1 class="entry-title">', '</h1>' );
						the_excerpt();
						$single_id = get_the_ID();
					?>
                    <div class="byline-date">
						<?PHP
						coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
						$pub = get_the_time( 'U' );
						$mod = get_the_modified_time( 'U' );
						$desc = $mod - $pub;
						$mod_time = get_post_meta( $single_id, 'hpm_no_mod_time', true );
						if ( $pub !== $mod && $desc > 900 && $mod > $pub && $mod_time == 0 ) :
							$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Last Updated: <time class="updated" datetime="%3$s">%4$s</time>)';
						endif;

						$time_string = sprintf( $time_string,
							esc_attr( get_the_date( 'c' ) ),
							get_the_date(),
							esc_attr( get_the_modified_date( 'c' ) ),
							get_the_modified_date()
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
							$time_string
						); ?>
					</div>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
					<div id="article-share">
					<?php
						$uri_title = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES ) );
						$uri_link = rawurlencode( get_the_permalink() );
						$uri_excerpt = rawurlencode( get_the_excerpt() ); ?>
						<h4>Share</h4>
						<div class="article-share-icon">
							<a href="https://www.facebook.com/sharer.php?u=<?php echo $uri_link; ?>" target="_blank" data-dialog="400:368">
								<span class="fa fa-facebook" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							 <a href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $uri_link; ?>" target="_blank" data-dialog="364:250">
								<span class="fa fa-twitter" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php echo $uri_title; ?>%0A%0A<?php echo $uri_link; ?>">
								<span class="fa fa-envelope" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="http://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $uri_link; ?>" target="_blank" data-dialog="600:471">
								<span class="fa fa-linkedin" aria-hidden="true"></span>
							</a>
						</div>
					</div>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
				<?PHP	
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) :
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					endif;
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<?php
				endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer('diversecity'); ?>
