<?php
/*
Template Name: Schedules
*/
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;

	if ( isset( $wp_query->query_vars['sched_station'] ) ) :
		$sched_station = urldecode($wp_query->query_vars['sched_station']);
	endif;
	
	if ( isset( $wp_query->query_vars['sched_endpoint'] ) ) :
		$sched_endpoint = urldecode($wp_query->query_vars['sched_endpoint']);
	endif;

	if ( isset( $wp_query->query_vars['sched_tv_query'] ) ) :
		$sched_tv_query = urldecode($wp_query->query_vars['sched_tv_query']);
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
	get_header(); 
?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$( "#datepicker" ).datepicker({
				dateFormat: "yy/mm/dd",
				onSelect: function(date) {
					location.href = '/<?PHP echo $sched_station; ?>/schedule/'+ date;
				}
			});
			$( "#tv-search-box" ).submit(function( event ) {
				event.preventDefault();
				var text = $('#searchtext').val();
				location.href = '/<?php echo $sched_station; ?>/schedule/search/'+encodeURI(text);
			});
			$("#tv-channel-drop").change(function(){
				var channel = $(this).val();
				$('.schedule-column, .time-column').hide();
				$('#'+channel).show().prev('.time-column').show();
			});
		});
	</script>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title entry-title"><?php the_title(); ?></h1>
				<div id="station-social">
				<?php
					if ( $sched_station == 'tv8' ) :
						$media = get_posts(array(
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						)); ?>
					<div class="station-social-icon">
						<a href="https://www.facebook.com/houstonpublicmedia" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://twitter.com/hpmeducation" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://www.youtube.com/user/houstonpublicmedia" target="_blank"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
					</div>
					<div class="station-printable">
						<a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">View Printable eGuide</a>
					</div>
				<?php
					elseif ( $sched_station == 'news887' ) :
						$media = get_posts(array(
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						)); ?>
					<div class="station-social-icon">
						<a href="https://www.facebook.com/HoustonNews887" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://twitter.com/hpmnews887" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://soundcloud.com/hpmnews887" target="_blank"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
					</div>
                    <div class="station-printable">
                        <a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">Printable Schedule</a>
                    </div>
				<?php
					elseif ( $sched_station == 'classical' ) :
						$media = get_posts(array(
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						)); ?>
					<div class="station-social-icon">
						<a href="https://www.facebook.com/houstonpublicmedia" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://twitter.com/hpmartsculture" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
					</div>
					<div class="station-social-icon">
						<a href="https://soundcloud.com/hpmartsandculture" target="_blank"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
					</div>
                    <div class="station-printable">
                        <a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">Printable Schedule</a>
                    </div>
				<?php
					endif; ?>
				</div>
			<?php	
	 			if ( $sched_station != 'tv8' ) : ?>
				<div id="schedule-search">
					<div id="day-select">
						<form role="form" method="" action="">
							<label for="datepicker">Select a Day</label>
							<input type="text" id="datepicker" placeholder="Enter a date" name="datepicker" />
						</form>
					</div>
				</div>
<?php	
				endif;
	/* if ( $sched_station == 'tv8' ) : ?>
					<div id="tv-search">
						<form role="form" method="" action="" id="tv-search-box">
							<label for="searchtext">Search TV 8 Schedule</label>
							<input type="text" id="searchtext" placeholder="Search" name="searchtext" />
							<button class="search-submit screen-reader-text">
								<span class="fa fa-search" aria-hidden="true"></span><span class="screen-reader-text">Search</span>
							</button>
						</form>
					</div>
<?php
	endif; */ ?>
			</header>
			
