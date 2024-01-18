			</div>
<?php if ( is_page_template( 'page-blank.php' ) ) { ?>
		</div>
<?php } else { ?>
			<h2 id="foot-banner"><a href="/support">Resources like these are made possible by the generosity of our community of donors, foundations, and corporate partners. Join others and make your gift to Houston Public Media today!<br /><br /><span class="donate"><?php echo hpm_svg_output( 'heart' ); ?> DONATE</span></a></h2>
			<footer id="colophon" class="site-footer" role="contentinfo">
				<div class="container">
					<div class="footer-section footer-top">
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Features</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59251 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Topic</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59257 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Art & Culture</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59249 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Awareness</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59250 ]); ?>
							</div>
						</div>
					</div>
					<div class="footer-section footer-middle">
						<h2>Programs & podcasts</h2>
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Local Programs</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59253 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>UH</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59258 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Education</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59788 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Podcasts</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59255 ]); ?>
							</div>
						</div>
					</div>
					<div class="footer-section footer-middle">
						<h2>Support</h2>
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Membership</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59256 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Giving Programs</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59252 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Volunteers</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59259 ]); ?>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Partnerships</h3>
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 59254 ]); ?>
							</div>
							<!--<div class="col-sm-6 col-md-4 col-lg-3 pb-4">
								<h3>Compliance</h3>
								<?php /*wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 42803 ]); */?>
							</div>-->
						</div>
					</div>
					<nav id="uh-foot-navigation" class="footer-navigation" role="navigation">
						<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 42803 ] ); ?>
					</nav>
					<div class="footer-section footer-last">
						<div class="row">
							<div class="col-sm-12 col-lg-7 col-xl-8">
								<?php wp_nav_menu([ 'menu_class' => 'nav-menu', 'menu' => 1956 ]); ?>
								<div class="footer-tag">
									<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" rel="noopener" target="_blank">University of Houston</a></p>
									<p>&copy; <?php echo date('Y'); ?> Houston Public Media</p>
								</div>
							</div>
							<div class="col-sm-12 col-lg-5 col-xl-4">
								<div class="icon-wrap">
									<div class="service-icon facebook">
										<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'facebook' ); ?><span class="screen-reader-text">Facebook</span></a>
									</div>
									<div class="service-icon twitter">
										<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'twitter' ); ?><span class="screen-reader-text">Twitter</span></a>
									</div>
									<div class="service-icon instagram">
										<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'instagram' ); ?><span class="screen-reader-text">Instagram</span></a>
									</div>
									<div class="service-icon youtube">
										<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'youtube' ); ?><span class="screen-reader-text">YouTube</span></a>
									</div>
									<div class="service-icon linkedin">
										<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'linkedin' ); ?><span class="screen-reader-text">LinkedIn</span></a>
									</div>
									<div class="service-icon mastodon">
										<a href="https://mastodon.social/@houstonpublicmedia" rel="noopener me" target="_blank"><?php echo hpm_svg_output( 'mastodon' ); ?><span class="screen-reader-text">Mastodon</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<nav id="uh-foot-navigation" class="footer-navigation" role="navigation">
					<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 58922 ] ); //56058  58922 ?>
				</nav>
			</footer>
		</div>
<?php }
		wp_footer(); ?>
	</body>
</html>