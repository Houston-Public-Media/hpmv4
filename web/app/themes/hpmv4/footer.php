			</div>
<?php if ( is_page_template( 'page-blank.php' ) ) : ?>
		</div>
<?php else : ?>
			<h2 id="foot-banner"><a href="/donate">Resources like these are made possible by the generosity of our community of donors, foundations, and corporate partners. Join others and make your gift to Houston Public Media today!<br /><br /><span class="donate"><?php echo hpm_svg_output( 'heart' ); ?> DONATE</span></a></h2>
			<footer id="colophon" class="site-footer" role="contentinfo">
				<section>
					<div class="foot-logo">
						<?php echo hpm_svg_output( 'hpm' ); ?>
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
								<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'facebook' ); ?></a>
							</div>
							<div class="social-icon twitter">
								<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'twitter' ); ?></svg></a>
							</div>
							<div class="social-icon instagram">
								<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'instagram' ); ?></a>
							</div>
							<div class="social-icon youtube">
								<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'youtube' ); ?></a>
							</div>
							<div class="social-icon linkedin">
								<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'linkedin' ); ?></a>
							</div>
						</div>
					</div>
				</section>
				<div class="foot-tag">
					<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" rel="noopener" target="_blank">University of Houston</a></p>
					<p>Copyright &copy; <?php echo date('Y'); ?></p>
				</div>
			</footer>
		</div>
	<?php
			wp_footer();
		endif; ?>
	</body>
</html>