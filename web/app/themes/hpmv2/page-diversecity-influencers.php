<?php
/*
Template Name: DiverseCity Influencers
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
		'category_name' => 'influencers'
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
				<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('large'); ?>)">
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
				</div>
				<header class="entry-header">
					<div class="dc-play-button"><span class="fa fa-play" aria-hidden="true"></span></div>
					<h3 class="dc-influencer-tag">Featured Influencer</h3>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				</header>
			</article>
			<section>
<?php
			else : ?>
				<article id="post-<?php the_ID(); ?>" class="<?php echo implode( ' ', $postClass ); ?>">
				<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('large'); ?>)">
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
					<div class="dc-play-button"><span class="fa fa-play" aria-hidden="true"></span></div>
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