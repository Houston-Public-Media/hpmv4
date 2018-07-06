<?php
/*
Template Name: Harvey Main
*/
	get_header( 'harvey' ); ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<header class="page-header">
							<h1 class="page-title"><?php echo get_the_title(); ?></h1>
							<h2><?php echo get_the_excerpt(); ?></h2>
							<div class="hah-hrm-wrap">
								<a href="https://www.houstonfloodmuseum.org" title="Houston Flood Museum" target="_blank"></a>
							</div>
							<a class="down scrollto" href="#main-content">
								Explore<br /><span class="vert-line"></span><br /><span class="fa fa-angle-double-down"></span>
							</a>
						</header><!-- .entry-header -->
						<div class="page-content" id="main-content">
							<section id="stories-from-the-storm" class="sfts">
								<div class="sfts-info">
									<h2>Stories from the<br /><span class="sfts-title">Storm</span></h2>
									<p>Hurricane Harvey became the nation’s worst rainstorm, flooding more than 154,000 homes across Harris County and forcing local and state leaders to rethink long-term flood mitigation plans. Nearly one year later, community leaders, public servants, and everyday Houstonians reflect on Hurricane Harvey and how the storm changed their lives – from the way they define community to how they envision their future and the future of Houston.</p>
								</div>
								<div class="sfts-interviews">
									<div class="hah-split stfs-interviews-info">
										<h3>the Interviews</h3>
										<p>Watch civic leaders, first responders and everyday Houstonians share stories of how Harvey tested their strength and changed them forever.</p>
										<a href="/harvey/stories-from-the-storm/" class="readmore">Watch the stories</a>
									</div>
									<div class="hah-split stfs-interviews-video">
										<p class="youtube-wrap"><iframe width="560" height="315" src="https://www.youtube.com/embed/kwQ-qx8JA18?rel=0&showinfo=0&enablejsapi=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></p>
									</div>
								</div>
								<div class="sfts-podcast">
									<div class="hah-split sfts-podcast-info">
										<h3>the Podcast</h3>
										<p>Subscribe and listen to full-length versions of our video stories, and be moved by acts of compassion and courage large and small.</p>
										<a href="/harvey/stories-from-the-storm/podcast/" class="readmore">Listen to the stories</a>
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
									</div>
									<div class="hah-split sfts-podcast-player">
										<div class="hah-podcast-image">
											<img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110913/Stories-from-the-storm-podcast-550x550.png" alt="Stories from the Storm podcast" />
										</div>
										<h4>Stories from the Storm Trailer</h4>
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
										<div class="screen-reader-text">
											<script type="text/javascript">
												jQuery(document).ready(function($){
													$("#jquery_jplayer_1").jPlayer({
														ready: function () {
															$(this).jPlayer("setMedia", {
																title: "Stories from the Storm Trailer",
																mp3: "https://ondemand.houstonpublicmedia.org/stories-from-the-storm/SFTS_Promo_01.mp3?source=jplayer-article"
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
								</div>
							</section>
							<section id="hurricane-season" class="hs">
								<div class="hs-podcast">
									<div class="hah-split hs-podcast-info">
										<h3>Hurricane Season</h3>
										<p><em>Hurricane Season</em> is an eight-episode podcast that explores how major storms going back to 1900 greatly impacted Greater Houston people and policies.</p>
										<a href="/harvey/hurricane-season/" class="readmore">Coming August 1st</a>
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
									</div>
									<div class="hah-split hs-podcast-player">
										<div class="hah-podcast-image">
											<img src="https://cdn.hpm.io/wp-content/uploads/2018/06/21110952/Hurricane-Season-podcast-550x550.png" alt="Hurricane Season podcast" />
										</div>
										<h4>Hurricane Season Trailer</h4>
										<div id="jquery_jplayer_2" class="jp-jplayer"></div>
										<div id="jp_container_2" class="jp-audio" role="application" aria-label="media player">
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
										<div class="screen-reader-text">
											<script type="text/javascript">
												jQuery(document).ready(function($){
													$("#jquery_jplayer_2").jPlayer({
														ready: function () {
															$(this).jPlayer("setMedia", {
																title: "Hurricane Season",
																mp3: "https://ondemand.houstonpublicmedia.org/hurricane-season/Hurricane_Season_TRAILER_01.mp3?source=jplayer-article"
															});
														},
														swfPath: "https://cdn.hpm.io/assets/js/jplayer",
														supplied: "mp3",
														preload: "metadata",
														cssSelectorAncestor: "#jp_container_2",
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
								</div>
								<div class="hs-broadcasts">
									<h3>Check out these special broadcasts on <b>August 24</b></h3>
									<div class="hah-split hs-broadcasts-info">
										<img src="https://cdn.hpm.io/assets/images/harvey/HM_logo@2x.png" alt="Listen on Houston Matters" />
										<p>Listen to the special edition Houston Matters at</p>
										<p class="hs-broadcasts-time">Noon</p>
									</div>
									<div class="hah-split hs-broadcasts-info">
										<img src="https://cdn.hpm.io/assets/images/harvey/TV8_icon@2x.png" alt="Watch on Houston Matters" />
										<p>Watch Stories from the Storm 1-hour special on TV 8 at</p>
										<p class="hs-broadcasts-time">7:30pm</p>
									</div>
								</div>
							</section>
<?php
	$news = new WP_Query([
		'category_name' => 'hurricane-harvey',
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 3,
		'ignore_sticky_posts' => 1
	]);
	if ( $news->have_posts() ) :
		$c = 0; ?>
							<section id="news">
								<h2>Latest News</h2>
<?php
		while ( $news->have_posts() ) :
			$news->the_post(); ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
			if ( $c == 0 ) : ?>
									<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
										<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
									</div>
<?php
			endif; ?>
									<header class="entry-header">
										<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										<div class="screen-reader-text"><?PHP
											coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
											$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
											$time_string = sprintf( $time_string,
												esc_attr( get_the_date( 'c' ) ),
												get_the_date( 'F j, Y' )
											);

											printf( '<span class="posted-on">%1$s %2$s</span>',
												_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
												$time_string
											);
										?></div>
									</header><!-- .entry-header -->
									<div class="entry-summary">
										<p><?php the_excerpt(); ?></p>
									</div><!-- .entry-summary -->
									<footer class="entry-footer">
										<?php 
											$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
											if ( $tags_list ) :
												printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
													_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
													$tags_list
												);
											endif;
											edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
									</footer><!-- .entry-footer -->
								</article><!-- #post-## -->
<?php
			$c++;
		endwhile; ?>
								<div class="readmore-wrap"><a href="/harvey/news/" class="readmore">Read more <span class="fa fa-long-arrow-right" aria-hidden="true"></span></a></div>
							</section>
<?php
		wp_reset_postdata();
	endif; ?>
							<section id="social" class="hah-social">
								<h2>#HoustonAfterHarvey</h2>
								<p>Share your after Hurricane Harvey moments using<br />#HoustonAfterHarvey</p>
								<div class="hah-social-instagram">
									<script id="twine-script" src="//apps.twinesocial.com/embed?app=houstonafterharveyhpm&showLoadMore=yes&autoload=no"></script>
								</div>
							</section>
							<section id="support" class="hah-support">
								<p>Support comes from</p>
								<div class="hah-support-hfm">
									<a href="https://www.houstonfloodmuseum.org" title="Houston Flood Museum" target="_blank"><img src="https://cdn.hpm.io/assets/images/harvey/houston_flood_museum@2x.png" alt="Houston Flood Museum" /></a>
								</div>
							</section>
						</div><!-- .entry-content -->
						<footer class="page-footer">
							<a href="/harvey/credits/" class="page-credits">Credits</a>
							<a href="#top" class="scrollto"><img src="https://cdn.hpm.io/assets/images/harvey/TOP_button@2x.png" title="Back to Top" alt="Back to Top"></a>
						</footer><!-- .entry-footer -->
					</main><!-- .site-main -->
				</div><!-- .content-area -->
			</div><!-- .site-content -->
<?php get_footer( 'harvey' ); ?>