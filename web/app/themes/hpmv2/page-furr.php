<?php
/*
Template Name: Furr High
*/
get_header(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.hpm.io/assets/css/furr.css" media="all" />
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
		<header class="page-header">
			<div id="furr-background"></div>
			<div class="page-header-wrap">
			<?php
				the_title( '<h1 class="entry-title">', '</h1>' );
				the_excerpt(); ?>
			</div>
		</header>
		<div class="page-content">
			<?php echo do_shortcode( get_the_content() ); ?>
		</div><!-- .entry-content -->
		<?php endwhile; ?>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<script>
	jQuery(document).ready(function($){
		$('.furr-menu-item').click(function(){
			if ( $(this).hasClass('open') ) {
				$('.furr-menu-list').slideUp(500);
				$(this).removeClass('open');
				$('.furr-menu-item').removeClass('closed');
			} else {
				var id = $(this).attr('id');
				$('.furr-menu-list').slideUp(500);
				$('#'+id+'-list').slideDown(500);
				$('.furr-menu-item').removeClass('open').addClass('closed');
				$(this).addClass('open').removeClass('closed');
			}
		});
	});
</script>
<?php get_footer(); ?>