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
		'theme_location' => 'footer'
	) );
?>
						<div class="clear"></div>
					</nav>
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="http://www.uh.edu" target="_blank" style="color: #cc0000;">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?> | <a href="http://www.uhsystem.edu/privacy-notice/">Privacy Policy</a></p>
				</div><!-- .site-info -->
			</footer><!-- .site-footer -->
		</div><!-- .site -->
		<script type="text/javascript">(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = '//stream.publicbroadcasting.net/analytics/ab0p.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script>
		<script src="https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/css/diversecity/diversecity.js"></script>
		<script>try{Typekit.load({ async: true });}catch(e){}</script>
<?php
	wp_footer(); ?>
		<a href="#masthead" id="dc-jump"><span class="fa fa-arrow-up" aria-hidden="true"></span></a>
<?php
	if ( is_page_template( 'page-diversecity-your-stories.php' ) ) : ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>
		<script>
			function masonLoad($) {
				if ( window.wide > 480 )
				{
					var $grid = $('#float-wrap').imagesLoaded( function() {
						$grid.masonry({
							itemSelector: '.grid-item',
							stamp: '.stamp',
							columnWidth: '.grid-sizer',
						});
					});
				}
				else
				{
					if ( $('#float-wrap').masonry() ) {
						$('#float-wrap').masonry('destroy');
					}
					$('.grid-item').removeAttr('style');
				}
			}
			jQuery(document).ready(function($){
				masonLoad($);
				$(window).resize(function(){
					masonLoad($);
				});
				window.setTimeout(masonLoad($), 2000);
			});
		</script>
<?php
	endif;
?>
	</body>
</html>