<?php	
	if ( $sched_station == 'tv8' ) : 
		/* function compare_channel($a, $b) {
			return strnatcmp($a['digital_channel'], $b['digital_channel']);
		}

		$opts = array(
			'http'=> array(
				'method'=>"GET",
				'header'=>"X-PBSAuth: houstonpublicmedia-50022ddd26361b6838fe0a7b102d00322fe87389d10340222049992ec93cd36b"
			)
		);
		$url_base = "http://services.pbs.org/tvss/kuht/";
		$endpoint = !empty( $sched_endpoint ) ? $sched_endpoint : 'day';
		if ($endpoint == 'episode') :
			if ( !empty( $sched_tv_query ) ) :
				$q = $sched_tv_query;
			else :
				echo "<h3>No Show ID Provided, Please Try Again.</h3>";
			endif;
			$url = $url_base."upcoming/show/".$q."/";
		elseif ( $endpoint == 'program' ) :
			if ( !empty( $sched_tv_query ) ) :
				$q = $sched_tv_query;
			else :
				echo "<h3>No Program ID Provided, Please Try Again.</h3>";
				die;
			endif;
			$url = $url_base."upcoming/program/".$q."/";
		elseif ( $endpoint == 'day' ) :
			$q = $sched_year.$sched_month.$sched_day;
			$url = $url_base."day/".$q."/";
		elseif ($endpoint == 'search') :
			if ( !empty( $sched_tv_query ) ) :
				$q = $sched_tv_query;
			else :
				echo "<h3>No Search Term Provided, Please Try Again.</h3>";
				die;
			endif;
			$url = $url_base."search/".$q;
		else :
			$url = $url_base."day/".date("Ymd")."/";
		endif;

		$context = stream_context_create($opts);
		$result = file_get_contents($url, FALSE, $context);
		$json = json_decode($result, true);
		if ($endpoint == 'episode') : 
			//  ** SHOW INFORMATION **  //
			//	** This is the display of information for individual episodes of a program **  //

?>
				<section id="station-schedule-display" class="column-span">
					<h2><?PHP echo $json['title']; ?></h2>
					<p><b><?PHP echo $json['episode_title']; ?></b></p>
<?PHP 
			echo (!empty($json['episode_description']) ? "<p>".$json['episode_description']."</p>" : "");
			if (!empty($json['upcoming_shows'])) : ?>
					<ul>
<?PHP
				foreach ($json['upcoming_shows'] as $show) :
					if (empty($show['feed']['analog_channel'])) :
						$date = str_split($show['day'], 2);
						$time = str_split($show['start_time'], 2);
						$dt = date("D, M j, Y \@ g:i a",mktime($time[0],$time[1],0,$date[2],$date[3],$date[0].$date[1])); ?>
						<li><i><?PHP echo $dt." on ".$show['feed']['full_name']; ?></i></li>
<?PHP
					endif;
				endforeach; ?>
					</ul>
<?PHP
			else : ?>
					<p>There are no additional airings scheduled.</p>
<?PHP
			endif; 
		elseif ($endpoint == 'program') : 

			//  ** PROGRAM INFORMATION **  //
			//	** This is the display of information for entire programs **  //

?>
				<section id="station-schedule-display" class="column-span">
					<h2><?PHP echo $json['title']; ?></h2>
					<p><?PHP echo $json['description']; ?></p>
<?php
			if (!empty($json['upcoming_episodes'])) : ?>
					<p>Upcoming Episodes</p>
					<ul>
<?PHP
				foreach ($json['upcoming_episodes'] as $up) :
					if (empty($up['feed']['analog_channel'])) :
						$show = !empty($up['show_id']) ? "<a title=\"View Episode Information\" href=\"/".$sched_station."/schedule/episode/".$up['show_id']."\">".$up['episode_title']."</a>" : $up['episode_title'];
						$desc = !empty($up['episode_description']) ? $up['episode_description'] : ""; 
						$date = str_split($up['day'], 2);
						$time = str_split($up['start_time'], 2);
						$dt = date("D, M j, Y \@ g:i a",mktime($time[0],$time[1],0,$date[2],$date[3],$date[0].$date[1])); ?>
						<li>
							<p><i><?PHP echo $dt." on ".$up['feed']['full_name']; ?></i><br />
							<b><?PHP echo $show; ?></b><br />
							<?PHP echo $desc; ?></p>
						</li>
<?PHP
					endif;	
				endforeach; ?>
					</ul>
<?PHP
			else : ?>
					<p>Sorry, there are no upcoming episodes.</p>
<?PHP
			endif;
			elseif ($endpoint == 'search') :
		
				//  ** SEARCH QUERIES **  //
				//	** Search queries are parsed, information displayed, and if available, airtimes and additional information is queried and displayed **  //

?>
				<section id="station-schedule-display" class="column-span">
					<h2>Search Results for <i>"<?PHP echo $q; ?>"</i></h2>
					<div class="station-search">
						<h3>Related Episodes</h3>
<?PHP
				if (!empty($json['show_results'])) : 
					foreach($json['show_results'] as $show) :?>
						<h4><?PHP echo (!empty($show['episode_title']) ? $show['episode_title'] : $show['title']);  ?></h4>
						<?PHP echo (!empty($show['episode_title']) ? "<p>Show: <i>".$show['title']."</i></p>" : ''); ?>
						<?PHP echo (!empty($show['episode_description']) ? "<p>".$show['episode_description']."</p>" : (!empty($show['description']) ? "<p>".$show['description']."</p>" : '')); ?>
<?PHP
						if (!empty($show['show_id'])) :
							$result2 = file_get_contents($url_base."upcoming/show/".$show['show_id']."/", FALSE, $context);
							$json2 = json_decode($result2, true);
							if (!empty($json2['upcoming_shows'])) : ?>
						<p><b>Upcoming Airtimes</b></p>
						<ul>
<?PHP
								foreach($json2['upcoming_shows'] as $ups) :
									if (empty($ups['feed']['analog_channel'])) :
										$time = str_split($ups['start_time'], 2);
										$day = str_split($ups['day']);
										$dt = mktime($time[0],$time[1],0,$day[2],$day[3],$day[0].$day[1]); ?>
							<li><?PHP echo date('g:i A',$dt)." on ".$ups['feed']['full_name']." (".$ups['feed']['digital_channel'].")"; ?></li>
<?PHP
									endif;
								endforeach; ?>
						</ul>
<?PHP
							endif;	
						endif; 
					endforeach; 
				else: ?>
							<h5>Sorry, no episodes matched your query.</h5>
<?PHP
				endif; ?>

					</div>
					<div class="station-search">
						<h3>Related Programs</h3>
<?PHP
				if (!empty($json['program_results'])) : 
					foreach($json['program_results'] as $program) : ?>
						<h4><?PHP echo $program['title'];  ?></h4>
						<?PHP echo (!empty($program['description']) ? "<p>".$program['description']."</p>" : ''); ?>
<?PHP
						if (!empty($program['program_id'])) :
							$result3 = file_get_contents($url_base."upcoming/program/".$program['program_id']."/", FALSE, $context);
							$json3 = json_decode($result3, true);
							if (!empty($json3['upcoming_episodes'])) : ?>
						<p><b>Upcoming Episodes</b></p>
						<ul>
<?PHP
								foreach($json3['upcoming_episodes'] as $upe) :
									if (empty($upe['feed']['analog_channel'])) :
										$time = str_split($upe['start_time'], 2);
										$day = str_split($upe['day']);
										$dt = mktime($time[0],$time[1],0,$day[2],$day[3],$day[0].$day[1]); ?>
							<li><?PHP echo "<i>".$upe['episode_title']."</i> @ ".date('g:i A',$dt)." on ".$upe['feed']['full_name']." (".$upe['feed']['digital_channel'].")"; ?></li>
<?PHP
									endif;
								endforeach; ?>
						</ul>
<?PHP
							endif;
						endif;
					endforeach;
				else :?>
						<h5>Sorry, no programs matched your query.</h5>
<?PHP
				endif; ?>
					</div>
<?php
			else :
				//  ** DAILY PROGRAMMING GRID **  //
				//	** Displays a program grid  **  //


				$start = mktime(0,0,0,1,1,2014);
				$end = mktime(1,0,0,1,2,2014);
				$date_unix = mktime(0,0,0,$sched_month,$sched_day,$sched_year);
				$tomorrow = date('Y/m/d',$date_unix + 86400);
				$yesterday = date('Y/m/d',$date_unix - 86400);
				$today = date('l, F j, Y',$date_unix); ?>
			<div id ="station-schedule-display" class="column-span">
				<div class="station-schedule-wrap">
					<h2>Schedule for <?PHP echo $today; ?></h2>
					<div id="tv-channel-select">
						<form>
							<select id="tv-channel-drop">
								<option value="KUHTDT">KUHT HDTV (CH. 8.1)</option>
								<option value="KUHTDT2">KUHT Create (CH. 8.2)</option>
								<option value="KUHTDT3">KUHT PBS Kids (CH. 8.3)</option>
								<option value="KUHTDT4">KUHT World (CH. 8.4)</option>
							</select>
						</form>
					</div>
					<div class="date-select">
						<a class="date-pick-left" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $yesterday."/"; ?>">&lt;&lt; Previous Day</a>
						<a class="date-pick-right" href="/<?php echo $sched_station; ?>/schedule/<?PHP echo $tomorrow."/"; ?>">Next Day &gt;&gt;</a>
					</div>
					<div class="time-column">
						<div class="head">Time</div>
<?PHP
				for ($i=$start;$i<$end;) :
					echo "<div class=\"time\">".date('g:i A',$i)."</div>";
					$i = $i+3600;
				endfor; ?>
					</div>
<?PHP	
				usort($json['feeds'], 'compare_channel');
				foreach ($json['feeds'] as $feed) :
					if (empty($feed['analog_channel'])) : ?>
					<div class="schedule-column" id="<?php echo $feed['short_name']; ?>">
						<div class="head"><?PHP echo $feed['full_name']." (CH. ".$feed['digital_channel'].")"; ?></div>
<?PHP
						$c = 0;
						foreach ($feed['listings'] as $list) :
							$ep_title = !empty($list['episode_title']) ? $list['episode_title'] : '';
							$ep_title = wp_trim_words( $ep_title, 6, '...' );
							$title = !empty($list['program_id']) ? "<a title=\"View Show Information\" href=\"/".$sched_station."/schedule/program/".$list['program_id']."\" class=\"program-title\">".$list['title']."</a>" : "<span class=\"program-title\">".$list['title']."</span>";
							$show = !empty($list['show_id']) ? "<br /><a title=\"View Episode Information\" href=\"/".$sched_station."/schedule/episode/".$list['show_id']."\">".(!empty($ep_title) ? $ep_title : 'Episode Information')."</a>" : (!empty($ep_title) ? "<br />".$ep_title : '');
							$desc = !empty($list['episode_description']) ? " title=\"".htmlentities($list['episode_description'])."\"" : '';
							$date = !empty($q) ? str_split($q, 2) : str_split(date("Ymd"),2);
							$time = str_split($list['start_time'], 2);
							$dt = mktime($time[0],$time[1],0,1,1,2014);
							$pad = '';
							if ($list['minutes'] == 30) :
								$pad .= " thirty";
							elseif ($list['minutes'] == 60) :
								$pad .= " sixty";
							elseif ($list['minutes'] == 90) :
								$pad .= " ninety";
							elseif ($list['minutes'] == 120) :
								$pad .= " onetwenty";
							elseif ($list['minutes'] == 150) :
								$pad .= " onefifty";
							elseif ($list['minutes'] < 30) :
								$pad .= " fifteen";
							endif;
							if ($c == 0 && $dt != $start) :
								$pad .= ' toppad';
							endif; ?>
						<div class="show<?PHP echo $pad; ?>"<?PHP echo $desc; ?>>
							<p><?PHP echo $title.$show; ?></p>
						</div>
<?PHP	
						$c++;
						endforeach; ?>
					</div>
					<div class="time-column">
						<div class="head">Time</div>
<?PHP
						for ($i=$start;$i<$end;) :
							echo "<div class=\"time\">".date('g:i A',$i)."</div>";
							$i = $i+3600;
						endfor; ?>
					</div>
<?php
					endif;
				endforeach; ?>
				</div>
			</div>
<?PHP
			endif;  */ ?>
				<section id="station-schedule-display" class="column-span">
					<iframe scrolling="auto" src="https://pw.myersinfosys.com/kuht/hour"></iframe>
