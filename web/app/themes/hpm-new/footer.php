			</div>
<?php if ( is_page_template( 'page-blank.php' ) ) : ?>
		</div>
<?php else : ?>
			<h2 id="foot-banner"><a href="/donate">Stories like this are made possible by the generosity of our community of donors, foundations and corporate partners. If you value our reporting, join others and make a gift to Houston Public Media.<br /><br /><span class="donate"><span class="fas fa-heart"></span> DONATE</span></a></h2>
			<footer id="colophon" class="site-footer" role="contentinfo">
				<section>
					<div class="foot-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-White.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
					</div>
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
					<div class="foot-newsletter">
						<h3>Subscribe to Our Newsletters</h3>
						<h4><a href="https://www.houstonpublicmedia.org/news/today-in-houston-newsletter/">Today in Houston</a></h4>
						<p>Let the Houston Public Media newsroom help you start your day.</p>
						<h4><a href="https://www.houstonpublicmedia.org/support/newslettereguide-signup/">This Week</a></h4>
						<p>Get highlights, trending news, and behind-the-scenes insights from Houston Public Media delivered to your inbox each week.</p>
					</div>
					<div class="foot-contact">
						<p class="foot-button"><a href="/contact-us/">Contact Us</a></p>
						<p>4343 Elgin, Houston, TX 77204-0008</p>
						<div class="social-wrap">
							<div class="social-icon facebook">
								<a href="https://www.facebook.com/houstonpublicmedia" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
							</div>
							<div class="social-icon twitter">
								<a href="https://twitter.com/houstonpubmedia" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
							</div>
							<div class="social-icon instagram">
								<a href="https://instagram.com/houstonpubmedia" target="_blank"><span class="fab fa-instagram" aria-hidden="true"></span></a>
							</div>
							<div class="social-icon youtube">
								<a href="https://www.youtube.com/user/houstonpublicmedia" target="_blank"><span class="fab fa-youtube" aria-hidden="true"></span></a>
							</div>
							<div class="social-icon linkedin">
								<a href="https://linkedin.com/company/houstonpublicmedia" target="_blank"><span class="fab fa-linkedin-in" aria-hidden="true"></span></a>
							</div>
						</div>
					</div>
				</section>
				<div class="foot-tag">
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" target="_blank">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?></p>
				</div>
			</footer>
		</div>
	<?php
			wp_footer();
		endif; ?>
	</body>
</html>