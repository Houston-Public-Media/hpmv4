<?php
/*
Template Name: Radio Schedules
*/
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;

	if ( isset( $wp_query->query_vars['sched_station'] ) ) :
		$sched_station = urldecode($wp_query->query_vars['sched_station']);
	endif;

	if ( isset( $wp_query->query_vars['sched_year'] ) ) :
		$sched_year = urldecode($wp_query->query_vars['sched_year']);
	else :
		$sched_year = date('Y', $t);
	endif;

	if ( isset( $wp_query->query_vars['sched_month'] ) ) :
		$sched_month = urldecode($wp_query->query_vars['sched_month']);
	else :
		$sched_month = date('m', $t);
	endif;

	if ( isset( $wp_query->query_vars['sched_day'] ) ) :
		$sched_day = urldecode($wp_query->query_vars['sched_day']);
	else :
		$sched_day = date('d', $t);
	endif;
	$today_date = date('Y-m-d', $t);
	$date = $sched_year."-".$sched_month."-".$sched_day;
	wp_enqueue_script('jquery-ui-datepicker');
	get_header();
?>
	<style>
		.date-select {
			display: flex;
			justify-content: space-between;
			font-weight: 700;
			text-transform: uppercase;
		}
		ul.proglist {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		ul.proglist li {
			padding: 1em;
			background-color: white;
			border: 1px solid rgba(0,0,0,0.25);
			margin: 0 0 1em 0;
		}
		ul.proglist li ul li {
			border: 0;
			margin: 0;
			padding: 0 0 0.5em 0;
		}
		ul.proglist li p {
			font-size: 90%;
		}
		ul.proglist li h3 {
			margin-bottom: 0;
		}
		ul.proglist > li > * + * {
			margin-top: 1rem;
		}
		.proglist .progsegment button {
			background-color: rgb(0,98,136);
			color: white;
			padding: 0.75em;
			margin: 0;
			position: relative;
			width: 100%;
			text-align: left;
		}
		.proglist .progsegment.seg-active button {
			font-weight: bolder;
		}
		.proglist .progsegment button:after {
			content: '+';
			position: absolute;
			right: 1em;
			font-weight: bolder;
		}
		.proglist .progsegment.seg-active button:after {
			content: '-';
		}
		.proglist .progsegment ul {
			position: absolute;
			top: 100%;
			left: 0;
			transform: rotateX(-90deg);
			transform-origin: top center;
			opacity: 0.3;
			transition: 280ms all 200ms ease-out;
			margin: 0;
			padding: 0;
		}
		ul.proglist .progsegment.seg-active ul {
			opacity: 1;
			transform: rotateX(0);
			visibility: visible;
			position: static;
		}
		ul.proglist .progsegment li {
			overflow: visible;
			padding: 0.5em 0;
			list-style: disc;
			margin: 0 0 0 2em;
		}
		ul.proglist .progsegment ul.progplay li {
			list-style: none;
			margin: 0;
			padding: 1em;
		}
		ul.proglist .progsegment ul.progplay li:nth-child(even) {
			background-color: #ddd;
		}
		ul.proglist .progsegment ul.progplay li em {
			color: rgb(49, 49, 49);
		}
		.page #main, article {
			background-color: transparent;
		}
		#main > aside {
			background-color: white;
			margin: 0;
			padding: 1rem;
		}
		.page-header {
			display: flex;
			flex-flow: row wrap;
			align-items: center;
			justify-content: space-between;
		}
		.page-header #schedule-search {
			width: 100%;
			margin-top: 1rem;
		}
	</style>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php
					if ( $sched_station == 'news887' ) :
						$media = get_posts([
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						]);
					elseif ( $sched_station == 'classical' ) :
						$media = get_posts([
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						]);
					endif; ?>
				<div class="station-printable">
					<a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">Printable Schedule</a>
				</div>
				<div id="schedule-search">
					<div id="day-select">
						<form role="form" method="" action="">
							<label for="datepicker">Select a Day</label>
							<input type="text" id="datepicker" placeholder="Enter a date" name="datepicker" />
						</form>
					</div>
				</div>
			</header>
