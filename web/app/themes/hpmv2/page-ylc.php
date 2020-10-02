<?php
/*
Template Name: Young Leaders Council
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<h2><?php echo get_the_excerpt(); ?></h2>
						<div class="header-logo">
							<a href="/" rel="home" title="Houston Public Media homepage"><img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-Reverse.png" alt="Houston Public Media, a service of the University of Houston" /></a>
						</div>
						<h1 class="page-title"><?php the_title(); ?></h1>
						<a class="down scrollto" href="#main-content">
							<i class="fas fa-chevron-down" aria-hidden="true"></i>
						</a>
					</header><!-- .entry-header -->
					<div class="page-content">
						<?php echo get_the_content(); ?>
					</div><!-- .entry-content -->

					<footer class="page-footer">
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
			<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<script>
		function modalSwitch(dataId,modal) {
			var dIndexSp = dataId.split('-');
			var dInt = parseInt(dIndexSp[2]);
			var roster = document.getElementsByClassName(dIndexSp[0]+'-'+dIndexSp[1]);
			if ( dInt - 1 == 0 ) {
				var prev = roster.length;
			} else {
				var prev = dInt - 1;
			}
			if ( dInt + 1 == roster.length + 1 ) {
				var next = 1;
			} else {
				var next = dInt + 1;
			}
			var current = jQuery('#'+dataId);
			jQuery('#ylc-prev').attr('data-item', 'ylc-'+dIndexSp[1]+'-'+prev);
			jQuery('#ylc-next').attr('data-item', 'ylc-'+dIndexSp[1]+'-'+next);
			var name = current.attr('data-name');
			var title = current.attr('data-title');
			var quote = current.attr('data-quote');
			var fTitle = title.replace(/\|\|/g, '<br />');
			jQuery('#ylc-overlay-person h1').html(name);
			jQuery('#ylc-overlay-person h3').html(fTitle);
			jQuery('#ylc-overlay-quote blockquote').html(quote);
			var image = current.children('img');
			jQuery('#ylc-overlay-img').html('<img src="'+image.attr('src')+'" alt="'+image.attr('alt')+'" title="'+image.attr('title')+'">');
			if (modal) {
				jQuery('#ylc-overlay').addClass('ylc-active');
			}
		}
		jQuery(document).ready(function($){
			var main = $('#main').offset();
			window.winhigh = $(window).height();
			var header_height = winhigh - main.top;
			$('.page-template-page-ylc .page-header').height(header_height);
			$('a.down').on('click', function (event) {
				event.preventDefault();
				$('html, body').animate({scrollTop: $('#main-content').offset().top}, 500);
			});
			$('.ylc-roster-item').on('click', function (event) {
				var dIndex = $(this).attr('id');
				modalSwitch(dIndex,true);
			});
			$('#ylc-close,#ylc-overlay').on('click', function(event) {
				event.preventDefault();
				$('#ylc-overlay').removeClass('ylc-active');
			});
			$('#ylc-overlay-wrap').on('click', function(event) {
				event.stopPropagation();
			});
			$('#ylc-next,#ylc-prev').on('click', function(event) {
				event.preventDefault();
				event.stopPropagation();
				var dIndex = $(this).attr('data-item');
				modalSwitch(dIndex,false);
			});
			$(document).on('keyup', function(event) {
				if ($('#ylc-overlay').hasClass('ylc-active')) {
					if (event.which == 37) {
						console.log( 'Keyboard Previous' );
						var dIndex = $('#ylc-prev').attr('data-item');
						modalSwitch(dIndex,false);
					} else if (event.which == 39) {
						console.log( 'Keyboard Next' );
						var dIndex = $('#ylc-next').attr('data-item');
						modalSwitch(dIndex,false);
					}
				}
			});
			$('#ylc-prev-class').on('click', function(event) {
				event.preventDefault();
				if ( $('.ylc-prev-class').hasClass('active') ) {
					$('.ylc-prev-class').removeClass('active');
				} else {
					$('.ylc-prev-class').addClass('active');
				}
			});
		});
	</script>
<?php get_footer(); ?>