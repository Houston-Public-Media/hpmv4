<?php
/*
Template Name: Harvey Hurricane Season
*/
	get_header( 'harvey' );
	$audio = new WP_Query([
		'category_name' => 'hurricane-season',
		'orderby' => 'date',
		'order'   => 'ASC',
		'posts_per_page' => -1,
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
									<h2>Hurricane Season</h2>
									<div>Podcasts</div>
									<div class="underline"></div>
								</div>
								<div class="sfts-interviews">
									<div class="hah-split stfs-interviews-video">
										<div class="hah-podcast-image">
											<img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110952/Hurricane-Season-podcast-550x550.png" alt="Hurricane Season podcast" />
										</div>
										<div id="jquery_jplayer_1" class="jp-jplayer"></div>
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
												<a href="https://itunes.apple.com/us/podcast/hurricane-season/id1406912452" target="_blank" title="Subscribe on Apple Podcasts"><img src="https://cdn.hpm.io/assets/images/harvey/apple@2x.png" alt="Subscribe on Apple Podcasts" /></a>
											</li>
											<li>
												<a href="https://playmusic.app.goo.gl/?ibi=com.google.PlayMusic&isi=691797987&ius=googleplaymusic&apn=com.google.android.music&link=https://play.google.com/music/m/I6es3pguso2wjj4ynom5wzpkw2a?t%3DHurricane_Season%26pcampaignid%3DMKT-na-all-co-pr-mu-pod-16" target="_blank" title="Subscribe on Google Podcasts"><img src="https://cdn.hpm.io/assets/images/harvey/google@2x.png" alt="Subscribe on Google Podcasts" /></a>
											</li>
											<li>
												<a href="https://www.houstonpublicmedia.org/podcasts/hurricane-season/" target="_blank" title="Subscribe via RSS"><img src="https://cdn.hpm.io/assets/images/harvey/podcast@2x.png" alt="Subscribe via RSS" /></a>
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
										<p id="sfts-yt-desc"><?php echo $first->post_excerpt; ?></p>
										<a href="#" class="readmore"><i class="fa fa-indent" aria-hidden="true"></i>
 											More episodes</a>
									</div>
								</div>
							</section>
							<aside id="videos-nav">
								<nav id="videos">
									<div class="videos-playlist">
										<p>Hurricane Season Episodes</p>
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
			if ( $post->post_status != 'publish' ) :
				$ytid = 'null';
				$later = "<br /><em>Coming on ".get_the_date( 'F j, Y' )."</em>";
			else :
				$ytid = $enclose['url'];
			endif; ?>
										<li <?php echo ( $c == 0 ? 'class="current"' : '' ); ?>id="<?php echo $id; ?>" data-ytid="<?php echo $ytid; ?>" data-yttitle="<?php the_title(); ?>" data-ytdesc="<?php the_excerpt(); ?>">
											<div class="videos-thumbnail"><img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110952/Hurricane-Season-podcast-550x550.png" alt="Hurricane Season podcast" /></div>
											<div class="videos-info"><?php the_title(); ?><?php echo $later; ?></div>
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
							jQuery('#sfts-yt-title').html(yttitle);
							jQuery('#sfts-yt-desc').html(ytdesc);
							$("#jquery_jplayer_1").jPlayer('stop');
							$("#jquery_jplayer_1").jPlayer("setMedia", {
								title: yttitle,
								mp3: ytid+"?source=jplayer-article"
							});
							$("#jquery_jplayer_1").jPlayer('play');
							$('#videos-nav').removeClass('nav-active');
							$('#videos-nav ul li').removeClass('current');
							$(this).addClass('current');
						}
					});
				});
			</script>
<?php get_footer( 'harvey' ); ?>