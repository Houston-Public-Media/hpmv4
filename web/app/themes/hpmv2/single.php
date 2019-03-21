<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header('single');
if ( is_preview() ) : ?>
	<div id="preview-warn">You're viewing a preview. Some things might be a little squirrelly.  --The Management</div>
<?php endif; ?>
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
						);
					?>
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
						$uri_title = rawurlencode( html_entity_decode( get_the_title() ) );
						$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$uri_excerpt = rawurlencode( get_the_excerpt() ); ?>
						<h4>Share</h4>
						<div class="article-share-icon">
							<a href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" target="_blank" data-dialog="400:368">
								<span class="fa fa-facebook" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							 <a href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" target="_blank" data-dialog="364:250">
								<span class="fa fa-twitter" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
								<span class="fa fa-envelope" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="http://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
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
			<aside class="column-right">
			<?php
				$categories = get_the_category( get_the_ID() );
				foreach ($categories as $cats) :
					$anc = get_ancestors( $cats->term_id, 'category' );
					if ( in_array( 9, $anc ) ) :
						$series = new WP_query( array(
							'cat' => $cats->term_id,
							'orderby' => 'date',
							'order'   => 'ASC',
							'posts_per_page' => 7
						) );
						if ( $series->have_posts() ) :
							$series_page = new WP_query( array( 'meta_key' => 'hpm_series_cat', 'meta_value' => $cats->term_id, 'post_type' => 'page' ) );
							if ( $series_page->have_posts() ) :
								while( $series_page->have_posts() ) :
									$series_page->the_post();
									$series_link = get_the_permalink();
								endwhile;
								wp_reset_postdata();
							else :
								$series_link = "/topics/".$cats->slug;
							endif; ?>
				<div id="current-series">
					<h4><a href="<?php echo $series_link; ?>">More from <?php echo $cats->cat_name; ?></a></h4>
						<?php
							while( $series->have_posts() ) :
								$series->the_post(); ?>
					<article class="related-content<?php echo ( $single_id == get_the_ID() ? ' current' : '' ); ?>">
					<?php
						if ( has_post_thumbnail() ) : ?>
						<div class="related-image" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
							<a href="<?PHP the_permalink(); ?>" class="post-thumbnail"></a>
						</div>
						<div class="related-text">
					<?php
						else : ?>
						<div class="related-text-full">
					<?php
						endif; ?>
							<h2><a href="<?php the_permalink(); ?>"><?PHP the_title(); ?></a></h2>
						</div>
					</article>
					<?php
							endwhile; ?>
				</div>
				<?php	
						endif;
					endif;
				endforeach;
				wp_reset_postdata();
			    get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<div id="author-wrap">
			<?php
				$author_terms = get_the_terms( get_the_ID(), 'author');
				if ( !empty( $author_terms ) ) :
					$matches = [];
					preg_match( "/([a-z\-]+) ([0-9]{1,3})/", $author_terms[0]->description, $matches );
				    if ( !empty( $matches ) ) :
					    $author_name = $matches[1];
				        $authid = $matches[2];
					    $author_check = new WP_Query( [
						    'post_type' => 'staff',
						    'name' => $author_name,
						    'post_status' => 'publish'
					    ] );
						if ( !$author_check->have_posts() ) :
							$author_check = new WP_Query( [
								'post_type' => 'staff',
								'post_status' => 'publish',
								'meta_query' => [ [
									'key' => 'hpm_staff_authid',
									'compare' => '=',
									'value' => $authid
								] ]
							] );
						endif;
                    endif;
					if ( $author_check->have_posts() ) :
						while ( $author_check->have_posts() ) :
							$author_check->the_post();
							$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE );
							$author_id = get_post_meta( get_the_ID(), 'hpm_staff_authid', TRUE ); ?>
				<div class="author-info-wrap">
					<div class="author-image">
						<?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
					</div>
					<div class="author-info">
						<h2><?php the_title(); ?></h2>
						<h3><?php echo $author['title']; ?></h3>
						<div class="author-social">
				<?php
							if (!empty($author['facebook'])) : ?>
							<div class="social-icon">
								<a href="<?php echo $author['facebook']; ?>" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
							</div>
				<?php
							endif;
							if (!empty($author['twitter'])) : ?>
							<div class="social-icon">
								<a href="<?php echo $author['twitter']; ?>" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
							</div>
				<?php
							endif; 
							$author_bio = get_the_content();
							if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) :
								$author_bio = '';
							endif; ?>
						</div>
						<p><?php echo wp_trim_words( $author_bio, 50, '...' ); ?></p>
						<p><a href="<?PHP echo get_the_permalink(); ?>">More Information</a></p>
					</div>
				<?php
						endwhile; ?>
				</div>
				<div class="author-other-stories">
				<?php
						$q = new WP_query( array(
							'posts_per_page' => 5,
							'post__not_in' => array($single_id),
							'author' => $author_id,
							'post_type' => 'post',
							'post_status' => 'publish'
						) );
						if ( $q->have_posts() ) :
							echo "<h4>Recent Stories</h4><ul>";
							while ( $q->have_posts() ) :
								$q->the_post();
								the_title( sprintf( '<li><h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2></li>' );
							endwhile;
							echo "</ul>";
						endif;
						wp_reset_query();
				?>
				</div>
		<?php
				endif;
			endif; ?>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
