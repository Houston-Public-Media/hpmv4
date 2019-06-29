<?php
/*
Template Name: Moonwalk
*/
	get_header( 'moonwalk' );
	$audio = new WP_Query([
		'category_name' => 'moonwalk',
		'orderby' => 'date',
		'order'   => 'ASC',
		'posts_per_page' => -1,
		'post_status' => [ 'publish' ],
		'ignore_sticky_posts' => 1
	]);
	$first = $audio->posts[0];
	$first_meta = get_post_meta( $first->ID, 'hpm_podcast_enclosure', true );
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
	$json = hpm_youtube_playlist_hah( 'PLGHyNdqkLN-ABBYbvKmMw7tGt_0eeALR-' );
	$titles = [
		'The Mission',
		'The Women Of Mission Control',
		'Breaking Barriers',
		'Generations',
		'The Next Adventure'
	]; ?>
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<header class="page-header">
							<div class="header-logo">
								<a href="/" rel="home" title="Houston Public Media homepage"><img src="https://cdn.hpm.io/assets/images/moon/HPM_Logo.svg" alt="Houston Public Media, a service of the University of Houston" /></a>
							</div>
							<div class="tch-wrap">
								<a href="http://www.texaschildrens.org/best" title="Presenting Sponsor: Texas Children's Hospital" target="_blank">Presenting Sponsor</a>
							</div>
						</header><!-- .entry-header -->
						<div class="page-content" id="main-content">
							<section id="moon-desc">
								<div class="full-wrap">
									<h2>Fifty years after Apollo 11, Houston Public Media shares stories of the Apollo missions and their impact on generations of future space explorers.</h2>
								</div>
							</section>
							<section id="moon-video">
								<div class="full-wrap">
									<div class="moon-wrap">
										<a href="#" class="readmore" id="video-more"><i class="fa fa-indent" aria-hidden="true"></i> See all videos</a>
										<div id="youtube-player" style="background-image: url( '<?php echo $json[0]['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $json[0]['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
											<span class="fa fa-play" id="play-button"></span>
										</div>
										<div class="moon-video-info">
											<h3 id="moon-yt-title"><?php echo $titles[0]; ?></h3>
											<p id="moon-yt-desc"><?php echo wp_trim_words( htmlentities( $json[0]['snippet']['description'] ), 50, '...' ); ?></p>
										</div>
									</div>
									<aside id="videos-nav">
										<nav id="videos">
											<div class="videos-playlist">
												<p>Moonwalk Videos</p>
												<div id="videos-close" class="playlist-close"><span class="fa fa-close"></span></div>
											</div>
											<ul>
<?php foreach ( $json as $k => $tubes ) : ?>
												<li id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo $titles[$k]; ?>" data-ytdesc="<?php echo wp_trim_words( htmlentities( $tubes['snippet']['description'] ), 50, '...' ); ?>"<?PHP echo ( $k == 0 ? ' class="current"' : '' ); ?>>
													<div class="videos-thumbnail"><img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $titles[$k]; ?>" /></div>
													<div class="videos-info"><?php echo $titles[$k]; ?></div>
												</li>
