<?php
function author_footer( $id ) {
	$output = '';
	$coauthors = get_coauthors( $id );
	foreach ( $coauthors as $k => $coa ) :
		$temp = '';
		$author_trans = get_transient( 'hpm_author_'.$coa->user_nicename );
		if ( !empty( $author_trans ) ) {
			$output .= $author_trans;
			continue;
		}
		$local = false;
		$author = null;
		if ( is_a( $coa, 'wp_user' ) ) {
			$author = new WP_Query( [
				'post_type' => 'staff',
				'post_status' => 'publish',
				'meta_query' => [ [
					'key' => 'hpm_staff_authid',
					'compare' => '=',
					'value' => $coa->ID
				] ]
			] );
		} elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) {
			if ( !empty( $coa->linked_account ) ) {
				$authid = get_user_by( 'login', $coa->linked_account );
				$author = new WP_Query([
					'post_type' => 'staff',
					'post_status' => 'publish',
					'meta_query' => [[
						'key' => 'hpm_staff_authid',
						'compare' => '=',
						'value' => $authid->ID
					]]
				]);
			}
		}
		if ( !empty( $author ) && $author->have_posts() ) {
			$local = true;
			$meta = $author->post->hpm_staff_meta;
		}
		$temp .= "
	<div class=\"author-inner-wrap\">
		<div class=\"author-info-wrap\">
			<div class=\"author-image\">" .
		         ( $local ? get_the_post_thumbnail( $author->post->ID, 'post-thumbnail', [ 'alt' => $author->post->post_title ] ) : '' ) .
		         "</div>
			<div class=\"author-info\">
				<h2>" . ( $local ? $author->post->post_title : $coa->display_name ) . "</h2>" .
				// ( $local && !empty( $meta['pronouns'] ) ? '<p class="staff-pronouns">(' . $meta['pronouns'] . ')</p>' : '' ) .
				"<h3>" . ( $local ? $meta['title'] : '' ) . "</h3>
				<div class=\"icon-wrap\">";
		if ( $local ) {
			if ( !empty( $meta['phone'] ) ) {
				$temp .= '<div class="service-icon phone"><a href="tel://+1' . str_replace( [ '(', ')', ' ', '-', '.' ], [ '', '', '', '', '' ], $meta['phone'] ) . '" rel="noopener" title="Call ' .
				( $local ? $author->post->post_title : $coa->display_name ) .
				' at ' . $meta['phone'] . '" data-phone="' . $meta['phone'] . '">' . hpm_svg_output( 'phone' ) . '</a></div>';
			}
			if ( !empty( $meta['facebook'] ) ) {
				$temp .= '<div class="service-icon facebook"><a href="' . $meta['facebook'] . '" rel="noopener" title="' .
					( $local ? $author->post->post_title : $coa->display_name ) .
					' on Facebook" target="_blank">' . hpm_svg_output( 'facebook' ) . '</a></div>';
			}
			if ( !empty( $meta['twitter'] ) ) {
				$temp .= '<div class="service-icon twitter"><a href="' . $meta['twitter'] . '" rel="noopener" title="' .
				( $local ? $author->post->post_title : $coa->display_name ) .
				' on Twitter" target="_blank">' . hpm_svg_output( 'twitter' ) . '</a></div>';
			}
			if ( !empty( $meta['linkedin'] ) ) {
				$temp .= '<div class="service-icon linkedin"><a href="' . $meta['linkedin'] . '" rel="noopener" title="' .
				( $local ? $author->post->post_title : $coa->display_name ) .
				' on LinkedIn" target="_blank">' . hpm_svg_output( 'linkedin' ) . '</a></div>';
			}
			if ( !empty( $meta['email'] ) ) {
				$temp .= '<div class="service-icon envelope"><a href="mailto:' . $meta['email'] . '" rel="noopener" title="Email ' .
				( $local ? $author->post->post_title : $coa->display_name ) .
				'" target="_blank">' . hpm_svg_output( 'envelope' ) . '</a></div>';
			}
			$author_bio = $author->post->post_content;
			if ( preg_match( '/Biography pending/', $author_bio ) ) {
				$author_bio = '';
			}
		} else {
			if ( !empty( $coa->user_email ) ) {
				$temp .= '<div class="service-icon envelope"><a href="mailto:' . $coa->user_email . '" target="_blank">' . hpm_svg_output( 'envelope' ) . '</a></div>';
			}
			if ( !empty( $coa->website ) ) {
				$temp .= '<div class="service-icon"><a href="' . $coa->website . '" target="_blank">' . hpm_svg_output( 'home' ) . '</a></div>';
			}
		}
		$temp .= "
				</div>
				<p>" . ( $local ? wp_trim_words( $author_bio, 50, '...' ) : '' ) . "</p>
				<p>" . ( $local ? '<a href="' . get_the_permalink( $author->post->ID ) . '">More Information</a>' : '' ) ."</p>
			</div>
		</div>
		<div class=\"author-other-stories\">";
		$q = new WP_query([
			'posts_per_page' => 4,
			'post_type' => 'post',
			'post_status' => 'publish',
			'author_name' => $coa->user_nicename
		]);
		if ( $q->have_posts() ) {
			$temp .= "
			<h4>Recent Stories</h4>
			<ul>";
			foreach ( $q->posts as $qp ) {
				$temp .= '<li><h2 class="entry-title"><a href="' . esc_url( get_permalink( $qp->ID ) ) . '" rel="bookmark">' . $qp->post_title . '</a></h2></li>';
			}
			$temp .= "
			</ul>
			<p><a href=\"/articles/author/" . $coa->user_nicename . "\">More Articles by This Author</a></p>";
		}
		$temp .= "
		</div>
	</div>";
		set_transient( 'hpm_author_' . $coa->user_nicename, $temp, 7200 );
		$output .= $temp;
	endforeach;
	return $output;
}

