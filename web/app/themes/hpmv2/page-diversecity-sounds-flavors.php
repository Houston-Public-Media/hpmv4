<?php
/*
Template Name: DiverseCity Sounds-Flavors
*/
get_header('diversecity'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) : the_post();
		echo do_shortcode( get_the_content() );
	endwhile; 
	$args = array(
		'ignore_sticky_posts' => 1,
		'posts_per_page' => -1,
		'category_name' => 'sounds-flavors'
	);
	$convos = new WP_Query( $args );
	if ( $convos->have_posts() ) :
		while ( $convos->have_posts() ) : $convos->the_post();
			$postClass = get_post_class();
			$fl_array = preg_grep("/felix-type-/", $postClass);
			$fl_arr = array_keys( $fl_array );
			unset($postClass[$fl_arr[0]]);
			if ( $convos->current_post == 0 ) :
				$postClass[] = 'dc-top'; ?>
			<article id="post-<?php the_ID(); ?>" class="<?php echo implode( ' ', $postClass ); ?>">
				<h3 class="toptag">Featured Sounds &amp;amp; Flavors</h3>
				<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('large'); ?>)">
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
				</div>
				<header class="entry-header">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
					<div class="byline-date">
						<?php coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> | <span class="posted-on"><span class="screen-reader-text">Posted on </span><time class="entry-date published updated" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time></span>
					</div>
				</header>
			</article>
			<section>
				<h3 class="toptag sections">Related Stories</h3>
<?php
			else : ?>
				<article id="post-<?php the_ID(); ?>" class="<?php echo implode( ' ', $postClass ); ?>">
				<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('large'); ?>)">
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
				</div>
				<header class="entry-header">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				</header>
			</article>
<?php
			endif;
		endwhile;
	endif; ?>
			</section>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer('diversecity'); ?>