<?php
/*
Template Name: Great Houston Read
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
						<?php echo get_the_excerpt(); ?>
						<a class="down scrollto" href="#main-content">
							Start Reading<br />
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
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
	<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick.min.css" />
	<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick-theme.css" />
	<script src="https://cdn.hpm.io/assets/js/slick/slick.min.js"></script>
	<script src="https://cdn.hpm.io/assets/js/hoverintent.min.js"></script>
<?php
	$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
	echo $embeds['bottom'];
?>
	<script>
		jQuery(document).ready(function($){
			if ( $('body').hasClass('ghr-vote') ) {
				return false;
			} else {
				$('.ghr-story-videos').slick({
					adaptiveHeight: true,
					autoplay: true,
					autoplaySpeed: 10000,
					dots: true,
					pauseOnDotsHover: true,
					speed: 500,
					appendDots: $('.ghr-story-wrap'),
					appendArrows: $('.ghr-story-wrap'),
					fade: true
				});
				var main = $('#main').offset();
				window.winhigh = $(window).height();
				var header_height = winhigh - main.top;
				$('.page-template-page-ghr .page-header').height(header_height);
				$('a.down').on('click', function (event) {
					event.preventDefault();
					jQuery('html, body').animate({scrollTop: $('#main-content').offset().top}, 500);
				});
				$('#ghr-credits').on('click', function () {
					$('#ghr-credits-popup').fadeIn();
				});
				$('#ghr-credit-close').on('click', function () {
					$('#ghr-credits-popup').fadeOut();
				});
				$('.slick-dots li').hoverIntent({
					over: function () {
						var hoverID = $(this).children('button').attr('id');
						var justID = hoverID.replace('slick-slide-control0', '');
						$('#ghr-story-tag').html(hoverData[justID]).fadeIn();
					},
					out: function () {
						return false;
					},
					timeout: 500
				});
				$('.slick-dots').hoverIntent({
					over: function () {
						return false;
					},
					out: function () {
						$('#ghr-story-tag').fadeOut();
					},
					timeout: 500
				});
			}
		});
		var tag = document.createElement('script');
		tag.src = "//www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		function onYouTubeIframeAPIReady() {
			var $ = jQuery;
			var players = [];
			var $allVideos = $("iframe[src*='youtube.com'], iframe[src*='youtube-nocookie.com']");
			$allVideos.each( function () {
				players.push(new YT.Player($(this).attr('id'), {
					events: {
						'onStateChange': function(event) {
							if (event.data === YT.PlayerState.PLAYING) {
								$('.ghr-story-videos').slick('slickPause');
								$.each(players, function() {
									if ( this.getPlayerState() === YT.PlayerState.PLAYING && this.getIframe()
										.id !== event.target.getIframe().id) {
										this.pauseVideo();
									}
								});
							} else {
								$('.ghr-story-videos').slick('slickPlay');
							}
						}
					}
				}))
			});
		}
	</script>
<?php get_footer(); ?>