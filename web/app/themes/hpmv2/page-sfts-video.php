<?php
/*
Template Name: Harvey SFTS Videos
*/
	get_header( 'harvey' );
	function hpm_youtube_playlist_hah( $key ) {
		$list = get_transient( 'hpm_yt_'.$key );
		if ( !empty( $list ) ) :
			return $list;
		endif;
		$remote = wp_remote_get( esc_url_raw( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$key.'&key=AIzaSyBHSGTRPfGElaMTniNCtHNbHuGHKcjPRxw' ) );
		if ( is_wp_error( $remote ) ) :
			return false;
		else :
			$yt = wp_remote_retrieve_body( $remote );
			$json = json_decode( $yt, TRUE );
			$items = $json['items'];
			set_transient( 'hpm_yt_'.$key, $items, 300 );
			return $items;
		endif;
	}
	$json = hpm_youtube_playlist_hah( 'PLGHyNdqkLN-DZNVnmifR7Idw8c_Wn0c3C' ); ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="page-content" id="main-content">
							<section id="stories-from-the-storm" class="sfts">
								<div class="sfts-info">
									<h2>Stories from the<br /><span class="sfts-title">Storm</span></h2>
									<div>Videos</div>
									<div class="underline"></div>
								</div>
								<div class="sfts-interviews">
									<div class="hah-split stfs-interviews-video">
										<div id="youtube-player" style="background-image: url( '<?php echo $json[0]['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $json[0]['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
											<span class="fa fa-play" id="play-button"></span>
										</div>
									</div>
									<div class="hah-split stfs-interviews-info">
										<h3 id="sfts-yt-title"><?php echo htmlentities( $json[0]['snippet']['title'], ENT_COMPAT ); ?></h3>
										<p id="sfts-yt-desc"><?php echo htmlentities( $json[0]['snippet']['description'] ); ?></p>
										<a href="#" class="readmore"><i class="fa fa-indent" aria-hidden="true"></i>
											 More stories</a>
									</div>
								</div>
							</section>
							<aside id="videos-nav">
								<nav id="videos">
									<div class="videos-playlist">
										<p>Stories from the Storm Videos</p>
										<div id="videos-close"><span class="fa fa-close"></span></div>
									</div>
									<ul>
<?php foreach ( $json as $tubes ) : ?>
										<li id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
											<div class="videos-thumbnail"><img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" /></div>
											<div class="videos-info"><?php echo $tubes['snippet']['title']; ?></div>
										</li>
<?php endforeach; ?>
										
									</ul>
									<h4 style="text-align: center;"><a href="/harvey/credits/#sfts-youtube-credits">Series Credits</a></h4>
								</nav>
							</aside>
						</div><!-- .entry-content -->
						<footer class="page-footer"></footer><!-- .entry-footer -->
					</main><!-- .site-main -->
				</div><!-- .content-area -->
			</div><!-- .site-content -->
			<script>
				var tag = document.createElement('script');
				tag.src = "//www.youtube.com/player_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
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
						if ( newYtid !== undefined )
						{
							var yttitle = nextVid.attr('data-yttitle');
							var ytdesc = nextVid.attr('data-ytdesc');
							ytid = newYtid;
							player.stopVideo();
							player.loadVideoById({
								videoId: ytid
							});
							jQuery('#sfts-yt-title').html(yttitle);
							jQuery('#sfts-yt-desc').html(ytdesc);
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
						$('#sfts-yt-title').html(yttitle);
						$('#sfts-yt-desc').html(ytdesc);
						$('#videos-nav').removeClass('nav-active');
						$('#videos-nav ul li').removeClass('current');
						$('#'+ytid).addClass('current');
					});
					$('#videos-nav ul li').click(function(){
						var newYtid = $(this).attr('data-ytid');
						var yttitle = $(this).attr('data-yttitle');
						var ytdesc = $(this).attr('data-ytdesc');
						if ( typeof ytid === typeof undefined ) {
							ytid = newYtid;
							$('#sfts-yt-title').html(yttitle);
							$('#sfts-yt-desc').html(ytdesc);
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
								$('#sfts-yt-title').html(yttitle);
								$('#sfts-yt-desc').html(ytdesc);
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
<?php get_footer( 'harvey' ); ?>