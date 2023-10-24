<?php
/*
Template Name: Planned Giving Page
*/
	get_header();
	 ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
 <?PHP
	while ( have_posts() ) {
		the_post();
        echo hpm_head_banners( get_the_ID(), 'page' ); ?>
		?>
        <div class="houston-matters-page">
            <?php echo hpm_head_banners( get_the_ID(), 'entry' ); ?>
					<?php the_content(); ?>
		</div>
<?php
	} ?>
		</main>
	</div>
<?php get_footer(); ?>