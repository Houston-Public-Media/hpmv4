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
					if ( $sched_station == 'news887' ) :
						$media = get_posts([
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						]); ?>
                    <div class="station-printable">
                        <a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">Printable Schedule</a>
                    </div>
				<?php
					elseif ( $sched_station == 'classical' ) :
						$media = get_posts([
							'post_parent' => get_the_ID(),
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf',
							'orderby' => 'date',
							'posts_per_page' => 1,
							'order' => 'DESC'
						]); ?>
                    <div class="station-printable">
                        <a href="<?php echo wp_get_attachment_url( $media[0]->ID ); ?>">Printable Schedule</a>
                    </div>
				<?php
					endif; ?>
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
						<h2><strong><?PHP echo $prog['time']; ?>:</strong> <?php echo ( !empty( $prog['link'] ) ? '<a href="'.$prog['link'].'">' : '' ) . $prog['name'] . ( !empty( $prog['link'] ) ? '</a>' : '' ); ?></h2>
						<p><?PHP echo $prog['desc']; ?></p>
<?PHP
					echo hpm_segments( $prog['name'], $date );
					if ( !empty( $prog['sub'] ) ) : ?>
					<div class="progsegment">
						<h4>Interstitials</h4>
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
						<h4>Program Playlist</h4>
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
							endif;
							if ( ( $ks + 1 ) & 1 ) : ?>
								<li>
<?PHP
							else : ?>
								<li class="shade">
<?PHP
							endif; ?>
									<h2><?PHP echo $song_start_string; ?>: <b><?php echo trim( $song['trackName'] ); ?></b></h2>
									<?php echo implode( '<br />', $song_info ); ?>
								</li>
<?PHP
						endforeach; ?>
							</ul>
<?PHP
					endif; ?>
					</li>
<?PHP
				endforeach; ?>
				</ul>
<?PHP
			endif;
		endif; ?>
			</section>
			<div id="top-schedule-wrap" class="column-right">
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
			</div>
			<div class="column-right">
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
	<script>
		jQuery(document).ready(function($){
			$('.progsegment h4').click(function(event){
				event.preventDefault();
				var next = $(this).next('ul');
				if ( next.hasClass('seg-active') ) {
					next.removeClass('seg-active');
				} else {
					next.addClass('seg-active');
				}
				if ( $(this).hasClass('seg-active') ) {
					$(this).removeClass('seg-active');
				} else {
					$(this).addClass('seg-active');
				}
			});
		});
	</script>
<?php get_footer(); ?>