<?php
		if ( $sched_station == 'news887' ) :
			$station = "519131dee1c8f40813e79115";
		elseif ( $sched_station == 'classical' ) :
			$station = "51913211e1c8408134a6d347";
		endif;

		$date_unix = mktime(0,0,0,$sched_month,$sched_day,$sched_year);
		$tomorrow = date('Y/m/d',$date_unix + 86400);
		$yesterday = date('Y/m/d',$date_unix - 86400); ?>
			<article>
				<div class="date-select">
					<a class="date-pick-left" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $yesterday."/"; ?>">&lt;&lt; Previous Day</a>
					<a class="date-pick-right" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $tomorrow."/"; ?>">Next Day &gt;&gt;</a>
				</div>
				<p>&nbsp;</p>
<?PHP
		$today = date('l, F j, Y',$date_unix);
		$api = file_get_contents("https://api.composer.nprstations.org/v1/widget/".$station."/day?date=".$date."&format=json");
		if (preg_match('~HTTP/1\.1 400 Bad Request~i',$api) && $today_date == $date ) :
			$api = file_get_contents( "https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/ytjson/".$sched_station.".json" );
		elseif (preg_match('~HTTP/1\.1 400 Bad Request~i',$api) && $today_date != $date ) : ?>
				<h3>Playlist Error</h3>
				<p>We&#39;re sorry, but there was an error in loading the playlist data.  Please try again shortly, or <a href="/<?php echo $sched_station; ?>/">return to today&#39;s playlist</a>.</p>
<?PHP
		else :
			$json = json_decode($api,TRUE);
			if (empty($json['onToday'])) :?>
				<h3>Playlist Error</h3>
				<p>We&#39;re sorry, but there isn&#39;t any playlist data for the selected date.  Please choose another date from the calendar, or <a href="/<?php echo $sched_station; ?>/">return to today&#39;s playlist</a>.</p>
<?PHP
			else:
				$current = [
					'name' => '',
					'time' => '',
					'index' => ''
				];
				$progs = [];

				foreach ( $json['onToday'] as $k => $v ) :
					$fullend = strtotime( preg_replace( '/ GMT\-0[45]00 \(E[SD]T\)/', '', $v['end_utc'] ) );
					$fullstart = strtotime( preg_replace( '/ GMT\-0[45]00 \(E[SD]T\)/', '', $v['start_utc'] ) );
					$duration = $fullend - $fullstart;
					$name = $v['program']['name'];
					if ( $duration > 600 ) :
						if (
							( empty( $current['name'] ) && empty( $current['time'] ) ) ||
							( $name !== $current['name'] && $fullstart !== $current['time'] )
						) :
							$current = [
								'name' => $name,
								'time' => $fullstart,
								'index' => $k
							];
							$progs[$k] = [
								'name' => $name,
								'time' => date( 'g:i a', $fullstart ),
								'link' => $v['program']['program_link'],
								'desc' => $v['program']['program_desc'],
								'playlist' => ( !empty( $v['playlist'] ) ? $v['playlist'] : [] ),
								'sub' => []
							];
						endif;
					else :
						if ( $name !== $current['name'] && $fullstart !== $current['time'] ) :
							$index = $current['index'];
							$progs[ $index ]['sub'][] = [
								'name' => $name,
								'time' => date( 'g:i a', $fullstart ),
								'link' => $v['program']['program_link']
							];
						endif;
					endif;
				endforeach; ?>
				<h3>Playlist for <?PHP echo $today; ?></h3>
				<ul class="proglist">
<?PHP
				foreach ( $progs as $prog ) : ?>
					<li>
						<h3><strong><?PHP echo $prog['time']; ?>:</strong> <?php echo ( !empty( $prog['link'] ) ? '<a href="'.$prog['link'].'">' : '' ) . $prog['name'] . ( !empty( $prog['link'] ) ? '</a>' : '' ); ?></h3>
						<p><?PHP echo $prog['desc']; ?></p>
