<?php
/*
Template Name: Radio Schedules
*/
	$t = time();
	$offset = (int)get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;

	if ( isset( $wp_query->query_vars['sched_station'] ) ) {
		$sched_station = urldecode( $wp_query->query_vars['sched_station'] );
	}

	if ( isset( $wp_query->query_vars['sched_year'] ) ) {
		$sched_year = urldecode( $wp_query->query_vars['sched_year'] );
	} else {
		$sched_year = date( 'Y', $t );
	}

	if ( isset( $wp_query->query_vars['sched_month'] ) ) {
		$sched_month = urldecode( $wp_query->query_vars['sched_month'] );
	} else {
		$sched_month = date( 'm', $t );
	}

	if ( isset( $wp_query->query_vars['sched_day'] ) ) {
		$sched_day = urldecode( $wp_query->query_vars['sched_day'] );
	} else {
		$sched_day = date( 'd', $t );
	}
	$today_date = date( 'Y-m-d', $t );
	$date = $sched_year . "-" . $sched_month . "-" . $sched_day;
	get_header();
?>
	<style>
		ul.proglist {
			list-style: none;
		}
		ul.proglist li {
			overflow: hidden;
			list-style: none;
		}
		.date-select {
			padding: 1em 0;
			overflow: hidden;
			width: 100%;
		}
		.date-select .date-pick-right {
			text-transform: uppercase;
			float: right;
			font-weight: bold;
		}
		.date-select .date-pick-left {
			text-transform: uppercase;
			float: left;
			font-weight: bold;
		}
		#station-schedule-display {
			width: 96%;
			margin: 0 2%;
		}
		#station-schedule-display ul {
			list-style: disc outside none;
			margin: 0;
			padding: 0;
		}
		#station-schedule-display iframe {
			height: 1000px;
			overflow: scroll;
			width: 100%;
		}
		#station-schedule-display > ul > li {
			padding: 1em;
			background-color: var(--main-element-background);
			border: 1px solid rgba(0,0,0,0.25);
			margin: 0 0 1em 0;
		}
		#station-schedule-display ul li p {
			font-size: 90%;
		}
		#station-schedule-display h3 {
			margin-bottom: 1rem;
		}
		.proglist .progsegment {
			padding: 0;
		}
		.proglist li > * + * {
			margin-top: 1rem;
		}
		.proglist details.progsegment summary::marker {
			color: white;
			padding-right: 0.25rem;
		}
		.proglist details.progsegment summary {
			background-color: rgb(0,98,136);
			color: white;
			padding: 0.75em;
			margin: 0;
			position: relative;
			font-style: normal;
		}
		#station-schedule-display .progsegment li {
			overflow: visible;
			padding: 0.5em 0;
			list-style: disc;
			margin: 0 0 0 2em;
		}
		#station-schedule-display .proglist .progsegment ul.progplay li {
			list-style: none;
			margin: 0;
			padding: 1em;
			background-color: var(--main-element-background);
		}
		#station-schedule-display .proglist .progsegment ul.progplay li:nth-child(even) {
			background-color: var(--main-background);
		}
		#station-schedule-display .proglist .progsegment ul.progplay li em {
			color: var(--secondary-text);
		}
		#schedule-search {
			clear: both;
		}
		#schedule-search #day-select {
			width: 100%;
			margin: 1em 0;
		}
		#schedule-search label {
			color: var(--main-text);
		}
		#schedule-search input {
			border: 0;
			outline: 0;
			-webkit-appearance: none;
			border-bottom: 0.125em solid var(--main-text);
			background-color: transparent;
			padding: 0 0.25em;
			width: 11em;
			text-transform: lowercase;
		}
		body.page.page-template-page-schedules-radio #main {
			margin-bottom: 1em;
			background-color: transparent;
		}
		body.page.page-template-page-schedules-radio .page-header .page-title {
			text-transform: uppercase;
		}
		body.page.page-template-page-schedules-radio .page-header {
			overflow: hidden;
		}
		body.page.page-template-page-schedules-radio .entry-content {
			padding: 1em;
		}
		.page-header #station-social {
			width: 100%;
			overflow: hidden;
		}
		.page-header #station-social .station-social-icon {
			float: left;
		}
		body.single-shows #station-social .station-social-icon {
			float: right;
		}
		.page-header #station-social .station-printable {
			float: left;
			padding: 0.6em;
		}
		.page-header #station-social .station-printable a {
			background-color: transparent;
			text-transform: uppercase;
		}
		@media screen and (min-width: 34rem) {
			.proglist .progtime {
				float: left;
				width: 15%;
				padding: 1em 1em 1em 0;
				text-align: right;
			}
			.proglist .progname {
				float: left;
				border-left: 1px solid #808080;
				padding: 0.5em 0 0 1em;
				width: 85%;
				margin: 0.5em 0 0 0;
			}
			#station-schedule-display {
				padding: 1em;
				overflow: hidden;
			}
			#station-schedule-display .station-search {
				float: left;
				width: 46%;
				padding: 1em;
				background-color: white;
				margin: 0 2%;
			}
			.page-header #station-social {
				width: 45%;
				float: right;
			}
			body.page.page-template-page-schedules-radio .page-header .page-title {
				display: block;
				float: left;
				margin-bottom: 0;
			}
			body.page.page-template-page-schedules-radio .entry-content {
				padding: 1em;
			}
		}
		@media screen and (min-width: 52.5rem) {
			.page-header #station-social {
				float: right;
				overflow: hidden;
				width: auto;
				padding: 0.5em 0;
			}
		}
		@media screen and (min-width: 64.25rem) {
			#station-schedule-display.column-left {
				width: 66%;
				margin: 0 0 1em;
			}
			body.page.page-template-page-schedules-radio .column-right article {
				width: 100%;
				padding: 0;
				background-color: var(--main-element-background);
			}
			#schedule-search {
				float: left;
				width: 50%;
				margin: 0;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post(); ?>
			<header class="page-header">
				<h1 class="page-title entry-title"><?php the_title(); ?></h1>
				<div id="station-social">
				<?php
					if ( $sched_station == 'news887' ) { ?>
					<div class="station-printable">
						<a href="/news887/weekly/">Printable Schedule</a>
					</div>
				<?php
					} ?>
				</div>
				<div id="schedule-search">
					<div id="day-select">
						<form role="form" method="" action="">
							<label for="datepicker">Select a Day</label>
							<input type="date" id="datepicker" name="datepicker" value="<?php echo date( 'Y/m/d' ); ?>" />
						</form>
					</div>
				</div>
			</header>
