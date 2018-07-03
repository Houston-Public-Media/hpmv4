<?php
/*
Template Name: Station Management
*/
	get_header(); ?>
	<style>
		article .entry-content ul li {
			padding: 0.5em 0;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
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
						if ( has_post_thumbnail() ) :
					?>
					<div class="post-thumbnail">
						<?php 
							the_post_thumbnail( 'hpm-large' );
							$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
							if (!empty($thumb_caption)) :
								echo "<p>".$thumb_caption."</p>";
							endif;
						?>
					</div><!-- .post-thumbnail -->
					<?PHP
						endif;
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
						$args = array(
							'post_type' => 'staff',
							'tax_query' => array(
								array(
									'taxonomy' => 'staff_category',
									'field'    => 'slug',
									'terms'    => 'leadership',
								)
							),
							'meta_query' => array( 
								'hpm_staff_alpha' => array( 
									'key' => 'hpm_staff_alpha'
								)
							),
							'orderby' => 'meta_value',
							'order' => 'ASC'
						);
						$staff = new WP_Query( $args ); ?>
					<ul>
					<?php
						while ( $staff->have_posts() ) : $staff->the_post();
							$meta = get_post_meta( get_the_ID(), 'hpm_staff_meta', true ); ?>
						<li>
							<?php the_title( sprintf( '<strong><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></strong>' ); ?><br />
							<?php echo $meta['title']."<br />".$meta['phone']."<br /><a href=\"mailto:".$meta['email']."\">".$meta['email']."</a>"; ?>
						</li>
					<?php		
						endwhile;
						wp_reset_postdata(); ?>
					</ul>
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
				<div id="top-schedule-wrap">
					<nav id="category-navigation" class="category-navigation" role="navigation" style="padding: 0;">
						<h4>About Us</h4>
				<?php
					wp_nav_menu( array(
						'menu_class' => 'nav-menu',
						'menu' => 2379
					) );
				?>
					</nav>
				</div>
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