<?PHP
					echo hpm_segments( $prog['name'], $date );
					if ( !empty( $prog['sub'] ) ) : ?>
						<div class="progsegment">
							<button aria-label="Program Interstitials">Interstitials</button>
							<ul>
<?PHP
						foreach( $prog['sub'] as $ksu => $vsu ) : ?>
								<li>
									<strong><?PHP echo $vsu['time']; ?>:</strong> <?php echo ( !empty( $vsu['link'] ) ? '<a href="'.$vsu['link'].'">' : '' ) . $vsu['name'] . ( !empty( $vsu['link'] ) ? '</a>' : '' ); ?>
								</li>
<?PHP
						endforeach; ?>
							</ul>
						</div>
<?php
					endif;
					if ( !empty( $prog['playlist'] ) ) : ?>
						<div class="progsegment">
							<button aria-label="Program Playlist">Program Playlist</button>
							<ul class="progplay">
<?PHP
						foreach( $prog['playlist'] as $ks => $song ) :
							$song_info = [];
							$song_start = explode(' ', $song['_start_time'] );
							$song_start_date = explode( '-', $song_start[0] );
							$song_start_time = explode( ':', $song_start[1] );
							$song_start_string = date( 'g:i a', mktime( $song_start_time[0], $song_start_time[1], $song_start_time[2], $song_start_date[0], $song_start_date[1], $song_start_date[2] ) );
							if ( !empty( $song['composerName'] ) ) :
								$song_info[] = "<em>Composer</em>: " . trim( $song['composerName'] );
							endif;
							if ( !empty( $song['ensembles'] ) ) :
								$song_info[] = "<em>Ensembles</em>: " . trim( $song['ensembles'] );
							endif;
							if ( !empty( $song['artistName'] ) ) :
								$song_info[] = "<em>Performer</em>: " . trim( $song['artistName'] );
							endif;
							if ( !empty( $song['conductor'] ) ) :
								$song_info[] = "<em>Conductor</em>: " . trim( $song['conductor'] );
							endif;
							if ( !empty( $song['copyright'] ) ) :
								if ( !empty( $song['catalogNumber'] ) ) :
									$song_info[] = "<em>Catalog Information</em>: " . trim( $song['copyright'] ) . " " . trim( $song['catalogNumber'] );
								else :
									$song_info[] = "<em>Label</em>: " . trim( $song['copyright'] );
								endif;
							endif; ?>
								<li>
									<h3><?PHP echo $song_start_string; ?>: <b><?php echo trim( $song['trackName'] ); ?></b></h3>
									<?php echo implode( '<br />', $song_info ); ?>
								</li>
<?PHP
						endforeach; ?>
							</ul>
						</div>
<?PHP
					endif; ?>
					</li>
<?PHP
				endforeach; ?>
				</ul>
<?PHP
			endif;
		endif; ?>
			</article>
			<aside>
				<section>
					<nav id="category-navigation" class="category-navigation" role="navigation">
						<h4><?php the_title(); ?> Quick Links</h4>
						<?php
							if ( $sched_station == 'news887' ) :
								$nav_id = 2213;
							elseif ( $sched_station == 'classical' ) :
								$nav_id = 2214;
							endif;
							wp_nav_menu( array(
								'menu_class' => 'nav-menu',
								'menu' => $nav_id
							) );
						?>
					</nav>
				</section>
				<section>
					<?php the_content(); ?>
				</section>
			</aside>
		<?php endwhile;	?>
		</main>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			var progSeg = document.querySelectorAll('.progsegment button');
			Array.from(progSeg).forEach((ps) => {
				ps.addEventListener('click', () => {
					ps.parentElement.classList.toggle('seg-active');
				});
			});
			jQuery( "#datepicker" ).datepicker({
				dateFormat: "yy/mm/dd",
				onSelect: function(date) {
					location.href = '/<?PHP echo $sched_station; ?>/schedule/'+ date;
				}
			});
		});
	</script>
<?php get_footer(); ?>