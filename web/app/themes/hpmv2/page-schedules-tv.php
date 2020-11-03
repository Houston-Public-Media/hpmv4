<?php
/*
Template Name: TV Schedule
*/
	get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title entry-title"><?php the_title(); ?></h1>
				<div id="station-social"></div>
			</header>
		<?php the_content();
		endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>