<?php

		$station = "be40a578-d4d8-4625-9729-e50b58c816c6";
		if ( $sched_station == 'classical' ) {
			$station = "98c1232d-c559-465a-978a-56d98f72bbb1";
		}

		$date_unix = mktime( 0, 0, 0, $sched_month, $sched_day, $sched_year );
		$tomorrow = date( 'Y/m/d', $date_unix + 86400 );
		$yesterday = date( 'Y/m/d', $date_unix - 86400 ); ?>
		<section id="station-schedule-display" class="column-left">
<div class="date-select">
	<a class="date-pick-left" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $yesterday; ?>/">&lt;&lt; Previous Day</a>
	<a class="date-pick-right" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $tomorrow; ?>/">Next Day &gt;&gt;</a>
</div>
<p>&nbsp;</p>
<?PHP
		$today = date( 'l, F j, Y', $date_unix );
		$remote = wp_remote_get( "https://cadence.nprstations.org/api/cadence/widget/" . $station . "/day?date=" . $date . "&callback=callback" );
		if ( is_wp_error( $remote ) ) { ?>
			<h3>Playlist Error</h3>
			<p>We&#39;re sorry, but there was an error in loading the playlist data.  Please try again shortly, or <a href="/<?php echo $sched_station; ?>/">return to today&#39;s playlist</a>.</p><?php
		} else {
			$api = wp_remote_retrieve_body( $remote );
			$json = json_decode( $api, TRUE );
			if ( empty( $json['episodes'] ) ) { ?>
				<h3>Playlist Error</h3>
				<p>We&#39;re sorry, but there isn&#39;t any playlist data for the selected date.  Please choose another date from the calendar, or <a href="/<?php echo $sched_station; ?>/">return to today&#39;s playlist</a>.</p>
<?PHP
			} else {
				$progs = [];
                $current = '';

				foreach ( $json['episodes'] as $k => $v ) {
					$fullend = strtotime( $v['episode']['end']['utc'] ) + $offset;
					$fullstart = strtotime( $v['episode']['start']['utc'] ) + $offset;
					$duration = $fullend - $fullstart;
					$name = $v['episode']['programName'];
					if (
						$duration > 600 ||
						!empty( $progs[ $v['episode']['episodeId'] ] ) ||
						( !empty( $json['episodes'][ $k + 2 ] ) && $json['episodes'][ $k + 2 ]['episode']['episodeId'] === $v['episode']['episodeId'] )
					) {
                        if ( empty( $progs[ $v['episode']['episodeId'] ] ) ) {
                            $progs[ $v['episode']['episodeId'] ] = [
                                'name' => $name,
                                'time' => date( 'g:i a', $fullstart ),
                                'link' => ( !empty( $v['programLink'] ) ? $v['programLink'] : '' ),
                                'desc' => ( !empty( $v['episode']['notes'] ) ? $v['episode']['notes'] : '' ),
                                'playlist' => ( !empty( $v['episode']['songs'] ) ? $v['episode']['songs'] : [] ),
                                'sub' => []
                            ];
                            $current = $v['episode']['episodeId'];
                        }
					} else {
                        $progs[ $current ]['sub'][] = [
                            'name' => $name,
                            'time' => date( 'g:i a', $fullstart ),
                            'link' =>  ( !empty( $v['programLink'] ) ? $v['programLink'] : '' )
                        ];
					}
				} ?>
				<h3>Playlist for <?PHP echo $today; ?></h3>
				<ul class="proglist">
<?PHP
				foreach ( $progs as $prog ) { ?>
					<li>
						<h2><strong><?PHP echo $prog['time']; ?>:</strong> <?php echo ( !empty( $prog['link'] ) ? '<a href="'.$prog['link'].'">' : '' ) . $prog['name'] . ( !empty( $prog['link'] ) ? '</a>' : '' ); ?></h2>
						<?PHP echo ( !empty( $prog['desc'] ) ? "<p>" . $prog['desc'] . "</p>" : '' ); ?>
<?PHP
					echo hpm_segments( $prog['name'], $date );
					if ( !empty( $prog['sub'] ) ) { ?>
					<details class="progsegment">
						<summary>Interstitials</summary>
						<ul>
<?PHP
						foreach( $prog['sub'] as $ksu => $vsu ) { ?>
							<li>
								<strong><?PHP echo $vsu['time']; ?>:</strong> <?php echo ( !empty( $vsu['link'] ) ? '<a href="'.$vsu['link'].'">' : '' ) . $vsu['name'] . ( !empty( $vsu['link'] ) ? '</a>' : '' ); ?>
							</li>
<?PHP
						} ?>
						</ul>
					</details>
<?php
					}
					if ( !empty( $prog['playlist'] ) ) { ?>
					<details class="progsegment">
						<summary>Program Playlist</summary>
						<ul class="progplay">
<?PHP
						foreach( $prog['playlist'] as $ks => $song ) {
							$song_info = [];
							$song_start = strtotime( $song['start']['utc'] ) + $offset;
							$song_start_string = date( 'g:i a', $song_start );
							if ( !empty( $song['composer'] ) ) {
								$song_info[] = "<em>Composer</em>: " . trim( $song['composer'] );
							}
							if ( !empty( $song['ensemble'] ) ) {
								$ensemble = implode( ', ', $song['ensemble'] );
								if ( !empty( $ensemble ) ) {
									$song_info[] = "<em>Ensembles</em>: " . $ensemble;
								}
							}
							if ( !empty( $song['artist'] ) ) {
								$artist = implode( ', ', $song['artist'] );
								if ( !empty( $artist ) ) {
									$song_info[] = "<em>Performer</em>: " . $artist;
								}
							}
							if ( !empty( $song['conductor'] ) ) {
								$song_info[] = "<em>Conductor</em>: " . implode( ', ', $song['conductor'] );
							}
							if ( !empty( $song['label'] ) ) {
								if ( !empty( $song['catalogNumber'] ) ) {
									$song_info[] = "<em>Catalog Information</em>: " . trim( $song['label'] ) . " " . trim( $song['catalogNumber'] );
								} else {
									$song_info[] = "<em>Label</em>: " . trim( $song['label'] );
								}
							}
							if ( ( $ks + 1 ) & 1 ) { ?>
								<li>
<?PHP
							} else { ?>
								<li class="shade">
<?PHP
							} ?>
									<h2><?PHP echo $song_start_string; ?>: <b><?php echo trim( $song['title'] ); ?></b></h2>
									<?php echo implode( '<br />', $song_info ); ?>
								</li>
<?PHP
						} ?>
							</ul>
					</details>
<?PHP
					} ?>
					</li>
<?PHP
				} ?>
				</ul>
<?PHP
			}
		} ?>
			</section>
			<div id="top-schedule-wrap" class="column-right">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php the_title(); ?> Quick Links</h4>
					<?php
						if ( $sched_station == 'news887' ) {
							$nav_id = 2213;
						} elseif ( $sched_station == 'classical' ) {
							$nav_id = 2214;
						}
						wp_nav_menu( [
							'menu_class' => 'nav-menu',
							'menu' => $nav_id
						] );
					?>
				</nav>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			</div>
		<?php
		}
		?>

		</main>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			let picker = document.getElementById('datepicker');
			picker.addEventListener( 'change', () => {
				let date = picker.value.replaceAll('-','/');
				location.href = '/<?PHP echo $sched_station; ?>/schedule/'+date;
			});
		});
	</script>
<?php get_footer(); ?>