function hpm_houston_matters_check() {
	$hm_air = get_transient( 'hpm_hm_airing' );
	if ( !empty( $hm_air ) ) {
		return $hm_air;
	}
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$date = date( 'Y-m-d', $t );
	$hm_airtimes = [
		9 => false,
		15 => false
	];
	$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/day?date=" . $date . "&format=json" ) );
	if ( is_wp_error( $remote ) ) {
		return false;
	} else {
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
		foreach ( $json['onToday'] as $j ) {
			if ( $j['program']['name'] == 'Houston Matters with Craig Cohen' ) {
				if ( $j['start_time'] == '09:00' ) {
					$hm_airtimes[9] = true;
				}
			} elseif ( $j['program']['name'] == 'Town Square with Ernie Manouse' ) {
				if ( $j['start_time'] == '15:00' ) {
					$hm_airtimes[15] = true;
				}
			}
		}
	}
	set_transient( 'hpm_hm_airing', $hm_airtimes, 3600 );
	return $hm_airtimes;
}

function hpm_chartbeat() {
	global $wp_query;
	$id = $wp_query->get_queried_object_id();
	$anc = get_post_ancestors( $id );
	if ( !in_array( 61383, $anc ) && WP_ENV !== 'development' ) {
		$auth = get_coauthors( $id );
		$auth_temp = [];
		$authors = '';
		if ( empty( $auth ) || is_front_page() ) {
			$authors = 'Houston Public Media';
		} else {
			foreach ( $auth as $a ) {
				$auth_temp[] = $a->display_name;
			}
			$authors = implode( ', ', $auth_temp );
		} ?>
		<script type='text/javascript'>
			var _sf_async_config={};
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
	}
}
add_action( 'wp_footer', 'hpm_chartbeat', 100 );

