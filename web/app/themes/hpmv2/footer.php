			</div><!-- .site-content -->
<?php if ( is_page_template( 'page-blank.php' ) ) : ?>
		</div>
<?php else : ?>
			<footer id="colophon" class="site-footer" role="contentinfo">
				<section>
					<div class="site-info">
						<div class="foot-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-White.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
						</div>
					</div>
					<div class="foot-nav">
						<div class="foot-hpm">
							<h3>Houston Public Media</h3>
							<nav id="second-navigation" class="footer-navigation" role="navigation">
								<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 1956 ] ); ?>
								<div class="clear"></div>
							</nav>
						</div>
						<div class="foot-comply">
							<h3>Compliance</h3>
							<nav id="third-navigation" class="footer-navigation" role="navigation">
								<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 42803 ] ); ?>
								<div class="clear"></div>
							</nav>
						</div>
					</div>
					<div class="foot-newsletter">
						<h3>Subscribe to Our Newsletter</h3>
						<p>Get highlights, trending news and behind-the-scenes insights from Houston Public Media delivered to your inbox each week.</p>
						<p><iframe style="border: 0 none transparent;" src="https://cdn.hpm.io/assets/enewsletter_signup_new.html" width="100%" height="115" frameborder="no" allowfullscreen="allowfullscreen"></iframe></p>
					</div>
					<div class="foot-contact">
						<p class="foot-button"><a href="/contact-us/">Contact Us</a></p>
						<p>4343 Elgin, Houston, TX 77204-0008</p>
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
						</div>
					</div>
				</section>
				<div class="foot-tag">
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" target="_blank">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?></p>
				</div>
			</footer><!-- .site-footer -->
		</div><!-- .site -->
		<?php wp_footer(); ?>
	<?php endif; ?>
	</body>
</html>