<?php
	else :
		if ( $sched_station == 'news887' ) :
			$station = "519131dee1c8f40813e79115";
		elseif ( $sched_station == 'classical' ) :
			$station = "51913211e1c8408134a6d347";
		endif;

		$date_unix = mktime(0,0,0,$sched_month,$sched_day,$sched_year);
		$tomorrow = date('Y/m/d',$date_unix + 86400);
		$yesterday = date('Y/m/d',$date_unix - 86400); ?>
		<section id="station-schedule-display" class="column-left">
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
			else: ?>
				<h3>Playlist for <?PHP echo $today; ?></h3>
				<ul class="proglist">
<?PHP
				foreach ($json['onToday'] as $show) :
					$start_date = explode('-',$show['date']);
					$start_time = explode(':',$show['start_time']);
					$start_string = date('g:i a',mktime($start_time[0],$start_time[1],0,$start_date[1],$start_date[2],$start_date[0]));
					$end_time = explode(':',$show['end_time']);
					$end_string = date('g:i a',mktime($end_time[0],$end_time[1],0,$start_date[1],$start_date[2],$start_date[0])); ?>
					<li>
						<div class="progtime"><?PHP echo $start_string; ?></div>
						<div class="progname">
							<h4><a href="<?PHP echo $show['program']['program_link']; ?>"><?PHP echo $show['program']['name']; ?></a></h4>
							<p><?PHP echo wp_trim_words( $show['program']['program_desc'], 25, '...' ); ?></p>
