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
	get_header();
	$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
	if ( !empty( $embeds ) ) :
		echo $embeds['bottom'];
	endif; ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			$banners = hpm_head_banners( get_the_ID() );
			echo $banners;
			while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header<?php echo ( !empty( $banners ) ? ' screen-reader-text' : '' ); ?>">
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
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
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
				</footer>
			</article>
			<?php
				endwhile; ?>
			<aside>
			<?php
				if ( $pagename == 'spelling-bee' ) : ?>
				<section class="sidebar-ad">
					<h4>Presented By</h4>
					<a href="http://www.texaschildrens.org/" target="_blank" class="beesponsor" id="texas-childrens-hospital">
						<img src="https://cdn.hpm.io/assets/images/TCH_sponsor-01.png" alt="Texas Children's Hospital" style="margin: 0 12.5%; width: 75%; ">
					</a>
				</section>
			<?php
				elseif ( $pagename == 'about' || in_array( 61381, $anc ) ) : ?>
				<section>
					<nav id="category-navigation" class="category-navigation" role="navigation" style="padding: 0;">
						<h4>About HPM</h4>
						<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 2379 ] ); ?>
					</nav>
				</section>
			<?php
				elseif ( $pagename == 'support' || in_array( 61383, $anc ) ) : ?>
				<section>
					<nav id="category-navigation" class="category-navigation" role="navigation" style="padding: 0;">
						<h4>Support HPM</h4>
						<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 2560 ] ); ?>
					</nav>
				</section>
			<?php
				endif;
			    get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>
