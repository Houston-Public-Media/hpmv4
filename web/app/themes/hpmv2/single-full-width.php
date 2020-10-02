<?php
/*
Template Name: Full Width Article
Template Post Type: post
 */

get_header('single');
if ( is_preview() ) : ?>
	<div id="preview-warn">You're viewing a preview. Some things might be a little squirrelly.  --The Management</div>
<?php endif; ?>
	<div id="primary" class="content-area">
		<div id="main" class="site-main" role="main">
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
						$uri_title = rawurlencode( get_the_title() );
						$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-article&utm_medium=button&utm_campaign=hpm-share-link' );
						$uri_excerpt = rawurlencode( get_the_excerpt() ); ?>
						<h4>Share</h4>
						<div class="article-share-icon">
							<a href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" target="_blank" data-dialog="400:368">
								<span class="fab fa-facebook-f" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							 <a href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" target="_blank" data-dialog="364:250">
								<span class="fab fa-twitter" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
								<span class="fas fa-envelope" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="http://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
								<span class="fab fa-linkedin-in" aria-hidden="true"></span>
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
			<div id="author-wrap">
			<?php
				$author_terms = get_the_terms( get_the_ID(), 'author');
				if ( !empty( $author_terms ) ) :
					$matches = [];
					preg_match( "/([a-z\-]+) ([0-9]{1,3})/", $author_terms[0]->description, $matches );
					if ( !empty( $matches ) ) :
						$author_name = $matches[1];
						$authid = $matches[2];
						$author_check = new WP_Query( array(
								'post_type' => 'staff',
								'name' => $author_name
							)
						);
						if ( empty( $author_check ) ) :
							$author_check = new WP_Query( array(
									'post_type' => 'staff',
									'p' => $authid
								)
							);
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
								<a href="<?php echo $author['facebook']; ?>" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
							</div>
				<?php
							endif;
							if (!empty($author['twitter'])) : ?>
							<div class="social-icon">
								<a href="<?php echo $author['twitter']; ?>" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
							</div>
				<?php
							endif;
							$author_bio = get_the_content();
							if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) :
								$author_bio = '';
							endif; ?>
						</div>
						<p><?php echo wp_trim_words( $author_bio, 50, '...' ); ?></p>
						<p><a href="/staff/<?PHP echo $author_name; ?>">More Information</a></p>
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
						$post = $orig_post;
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
