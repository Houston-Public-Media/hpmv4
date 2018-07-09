<?php
/******
 * Add a next-id data element to the player, and write code so that the file ending kicks that off
 */



/*
Template Name: Harvey SFTS Podcast
*/
	get_header( 'harvey' );
	$audio = new WP_Query([
		'category_name' => 'stories-from-the-storm',
		'orderby' => 'date',
		'order'   => 'ASC',
		'posts_per_page' => -1,
		'post__not_in' => [ 292849 ],
		'post_status' => [ 'publish', 'future' ],
		'ignore_sticky_posts' => 1
	]);
	$first = $audio->posts[0];
	$first_meta = get_post_meta( $first->ID, 'hpm_podcast_enclosure', true ); ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="page-content" id="main-content">
							<section id="stories-from-the-storm" class="sfts">
								<div class="sfts-info">
									<h2>Stories from the<br /><span class="sfts-title">Storm</span></h2>
									<div>Podcasts</div>
									<div class="underline"></div>
								</div>
								<div class="sfts-interviews">
									<div class="hah-split stfs-interviews-video">
										<div class="hah-podcast-image">
											<img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110913/Stories-from-the-storm-podcast-550x550.png" alt="Stories from the Storm podcast" />
										</div>
										<div id="jquery_jplayer_1" class="jp-jplayer" data-next-id="<?php echo $audio->posts[1]->ID; ?>"></div>
										<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
											<div class="jp-type-single">
												<div class="jp-gui jp-interface">
													<div class="jp-controls">
														<button class="jp-play" role="button" tabindex="0">
															<span class="fa fa-play" aria-hidden="true"></span>
														</button>
														<button class="jp-pause" role="button" tabindex="0">
															<span class="fa fa-pause" aria-hidden="true"></span>
														</button>
													</div>
													<div class="jp-progress-wrapper">
														<div class="jp-progress">
															<div class="jp-seek-bar">
																<div class="jp-play-bar"></div>
															</div>
														</div>
														<div class="jp-details">
															<div class="jp-title" aria-label="title">&nbsp;</div>
														</div>
														<div class="jp-time-holder">
															<span class="jp-current-time" role="timer" aria-label="time"></span> /<span class="jp-duration" role="timer" aria-label="duration"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<p class="subscribe">Subscribe</p>
										<ul>
											<li>
												<a href="https://itunes.apple.com/us/podcast/stories-from-the-storm/id1406912537" target="_blank" title="Subscribe on Apple Podcasts"><img src="https://cdn.hpm.io/assets/images/harvey/apple@2x.png" alt="Subscribe on Apple Podcasts" /></a>
											</li>
											<li>
												<a href="https://playmusic.app.goo.gl/?ibi=com.google.PlayMusic&isi=691797987&ius=googleplaymusic&apn=com.google.android.music&link=https://play.google.com/music/m/Iewfxybo66acwbsqei5lqe3zncy?t%3DStories_from_the_Storm%26pcampaignid%3DMKT-na-all-co-pr-mu-pod-16" target="_blank" title="Subscribe on Google Podcasts"><img src="https://cdn.hpm.io/assets/images/harvey/google@2x.png" alt="Subscribe on Google Podcasts" /></a>
											</li>
											<li>
												<a href="https://www.houstonpublicmedia.org/podcasts/stories-from-the-storm/" target="_blank" title="Subscribe via RSS"><img src="https://cdn.hpm.io/assets/images/harvey/podcast@2x.png" alt="Subscribe via RSS" /></a>
											</li>
										</ul>
										<div class="screen-reader-text">
											<script type="text/javascript">
												jQuery(document).ready(function($){
													$("#jquery_jplayer_1").jPlayer({
														ready: function () {
															$(this).jPlayer("setMedia", {
																title: "<?php echo $first->post_title; ?>",
																mp3: "<?php echo $first_meta['url']; ?>?source=jplayer-article"
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
														remainingDuration: false,
														toggleDuration: true
													});
												});
											</script>
										</div>
									</div>
									<div class="hah-split stfs-interviews-info">
										<h3 id="sfts-yt-title"><?php echo $first->post_title; ?></h3>
										<p id="sfts-yt-desc"><?php echo wp_strip_all_tags( strip_shortcodes( $first->post_content ) ); ?></p>
										<a href="#" class="readmore"><i class="fa fa-indent" aria-hidden="true"></i> More episodes</a>
										<h4><a href="/harvey/credits/">Series Credits</a></h4>
									</div>
								</div>
							</section>
							<aside id="videos-nav">
								<nav id="videos">
									<div class="videos-playlist">
										<p>Stories from the Storm Episodes</p>
										<div id="videos-close"><span class="fa fa-close"></span></div>
									</div>
									<ul>
<?php
	$c = 0;
	if ( $audio->have_posts() ) :
		while ( $audio->have_posts() ) :
			$audio->the_post();
			$id = get_the_ID();
			$enclose = get_post_meta( $id, 'hpm_podcast_enclosure', true );
			$later = '';
			$ytid = $enclose['url'];
			$text = wp_strip_all_tags( strip_shortcodes( get_the_content() ) ); ?>
										<li <?php echo ( $c == 0 ? 'class="current"' : '' ); ?>id="<?php echo $id; ?>" data-ytid="<?php echo $ytid; ?>" data-yttitle="<?php the_title(); ?>" data-ytdesc="<?php echo $text; ?>">
											<div class="videos-thumbnail"><img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110913/Stories-from-the-storm-podcast-550x550.png" alt="Stories from the Storm podcast" /></div>
											<div class="videos-info"><?php the_title(); ?></div>
										</li>
<?php
			$c++;
		endwhile;
		wp_reset_postdata();
	endif; ?>
										
									</ul>
								</nav>
							</aside>
						</div><!-- .entry-content -->
						<footer class="page-footer"></footer><!-- .entry-footer -->
					</main><!-- .site-main -->
				</div><!-- .content-area -->
			</div><!-- .site-content -->
			<script>
				jQuery(document).ready(function($){
					$('#videos-nav ul li').click(function() {
						var ytid = $(this).attr('data-ytid');
						var yttitle = $(this).attr('data-yttitle');
						var ytdesc = $(this).attr('data-ytdesc');
						if ( ytid === 'null' ) {
							return false;
						} else {
							$('#sfts-yt-title').html(yttitle);
							$('#sfts-yt-desc').html(ytdesc);
							if ( $(this).next('li').length ) {
								var next = $(this).next('li').attr('id');
							} else {
								var next = $('#videos > ul li:first-child').attr('id');
							}
							$("#jquery_jplayer_1").jPlayer('stop').jPlayer("setMedia", {
								title: yttitle,
								mp3: ytid+"?source=jplayer-article"
							}).attr('data-next-id', next).jPlayer('play');
							$('#videos-nav').removeClass('nav-active');
							$('#videos-nav ul li').removeClass('current');
							$(this).addClass('current');
						}
					});
					$("#jquery_jplayer_1").bind(
						$.jPlayer.event.ended, function(event) {
							var nextId = $('#jquery_jplayer_1').attr('data-next-id');
							var nextEp = $('#'+nextId);
							var ytid = nextEp.attr('data-ytid');
							var yttitle = nextEp.attr('data-yttitle');
							var ytdesc = nextEp.attr('data-ytdesc');
							var next = nextEp.next('li').attr('id');
							if ( $(this).next('li').length ) {
								var next = nextEp.next('li').attr('id');
							} else {
								var next = $('#videos > ul li:first-child').attr('id');
							}
							$('#sfts-yt-title').html(yttitle);
							$('#sfts-yt-desc').html(ytdesc);
							$("#jquery_jplayer_1").jPlayer("setMedia", {
								title: yttitle,
								mp3: ytid+"?source=jplayer-article"
							}).attr('data-next-id', next).jPlayer('play');
							$('#videos-nav ul li').removeClass('current');
							nextEp.addClass('current');
						}
					);
				});
			</script>
<?php get_footer( 'harvey' ); ?>