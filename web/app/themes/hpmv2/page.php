<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
	$pagename = get_query_var( 'pagename' );
	$anc = get_post_ancestors( get_the_ID() );
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			$page_head_class = hpm_head_banners( get_the_ID() );
			while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header<?php echo $page_head_class; ?>">
					<?php
						the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<p class="byline-date screen-reader-text">
					<?PHP
						coauthors_posts_links( ', ', ', ', '', '', true );
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
							$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Updated: <time class="updated" datetime="%3$s">%4$s</time>';
						}

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
					</p>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						/* $classes = get_body_class();
						if ( has_post_thumbnail() && !in_array( 'hide-featured-image', $classes ) ) :
					?>
					<div class="post-thumbnail">
						<?php
							the_post_thumbnail( 'large' );
							$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
							if (!empty($thumb_caption)) :
								echo "<p>".$thumb_caption."</p>";
							endif;
						?>
					</div><!-- .post-thumbnail -->
					<?PHP
						endif; */
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<?php
				endwhile; ?>
			<aside class="column-right">
			<?php
				if ( $pagename == 'spelling-bee' ) : ?>
				<div class="sidebar-ad">
					<h4>Presented By</h4>
					<a href="http://www.texaschildrens.org/" target="_blank" class="beesponsor" id="texas-childrens-hospital">
						<img src="https://cdn.hpm.io/assets/images/TCH_sponsor-01.png" alt="Texas Children's Hospital" style="margin: 0 12.5%; width: 75%; ">
					</a>
				</div>
			<?php
				elseif ( $pagename == 'about' || in_array( 61381, $anc ) ) : ?>
				<div id="top-schedule-wrap">
					<nav id="category-navigation" class="category-navigation" role="navigation" style="padding: 0;">
						<h4>About HPM</h4>
				<?php
					wp_nav_menu( array(
						'menu_class' => 'nav-menu',
						'menu' => 2379
					) );
				?>
					</nav>
				</div>
			<?php
				elseif ( $pagename == 'support' || in_array( 61383, $anc ) ) : ?>
				<div id="top-schedule-wrap">
					<nav id="category-navigation" class="category-navigation" role="navigation" style="padding: 0;">
						<h4>Support HPM</h4>
				<?php
					wp_nav_menu( array(
						'menu_class' => 'nav-menu',
						'menu' => 2560
					) );
				?>
					</nav>
				</div>
			<?php
				endif;
			    get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
