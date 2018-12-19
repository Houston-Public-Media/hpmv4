			</div><!-- .site-content -->

			<footer id="colophon" class="site-footer" role="contentinfo">
				<div class="site-info">
					<div class="foot-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><img src="https://cdn.hpm.io/assets/images/HPM_OneLine_UH.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
					</div>
					<div id="footer-social">
						<div class="footer-social-icon footer-facebook">
							<a href="https://www.facebook.com/houstonpublicmedia" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
						</div>
						<div class="footer-social-icon footer-twitter">
							<a href="https://twitter.com/houstonpubmedia" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
						</div>
						<div class="footer-social-icon footer-instagram">
							<a href="https://instagram.com/houstonpubmedia" target="_blank"><span class="fa fa-instagram" aria-hidden="true"></span></a>
						</div>
						<div class="footer-social-icon footer-youtube">
							<a href="https://www.youtube.com/user/houstonpublicmedia" target="_blank"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
						</div>
						<div class="footer-social-icon footer-soundcloud">
							<a href="https://soundcloud.com/houston-public-media" target="_blank"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
						</div>
					</div>
					<nav id="secondary-navigation" class="footer-navigation" role="navigation">
<?php
	wp_nav_menu( array(
		'menu_class' => 'nav-menu',
		'theme_location' => 'footer',
		'walker' => new HPMv2_Menu_Walker
	) );
?>
						<div class="clear"></div>
					</nav>
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" target="_blank" style="color: #cc0000;">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?> | <a href="http://www.uhsystem.edu/privacy-notice/">Privacy
							Policy</a></p>
				</div><!-- .site-info -->
			</footer><!-- .site-footer -->
		</div><!-- .site -->
<?php
	/*
		Set up properly offset times for banner insertions
	*/
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	if ( !empty( $_GET['testtime'] ) ) :
		$tt = explode( '-', $_GET['testtime'] );
		$now = getdate( mktime( $tt[0], $tt[1], 0, $tt[2], $tt[3], $tt[4] ) );
	endif;
	wp_reset_query();
	/*
		Include the masonry plugins and code for any pages that use the tiled display (front page, main category pages, tiled series and single shows)
	*/
	$post_type = get_post_type();
	if ( is_page_template( 'page-main-categories.php' ) || is_front_page() || $post_type == 'shows' || is_page_template( 'page-series-tiles.php' ) || is_page_template( 'page-vietnam.php' ) ) :
		if ( get_the_ID() != 61247 || get_the_ID() != 315974 ) : ?>
		<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
		<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js"></script>
		<script>
			function masonLoad() {
				var isActive = false;
				if ( window.wide > 800 )
				{
					imagesLoaded( '#float-wrap', function() {
						var msnry = new Masonry( '#float-wrap', {
							itemSelector: '.grid-item',
							stamp: '.stamp',
							columnWidth: '.grid-sizer'
						});
						isActive = true;
					});
<?php
		/*
			Manually set the top pixel offset of the NPR articles box on the homepage, since Masonry doesn't calculate offsets for stamped elements
		*/
			if ( is_front_page() ) : ?>
					var topSched = document.querySelector('#top-schedule-wrap').getBoundingClientRect().height;
					document.getElementById('npr-side').style.cssText += 'top: '+topSched+'px';
<?php
			endif; ?>
				}
				else
				{
					if ( isActive ) {
						msnry.destroy();
						isActive = !isActive;
					}
					var gridItem = document.querySelectorAll('.grid-item');
					for ( i = 0; i < gridItem.length; ++i ) {
						gridItem[i].removeAttribute('style');
					}
				}
			}
			document.addEventListener("DOMContentLoaded", function() {
				masonLoad();
				var resizeTimeout;
				function resizeThrottler() {
					if ( !resizeTimeout ) {
						resizeTimeout = setTimeout(function() {
							resizeTimeout = null;
							masonLoad();
						}, 66);
					}
				}
				window.addEventListener("resize", resizeThrottler(), false);
				window.setTimeout(masonLoad(), 5000);
			});
		</script>
<?php
		endif;
	endif;
	/*
	Insert banners for when Houston Matters is airing
	*/
	$anc = get_post_ancestors( get_the_ID() );
	$bans = [ 135762, 290722, 303436, 303018, 315974 ];
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) ) :
		if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && ( $now['hours'] == 12 || $now['hours'] == 19 ) ) :
			$alt = ($now['hours'] == 19 ? 'This Is an Encore Broadcast, But You Can Still Get in Touch: talk@houstonmatters.org | @HoustonMatters | facebook.com/houstonmatters': 'Listening Now? Join the Conversation: Call (713) 440-8870 | Email talk@houstonmatters.org | Tweet @HoustonMatters');

			if ( is_front_page() || is_page_template( 'page-main-categories.php' ) ) :
				$jquery = "#top-schedule-wrap";
			else :
				$jquery = "aside.column-right";
			endif; ?>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				var banner = '<div id="houston-matters" class="top-banner"><a href="http://houstonmatters.org" target="_blank"><img src="https://cdn.hpm.io/assets/images/HoustonMatters_WebBanners-<?php echo $now['hours']; ?>.png" alt="<?php echo
				$alt; ?>" /></a></div>';
				if ( document.querySelector('<?php echo $jquery; ?>') !== null ) {
					document.querySelector('<?php echo $jquery; ?>').insertAdjacentHTML('afterbegin',banner);
				}
				var topBanner = document.querySelectorAll('.top-banner');
				for (i = 0; i < topBanner.length; ++i) {
					topBanner[i].addEventListener('click', function() {
						var attr = this.id;
						if ( typeof attr !== typeof undefined && attr !== false) {
							ga('send', 'event', 'Top Banner', 'click', attr);
							ga('hpmRollup.send', 'event', 'Top Banner', 'click', attr);
						}
					});
				}
			});
		</script>
<?php
		endif;
	endif;

	wp_reset_postdata();
	if ( !in_array( 61383, $anc ) ) : ?>
		<script type='text/javascript'>
			var _sf_async_config={};
			/** CONFIGURATION START **/
			_sf_async_config.uid = 33583;
			_sf_async_config.domain = 'houstonpublicmedia.org';
			_sf_async_config.useCanonical = true;
			_sf_async_config.sections = "<?php echo str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', get_the_ID()	) ) );
			?>";
			_sf_async_config.authors = "<?php coauthors( ',', ',', '', '', true ); ?>";
			(function(){
				function loadChartbeat() {
					window._sf_endpt=(new Date()).getTime();
					var e = document.createElement('script');
					e.setAttribute('language', 'javascript');
					e.setAttribute('type', 'text/javascript');
					e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
					document.body.appendChild(e);
				}
				var oldonload = window.onload;
				window.onload = (typeof window.onload != 'function') ?
					loadChartbeat : function() { oldonload(); loadChartbeat(); };
			})();
		</script>
<?php
	endif;
	wp_footer(); ?>
	</body>
</html>


