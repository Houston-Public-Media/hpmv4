<?php
function hpm_site_header(): void { ?>
			<header id="masthead" class="site-header" role="banner">
				<div class="header-container">
					<div class="site-branding">
						<div class="site-logo">
						<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston">
							<img src="<?php echo get_template_directory_uri(); ?>/images/houston-public-media-logo.png" alt="Houston Public Media" rel="Houston Public Media">

						</a>
							<!-- <?php echo hpm_svg_output( 'hpm' ); ?> -->
						</div>

						<div class="header-highlight-text">
							<a href="/tv8">TV 8</a> | <a href="/news887">News 88.7</a> | <a href="/classical">Classical</a> | <a href="/mixtape">Mixtape</a>
						</div>

						<div class="header-weather">
							<?php /* echo do_shortcode('[location-weather id="450650"]'); */?>
                        <?php echo hpm_weather(); ?>
						<!--	<div class="header-weather">
							<style id="sp_lw_dynamic_css450650">#splw-location-weather-450650.splw-main-wrapper {max-width: 320px;margin : auto;margin-bottom: 2em;}#splw-location-weather-450650 .splw-lite-wrapper,#splw-location-weather-450650 .splw-forecast-weather select,#splw-location-weather-450650 .splw-forecast-weather option,#splw-location-weather-450650 .splw-lite-wrapper .splw-weather-attribution a{color:#fff;text-decoration: none;}#splw-location-weather-450650 .splw-lite-wrapper{ border: 0px solid #e2e2e2}#splw-location-weather-450650 .splw-lite-wrapper{border-radius: 8px;}#splw-location-weather-450650 .splw-weather-title {margin-top :0px;margin-right :0px;margin-bottom: 20px;margin-left: 0px;}#splw-location-weather-450650 .splw-weather-icons div svg path{fill:#dfe6e9;}#splw-location-weather-450650 .splw-lite-wrapper,#splw-location-weather-450650 .splw-forecast-weather option{background:#222054}</style>
							<div id="splw-location-weather-450650" class="splw-main-wrapper" data-shortcode-id="450650">
								<div class="splw-weather-title"></div><div class="splw-lite-wrapper">
									<div class="splw-lite-header"><div class="splw-lite-header-title-wrapper">
										<div class="splw-lite-header-title">Houston, US</div> <div class="splw-lite-current-time">September 18, 2023</div></div></div><div class="splw-lite-body"><div class="splw-lite-current-temp"><div class="splw-cur-temp"><img src="https://openweathermap.org/img/w/03n.png" class="weather-icon"> <span class="cur-temp"> 74° F</span></div></div><div class="splw-lite-current-text"> </div></div> --></div></div>
							</div>

						</div>					</div>
				</div>
	<div class="navigation-wrap">
		<div class="header-container">
		  
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div tab-index="0" id="top-mobile-menu" class="nav-button" aria-expanded="false"><?php echo hpm_svg_output( 'bars' ); ?><br /><span class="top-mobile-text">MENU</span></div><div id="focus-sink" tab-index="-1" style="position: absolute; top: 0; left: 0;height:1px; width: 1px;"></div>
				<div tab-index="0" id="top-mobile-close" class="nav-button"><?php echo hpm_svg_output( 'times' ); ?><br /><span class="top-mobile-text">CLOSE</span></div>
				<div class="quick-access">

					<ul>
						<li class="nav-top nav-passport"><a href="/support/passport/" tab-index="0"><span style="text-indent:-9999px;">PBS Passport</span><!--?xml version="1.0" encoding="utf-8"?--><svg id="pbs-passport-logo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 488.8 80" style="enable-background:new 0 0 488.8 80;" xml:space="preserve" aria-hidden="true"> <style type="text/css"> .st0{fill:#0A145A;} .st1{fill:#5680FF;} .st2{fill:#FFFFFF;} </style> <g> <g> <path class="st0" d="M246.2,18c2.6,1.2,4.8,3.1,6.3,5.5s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2H232v15.6h-11.1V16h15.2C239.5,15.9,243,16.6,246.2,18z M241.1,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.9-2.2-5.2c-1.6-1.3-3.6-1.9-5.7-1.8H232v14.5h3C237.2,39.5,239.4,38.7,241.1,37.2L241.1,37.2z"></path> <path class="st0" d="M284.5,31.4c2.6,2.6,3.9,6.1,3.9,10.7v21.8H280l-1.2-3c-1.3,1.1-2.9,2-4.5,2.6c-1.9,0.7-4,1.1-6.1,1.1 c-3.1,0.1-6.2-0.9-8.5-2.9c-2.2-2.1-3.4-5-3.2-8.1c0-4.2,1.6-7.2,4.7-9c3.6-2,7.6-2.9,11.7-2.8c1.7,0,3.4,0.1,5.1,0.4 c0.1-1.7-0.4-3.4-1.4-4.8c-0.9-1.1-2.8-1.7-5.6-1.7c-1.9,0-3.8,0.2-5.6,0.7c-1.9,0.4-3.8,1.1-5.6,1.9v-8.6c4.2-1.5,8.6-2.3,13-2.3 C278,27.5,281.9,28.8,284.5,31.4z M268.4,55.5c0.9,0.7,2,1.1,3.2,1c2.3-0.1,4.5-0.8,6.3-2.1v-5.7c-1.1-0.1-2.2-0.2-3.3-0.2 c-1.8-0.1-3.6,0.3-5.3,1c-1.3,0.6-2.1,1.9-2,3.4C267.2,53.9,267.6,54.8,268.4,55.5z"></path> <path class="st0" d="M294.5,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.7-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.8,0.5,10,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.2,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C302,64.7,298.1,64,294.5,62.6z"></path> <path class="st0" d="M325.1,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.8-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.7,0.5,9.9,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.1,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C332.5,64.7,328.7,64,325.1,62.6z"></path> <path class="st0" d="M386.9,32.3c3.2,3.2,4.9,7.7,4.9,13.7c0.1,3.4-0.6,6.7-2.1,9.8c-1.3,2.7-3.3,5-5.9,6.6 c-2.7,1.6-5.8,2.4-9,2.3c-2.4,0.1-4.8-0.4-7.1-1.3v15.1h-10.5V30.4c5.2-1.8,10.7-2.8,16.2-2.9C379.1,27.5,383.6,29.1,386.9,32.3z M378.6,52.8c1.5-2.1,2.3-4.6,2.2-7.2c0-3-0.7-5.2-2.1-6.8s-3.5-2.5-5.7-2.4c-1.8,0-3.6,0.3-5.4,0.8v17.1c1.6,0.8,3.3,1.1,5,1.1 C374.9,55.6,377.1,54.6,378.6,52.8z"></path> <path class="st0" d="M404.6,62.4c-2.8-1.5-5.1-3.7-6.6-6.4c-1.7-3.1-2.5-6.5-2.4-10c-0.1-3.5,0.7-6.9,2.4-9.9 c1.5-2.7,3.9-4.9,6.6-6.4c3-1.5,6.3-2.3,9.6-2.2c3.3,0,6.5,0.7,9.4,2.2c2.8,1.4,5.1,3.6,6.7,6.3c1.6,2.9,2.5,6.2,2.4,9.5 c0.1,3.6-0.7,7.1-2.4,10.2c-1.5,2.8-3.8,5.1-6.6,6.6c-3,1.6-6.3,2.3-9.6,2.3C410.8,64.7,407.5,63.9,404.6,62.4z M419.6,53.1 c1.4-1.7,2.1-4.2,2.1-7.4c0.2-2.4-0.6-4.9-2-6.8c-1.3-1.6-3.4-2.6-5.5-2.5c-2.1-0.1-4.2,0.8-5.5,2.4c-1.4,1.6-2.1,4-2.1,7.1 s0.7,5.5,2.1,7.2c2.5,3,6.9,3.4,10,1C419.1,53.8,419.4,53.5,419.6,53.1L419.6,53.1z"></path> <path class="st0" d="M461,28.2v10.1c-0.7-0.2-1.4-0.4-2.1-0.5c-0.8-0.1-1.5-0.2-2.3-0.2c-1.5,0-3.1,0.4-4.4,1.1 c-1.3,0.7-2.3,1.6-3.2,2.8v22.4h-10.6V28.4h9.1l1.3,4.4c0.9-1.5,2.1-2.8,3.6-3.6c1.7-0.9,3.5-1.3,5.4-1.3 C458.9,27.8,460,27.9,461,28.2z"></path> <path class="st0" d="M479.6,36.2v14.5c-0.1,1.4,0.3,2.8,1.1,4c1,1,2.4,1.5,3.8,1.4c1.4,0,2.7-0.2,4-0.6v8c-1,0.4-2.1,0.6-3.1,0.8 c-1.3,0.2-2.7,0.3-4,0.3c-4.1,0-7.2-1-9.3-3.1c-2-2.1-3.1-5.1-3.1-9V36.2h-5.5v-7.8h5.5v-7.7l10.6-2.9v10.6h9.2v7.8H479.6z"></path> </g> <g> <path class="st0" d="M25.3,17.9c2.6,1.2,4.8,3,6.3,5.4s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2h-4.1v15.7H0V16h15.2C18.7,15.9,22.1,16.6,25.3,17.9z M20.2,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.8-2.1-5.1c-1.6-1.3-3.6-1.9-5.7-1.8h-3.3v14.5h3C16.4,39.5,18.6,38.7,20.2,37.2z"></path> <path class="st0" d="M70.1,41.8c2,2.1,3,5,2.9,7.9c0.1,4-1.6,7.8-4.7,10.3s-7.5,3.8-13.2,3.8H38.3V16h15.6c5.2,0,9.1,1,11.9,3 c2.7,2,4.1,5,4.1,9c0.1,2.2-0.5,4.5-1.8,6.3c-1.1,1.7-2.6,3-4.4,3.7C66.1,38.6,68.4,39.9,70.1,41.8z M49.4,24.3v10.8h3.2 c1.7,0.1,3.3-0.4,4.5-1.5c1.1-1.1,1.7-2.6,1.6-4.2c0.1-1.4-0.5-2.8-1.5-3.8c-1.3-1-2.8-1.4-4.4-1.3H49.4z M59.6,53.7 c1.3-1.2,1.9-2.9,1.8-4.6c0.1-1.7-0.6-3.3-1.9-4.4c-1.2-1-3.1-1.6-5.7-1.6h-4.4v12.3h4.4C56.5,55.3,58.4,54.8,59.6,53.7z"></path> <path class="st0" d="M83.3,63.8c-2.1-0.4-4.2-1-6.2-1.9V51.5c2,1,4,1.9,6.2,2.5c2.2,0.7,4.4,1,6.7,1c2,0.1,3.9-0.3,5.7-1.2 c1.2-0.7,1.9-2,1.9-3.4s-0.8-2.8-2-3.5c-2.2-1.5-4.6-2.7-7.1-3.7c-4.1-1.8-7.1-3.8-8.9-6c-1.9-2.3-2.9-5.1-2.8-8.1 c0-2.6,0.8-5.2,2.3-7.3c1.6-2.2,3.8-3.8,6.3-4.8c2.9-1.1,6-1.7,9.1-1.7c2.2,0,4.4,0.1,6.6,0.5c1.7,0.3,3.4,0.7,5.1,1.3v9.7 c-3.3-1.3-6.8-1.9-10.3-1.9c-1.8-0.1-3.7,0.3-5.3,1c-1.2,0.6-2,1.8-2,3.2c0,0.9,0.4,1.7,1,2.3c0.8,0.7,1.6,1.2,2.5,1.7 c1.1,0.5,3.1,1.4,6,2.7c4,1.8,6.8,3.8,8.5,6.1s2.6,5.1,2.5,7.9c0.2,5.6-3.1,10.8-8.3,12.9c-3.2,1.3-6.6,2-10,1.9 C88.1,64.5,85.7,64.3,83.3,63.8z"></path> </g> <g> <circle class="st1" cx="164.9" cy="40" r="40"></circle> <path class="st2" d="M164.8,4.5c-19.8,0-35.8,15.9-35.9,35.7c0,19.6,15.9,35.6,35.5,35.7c19.7,0.1,35.8-15.8,35.9-35.5 C200.4,20.7,184.5,4.6,164.8,4.5z M134.5,40.3L134.5,40.3l23.3,6.8l6.9,23.2C148.1,70.2,134.7,56.9,134.5,40.3z M157.8,33.2 L134.5,40c0.1-16.6,13.6-29.9,30.2-30L157.8,33.2z M164.9,70.3L164.9,70.3l6.9-23.2l23.3-6.8C195,56.9,181.5,70.3,164.9,70.3z M171.8,33.2L165,10c16.6,0,30,13.4,30.1,30l0,0L171.8,33.2z"></path> <polygon class="st2" points="151.3,49.2 146,58.9 155.7,53.6 154.7,50.2"></polygon> <polygon class="st2" points="174.9,30.1 178.3,31.1 183.6,21.5 173.9,26.7"></polygon> <polygon class="st2" points="178.3,49.2 174.9,50.2 173.9,53.6 183.6,58.9"></polygon> <polygon class="st2" points="154.7,30.1 155.7,26.7 146,21.5 151.3,31.1"></polygon> </g> </g> </svg></a></li>
						<li class="nav-top nav-uh"><a target="_blank" href="https://www.uh.edu" rel="noopener" tab-index="0">UH</a></li>
					</ul>
					<div id="top-search" tab-index="0" aria-expanded="false"><?php echo hpm_svg_output( 'search' ); get_search_form(); ?></div>

				</div>
				<div class="nav-wrap">
					<?php
					// Primary navigation menu.
					wp_nav_menu([
						'menu_class' => 'nav-menu',
						'menu' => 2111,
						'walker' => new HPM_Menu_Walker
					]);
					?>
					<div class="d-flex nav-right">
						<div class="nav-buttons" id="top-listen">
							<button aria-label="Listen Live" data-href="/listen-live" type="button" data-dialog="480:855">
								<img src="<?php echo get_template_directory_uri(); ?>/images/icon-listen.png" alt="Listen"> Listen
							</button>
							<button aria-label="Watch Live" data-href="/watch-live" type="button" data-dialog="820:850">
								<img src="<?php echo get_template_directory_uri(); ?>/images/icon-watch.png" alt="Watch"> Watch
							</button>
							<a href="/support" class="btn-donate">
								<img src="<?php echo get_template_directory_uri(); ?>/images/icon-donate.png" alt="Donate"> Donate
							</a>
						</div>
						<div class="d-flex social-icon-wrap">
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
				</div>
			</nav>
		</div>
	</div>
			</header><?php
}

function hpm_header_info(): void {
	global $wp_query;
	$reqs = [
		'description' => 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.',
		'keywords' => [ 'Houston Public Media', 'KUHT', 'TV 8', 'Houston Public Media Schedule', 'Educational TV Programs', 'independent program broadcasts', 'University of Houston', 'nonprofit', 'NPR News', 'KUHF', 'Classical Music', 'Arts &amp; Culture', 'News 88.7' ],
		'permalink' => 'https://www.houstonpublicmedia.org',
		'title' => 'Houston Public Media',
		'thumb' => 'https://cdn.houstonpublicmedia.org/assets/images/HPM-logo-OGimage-2.jpg',
		'thumb_meta' => [
			'width' => 1200,
			'height' => 630,
			'mime-type' => 'image/jpeg'
		],
		'og_type' => 'website',
		'author' => [],
		'publish_date' => '',
		'modified_date' => '',
		'hpm_section' => ''
	];

	if ( is_home() || is_404() ) {
		// Do Nothing
	} else {
		$ID = $wp_query->get_queried_object_id();
		$query_obj = $wp_query->get_queried_object();

		if ( is_author() ) {
			global $curauth;
			global $author_check;
			$reqs['og_type'] = 'profile';
			$reqs['permalink'] = get_author_posts_url( $curauth->ID, $curauth->user_nicename );
			$reqs['title'] = $curauth->display_name." | Houston Public Media";
			if ( !empty( $author_check ) ) {
				while ( $author_check->have_posts() ) {
					$author_check->the_post();
					$head_excerpt = htmlentities( wp_strip_all_tags( get_the_content(), true ), ENT_QUOTES );
					if ( !empty( $head_excerpt ) && $head_excerpt !== 'Biography pending.' ) {
						$reqs['description'] = $head_excerpt;
					}
					$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE );
					$head_categories = get_the_terms( get_the_ID(), 'staff_category' );
					if ( !empty( $head_categories ) ) {
						$reqs['keywords'] = [];
						foreach( $head_categories as $hcat ) {
							$reqs['keywords'][] = $hcat->name;
						}
					}
					$reqs['title'] = $curauth->display_name.", ".$author['title']." | Houston Public Media";
				}
				wp_reset_query();
			}
		} elseif ( is_archive() ) {
			if ( is_post_type_archive() ) {
				$obj = get_post_type_object( get_post_type() );
				$reqs['permalink'] = get_post_type_archive_link( get_post_type() );
				$reqs['title'] = $obj->labels->name . ' | Houston Public Media';
				$reqs['description'] = wp_strip_all_tags( $obj->description, true );
			} else {
				$reqs['permalink'] = get_the_permalink( $ID );
				if ( !empty( $query_obj->name ) ) {
					$reqs['title'] = $query_obj->name . ' | Houston Public Media';
				}
			}
		} elseif ( is_page_template( 'page-npr-articles.php' ) ) {
				global $nprdata;
				$reqs['title'] = $nprdata['title'];
				$reqs['permalink'] = $nprdata['permalink'];
				$reqs['description'] = htmlentities( wp_strip_all_tags( $nprdata['excerpt'], true ), ENT_QUOTES );
				$reqs['keywords'] = $nprdata['keywords'];
				$reqs['thumb'] = $nprdata['image']['src'];
				$reqs['thumb_meta'] = [
					'width' => $nprdata['image']['width'],
					'height' => $nprdata['image']['height'],
					'mime-type' => $nprdata['image']['mime-type']
				];
				$reqs['publish_date'] = $nprdata['date'];
		} elseif ( is_single() || is_page() || get_post_type() == 'embeds' ) {
			$attach_id = get_post_thumbnail_id( $ID );
			if ( !empty( $attach_id ) ) {
				$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
				if ( $feature_img !== false ) {
					$reqs['thumb_meta'] = [
						'width' => $feature_img[1],
						'height' => $feature_img[2],
						'mime-type' => get_post_mime_type( $attach_id )
					];
					$reqs['thumb'] = $feature_img[0];
				}
			}
			$seo_headline = get_post_meta( $ID, 'hpm_seo_headline', true );
			if ( !empty( $seo_headline ) ) {
				$reqs['title'] = wp_strip_all_tags( $seo_headline ) . ' | Houston Public Media';
			} else {
				$reqs['title'] = wp_strip_all_tags( get_the_title( $ID ), true ) . ' | Houston Public Media';
			}
			$reqs['permalink'] = get_the_permalink( $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$reqs['og_type'] = 'article';
			$coauthors = get_coauthors( $ID );
			foreach ( $coauthors as $coa ) {
				$author_fb = '';
				if ( is_a( $coa, 'wp_user' ) ) {
					$author_check = new WP_Query([
						'post_type' => 'staff',
						'post_status' => 'publish',
						'meta_query' => [[
							'key' => 'hpm_staff_authid',
							'compare' => '=',
							'value' => $coa->ID
						]]
					]);
					if ( $author_check->have_posts() ) {
						$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
						if ( !empty( $author_meta['facebook'] ) ) {
							$author_fb = $author_meta['facebook'];
						}
					}
				} elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) {
					if ( !empty( $coa->linked_account ) ) {
						$authid = get_user_by( 'login', $coa->linked_account );
						if ( $authid !== false ) {
							$author_check = new WP_Query([
								'post_type' => 'staff',
								'post_status' => 'publish',
								'meta_query' => [[
									'key' => 'hpm_staff_authid',
									'compare' => '=',
									'value' => $authid->ID
								]]
							]);
							if ( $author_check->have_posts() ) {
								$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
								if ( !empty( $author_meta['facebook'] ) ) {
									$author_fb = $author_meta['facebook'];
								}
							}
						}
					}
				}
				$reqs['author'][] = [
					'profile' => ( !empty( $author_fb ) ? $author_fb : get_author_posts_url( $coa->ID, $coa->user_nicename ) ),
					'first_name' => $coa->first_name,
					'last_name' => $coa->last_name,
					'username' => $coa->user_nicename
				];
			}
			$reqs['publish_date'] = get_the_date( 'c', $ID );
			$reqs['modified_date'] = get_the_modified_date( 'c', $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$head_categories = get_the_category( $ID );
			$head_tags = wp_get_post_tags( $ID );
			$reqs['keywords'] = [];
			foreach( $head_categories as $hcat ) {
				$reqs['keywords'][] = $hcat->name;
			}
			foreach( $head_tags as $htag ) {
				$reqs['keywords'][] = $htag->name;
			}
			if ( get_post_type() === 'post' ) {
				$reqs['hpm_section'] = hpm_top_cat( $ID );
			} elseif ( get_post_type() === 'staff' ) {
				$reqs['og_type'] = 'profile';
			}
		}
	}
?>
		<script type='text/javascript'>let _sf_startpt=(new Date()).getTime();</script>
		<link rel="profile" href="https://gmpg.org/xfn/11" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
		<meta name="description" content="<?PHP echo $reqs['description']; ?>" />
		<meta name="keywords" content="<?php echo implode( ', ', $reqs['keywords'] ); ?>" />
		<meta name="bitly-verification" content="7777946f1a0a" />
		<meta name="google-site-verification" content="WX07OGEaNirk2km8RjRBernE0mA7_QL6ywgu6NXl1TM" />
		<meta name="theme-color" content="#f5f5f5" />
		<link rel="icon" sizes="48x48" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-48.png" />
		<link rel="icon" sizes="96x96" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-96.png" />
		<link rel="icon" sizes="144x144" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-144.png" />
		<link rel="icon" sizes="192x192" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-192.png" />
		<link rel="icon" sizes="256x256" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-256.png" />
		<link rel="icon" sizes="384x384" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-384.png" />
		<link rel="icon" sizes="512x512" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/icon-512.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-57.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-120.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-152.png" />
		<link rel="apple-touch-icon" sizes="167x167" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-167.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/apple-touch-icon-180.png" />
		<link rel="mask-icon" href="https://cdn.houstonpublicmedia.org/assets/images/favicon/safari-pinned-tab.svg" color="#ff0000" />
		<meta name="msapplication-config" content="https://cdn.houstonpublicmedia.org/assets/images/favicon/config.xml" />
		<link rel="manifest" href="/manifest.webmanifest" />
		<meta name="apple-itunes-app" content="app-id=1549226694,app-argument=<?php echo $reqs['permalink']; ?>" />
		<meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
		<meta property="fb:app_id" content="523938487799321" />
		<meta property="fb:admins" content="37511993" />
		<meta property="fb:pages" content="27589213702" />
		<meta property="fb:pages" content="183418875085596" />
		<meta property="og:type" content="<?php echo $reqs['og_type'] ?>" />
		<meta property="og:title" content="<?php echo $reqs['title']; ?>" />
		<meta property="og:url" content="<?php echo $reqs['permalink']; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $reqs['description']; ?>" />
		<meta property="og:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:url" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:height" content="<?php echo $reqs['thumb_meta']['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $reqs['thumb_meta']['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $reqs['thumb_meta']['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo $reqs['thumb']; ?>" />
<?php
	if ( ( is_single() || is_page_template( 'page-npr-articles.php' ) ) && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) { ?>
		<meta property="article:content_tier" content="free" />
		<meta property="article:published_time" content="<?php echo $reqs['publish_date']; ?>" />
		<meta property="article:modified_time" content="<?php echo $reqs['modified_date']; ?>" />
		<meta property="article:publisher" content="https://www.facebook.com/houstonpublicmedia/" />
		<meta property="article:section" content="<?php echo $reqs['hpm_section']; ?>" />
<?php
		if ( !empty( $reqs['keywords'] ) ) {
			foreach( $reqs['keywords'] as $keys ) { ?>
		<meta property="article:tag" content="<?php echo $keys; ?>" />
<?php
			}
		}
		foreach ( $reqs['author'] as $aup ) { ?>
		<meta property="article:author" content="<?php echo $aup['profile']; ?>" />
<?php
		}
	}
	if ( is_author() ) { ?>
		<meta property="profile:first_name" content="<?php echo $curauth->first_name; ?>" />
		<meta property="profile:last_name" content="<?php echo $curauth->last_name; ?>" />
		<meta property="profile:username" content="<?php echo $curauth->user_nicename; ?>" />
<?php
	} elseif ( is_single() && get_post_type() === 'staff' ) {
		global $curstaff;
		if ( !empty( $curstaff['first_name'] ) && !empty( $curstaff['last_name'] ) ) { ?>
		<meta property="profile:first_name" content="<?php echo $curstaff['first_name']; ?>" />
		<meta property="profile:last_name" content="<?php echo $curstaff['last_name']; ?>" />
		<meta property="profile:username" content="<?php echo $curstaff['user_nicename']; ?>" />
<?php
		}
	} ?>
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@houstonpubmedia" />
		<meta name="twitter:creator" content="@houstonpubmedia" />
		<meta name="twitter:title" content="<?php echo $reqs['title']; ?>" />
		<meta name="twitter:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta name="twitter:url" content="<?php echo $reqs['permalink']; ?>" />
		<meta name="twitter:description" content="<?php echo $reqs['description']; ?>" />
		<meta name="twitter:widgets:link-color" content="#000000" />
		<meta name="twitter:widgets:border-color" content="#000000" />
		<meta name="twitter:partner" content="tfwp" />
<?php
	if ( is_single() && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) {
		$jsonLd = new stdClass;
		$jsonLd->{'@context'} = "https://schema.org";
		$jsonLd->{'@type'} = "NewsArticle";
		$jsonLd->headline = str_replace( ' | Houston Public Media', '', $reqs['title'] );
		$jsonLd->datePublished = $reqs['publish_date'];
		$jsonLd->dateModified = $reqs['modified_date'];
		$jsonLd->image = [ $reqs['thumb'] ];
		$jsonLd->author = [];
		$artpub = new stdClass;
		$artpub->{'@type'} = "Organization";
		$artpub->name = 'Houston Public Media';
		$artpub->url = 'https://www.houstonpublicmedia.org';
		$jsonLd->publisher = [ $artpub ];
		foreach ( $reqs['author'] as $auth ) {
			$new_auth = new stdClass;
			$new_auth->{'@type'} = "Person";
			$new_auth->name = $auth['first_name'] . " " . $auth['last_name'];
			$new_auth->url = $auth['profile'];
			$jsonLd->author[] = $new_auth;
		}
		echo '<script type="application/ld+json">' . json_encode( $jsonLd ) . '</script>';
	}
}
add_action( 'wp_head', 'hpm_header_info', 2 );
add_action( 'wp_head', 'hpm_google_tracker', 100 );

function hpm_body_open(): void {
	global $wp_query;
	if ( !empty( $_GET['browser'] ) && $_GET['browser'] == 'inapp' ) { ?>
	<script>setCookie('inapp','true',1);</script>
	<style>#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }</style>
<?php } ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv4' ); ?></a>
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) { ?>
		<?php echo HPM_Promos::generate_static( 'emergency' ); ?>
		<?php hpm_site_header(); ?>
		<?php echo hpm_breaking_banner(); echo hpm_talkshows(); ?>
<?php
	} elseif ( is_page_template( 'page-listen.php' ) ) { ?>
		<?php echo HPM_Promos::generate_static( 'emergency' ); ?>
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<?php //echo hpm_svg_output( 'hpm' ); ?>
						<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston">
							<img src="<?php echo get_template_directory_uri(); ?>/images/houston-public-media-logo.png" alt="Houston Public Media" rel="Houston Public Media">
						</a>
					</div>
					<div id="top-donate"><a href="/donate"><?php echo hpm_svg_output( 'heart' ); ?><br /><span class="top-mobile-text">Donate</span></a></div>
					<div tab-index="0" id="top-mobile-close" class="nav-button"><?php echo hpm_svg_output( 'times' ); ?><br /><span class="top-mobile-text">CLOSE</span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div tab-index="0" id="top-mobile-menu" class="nav-button" aria-expanded="false"><?php echo hpm_svg_output( 'bars' ); ?><br /><span class="top-mobile-text">MENU</span></div><div id="focus-sink" tab-index="-1" style="position: absolute; top: 0; left: 0;height:1px; width: 1px;"></div>
						<div class="nav-wrap">
							<div id="top-search" tab-index="0" aria-expanded="false"><?php echo hpm_svg_output( 'search' ); get_search_form(); ?></div>
							<?php
								wp_nav_menu([
									'menu_class' => 'nav-menu',
									'menu' => 12244,
									'walker' => new HPM_Menu_Walker
								]);
							?>
						</div>
					</nav>
				</div>
			</header>
<?php
	} ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) { ?>
				<!-- /9147267/HPM_Under_Nav -->
				<!--<div id='div-gpt-ad-1488818411584-0'>
					<script>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
					</script>
				</div>-->
<?php
	}
	echo HPM_Promos::generate_static( 'top' );
}
add_action( 'body_open', 'hpm_body_open', 11 );

function hpm_talkshows(): string {
	wp_reset_query();
	global $wp_query;
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$output = '';
	$anc = get_post_ancestors( get_the_ID() );
	$bans = [ 135762, 290722, 303436, 303018, 315974 ];
	$hm_air = hpm_houston_matters_check();
	if ( empty( $wp_query->post ) ) {
		return '';
	}
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) && !empty( $wp_query->post ) && $wp_query->post->post_type !== 'embeds' ) {
		if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && ( $now['hours'] == 9 || $now['hours'] == 15 ) && !empty( $hm_air[ $now['hours'] ] ) && $hm_air[ $now['hours'] ] ) {
			if ( $now['hours'] == 15 ) {
				$output .= '<div id="hm-top" class="townsquare"><p><span><a href="/listen-live/"><strong>Town Square</strong> is on the air now!</a> Join the conversation:</span> Call <strong><a href="tel://+18884869677">888.486.9677</a></strong> | Email <a href="mailto:talk@townsquaretalk.org">talk@townsquaretalk.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			} else {
				$output .= '<div id="hm-top"><p><span><a href="/listen-live/"><strong>Houston Matters</strong> is on the air now!</a> Join the conversation:</span> Call <strong><a href="tel://+17134408870">713.440.8870</a></strong> | Email <a href="mailto:talk@houstonmatters.org">talk@houstonmatters.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			}
		}
	}
	return $output;
}

