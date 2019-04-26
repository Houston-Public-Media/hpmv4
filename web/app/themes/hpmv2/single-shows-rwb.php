<?php
/*
Template Name: Red, White and Blue
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post();
				$show_name = $post->post_name;
				$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
				$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true );
				$header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$show_title = get_the_title();
				$show_content = get_the_content();
				$categories = get_the_category();
				$page_head_style = '';
				$page_head_class = '';
				if ( !empty( $show['banners']['mobile'] ) || !empty( $show['banners']['tablet'] ) || !empty( $show['banners']['desktop'] ) ) :
					$page_head_class = ' shows-banner-variable';
					foreach ( $show['banners'] as $bk => $bv ) :
						if ( $bk == 'mobile' ) :
							$page_head_style .= ".page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); }";
						elseif ( $bk == 'tablet' ) :
							$page_head_style .= " @media screen and (min-width: 30.0625em) { .page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); } }";
						elseif ( $bk == 'desktop' ) :
							$page_head_style .= " @media screen and (min-width: 50.0625em) { .page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); } }";
						endif;
					endforeach;
				elseif ( !empty( $header_back[0] ) ) :
					$page_head_style = ".page-header { background-image: url($header_back[0]); }";
				else :
					$page_head_class = ' no-back';
				endif;
				if ( !empty( $page_head_style ) ) :
					echo "<style>".$page_head_style."</style>";
				endif; ?>
			<header class="page-header<?php echo $page_head_class; ?>">
				<h1 class="page-title<?php echo (!empty( $header_back ) ? ' screen-reader-text' : ''); ?>"><?php the_title(); ?></h1>
			</header>
			<?php
				$no = $sp = $c = 0;
				foreach( $show as $sk => $sh ) :
					if ( !empty( $sh ) && $sk != 'banners' ) :
						$no++;
					endif;
				endforeach;
				foreach( $social as $soc ) :
					if ( !empty( $soc ) ) :
						$no++;
					endif;
				endforeach;
				if ( $no > 0 ) : ?>
			<div id="station-social">
			<?php
					if ( !empty( $show['times'] ) ) : ?>
				<h3><?php echo $show['times']; ?></h3>
			<?php
					endif;
					if ( !empty( $show['gplay'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['gplay']; ?>" target="_blank" title="Google Play Podcasts Feed"><span class="fa fa-google" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $show['podcast'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['podcast']; ?>" target="_blank" title="Podcast Feed"><span class="fa fa-rss" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $show['itunes'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['itunes']; ?>" target="_blank" title="iTunes Feed"><span class="fa fa-apple" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['snapchat'] ) ) : ?>
				<div class="station-social-icon">
					<a href="http://www.snapchat.com/add/<?php echo $social['snapchat']; ?>" target="_blank" title="Snapchat"><span class="fa fa-snapchat-ghost" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['tumblr'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $social['tumblr']; ?>" target="_blank" title="Tumblr"><span class="fa fa-tumblr" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['insta'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://instagram.com/<?php echo $social['insta']; ?>" target="_blank" title="Instagram"><span class="fa fa-instagram" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['sc'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://soundcloud.com/<?php echo $social['sc']; ?>" target="_blank" title="SoundCloud"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['yt'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $social['yt']; ?>" target="_blank" title="YouTube"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['twitter'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://twitter.com/<?php echo $social['twitter']; ?>" target="_blank" title="Twitter"><span class="fa fa-twitter" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['fb'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://www.facebook.com/<?php echo $social['fb']; ?>" target="_blank" title="Facebook"><span class="fa fa-facebook" aria-hidden="true"></span></a>
				</div>
			<?php
					endif; ?>
			</div>
			<?php 
				endif;
			endwhile; ?>
			<div id="shows-youtube">
				<div id="youtube-wrap">
					<div class="column-right">
						<h3>About</h3>
						<div class="show-content">
							<?php echo apply_filters( 'the_content', $show_content ); ?>
						</div>
		</div>
				<?php
					$json = hpm_youtube_playlist( $show['ytp'], 10 );
					foreach ( $json as $tubes ) :
						$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
						if ( $c == 0 ) : ?>
					<div id="youtube-main">
						<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<span class="fa fa-play" id="play-button"></span>
						</div>
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
						<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						<p class="desc"><?php echo $tubes['snippet']['description']; ?></p>
					</div>
					<div id="youtube-upcoming">
						<h4>Previous Episodes</h4>
					<?php
						endif; ?>
						<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdate="<?php echo date( 'F j, Y', $pubtime); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
							<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
							<h2><?php echo $tubes['snippet']['title']; ?></h2>
							<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						</div>
					<?php
						$c++;
					endforeach; ?>
					</div>
					<div class="readmore">
						<a href="<?php echo $social['yt']; ?>">View More Episodes</a>
					</div>
				</div>
			</div>
			<div id="rwb-austin">
				<div class="rwb-austin-desc">
					<img src="https://cdn.hpm.io/assets/images/austin-polland.jpg" alt="Mr. Polland Goes to Austin" />
					<div class="rwb-austin-desc-wrap">
						<h3>Mr. Polland Goes to Austin</h3>
						<p>Host Gary Polland travels to the Texas Capitol to check in with Texas legislators as they conduct the business of the 86th legislative session. This special edition includes exclusive interviews with Speaker of the House Dennis Bonnen, Senator Paul Bettencourt (R-Houston), Representative John Zerwas (R-Richmond), Representative Harold Dutton (D-Houston), and Representative James White (R-Hillister).</p>
					</div>
				</div>
				<div class="rwb-austin-slideshow">
				<?php
					$a_json = hpm_youtube_playlist( 'PLGHyNdqkLN-CFayr3GPM4r4zDDBGqpAmP', 15 );
					foreach ( $a_json as $aj ) : ?>
					<div>
						<p><iframe src="https://www.youtube.com/embed/<?php echo $aj['snippet']['resourceId']['videoId']; ?>?rel=0&amp;showinfo=0&amp;enablejsapi=1" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
						<h4><?php echo htmlentities( $aj['snippet']['title'], ENT_COMPAT ); ?></h4>
					</div>
				<?php
					endforeach; ?>
				</div>
			</div>
			<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick.min.css" />
			<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick-theme.css" />
			<script src="https://cdn.hpm.io/assets/js/slick/slick.min.js"></script>
			<script>
				jQuery(document).ready(function($){
					var options = { slidesToShow: 3, rows: 1, slidesToScroll: 3, infinite: false, autoplay: false, lazyLoad: 'ondemand', responsive: [ { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } }, { breakpoint: 800, settings: { slidesToShow: 2, slidesToScroll: 2, rows: 1 } }, { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, rows: 2 } }] };
					$('.rwb-austin-slideshow').slick(options);
				});
				var tag = document.createElement('script');
				tag.src = "//www.youtube.com/player_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				function ytdimensions() {
					var youtube = document.getElementById('youtube-player');
					window.ytwide = youtube.getBoundingClientRect().width;
					window.ythigh = ytwide/1.77777777777778;
					youtube.style.height = ythigh+'px';
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
						var nextVid = document.getElementById(current.searchObject.v).nextSibling();
						var newYtid = nextVid.getAttribute('data-ytid');
						if ( newYtid !== undefined )
						{
							var yttitle = nextVid.getAttribute('data-yttitle');
							var ytdesc = nextVid.getAttribute('data-ytdesc');
							var ytdate = nextVid.getAttribute('data-ytdate');
							ytid = newYtid;
							player.stopVideo();
							player.loadVideoById({
								videoId: ytid
							});
							var d = document.getElementById('youtube-main');
							d.querySelector('h2').innerHTML = yttitle;
							d.querySelector('.desc').innerHTML = ytdesc;
							d.querySelector('.date').innerHTML = ytdate;
							var c = document.getElementById('yt-nowplay');
							c.parentNode.removeChild(c);
							document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
						}
						else
						{
							return false;
						}
					}
				}
				document.addEventListener("DOMContentLoaded", function() {
					ytdimensions();
					var resizeTimeout;
					function resizeThrottler() {
						if (!resizeTimeout) {
							resizeTimeout = setTimeout(function () {
								resizeTimeout = null;
								ytdimensions();
							}, 66);
						}
					}
					window.addEventListener("resize", resizeThrottler(), false);
					document.getElementById('play-button').addEventListener('click', function(){
						window.ytid = this.parentNode.getAttribute('data-ytid');
						var f = document.getElementById('yt-nowplay');
						if ( f !== null ) {
							f.parentNode.removeChild(f);
						}
						document.getElementById(ytid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
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
						var yttitle = this.parentNode.getAttribute('data-yttitle');
					});
					var ytc = document.querySelectorAll('.youtube');
					for ( i = 0; i < ytc.length; i++ ) {
						ytc[i].addEventListener('click', function(){
							var newYtid = this.getAttribute('data-ytid');
							var yttitle = this.getAttribute('data-yttitle');
							var ytdesc = this.getAttribute('data-ytdesc');
							var ytdate = this.getAttribute('data-ytdate');
							if ( typeof ytid === typeof undefined ) {
								var d = document.getElementById('youtube-main');
								d.querySelector('h2').innerHTML = yttitle;
								d.querySelector('.desc').innerHTML = ytdesc;
								d.querySelector('.date').innerHTML = ytdate;
								var c = document.getElementById('yt-nowplay');
								if ( c !== null ) {
									c.parentNode.removeChild(c);
								}
								document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
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
									var d = document.getElementById('youtube-main');
									d.querySelector('h2').innerHTML = yttitle;
									d.querySelector('.desc').innerHTML = ytdesc;
									d.querySelector('.date').innerHTML = ytdate;
									var c = document.getElementById('yt-nowplay');
									if ( c !== null ) {
										c.parentNode.removeChild(c);
									}
									document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
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
					}
				});
			</script>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>