<?php
/*
Template Name: TV Schedule
*/
	get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title entry-title"><?php the_title(); ?></h1>
				<div id="station-social">
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
						<a href="<?php echo hpm_tvguide_url(); ?>">View Printable eGuide</a>
					</div>

				</div>
			</header>
			<section id="station-schedule-display" class="column-span">
				<iframe scrolling="auto" src="https://proweb.myersinfosys.com/kuht/day?time_zone=America%2FChicago&provider=2"></iframe>
			</section>
			<div id="top-schedule-wrap" class="column-right">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php the_title(); ?> Quick Links</h4>
					<?php
						wp_nav_menu( array(
							'menu_class' => 'nav-menu',
							'menu' => 2212
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