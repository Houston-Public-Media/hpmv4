<?php
/*
Template Name: Health Matters
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
				$media = get_attached_media( 'audio' );
				if ( !empty( $media ) ) :
					$c = 0;
					foreach ( $media as $m ) :
						if ( $c == 0 ) :
							$first = $m;
						elseif ( $c == 1 ) :
							$second = $m;
						endif;
						$c++;
					endforeach;
				endif; ?>
			<header class="page-header<?php echo (!empty( $header_back ) ? '" style="background-image: url(\''.$header_back[0].'\');"' : ' no-back'); ?>">
				<h1 class="page-title<?php echo (!empty( $header_back ) ? ' screen-reader-text' : ''); ?>"><?php the_title(); ?></h1>
			<?php
				$no = $sp = $c = 0;
				foreach( $show as $sh ) :
					if ( !empty( $sh ) ) :
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
					if ( !empty( $social['fb'] ) ) : ?>
					<div class="station-social-icon">
						<a href="https://www.facebook.com/<?php echo $social['fb']; ?>" target="_blank" title="Facebook"><span class="fa fa-facebook" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['twitter'] ) ) : ?>
					<div class="station-social-icon">
						<a href="https://twitter.com/<?php echo $social['twitter']; ?>" target="_blank" title="Twitter"><span class="fa fa-twitter" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['yt'] ) ) : ?>
					<div class="station-social-icon">
						<a href="<?php echo $social['yt']; ?>" target="_blank" title="YouTube"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['sc'] ) ) : ?>
					<div class="station-social-icon">
						<a href="https://soundcloud.com/<?php echo $social['sc']; ?>" target="_blank" title="SoundCloud"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['insta'] ) ) : ?>
					<div class="station-social-icon">
						<a href="https://instagram.com/<?php echo $social['insta']; ?>" target="_blank" title="Instagram"><span class="fa fa-instagram" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['tumblr'] ) ) : ?>
					<div class="station-social-icon">
						<a href="<?php echo $social['tumblr']; ?>" target="_blank" title="Tumblr"><span class="fa fa-tumblr" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $social['snapchat'] ) ) : ?>
					<div class="station-social-icon">
						<a href="http://www.snapchat.com/add/<?php echo $social['snapchat']; ?>" target="_blank" title="Snapchat"><span class="fa fa-snapchat-ghost" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $show['itunes'] ) ) : ?>
					<div class="station-social-icon">
						<a href="<?php echo $show['itunes']; ?>" target="_blank" title="iTunes Feed"><span class="fa fa-apple" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $show['podcast'] ) ) : ?>
					<div class="station-social-icon">
						<a href="<?php echo $show['podcast']; ?>" target="_blank" title="Podcast Feed"><span class="fa fa-rss" aria-hidden="true"></span></a>
					</div>
			<?php
					endif;
					if ( !empty( $show['gplay'] ) ) : ?>
					<div class="station-social-icon">
						<a href="<?php echo $show['gplay']; ?>" target="_blank" title="Google Play Podcasts Feed"><span class="fa fa-google" aria-hidden="true"></span></a>
					</div>
			<?php
					endif; ?>
				</div>
			<?php
				endif;?>
			</header>
		<?php
			endwhile; ?>
			<section id="stories-from-the-storm" class="alignleft">
				<div class="hah-split sfts-interviews-video">
				<div id="jquery_jplayer_1" class="jp-jplayer" data-next-id="<?php echo $second->ID; ?>"></div>
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
											title: "<?php echo $first->post_title; ?>",
											mp3: "<?php echo wp_get_attachment_url( $first->ID ); ?>?source=jplayer-article"
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
					<h3 id="sfts-yt-title"><?php echo $first->post_title; ?></h3>
				</div>
				<aside id="videos-nav">
					<nav id="videos">
						<div class="videos-playlist">
							<p><?php echo $show_title; ?> Episodes</p>
						</div>
						<ul>
							<?php 
							foreach ( $media as $m ) : ?>
							<li <?php echo ( $m->ID == $first->ID ? 'class="current" ' : '' ); ?>id="<?php echo $m->ID; ?>" data-ytid="<?php echo wp_get_attachment_url( $m->ID ); ?>" data-yttitle="<?php echo $m->post_title; ?>">
								<div class="videos-info"><?php echo $m->post_title; ?></div>
							</li>
							<?php
							endforeach; ?>
						</ul>
					</nav>
				</aside>
			</section>
			<aside class="alignleft">
				<h3>About <?php echo $show_title; ?></h3>
				<div class="show-content">
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</div>
				<div class="sidebar-ad">
					<div id="div-gpt-ad-1394579228932-1">
						<h4>Support Comes From</h4>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<script type="text/javascript" src='https://assets.hpm.io/app/themes/hpmv2/js/jplayer/jquery.jplayer.min.js?ver=20170928'></script>
	<script>
		jQuery(document).ready(function($){
			$('#videos-nav ul li').click(function() {
				var ytid = $(this).attr('data-ytid');
				var yttitle = $(this).attr('data-yttitle');
				if ( ytid === 'null' ) {
					return false;
				} else {
					$('#sfts-yt-title').html(yttitle);
					if ( $(this).next('li').length ) {
						var next = $(this).next('li').attr('id');
					} else {
						var next = $('#videos > ul li:first-child').attr('id');
					}
					$("#jquery_jplayer_1").jPlayer('stop').jPlayer("setMedia", {
						title: yttitle,
						mp3: ytid+"?source=jplayer-article"
					}).attr('data-next-id', next).jPlayer('play');
					$('#videos-nav ul li').removeClass('current');
					$(this).addClass('current');
				}
			});
			$("#jquery_jplayer_1").bind(
				$.jPlayer.event.ended, function(event) {
					var nextId = $('#jquery_jplayer_1').attr('data-next-id');
					var nextEp = $('#'+nextId);
					var ytid = nextEp.attr('data-ytid');
					if ( ytid === 'null' ) {
						return false;
					} else {
						var yttitle = nextEp.attr('data-yttitle');
						var next = nextEp.next('li').attr('id');
						if ( $(this).next('li').length ) {
							var next = nextEp.next('li').attr('id');
						} else {
							var next = $('#videos > ul li:first-child').attr('id');
						}
						$('#sfts-yt-title').html(yttitle);
						$("#jquery_jplayer_1").jPlayer("setMedia", {
							title: yttitle,
							mp3: ytid+"?source=jplayer-article"
						}).attr('data-next-id', next).jPlayer('play');
						$('#videos-nav ul li').removeClass('current');
						nextEp.addClass('current');
					}
				}
			);
		});
	</script>
<?php get_footer(); ?>