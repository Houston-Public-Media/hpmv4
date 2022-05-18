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
			echo hpm_head_banners( get_the_ID() );
			while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php
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
