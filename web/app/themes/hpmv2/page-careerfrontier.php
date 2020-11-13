<?php
/*
Template Name: Career Frontier
*/
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/qmq1vwk.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<?php
						the_title( '<h1 class="page-title screen-reader-text">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<div class="page-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<?php
				endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<script>
		jQuery(document).ready(function($){
			$('.cf-eps-wrap article header').click(function(){
				var par = $(this).parents('article');
				if (par.hasClass('topic-active'))
				{
					par.removeClass('topic-active');
				} else  {
					par.addClass('topic-active');
				}
			});
		});
	</script>
	<style>
		.page-content {
			overflow: hidden;
			padding: 1em 0 0;
		}
		.page-content p {
			margin-bottom: 1em;
			font-family: usual,arial,sans-serif;
		}
		.page-content a {
			color: rgb(80, 127, 145);
		}
		.page-header {
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			height: 0;
			margin: 0;
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			padding-bottom: calc(100%/1.5);
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/CF-Large-Banner-Phone-300x200.jpg);
		}
		.page-header .page-header-wrap {
			display: flex;
			align-content: center;
			align-items: center;
			justify-items: flex-start;
			width: 100%;
			height: 100%;
			position: absolute;
			flex-flow: row wrap
		}
		.page-header .page-header-wrap div {
			padding: 1em;
		}
		.page-header h1 {
			color: white;
			margin: 0;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 700;
			width: 100%;
			font-size: 500%;
			line-height: 100%;
		}
		.page-header p {
			font-size: 125%;
			width: 100%;
			font-family: usual,arial,sans-serif;
			color: white;
			margin: 0;
		}
		.page-template-page-careerfrontier article {
			padding: 0;
			margin: 0;
		}
		.page-content h2 {
			font-size: 200%;
			border-bottom: 1px solid #00566d;
			padding: 0 0 0.25em 0;
			margin-bottom: 1em;
			color: #00566d;
			text-transform: uppercase;
			font-family: Montserrat,Arial, Helvetica, sans-serif;
			font-weight: 800;
		}
		.cf-content {
			padding-bottom: 2.5em;
		}
		.cf-content-wrap img,
		.cf-guest-wrap article img {
			width: 70%;
			margin: 0 15% 1em;
		}
		.cf-eps-wrap,
		.cf-guest-wrap {
			display: flex;
			flex-flow: row wrap;
			justify-content: space-evenly;
			align-content: flex-start;
			align-items: flex-start;
		}
		.cf-eps-wrap article {
			width: 100%;
			margin-bottom: 1em;
			border-bottom: 1px solid #b1b1b1;
			background-color: #fff;
		}
		.cf-guest-wrap article {
			width: 100%;
			margin-bottom: 2em;
			border-bottom: 1px solid #b1b1b1;
			background-color: #fff;
			padding: 0 1em 1em;
		}
		.cf-eps-wrap article h1 {
			margin-bottom: 0em;
			font-size: 175%;
			font-family: Montserrat,Arial, Helvetica, sans-serif;
			font-weight: 800;
			position: relative;
			text-transform: uppercase;
			background-color: rgb(80, 127, 145);
			padding: 0.5em 1em 0.5em 0.5em;
			color: white;
		}
		.cf-guest-wrap article h1 {
			margin-bottom: 0.75em;
			font-size: 200%;
			font-family: Montserrat,Arial, Helvetica, sans-serif;
			font-weight: 800;
		}
		.cf-eps-wrap article header h1:after {
			content: '\f0d7';
			display: inline-block;
			-webkit-font-smoothing: antialiased;
			font: 900 1em/1 'Font Awesome 5 Free';
			position: absolute;
			top: 0.5em;
			right: 0.5em;
		}
		.cf-eps-wrap article.topic-active header h1:after {
			content: '\f0d8';
		}
		.cf-eps-wrap .jp-type-single {
			background-color: transparent;
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-controls button {
			background-color: transparent;
			width: 4em;
			height: 4em;
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-controls button .fa {
			font-size: 3.25em;
			color: rgb(80, 127, 145);
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-progress-wrapper {
			position: relative;
			padding: 1em 0.5em;
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
			margin: 0;
			background-color: rgb(79, 79, 79);
			z-index: 9;
			position: relative;
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
			z-index: 11;
		}
		.cf-eps-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
			position: absolute;
			top: 1.5em;
			right: 1em;
			z-index: 10;
			float: none;
			width: initial;
			display: inline;
			padding: 0;
			color: white;
		}
		.cf-eps-wrap article .episode-content {
			clip: rect(1px, 1px, 1px, 1px);
			height: 1px;
			overflow: hidden;
			width: 1px;
			position: absolute;
			background-color: white;
			padding: 1em 1em 0;
		}
		.cf-eps-wrap article.topic-active .episode-content {
			clip: initial !important;
			height: auto;
			overflow: hidden;
			width: 100%;
			position: static;
		}
		.cf-content,
		.cf-eps,
		.cf-guests {
			width: 100%;
			margin: 0;
			padding: 1em 1em 4em;
			background-image: url(https://cdn.hpm.io/assets/images/ConnectionsGraphic1-sm.png);
			background-position: bottom;
			background-repeat: no-repeat;
			background-size: contain;
		}
		.cf-eps {
			background-color: #00566d;
		}
		.cf-eps h2 {
			border-bottom: 1px solid #fff;
			color: #fff;
		}
		.cf-eps article h2 {
			display: none;
			margin-bottom: 0.5em;
			font-size: 150%;
			font-family: Montserrat,Arial, Helvetica, sans-serif;
			font-weight: 400;
			color: #55565a;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				background-image: url(https://cdn.hpm.io/assets/images/CF-Large-Banner-Tablet-800x200.jpg);
			}
			.cf-content-wrap {
				display: flex;
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
			}
			.cf-content-wrap img {
				width: 33%;
				margin: 0;
				padding: 0 1em;
				order: 2;
				min-width: 250px;
			}
			.cf-eps-wrap .jp-gui.jp-interface .jp-details {
				display: none;
			}
			.cf-eps-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
				top: 1.25em;
			}
			.cf-guest-wrap article {
				width: 45%;
				margin: 0 2.5% 2em;
			}
			.cf-eps-wrap article header:hover {
				cursor: pointer;
				opacity: 0.75;
			}
			.cf-guest-wrap article {
				min-height: 46.375em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/6);
				background-image: url(https://cdn.hpm.io/assets/images/CF-Large-Banner-Desktop-1200x200.jpg);
			}
			.page-template-page-careerfrontier article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.page-template-page-careerfrontier .cf-eps-wrap article,
			.page-template-page-careerfrontier .cf-guest-wrap article {
				width: 32%;
				padding: 1em;
				margin: 0 0.6% 2em;
			}
			.cf-eps-wrap article .episode-content,
			.cf-eps-wrap article.topic-active .episode-content {
				clip: initial !important;
				height: auto;
				overflow: hidden;
				width: 100%;
				position: static;
			}
			.cf-content-wrap {
				max-width: 75%;
				margin: 0 12.5%;
			}
			.cf-eps-wrap article {
				min-height: 42em;
			}
			.cf-eps-wrap article h1 {
				padding: 0.5em;
			}
			.cf-eps-wrap article header h1:after {
				display: none;
			}
			.cf-eps article h2 {
				display: block;
			}
			.cf-full-title {
				display: none;
			}
		}
	</style>
<?php get_footer(); ?>