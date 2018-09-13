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
						<h5><a href="https://www.texaschildrens.org/" target="_blank">Presented By<br />
							<img src="https://cdn.hpm.io/assets/images/ghr/TCH-logo.png" alt="Texas Children's Hospital" class="tch-logo" /></a></h5>
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
	<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/lightbox/css/lightbox.min.css" />
	<script src="https://cdn.hpm.io/assets/js/slick/slick.min.js"></script>
	<script src="https://cdn.hpm.io/assets/js/lightbox/js/lightbox.min.js"></script>
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
				var options = { slidesToShow: 3, rows: 2, slidesToScroll: 3, infinite: false, autoplay: false, lazyLoad: 'ondemand', responsive: [ { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } }, { breakpoint: 800, settings: { slidesToShow: 2, slidesToScroll: 2 } }, { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1 } }] };
				$('.c2c-slick').slick(options);
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
		function ytdimensions($) {
			window.ytwide = $('#youtube-player').width();
			window.ythigh = ytwide/1.77777777777778;
			$('#youtube-player').height(ythigh);
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
				var current = parseURL(player.getVideoUrl());
				var nextVid = jQuery('#'+current.searchObject.v).next();
				var newYtid = nextVid.attr('data-ytid');
				if ( newYtid !== undefined || nextVid.hasClass('pending') )
				{
					var yttitle = nextVid.attr('data-yttitle');
					var ytdesc = nextVid.attr('data-ytdesc');
					ytid = newYtid;
					player.stopVideo();
					player.loadVideoById({
						videoId: ytid
					});
					jQuery('#hpm-yt-title').html(yttitle);
					jQuery('#hpm-yt-desc').html(ytdesc);
					jQuery('#videos-nav').removeClass('nav-active');
					jQuery('#videos-nav ul li').removeClass('current');
					jQuery('#'+ytid).addClass('current');
				}
				else
				{
					return false;
				}
			}
		}
		jQuery(document).ready(function($){
			ytdimensions($);
			$(window).resize(function(){
				ytdimensions($);
			});
			window.eventType = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
			$('a.readmore').on(eventType, function(event) {
				event.preventDefault();
				if ($('#videos-nav').hasClass('nav-active'))
				{
					$('#videos-nav').removeClass('nav-active');
				}
				else
				{
					$('#videos-nav').addClass('nav-active');
				}
			});
			$('#videos-close').on(eventType, function(event) {
				event.preventDefault();
				if ($('#videos-nav').hasClass('nav-active'))
				{
					$('#videos-nav').removeClass('nav-active');
				}
				else
				{
					$('#videos-nav').addClass('nav-active');
				}
			});
			$('#play-button').click(function(){
				window.ytid = $(this).parent().attr('data-ytid');
				window.player;
				player = new YT.Player('youtube-player', {
					height: ythigh,
					width: ytwide,
					videoId: ytid,
					events: {
						'onReady': onPlayerReady,
						'onStateChange': onPlayerStateChange
					}
				});
				var yttitle = $(this).parent().attr('data-yttitle');
				var ytdesc = $(this).parent().attr('data-ytdesc');
				$('#hpm-yt-title').html(yttitle);
				$('#hpm-yt-desc').html(ytdesc);
				$('#videos-nav').removeClass('nav-active');
				$('#videos-nav ul li').removeClass('current');
				$('#'+ytid).addClass('current');
			});
			$('#videos-nav ul li').click(function(){
				var newYtid = $(this).attr('data-ytid');
				var yttitle = $(this).attr('data-yttitle');
				var ytdesc = $(this).attr('data-ytdesc');
				if ( $(this).hasClass('pending') ) {
					return false;
				}
				if ( typeof ytid === typeof undefined ) {
					ytid = newYtid;
					$('#hpm-yt-title').html(yttitle);
					$('#hpm-yt-desc').html(ytdesc);
					$('#videos-nav').removeClass('nav-active');
					$('#videos-nav ul li').removeClass('current');
					$('#'+ytid).addClass('current');
					window.ytid = newYtid;
					window.player;
					player = new YT.Player('youtube-player', {
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
						$('#hpm-yt-title').html(yttitle);
						$('#hpm-yt-desc').html(ytdesc);
						$('#videos-nav').removeClass('nav-active');
						$('#videos-nav ul li').removeClass('current');
						$('#'+ytid).addClass('current');
					}
					else
					{
						return false;
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