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
							<div id="ep-yt-player">
								<div id="ep-youtube"></div>
							</div>
							<div id="ep-yt-close"><span class="fa fa-close"></span></div>
						</div>
					</div>
					<footer class="page-footer">
						<div class="elevator-foot">
							<div class="foot-logo">
								<img src="https://cdn.hpm.io/assets/images/elevator/ep_small_logo@2x.png" alt="Houston Public Media's Elevator Pitch" />
							</div>
							<div class="foot-party" id="national">National</div>
							<div class="foot-party" id="state">State</div>
							<div class="foot-party" id="local">Local</div>
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
		var tag = document.createElement('script');
		tag.src = "//www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		function ytdimensions($) {
			window.ytwide = $('#ep-youtube').width();
			window.ythigh = ytwide/1.77777777777778;
			$('#ep-youtube').height(ythigh);
		}
		function parseURL(url) {
			var parser = document.createElement('a'),
				searchObject = {},
				queries, split, i;
			// Let the browser do the work
			parser.href = url;
			// Convert query string to object
			queries = parser.search.replace(/^\?/, '').split('&');
			for( i = 0; i < queries.length; i++ ) {
				split = queries[i].split('=');
				searchObject[split[0]] = split[1];
			}
			return {
				protocol: parser.protocol,
				host: parser.host,
				hostname: parser.hostname,
				port: parser.port,
				pathname: parser.pathname,
				search: parser.search,
				searchObject: searchObject,
				hash: parser.hash
			};
		}
		function onPlayerReady(event) {
			if (navigator.userAgent.match(/(iPad|iPhone|iPod touch)/i) == null)
			{
				event.target.playVideo();
			}
		}
		function onPlayerStateChange(event) {
			if (event.data == YT.PlayerState.ENDED)
			{
				player.stopVideo();
				jQuery('#ep-yt-overlay').addClass('ep-yt-active');
			}
		}
		jQuery(document).ready(function($){
			ytdimensions($);
			$(window).resize(function(){
				ytdimensions($);
			});
			window.eventType = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

			$('#ep-yt-close, #ep-yt-overlay').on(eventType, function(event) {
				event.preventDefault();
				$('#ep-yt-overlay').removeClass('ep-yt-active');
				player.pauseVideo();
			});
			$('.foot-party').on(eventType, function(event) {
				event.preventDefault();
				if ( $(this).hasClass('foot-active') ) {
					$('.ep-race').show();
					$('.foot-party').removeClass('foot-active');
				} else {
					var race = $(this).attr('id');
					$('.ep-race').hide();
					$('.ep-'+race).show();
					$('.foot-party').removeClass('foot-active');
					$(this).addClass('foot-active');
				}
				$('html, body').animate({scrollTop: $('.page-content').offset().top}, 500);
			});
			$('.ep-pitch').on(eventType, function(event) {
				event.preventDefault();
				if ( $(this).hasClass('ep-inactive') ) {
					return false;
				}
				var newYtid = $(this).attr('data-ytid');
				if ( typeof ytid === typeof undefined ) {
					ytid = newYtid;
					$('#ep-yt-overlay').addClass('ep-yt-active');
					window.ytid = newYtid;
					window.player;
					player = new YT.Player('ep-youtube', {
						height: ythigh,
						width: ytwide,
						videoId: ytid,
						events: {
							'onReady': onPlayerReady,
							'onStateChange': onPlayerStateChange
						}
					});
				}
				else if ( typeof ytid !== typeof undefined )
				{
					if ( ytid !== newYtid )
					{
						ytid = newYtid;
						player.stopVideo();
						player.loadVideoById({
							videoId: ytid
						});
						$('#ep-yt-overlay').addClass('ep-yt-active');
					}
					else
					{
						$('#ep-yt-overlay').addClass('ep-yt-active');
						player.playVideo();
					}
				}
				else
				{
					return false;
				}
			});
		});
	</script>
<?php get_footer(); ?>