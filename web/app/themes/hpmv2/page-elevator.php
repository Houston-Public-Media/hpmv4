<?php
/*
Template Name: Elevator Pitch
*/
get_header('elevator'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<div class="header-logo">
							<a href="/" rel="home" title="Houston Public Media homepage"><img src="https://cdn.hpm.io/assets/images/HPM_OneLine_UH.png" alt="Houston Public Media, a service of the University of Houston" /></a>
						</div>
						<h1 class="page-title"><?php the_title(); ?></h1>
						<?php echo get_the_excerpt(); ?>
					</header><!-- .entry-header -->
					<div class="page-content">
						<?php echo get_the_content(); ?>
					</div><!-- .entry-content -->
					<div id="ep-yt-overlay">
						<div id="ep-yt-wrap">
							<div id="ep-yt-player"></div>
							<div id="ep-yt-close"><span class="fa fa-close"></span></div>
						</div>
					</div>
					<footer class="page-footer">
						<div class="elevator-foot">
							<div class="foot-logo">
								<img src="https://cdn.hpm.io/assets/images/elevator/ep_small_logo@2x.png" alt="Houston Public Media's Elevator Pitch" />
							</div>
							<div class="foot-party dem">Democrat</div>
							<div class="foot-party rep">Republican</div>
							<div class="foot-logo">
								<a href="/"><img src="https://cdn.hpm.io/assets/images/elevator/hpm_logo_red@2x.png" alt="Houston Public Media, a service of the University of Houston" /></a>
							</div>
						</div>
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
		jQuery(document).ready(function($){
			var main = $('#main').offset();
			window.winhigh = $(window).height();
			var header_height = winhigh - main.top;
			$('header.page-header').height(header_height);
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