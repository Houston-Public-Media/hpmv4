<?php
function author_footer( $id ): string {
	$output = '<div id="author-wrap">';
	$coauthors = get_coauthors( $id );
	foreach ( $coauthors as $coa ) {
		$temp = get_transient( 'hpm_author_' . $coa->user_nicename );
		if ( !empty( $temp ) ) {
			$output .= $temp;
			continue;
		}
		$local = $guest = false;
		$author = null;
		$meta = '';
		if ( is_a( $coa, 'wp_user' ) ) {
			$author = new WP_Query( [ 'post_type' => 'staff', 'post_status' => 'publish', 'meta_query' => [ [ 'key' => 'hpm_staff_authid', 'compare' => '=', 'value' => $coa->ID ] ] ] );
		} elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) {
			if ( !empty( $coa->linked_account ) ) {
				$authid = get_user_by( 'login', $coa->linked_account );
				if ( $authid !== false ) {
					$author = new WP_Query( [ 'post_type' => 'staff', 'post_status' => 'publish', 'meta_query' => [ [ 'key' => 'hpm_staff_authid', 'compare' => '=', 'value' => $authid->ID ] ] ] );
				}
			} else {
				$guest = true;
			}
		}
		if ( !empty( $author ) && $author->have_posts() ) {
			$local = true;
			$meta = $author->post->hpm_staff_meta;
		}

		$temp .= '<div class="author-card"><section class="sidebar-author">' .
			'<div class="d-flex sa-header">' .
				( $local && has_post_thumbnail( $author->post->ID ) ? '<div class="author-image sa-pic">' . get_the_post_thumbnail( $author->post->ID, 'post-thumbnail', [ 'alt' => $author->post->post_title ] ) . '</div>' : '' ) .
				'<div class="sa-info flex-grow-1">'.
					'<h2>' .
						( $local ? $author->post->post_title : $coa->display_name ) .
						( $local && !empty( $meta[ 'pronouns' ] ) ? ' <span class="staff-pronouns">' . $meta[ 'pronouns' ] . '</span>' : '' ) .
					'</h2>' .
						( $local && !empty( $meta['title'] ) ? '<h3>' . $meta[ 'title' ] . '</h3>' : '' );
		$icon_wrap = '';
		if ( $local ) {
			if ( !empty( $meta[ 'phone' ] ) ) {
				$icon_wrap .= '<div class="service-icon phone"><a href="tel://+1' . str_replace( [ '(', ')', ' ', '-', '.' ], [ '', '', '', '', '' ], $meta[ 'phone' ] ) . '" rel="noopener" title="Call ' . ( $author->post->post_title ?? $coa->display_name ) . ' at ' . $meta[ 'phone' ] . '" data-phone="' . $meta[ 'phone' ] . '">' . hpm_svg_output( 'phone' ) . '<span class="screen-reader-text" >Call</span></a></div>';
			}
			if ( !empty( $meta[ 'facebook' ] ) ) {
				$icon_wrap .= '<div class="service-icon facebook"><a href="' . $meta[ 'facebook' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on Facebook" target="_blank">' . hpm_svg_output( 'facebook' ) . '<span class="screen-reader-text" >Facebook</span></a></div>';
			}
			if ( !empty( $meta[ 'twitter' ] ) ) {
				$icon_wrap .= '<div class="service-icon twitter"><a href="' . $meta[ 'twitter' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on Twitter" target="_blank">' . hpm_svg_output( 'twitter' ) . '<span class="screen-reader-text" >Twitter</span></a></div>';
			}
			if ( !empty( $meta[ 'linkedin' ] ) ) {
				$icon_wrap .= '<div class="service-icon linkedin"><a href="' . $meta[ 'linkedin' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on LinkedIn" target="_blank">' . hpm_svg_output( 'linkedin' ) . '<span class="screen-reader-text" >Linkedln</span></a></div>';
			}
			if ( !empty( $meta[ 'fediverse' ] ) ) {
				if ( str_contains( $meta['fediverse'], 'threads.net' ) ) {
					$icon_wrap .= '<div class="service-icon threads"><a href="' . $meta[ 'fediverse' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on Threads" target="_blank">' . hpm_svg_output( 'threads' ) . '<span class="screen-reader-text" >Threads</span></a></div>';
				} else {
					$icon_wrap .= '<div class="service-icon mastodon"><a href="' . $meta[ 'fediverse' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on Mastodon" target="_blank">' . hpm_svg_output( 'mastodon' ) . '<span class="screen-reader-text" >Mastodon</span></a></div>';
				}
			}
			if ( !empty( $meta[ 'bluesky' ] ) ) {
				$icon_wrap .= '<div class="service-icon bluesky"><a href="' . $meta[ 'bluesky' ] . '" rel="noopener" title="' . ( $author->post->post_title ?? $coa->display_name ) . ' on Bluesky" target="_blank">' . hpm_svg_output( 'bluesky' ) . '<span class="screen-reader-text" >Bluesky</span></a></div>';
			}
			if ( !empty( $meta[ 'email' ] ) ) {
				$icon_wrap .= '<div class="service-icon envelope"><a href="mailto:' . $meta[ 'email' ] . '" rel="noopener" title="Email ' . ( $author->post->post_title ?? $coa->display_name ) . '" target="_blank">' . hpm_svg_output( 'envelope' ) . '<span class="screen-reader-text" >Email</span></a></div>';
			}
			$author_bio = $author->post->post_content;
			if ( str_contains( 'Biography pending', $author_bio ) ) {
				$author_bio = '';
			}
		} else {
			if ( !empty( $coa->user_email ) ) {
				$icon_wrap .= '<div class="service-icon envelope"><a href="mailto:' . $coa->user_email . '" target="_blank">' . hpm_svg_output( 'envelope' ) . '<span class="screen-reader-text" >Email</span></a></div>';
			}
			if ( !empty( $coa->website ) ) {
				$icon_wrap .= '<div class="service-icon"><a href="' . $coa->website . '" target="_blank">' . hpm_svg_output( 'home' ) . '<span class="screen-reader-text" >Conact</span></a></div>';
			}
		}
		if ( !empty( $icon_wrap ) ) {
			$temp .= '<div class="icon-wrap">' . $icon_wrap . '</div>';
		}
		$temp .= '</div></div>';
		if ( !empty( $author_bio ) ) {
			$temp .= '<div class="sa-body">' . ( $local ? wp_trim_words( $author_bio, 50, '...' ) : '' ) . '</div>';
		}
		$temp .= ( $local ? '<p style="padding: 15px;"><a href="' . get_the_permalink( $author->post->ID ) . '"><strong>Know more about ' . $coa->display_name . '</strong></a></p>' : '' ) . '</section>';
		if ( $local || $guest ) {
			$q = new WP_Query( [ 'posts_per_page' => 4, 'post_type' => 'post', 'post_status' => 'publish', 'author_name' => $coa->user_nicename, 'no_found_posts' => true ] );
			if ( $q->have_posts() ) {
				$temp .= '<section class="highlights">';
				$temp .= '<div class="news-list-right most-view"><h2 class="title title-full"><strong>Other Stories by <span>' . strtok( $coa->first_name, "-" ) . '</span></strong></h2><ul class="list-none news-links list-dashed">';
				foreach ( $q->posts as $qp ) {
					$imgblock = "";
					if ( has_post_thumbnail( $qp->ID ) ) {
						$imgblock = get_the_post_thumbnail( $qp->ID, "thumbnail" );
					}
					$temp .= '<li><a href="' . esc_url( get_permalink( $qp->ID ) ) . '" rel="bookmark"><span>' . $qp->post_title . '</span>' . $imgblock . '</a></li>';
				}
				$temp .= '</ul><p><a href="/articles/author/' . $coa->user_nicename . '">More Articles by This Author</a></p></div></section>';
			}
		}
		$temp .= "</div>";
		set_transient( 'hpm_author_' . $coa->user_nicename, $temp, 600 );
		$output .= $temp;
	}
	$output .= "</div>";
	return $output;
}

function hpm_houston_matters_check(): array {
	$hm_airtimes = get_transient( 'hpm_hm_airing' );
	if ( !empty( $hm_airtimes ) ) {
		return $hm_airtimes;
	}
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$date = date( 'Y-m-d', $t );
	$hm_airtimes = [
		9 => false,
		12 => false
	];
	$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/day?date=" . $date . "&format=json" ) );
	if ( is_wp_error( $remote ) ) {
		return $hm_airtimes;
	} else {
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
		foreach ( $json['onToday'] as $j ) {
			if ( $j['program']['name'] == 'Houston Matters with Craig Cohen' ) {
				if ( $j['start_time'] == '09:00' ) {
					$hm_airtimes[9] = true;
				}
			} elseif ( $j['program']['name'] == 'Houston Election 2024' ) {
				$hm_airtimes[12] = true;
			}
		}
	}
	set_transient( 'hpm_hm_airing', $hm_airtimes, 600 );
	return $hm_airtimes;
}

function hpm_chartbeat(): void {
	global $wp_query;
	$id = $wp_query->get_queried_object_id();
	$anc = get_post_ancestors( $id );
	if ( !in_array( 61383, $anc ) && WP_ENV !== 'development' ) {
		$auth = get_coauthors( $id );
		$auth_temp = [];
		if ( empty( $auth ) || is_front_page() ) {
			$authors = 'Houston Public Media';
		} else {
			foreach ( $auth as $a ) {
				$auth_temp[] = $a->display_name;
			}
			$authors = implode( ', ', $auth_temp );
		} ?>
		<script type='text/javascript'>
			let _sf_async_config={};
			/** CONFIGURATION START **/
			_sf_async_config.uid = 33583;
			_sf_async_config.domain = 'houstonpublicmedia.org';
			_sf_async_config.useCanonical = true;
			_sf_async_config.sections = "<?php echo ( is_front_page() ? 'News, Arts & Culture, Education' : str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', $id ) ) ) );
			?>";
			_sf_async_config.authors = "<?php echo $authors; ?>";
			(function(){
				function loadChartbeat() {
					window._sf_endpt=(new Date()).getTime();
					let e = document.createElement('script');
					e.setAttribute('language', 'javascript');
					e.setAttribute('type', 'text/javascript');
					e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
					document.body.appendChild(e);
				}
				let oldonload = window.onload;
				window.onload = (typeof window.onload != 'function') ?
					loadChartbeat : function() { oldonload(); loadChartbeat(); };
			})();
		</script>
<?php
	}
}
add_action( 'wp_footer', 'hpm_chartbeat', 100 );

function hpm_blank_footer(): void {
	add_action( 'wp_footer', function(){
		wp_dequeue_style( 'hpm-css' );
		wp_deregister_style( 'hpm-css' );
		wp_dequeue_script( 'hpm-js' );
		wp_deregister_script( 'hpm-js' );
	}, 1 );
	remove_action( 'wp_footer', 'hpm_inline_script', 100 );
	wp_footer();
	echo "</html>";
}

add_action( 'wp_footer', function(){
	if ( !empty( $_GET['utm_source'] ) && strtolower( $_GET['utm_source'] ) === 'high5media' && !empty( $_GET['utm_content'] ) ) {
		$content = '';
		if ( strtolower($_GET['utm_content'] ) === 'inspiring' ) {
			$content .= '<div id="campaign-splash" data-campaign="high5-inspiring" class="lightbox"><div id="splash" style="width: 85% !important; max-width: 85% !important;"><picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_Inspiring_Mobile.png.webp" media="(max-width: 700px)" /><source srcset="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_Inspiring_Desktop.jpg.webp" /><img src="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_Inspiring_Mobile.png.webp" alt="Houston Public Media is informing and inspiring the people of Greater Houston. Wherever you are, whenever you want it." /></picture><div id="campaign-close">X</div></div></div>';
		} elseif ( strtolower($_GET['utm_content'] ) === 'trusted' ) {
			$content .= '<div id="campaign-splash" data-campaign="high5-trusted-news" class="lightbox""><div id="splash" style="width: 85% !important; max-width: 85% !important;"><picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_TrustedNews_Mobile.png.webp" media="(max-width: 700px)" /><source srcset="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_TrustedNews_Desktop.png.webp" /><img src="https://cdn.houstonpublicmedia.org/assets/images/HPM_SplashPage_TrustedNews_Mobile.png.webp" alt="Houston Public Media is trusted news and information that matters to you. Wherever you are, whenever you want it." /></picture><div id="campaign-close">X</div></div></div>';
		}
		echo <<<EOT
<script>
	(function(){
		let lightBox = '$content';
		document.getElementById('primary').insertAdjacentHTML('afterbegin', lightBox);
		let campaign = document.querySelectorAll('#campaign-splash, #campaign-close');
		let campaignData = document.querySelector('#campaign-splash').getAttribute('data-campaign');
		setTimeout(() => { gtag('event', 'lightbox', {'event_label': campaignData,'event_category': 'view'}); }, 1000);
		setTimeout(() => { document.getElementById('campaign-splash').style.display = 'none'; }, 18000);
		for (let i = 0; i < campaign.length; ++i) {
			campaign[i].addEventListener('click', (event) => {
				event.stopPropagation();
				document.getElementById('campaign-splash').style.display = 'none';
				gtag('event', 'lightbox', {'event_label': campaignData,'event_category': 'dismiss'});
			});
		}
		let lBox = document.querySelectorAll('#campaign-splash a');
		if (lBox !== null) {
			Array.from(lBox).forEach((item) => {
				item.addEventListener('click', (event) => {
					event.stopPropagation();
					let campaign = document.querySelector('#campaign-splash').getAttribute('data-campaign');
					if ( typeof campaign !== typeof undefined && campaign !== false) {
						gtag('event', 'lightbox', {'event_label': campaign,'event_category': 'click'});
					}
				});
			});
		}
	}());
</script>
<style>
	.lightbox {
		opacity: 0;
		animation-name: fades;
		animation-duration: 17s;
		animation-delay: 0s;
		animation-fill-mode: both;
	}
	@keyframes fades {
		0% { opacity: 0; }
		3% { opacity: 0; }
		7% { opacity: 1; }
		94% { opacity: 1; }
		100% { opacity: 0; }
	}
</style>
EOT;
	}
}, 100 );

function hpm_dark_mode_toggle(): void {
	if ( !is_admin() ) { ?>
<style>
	#theme-switch {
		position: fixed;
		bottom: 0;
		right: 0;
		background: rgba(0,0,0,0.5);
		padding: 1rem;
	}
	#theme-switch::before {
		content: '🌙';
		margin-right: 1.5rem;
	}
	#theme-switch input {
		transform: scale(3);
	}
</style>
<label id="theme-switch" class="theme-switch" for="checkbox_theme">
	<input type="checkbox" id="checkbox_theme">
</label>
<script>
	//determines if the user has a set theme
	let detectColorScheme = () => {
		let theme = "light";	//default to light

		//local storage is used to override OS theme settings
		if ( localStorage.getItem("theme") ) {
			if ( localStorage.getItem("theme") === "dark" ) {
				theme = "dark";
			}
		} else if ( !window.matchMedia ) {
			//matchMedia method not supported
			return false;
		} else if ( window.matchMedia("(prefers-color-scheme: dark)").matches ) {
			//OS theme setting detected as dark
			theme = "dark";
		}

		//dark theme preferred, set document with a `data-theme` attribute
		if ( theme === "dark" ) {
			document.documentElement.setAttribute("data-theme", "dark");
		}
	}
	detectColorScheme();

	//identify the toggle switch HTML element
	const toggleSwitch = document.querySelector('#theme-switch input[type="checkbox"]');

	//function that changes the theme, and sets a localStorage variable to track the theme between page loads
	let switchTheme = (e) => {
		if ( e.target.checked ) {
			localStorage.setItem('theme', 'dark');
			document.documentElement.setAttribute('data-theme', 'dark');
			toggleSwitch.checked = true;
		} else {
			localStorage.setItem('theme', 'light');
			document.documentElement.setAttribute('data-theme', 'light');
			toggleSwitch.checked = false;
		}
	}

	//listener for changing themes
	toggleSwitch.addEventListener( 'change', switchTheme, false );

	//pre-check the dark-theme checkbox if dark-theme is set
	if ( document.documentElement.getAttribute("data-theme") === "dark" ) {
		toggleSwitch.checked = true;
	}
</script>
<?php }
}
// add_action( 'wp_footer', 'hpm_dark_mode_toggle', 50 );
function hpm_persistent_player_head(): void {
	global $wp_query;
	$queried_object = $wp_query->get_queried_object_id();
	if ( !is_admin() && $queried_object !== 61263 ) {
		echo '<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>' .
			'<script src="https://assets.houstonpublicmedia.org/app/themes/hpmv4/js/experiments/jppIframe.js?v=20240903"></script>';
	}
}

function hpm_persistent_player_foot(): void {
	global $wp_query;
	$queried_object = $wp_query->get_queried_object_id();
	if ( !is_admin() && $queried_object !== 61263 ) {
		$prefStream = "news";
		if ( !empty( $_COOKIE ) && !empty( $_COOKIE['prefStream'] ) && preg_match( '/[clasimxtpenw]{4,9}/', $_COOKIE['prefStream'] ) ) {
			$prefStream = $_COOKIE['prefStream'];
		}
		?>
		<div id="jpp-player-persist" class="hidden visually-hidden">
			<div id="jpp-main">
				<div id="jpp-player-wrap" class="jpp-button-wrap">
					<button id="jpp-player-play"><?php echo hpm_svg_output( 'play' ); ?></button>
					<button id="jpp-player-stop" class="hidden"><?php echo hpm_svg_output( 'pause' ); ?></button>
					<audio id="jpp-player" preload="none" hidden></audio>
				</div>
				<div id="jpp-player-controls" class="jpp-button-wrap">
					<button id="jpp-player-volume"><?php echo hpm_svg_output( 'volume-up' ); ?></button>
					<button id="jpp-player-mute" class="hidden"><?php echo hpm_svg_output( 'mute' ); ?></button>
				</div>
				<div id="jpp-now-playing">Now Playing: Nothing yet...</div>
				<div class="jpp-button-wrap">
					<button id="jpp-menu-up"><?php echo hpm_svg_output( 'chevron-up' ); ?></button>
					<button id="jpp-menu-down" class="hidden"><?php echo hpm_svg_output( 'chevron-down' ); ?></button>
				</div>
			</div>
			<div id="jpp-menu-wrap">
				<aside id="jpp-menu">
					<h1>Streams</h1>
				</aside>
				<div id="jpp-submenus">
					<aside id="jpp-streams" class="jpp-section-active"></aside>
				</div>
			</div>
		</div><?php
	}
}

add_action( 'wp_footer', 'hpm_persistent_player_foot', 200 );
add_action( 'wp_head', 'hpm_persistent_player_head', 102 );