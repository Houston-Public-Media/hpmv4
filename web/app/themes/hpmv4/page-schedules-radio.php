<?php
/*
Template Name: Radio Schedules
*/
	if ( isset( $wp_query->query_vars['sched_station'] ) ) {
		$sched_station = urldecode( $wp_query->query_vars['sched_station'] );
	} else {
		$sched_station = 'news887';
	}
	get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post();
				echo hpm_radio_schedule_shortcode( false ); ?>

			<div id="top-schedule-wrap" class="column-right">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php the_title(); ?> Quick Links</h4>
					<?php
						$nav_id = 2213;
						if ( $sched_station == 'classical' ) {
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
<?php get_footer(); ?>