function hpm_dark_mode_toggle() {
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
	var detectColorScheme = () => {
		var theme = "light";    //default to light

		//local storage is used to override OS theme settings
		if ( localStorage.getItem("theme") ) {
			if ( localStorage.getItem("theme") == "dark" ) {
				var theme = "dark";
			}
		} else if ( !window.matchMedia ) {
			//matchMedia method not supported
			return false;
		} else if ( window.matchMedia("(prefers-color-scheme: dark)").matches ) {
			//OS theme setting detected as dark
			var theme = "dark";
		}

		//dark theme preferred, set document with a `data-theme` attribute
		if ( theme == "dark" ) {
			document.documentElement.setAttribute("data-theme", "dark");
		}
	}
	detectColorScheme();

	//identify the toggle switch HTML element
	const toggleSwitch = document.querySelector('#theme-switch input[type="checkbox"]');

	//function that changes the theme, and sets a localStorage variable to track the theme between page loads
	var switchTheme = (e) => {
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
	if ( document.documentElement.getAttribute("data-theme") == "dark" ) {
		toggleSwitch.checked = true;
	}
</script>
<?php }
}
// add_action( 'wp_footer', 'hpm_dark_mode_toggle', 50 );

/* **
 * Persistent Player Setup
 */
/*
add_action( 'wp_head', function() {
	global $wp_query;
	$queried_object = $wp_query->get_queried_object_id();
	if ( $_SERVER['HTTP_X_FORWARDED_HOST'] !== 'jcounts.ngrok.io' && !is_admin() && WP_ENV !== 'production' && $queried_object !== 61263 ) {
		echo '<script type="module"> import hotwiredTurbo from \'https://cdn.skypack.dev/@hotwired/turbo\'; </script>';
		wp_enqueue_script( 'hpm-jpp', get_template_directory_uri().'/js/experiments/jppTurbo.js', [ 'hpm-plyr' ], date('Y-m-d-H'), true );
		//wp_enqueue_script( 'hpm-jpp', get_template_directory_uri().'/js/experiments/jppIframe.js', [ 'hpm-plyr' ], date('Y-m-d-H') );
		wp_enqueue_style( 'hpm-persistent', get_template_directory_uri().'/js/experiments/persistent.css', [], date('Y-m-d-H') );
	}
}, 102 );
function hpm_persistent_player() {
	global $wp_query;
	$queried_object = $wp_query->get_queried_object_id();
	if ( $_SERVER['HTTP_X_FORWARDED_HOST'] !== 'jcounts.ngrok.io' && !is_admin() && WP_ENV !== 'production' && $queried_object !== 61263 ) {
		$prefStream = "news";
		if ( !empty( $_COOKIE ) && !empty( $_COOKIE['prefStream'] ) && preg_match( '/[clasimxtpenw]{4,9}/', $_COOKIE['prefStream'] ) ) {
			$prefStream = $_COOKIE['prefStream'];
		}
	?>
		<div id="jpp-player-persist" data-turbo-permanent>
			<div id="jpp-main">
				<div id="jpp-player-wrap"><audio id="jpp-player" playsinline preload="none">
					<source src="https://stream.houstonpublicmedia.org/<?php echo $prefStream; ?>-aac" type="audio/aac" />
					<source src="https://stream.houstonpublicmedia.org/<?php echo $prefStream; ?>-mp3" type="audio/mpeg" />
				</audio></div>
				<div id="jpp-now-playing">Now Playing: Nothing yet...</div>
				<div id="jpp-button-wrap">
					<button id="jpp-menu-up"><?php echo hpm_svg_output( 'chevron-up' ); ?></button>
					<button id="jpp-menu-down" class="hidden"><?php echo hpm_svg_output( 'chevron-down' ); ?></button>
				</div>
			</div>
			<div id="jpp-menu-wrap">
				<aside id="jpp-menu">
					<button data-section="streams" id="jpp-button-streams" class="jpp-menu-section jpp-button-active">Streams</button>
					<button data-section="podcasts" id="jpp-button-podcasts" class="jpp-menu-section">Podcasts</button>
				</aside>
				<div id="jpp-submenus">
					<aside id="jpp-streams" class="jpp-section-active"></aside>
					<aside id="jpp-podcasts"></aside>
				</div>
			</div>
			<div id="sprite-plyr" hidden><!--?xml version="1.0" encoding="UTF-8"?--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><symbol id="plyr-airplay" viewBox="0 0 18 18"><path d="M16 1H2a1 1 0 00-1 1v10a1 1 0 001 1h3v-2H3V3h12v8h-2v2h3a1 1 0 001-1V2a1 1 0 00-1-1z"></path><path d="M4 17h10l-5-6z"></path></symbol><symbol id="plyr-captions-off" viewBox="0 0 18 18"><path d="M1 1c-.6 0-1 .4-1 1v11c0 .6.4 1 1 1h4.6l2.7 2.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3l2.7-2.7H17c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1H1zm4.52 10.15c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41C8.47 4.96 7.46 3.76 5.5 3.76c-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69zm7.57 0c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41c-.28-1.15-1.29-2.35-3.25-2.35-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69z" fill-rule="evenodd" fill-opacity=".5"></path></symbol><symbol id="plyr-captions-on" viewBox="0 0 18 18"><path d="M1 1c-.6 0-1 .4-1 1v11c0 .6.4 1 1 1h4.6l2.7 2.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3l2.7-2.7H17c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1H1zm4.52 10.15c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41C8.47 4.96 7.46 3.76 5.5 3.76c-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69zm7.57 0c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41c-.28-1.15-1.29-2.35-3.25-2.35-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69z" fill-rule="evenodd"></path></symbol><symbol id="plyr-download" viewBox="0 0 18 18"><path d="M9 13c.3 0 .5-.1.7-.3L15.4 7 14 5.6l-4 4V1H8v8.6l-4-4L2.6 7l5.7 5.7c.2.2.4.3.7.3zm-7 2h14v2H2z"></path></symbol><symbol id="plyr-enter-fullscreen" viewBox="0 0 18 18"><path d="M10 3h3.6l-4 4L11 8.4l4-4V8h2V1h-7zM7 9.6l-4 4V10H1v7h7v-2H4.4l4-4z"></path></symbol><symbol id="plyr-exit-fullscreen" viewBox="0 0 18 18"><path d="M1 12h3.6l-4 4L2 17.4l4-4V17h2v-7H1zM16 .6l-4 4V1h-2v7h7V6h-3.6l4-4z"></path></symbol><symbol id="plyr-fast-forward" viewBox="0 0 18 18"><path d="M7.875 7.171L0 1v16l7.875-6.171V17L18 9 7.875 1z"></path></symbol><symbol id="plyr-logo-vimeo" viewBox="0 0 18 18"><path d="M17 5.3c-.1 1.6-1.2 3.7-3.3 6.4-2.2 2.8-4 4.2-5.5 4.2-.9 0-1.7-.9-2.4-2.6C5 10.9 4.4 6 3 6c-.1 0-.5.3-1.2.8l-.8-1c.8-.7 3.5-3.4 4.7-3.5 1.2-.1 2 .7 2.3 2.5.3 2 .8 6.1 1.8 6.1.9 0 2.5-3.4 2.6-4 .1-.9-.3-1.9-2.3-1.1.8-2.6 2.3-3.8 4.5-3.8 1.7.1 2.5 1.2 2.4 3.3z"></path></symbol><symbol id="plyr-logo-youtube" viewBox="0 0 18 18"><path d="M16.8 5.8c-.2-1.3-.8-2.2-2.2-2.4C12.4 3 9 3 9 3s-3.4 0-5.6.4C2 3.6 1.3 4.5 1.2 5.8 1 7.1 1 9 1 9s0 1.9.2 3.2c.2 1.3.8 2.2 2.2 2.4C5.6 15 9 15 9 15s3.4 0 5.6-.4c1.4-.3 2-1.1 2.2-2.4.2-1.3.2-3.2.2-3.2s0-1.9-.2-3.2zM7 12V6l5 3-5 3z"></path></symbol><symbol id="plyr-muted" viewBox="0 0 18 18"><path d="M12.4 12.5l2.1-2.1 2.1 2.1 1.4-1.4L15.9 9 18 6.9l-1.4-1.4-2.1 2.1-2.1-2.1L11 6.9 13.1 9 11 11.1zM3.786 6.008H.714C.286 6.008 0 6.31 0 6.76v4.512c0 .452.286.752.714.752h3.072l4.071 3.858c.5.3 1.143 0 1.143-.602V2.752c0-.601-.643-.977-1.143-.601L3.786 6.008z"></path></symbol><symbol id="plyr-pause" viewBox="0 0 18 18"><path d="M6 1H3c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h3c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1zm6 0c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h3c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1h-3z"></path></symbol><symbol id="plyr-pip" viewBox="0 0 18 18"><path d="M13.293 3.293L7.022 9.564l1.414 1.414 6.271-6.271L17 7V1h-6z"></path><path d="M13 15H3V5h5V3H2a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1v-6h-2v5z"></path></symbol><symbol id="plyr-play" viewBox="0 0 18 18"><path d="M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z"></path></symbol><symbol id="plyr-restart" viewBox="0 0 18 18"><path d="M9.7 1.2l.7 6.4 2.1-2.1c1.9 1.9 1.9 5.1 0 7-.9 1-2.2 1.5-3.5 1.5-1.3 0-2.6-.5-3.5-1.5-1.9-1.9-1.9-5.1 0-7 .6-.6 1.4-1.1 2.3-1.3l-.6-1.9C6 2.6 4.9 3.2 4 4.1 1.3 6.8 1.3 11.2 4 14c1.3 1.3 3.1 2 4.9 2 1.9 0 3.6-.7 4.9-2 2.7-2.7 2.7-7.1 0-9.9L16 1.9l-6.3-.7z"></path></symbol><symbol id="plyr-rewind" viewBox="0 0 18 18"><path d="M10.125 1L0 9l10.125 8v-6.171L18 17V1l-7.875 6.171z"></path></symbol><symbol id="plyr-settings" viewBox="0 0 18 18"><path d="M16.135 7.784a2 2 0 01-1.23-2.969c.322-.536.225-.998-.094-1.316l-.31-.31c-.318-.318-.78-.415-1.316-.094a2 2 0 01-2.969-1.23C10.065 1.258 9.669 1 9.219 1h-.438c-.45 0-.845.258-.997.865a2 2 0 01-2.969 1.23c-.536-.322-.999-.225-1.317.093l-.31.31c-.318.318-.415.781-.093 1.317a2 2 0 01-1.23 2.969C1.26 7.935 1 8.33 1 8.781v.438c0 .45.258.845.865.997a2 2 0 011.23 2.969c-.322.536-.225.998.094 1.316l.31.31c.319.319.782.415 1.316.094a2 2 0 012.969 1.23c.151.607.547.865.997.865h.438c.45 0 .845-.258.997-.865a2 2 0 012.969-1.23c.535.321.997.225 1.316-.094l.31-.31c.318-.318.415-.781.094-1.316a2 2 0 011.23-2.969c.607-.151.865-.547.865-.997v-.438c0-.451-.26-.846-.865-.997zM9 12a3 3 0 110-6 3 3 0 010 6z"></path></symbol><symbol id="plyr-volume" viewBox="0 0 18 18"><path d="M15.6 3.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4C15.4 5.9 16 7.4 16 9c0 1.6-.6 3.1-1.8 4.3-.4.4-.4 1 0 1.4.2.2.5.3.7.3.3 0 .5-.1.7-.3C17.1 13.2 18 11.2 18 9s-.9-4.2-2.4-5.7z"></path><path d="M11.282 5.282a.909.909 0 000 1.316c.735.735.995 1.458.995 2.402 0 .936-.425 1.917-.995 2.487a.909.909 0 000 1.316c.145.145.636.262 1.018.156a.725.725 0 00.298-.156C13.773 11.733 14.13 10.16 14.13 9c0-.17-.002-.34-.011-.51-.053-.992-.319-2.005-1.522-3.208a.909.909 0 00-1.316 0zm-7.496.726H.714C.286 6.008 0 6.31 0 6.76v4.512c0 .452.286.752.714.752h3.072l4.071 3.858c.5.3 1.143 0 1.143-.602V2.752c0-.601-.643-.977-1.143-.601L3.786 6.008z"></path></symbol></svg></div>
		</div><?php
	}
}

add_action( 'wp_footer', 'hpm_persistent_player', 200 ); */