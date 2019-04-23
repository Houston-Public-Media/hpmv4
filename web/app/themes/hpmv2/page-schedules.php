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
				endif; ?>
			</header>
			
<?php	
	if ( $sched_station == 'tv8' ) : ?>
				<section id="station-schedule-display" class="column-span">
					<iframe scrolling="auto" src="https://proweb.myersinfosys.com/kuht/day"></iframe>
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