<?php endforeach; ?>
										
											</ul>
										</nav>
									</aside>
								</div>
							</section>
							<section id="moon-podcast">
								<div class="full-wrap">
									<h2>Listen to Moonwalk Podcast</h2>
									<div id="jquery_jplayer_1" class="jp-jplayer"></div>
									<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
										<div class="moon-wrap">
											<p>Listen to extended conversations and get a behind the scenes glimpse at the making of the show. Subscribe anywhere you get your podcasts.</p>
											<p>&nbsp;</p>
											<ul class="pod-sub">
												<li>
													<a href="https://podcasts.apple.com/us/podcast/moonwalk/id1470272336" target="_blank" title="Subscribe on Spotify"><img src="https://cdn.hpm.io/assets/images/harvey/apple_pod.png" alt="Subscribe on Apple Podcasts" /></a>
												</li>
												<li>
													<a href="https://playmusic.app.goo.gl/?ibi=com.google.PlayMusic&isi=691797987&ius=googleplaymusic&apn=com.google.android.music&link=https://play.google.com/music/m/I44jctducmfyn6wjyajavcamqfq?t%3DMoonwalk%26pcampaignid%3DMKT-na-all-co-pr-mu-pod-16" target="_blank" title="Subscribe on Google Podcasts"><img src="https://cdn.hpm.io/assets/images/harvey/google_pod.png" alt="Subscribe on Google Podcasts" /></a>
												</li>
											</ul>
											<div class="podcast-image">
												<img src="https://cdn.hpm.io/assets/images/moon/moonwalk_header-small.jpg" alt="Moonwalk podcast" />
											</div>
											<a href="#" class="readmore" id="pod-more"><i class="fa fa-indent" aria-hidden="true"></i> All episodes</a>
											<div class="jp-type-single">
												<div class="jp-gui jp-interface">
													<div class="jp-progress-wrapper">
														<div class="jp-progress">
															<div class="jp-seek-bar">
																<div class="jp-play-bar"></div>
															</div>
														</div>
														<div class="jp-time-holder">
															<div class="jp-current-time" role="timer" aria-label="time"></div>
															<div class="jp-duration" role="timer" aria-label="duration"></div>
														</div>
													</div>
													<div class="jp-controls">
														<button class="jp-previous" role="button" tabindex="0">
															<span class="fa fa-step-backward" aria-hidden="true"></span>
															<span class="screen-reader-text">Previous</span>
														</button>
														<button class="jp-play" role="button" tabindex="0">
															<span class="fa fa-play" aria-hidden="true"></span>
															<span class="screen-reader-text">Play</span>
														</button>
														<button class="jp-pause" role="button" tabindex="0">
															<span class="fa fa-pause" aria-hidden="true"></span>
															<span class="screen-reader-text">Pause</span>
														</button>
														<button class="jp-next" role="button" tabindex="0">
															<span class="fa fa-step-forward" aria-hidden="true"></span>
															<span class="screen-reader-text">Next</span>
														</button>
														<button class="jp-mute" role="button" tabindex="0">
															<span class="screen-reader-text">Mute</span>
														</button>
													</div>
												</div>
											</div>
										</div>
										<aside id="pod-nav">
											<nav id="pod">
												<div class="pod-playlist">
													<p>Moonwalk Podcast</p>
													<div id="pod-close" class="playlist-close"><span class="fa fa-close"></span></div>
												</div>
												<div class="jp-playlist">
													<ul>
														<li></li>
													</ul>
												</div>
											</nav>
										</aside>
									</div>
								</div>
							</section>
							<section id="moon-look">
								<div class="full-wrap">
									<h2>A Look Back at the Space Age</h2>
									<p>Check out these flashbacks from our archives.</p>
									<article>
										<p class="youtube-wrap"><iframe width="560" height="315" src="https://www.youtube.com/embed/Yv0Q36pjDoM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
										<h2><strong>KUHT Flashback</strong>: 1962 Interview With Tech Working On Apollo Simulator Model</h2>
									</article>
									<article>
										<p class="youtube-wrap"><iframe width="560" height="315" src="https://www.youtube.com/embed/7Fuqh7Xj1zg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
										<h2><strong>KUHT Flashback</strong>: 1962 Report On Plans For NASA’s “Manned Space Center”</h2>
									</article>
									<article>
										<p class="youtube-wrap"><iframe width="560" height="315" src="https://www.youtube.com/embed/VJFir6uanW8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
										<h2><strong>KUHT Flashback</strong>: 1962 Report On People Relocating To Houston To Work For NASA</h2>
									</article>
								</div>
							</section>
							<section id="moon-behind">
								<h2>Behind the Scenes</h2>
								<p>Take a behind-the-scenes look at the making of <em>Moonwalk</em></p>
								<p class="explore-button"><a href="https://www.flickr.com/photos/houstonpubmedia/albums/72157709085401958" target="_blank">Explore</a></p>
							</section>
						</div><!-- .entry-content -->
					</main><!-- .site-main -->
				</div><!-- .content-area -->
			</div><!-- .site-content -->
<?php 
$eps = [];
$c = 0;
if ( $audio->have_posts() ) :
	while ( $audio->have_posts() ) :
		$audio->the_post();
		$id = get_the_ID();
		$enclose = get_post_meta( $id, 'hpm_podcast_enclosure', true );
		if ( $c == 0 ) :
			$first = [
				'title' => get_the_title(),
				'url' => $enclose['url']
			];
		endif;
		$eps[] = '{title: "'.get_the_title().'",artist:"",mp3:"'.$enclose['url'].'"}';
		$c++;
	endwhile;
endif;
wp_reset_postdata(); ?>
			<script>
				jQuery(document).ready(function($){
					$("#jquery_jplayer_1").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								title: "<?php echo $first['title']; ?>",
								mp3: "<?php echo $first['url']; ?>?source=jplayer-article"
							});
						},
						swfPath: "https://cdn.hpm.io/assets/js/jplayer",
						supplied: "mp3",
						preload: "metadata",
						cssSelectorAncestor: "#jp_container_1",
						wmode: "window",
						useStateClassSkin: true,
						autoBlur: false,
						smoothPlayBar: true,
						keyEnabled: true,
						remainingDuration: true,
						toggleDuration: false,
						muted: false
					});
					var myPlaylist = new jPlayerPlaylist({
						jPlayer: "#jquery_jplayer_1",
						cssSelectorAncestor: "#jp_container_1"
					}, [<?php echo implode( ',', $eps ); ?>], {
						playlistOptions: {
							enableRemoveControls: false
						}
					});
					$("#jquery_jplayer_1").bind( $.jPlayer.event.play, function(event) {
						console.log('Close Podcast Nav');
						$('#pod-nav').removeClass('nav-active');
					});
				});
			</script>
<?php get_footer( 'moonwalk' ); ?>