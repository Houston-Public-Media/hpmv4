<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="page-header">
				<h1 class="entry-title">Staff Category: <?php single_cat_title(); ?></h1>
			</header><!-- .page-header -->
			<section id="search-results">
			<?php
			// Start the loop.
		if ( have_posts() ) :	
			while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
					<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
					<?php 
						$author_bio = get_the_content();
						if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" || $author_bio == '' ) :
							echo '<h2 class="entry-title">'.get_the_title().'</h2>';
						else :
							the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						endif; ?>
					</header><!-- .entry-header -->
					<div class="entry-summary">
						<p>
					<?php
						$staff = get_post_meta( get_the_ID(), 'hpm_staff_meta', true );
						echo $staff['title'];
					?>
						</p>
					</div><!-- .entry-summary -->
					<?php edit_post_link( __( 'Edit', 'hpmv2' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
				</article><!-- #post-## -->
		<?php
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text' => __( '&lt;', 'hpmv2' ),
				'next_text' => __( '&gt;', 'hpmv2' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv2' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>
			</section>
			<aside class="column-right">
				<div id="staff-categories">
					<h4>Staff Categories</h4>
					<ul>
<?php
	$current_cat = $wp_query->queried_object_id;
	$tax_menu_items = get_terms( 'staff_category', 'hide_empty=0&parent=0' );
	foreach( $tax_menu_items as $taxmen ) : ?>
						<li>
<?php
		if ( $current_cat == $taxmen->term_id ): ?>
							<strong><a href="//www.houstonpublicmedia.org/staff-category/<?php echo $taxmen->slug; ?>"><?php echo $taxmen->name; ?></a></strong>
<?php
		else : ?>
							<a href="//www.houstonpublicmedia.org/staff-category/<?php echo $taxmen->slug; ?>"><?php echo $taxmen->name; ?></a>
<?php
		endif;
		$term_child = get_terms( $taxmen->taxonomy, 'hide_empty=0&parent='.$taxmen->term_id );
		if ( !empty( $term_child ) ) : ?>
							<ul>
<?php
			foreach ( $term_child as $child ) : ?>
								<li>
<?php
				if ( $current_cat == $child->term_id ): ?>
									<strong><a href="//www.houstonpublicmedia.org/staff-category/<?php echo $child->slug; ?>"><?php echo $child->name; ?></a></strong>
<?php
				else : ?>
									<a href="//www.houstonpublicmedia.org/staff-category/<?php echo $child->slug; ?>"><?php echo $child->name; ?></a>
<?php
				endif;
				$term_child_child = get_terms( $child->taxonomy, 'hide_empty=0&parent='.$child->term_id );
				if ( !empty( $term_child_child ) ) : ?>
									<ul>
<?php
					foreach ( $term_child_child as $child_child ) : ?>
										<li>
<?php
						if ( $current_cat == $child_child->term_id ): ?>
											<strong><a href="//www.houstonpublicmedia.org/staff-category/<?php echo $child_child->slug; ?>"><?php echo $child_child->name; ?></a></strong>
<?php
						else : ?>
											<a href="//www.houstonpublicmedia.org/staff-category/<?php echo $child_child->slug; ?>"><?php echo $child_child->name; ?></a>
<?php
						endif; ?>
										</li>
<?php
					endforeach; ?>
									</ul>
<?php
				endif; ?>	
								</li>
<?php
			endforeach;	?>
							</ul>
<?php
		endif; ?>
						</li>
<?php
	endforeach; ?>
					</ul>
				</div>
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
