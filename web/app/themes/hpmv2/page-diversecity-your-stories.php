<?php
/*
Template Name: DiverseCity Your Stories
*/
get_header('diversecity'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div id="float-wrap">
				<div class="grid-sizer"></div>
<?php
	while ( have_posts() ) : the_post();
		$postClass = get_post_class();
		$postClass[] = 'grid-item';
		$postClass[] = 'grid-item--width2'; ?>
				<article id="post-<?php the_ID(); ?>" class="<?php echo implode( ' ', $postClass ); ?>">
					<header class="entry-header">
						<h2 class="entry-title"><?php the_title(); ?></h2>
					</header>
					<div class="entry-content">
						<?PHP the_content(); ?>
					</div>
				</article>
<?php
	endwhile;
	$args = array(
		'post_type' => 'dc-stories',
		'posts_per_page' => -1,
		'ignore_sticky_posts' => 1
	);
	$stories = new WP_Query( $args );
	if ( $stories->have_posts() ) :
		while ( $stories->have_posts() ) : $stories->the_post();
			$dc_type = get_post_meta( get_the_ID(), 'hpm_dc_story_type', true );
			$postClass = get_post_class();
			$postClass[] = 'grid-item';
			if ( !empty( $dc_type['type'] ) ) :
				$postClass[] = $dc_type['type'];
			endif; ?>
				<article id="post-<?php the_ID(); ?>" class="<?php echo implode( ' ', $postClass ); ?>">
					<div class="entry-content">
						<?PHP the_content(); ?>
					</div>
					<p class="dc-author"><?PHP the_title(); ?></p>
<?php
	if ( !empty( $dc_type['author_desc'] ) ) : ?>
					<p class="dc-desc"><?PHP echo $dc_type['author_desc']; ?></p>
<?php
	endif; ?>
				</article>
<?php 
		endwhile;
	endif; ?>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer('diversecity'); ?>