function hpm_breaking_banner(): string {
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$output = '';
	$hpm_priority = get_option( 'hpm_priority' );
	$hpm_breakingnews = get_option( 'hpm_breakingnews' );
	if ( !empty( $hpm_breakingnews['homepage'] ) ) {
		$publish = get_the_date( 'U', $hpm_breakingnews['homepage'][0]);


		$ptime = get_the_time('U', $hpm_breakingnews['homepage'][0]);
		$diff = $now[0] - $ptime;
		$expirationtime = (int)$hpm_breakingnews['expirationdate'][0] * 3600;
		$newstype = $hpm_breakingnews['type'];
		$newsclasstype = ( $newstype == "Breaking News" ? "breakingnews" : "developingstory" );
		$newclassheading = ( $newstype == "Breaking News" ? '<span class="breakingnews-header" style="background-color: #ee1812;"><strong>Breaking News</strong></span>' : '<span class="developingstory-header"><strong>Developing Story</strong></span>' );
		if ( $diff < $expirationtime ) {
			$output .= '<div id="hm-top" class="'.$newsclasstype.'"><p>'.$newclassheading.' <a href="' . get_the_permalink( $hpm_breakingnews['homepage'][0] ) . '">' . get_the_title( $hpm_breakingnews['homepage'][0] ) . '</a></p></div>';
			return $output;
		}
	}
	return $output;
}

function hpm_blank_header(): void {
	echo '<!DOCTYPE html>' .
		'<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">' .
		 '<head>' .
		 '<meta charset="' . get_bloginfo( 'charset', 'display' ) . '">';
	add_action( 'wp_head', function(){
		wp_dequeue_style( 'hpm-css' );
		wp_deregister_style( 'hpm-css' );
		wp_dequeue_script( 'hpm-js' );
		wp_deregister_script( 'hpm-js' );
	}, 1 );

	remove_action( 'wp_head', 'hpm_inline_style', 100 );
	wp_head();
	echo "</head>";
}