<?PHP
					echo hpm_segments( $show['program']['name'], $date );
					if (!empty($show['playlist'])) : ?>
							<h5>Program Playlist</h5>
							<ul class="progplay">
<?PHP
						$c = 1;
						foreach($show['playlist'] as $song) :
							$song_info = array();
							$song_start = explode(' ',$song['_start_time']);
							$song_start_date = explode('-',$song_start[0]);
							$song_start_time = explode(':',$song_start[1]);
							$song_start_string = date('g:i a',mktime($song_start_time[0],$song_start_time[1],$song_start_time[2],$song_start_date[0],$song_start_date[1],$song_start_date[2]));
							if (!empty($song['composerName'])) :
								$song_info[] = "<i>Composer</i>: ".trim($song['composerName']);
							endif;
							if (!empty($song['ensembles'])) :
								$song_info[] = "<i>Ensembles</i>: ".trim($song['ensembles']);
							endif;
							if (!empty($song['artistName'])) :
								$song_info[] = "<i>Performer</i>: ".trim($song['artistName']);
							endif;
							if (!empty($song['conductor'])) : 
								$song_info[] = "<i>Conductor</i>: ".trim($song['conductor']);
							endif;
							if (!empty($song['copyright'])) :
								if (!empty($song['catalog'])) : 
									$song_info[] = "<br />(Catalog Information: ".trim($song['copyright'])." ".trim($song['catalog']).")";
								else :
									$song_info[] = "<br />(Label: ".trim($song['copyright']).")";
								endif;
							endif;
							if ($c & 1) : ?>
								<li>
<?PHP
							else : ?>
								<li class="shade">
<?PHP
							endif; ?>
									<div class="progtime"><?PHP echo $song_start_string; ?></div>
									<div class="progname">
										<b><?php echo trim($song['trackName']); ?>,</b><br />
										<?php echo implode( ', ', $song_info ); ?><br /><br />
									</div>
								</li>
<?PHP
							$c++;
						endforeach; ?>
							</ul>
<?PHP
					endif; ?>
						</div>
					</li>
<?PHP
				endforeach; ?>
				</ul>
<?PHP
			endif;
		endif;
	endif; ?>
			</section>
			<div id="top-schedule-wrap" class="column-right">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php the_title(); ?> Quick Links</h4>
					<?php
						if ( $sched_station == 'tv8' ) :
							$nav_id = 2212;
						elseif ( $sched_station == 'news887' ) :
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
			</div>
			<div class="column-left">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			</div>
		<?php
		endwhile;
		?>
			
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>