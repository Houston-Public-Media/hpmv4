			<footer id="colophon" class="site-footer" role="contentinfo">
				<div class="site-info">
					<div class="foot-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><img src="https://cdn.hpm.io/assets/images/KO_1Line.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
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
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="http://www.uh.edu" target="_blank" style="color: #cc0000;">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?> | <a href="http://www.uhsystem.edu/privacy-notice/">Privacy
							Policy</a></p>
				</div><!-- .site-info -->
			</footer><!-- .site-footer -->
			<script type="text/javascript" src='https://assets.hpm.io/app/themes/hpmv2/js/jplayer/jquery.jplayer.min.js?ver=20170928'></script>
		</div><!-- .site -->
		<script type='text/javascript'>
			var _sf_async_config={};
			/** CONFIGURATION START **/
			_sf_async_config.uid = 33583;
			_sf_async_config.domain = 'houstonpublicmedia.org';
			_sf_async_config.useCanonical = true;
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
	</body>
</html>


