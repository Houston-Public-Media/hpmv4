<?php
/*
Template Name: Kids
*/

get_header(); ?>
	<style>
		@font-face {
			font-family: 'PBSKids';
			src: url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.ttf') format('truetype'), url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.woff') format('woff'), url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.eot') format('eot');
			font-weight: normal;
			font-style: normal;
		}
		#station-module {
			height: 450px;
		}
        .category-list-wrapper {
            display: -webkit-box;
            display: -moz-box;
            display: box;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-flow: row nowrap;
            -webkit-justify-content: center;
            -webkit-align-content: center;
            -webkit-align-items: center;
            -ms-flex-flow: row nowrap;
            -ms-justify-content: center;
            -ms-align-content: center;
            -ms-align-items: center;
            flex-flow: row nowrap;
            justify-content: center;
            align-content: center;
            align-items: center;
            position: relative;
            background-color: #00edff;
            padding: 0.5em;
            box-sizing: border-box;
            font-size: 13.3px;
        }
        .category-list-wrapper p {
            font-family: 'PBSKids',arial,helvetica,sans-serif;
            color: white;
            margin: 0 1em;
            font-size: 1.25em;
        }
        .category-list-wrapper .live-streaming-button {
            background-color: #ff8b00;
            min-width: 4.8em;
            height: 5.8em;
            border-radius: 1em;
            margin: 0.7em 0.5em 0 0.25em;
            cursor: pointer;
            position: relative;
        }
        .category-list-wrapper .live-streaming-button .live-streaming-title {
            font-family: 'PBSKids', Arial, Helvetica, sans-serif;
            font-size: 0.8em;
            font-weight: normal;
            font-style: normal;
            line-height: 1.2;
            letter-spacing: 1px;
            position: absolute;
            text-transform: uppercase;
            text-align: center;
            line-height: normal;
            left: 0;
            right: 0;
            bottom: 0.5em;
            z-index: 1;
            color: white;
        }
        .hidden-important {
            display: none !important;
            visibility: hidden !important;
        }
        .category-list-wrapper .live-streaming-button .live-streaming-series-logo {
            width: 3.8em;
            height: 3.8em;
            border-radius: 3.8em;
            background: #fff;
            overflow: hidden;
            position: relative;
            margin: 0.25em auto 0;
        }
        .category-list-wrapper .live-streaming-button .live-streaming-series-logo img {
            position: absolute;
            left: 0;
            bottom: 0;
            max-width: 100%;
            max-height: 100%;
            border-radius: 3.8em;
        }
        .category-list-wrapper .live-streaming-button:after {
            content: "";
            position: absolute;
            top: -0.7em;
            height: 0.7em;
            width: 100%;
            background: url(https://cms-tc.pbskids.org/nationalvideoplayer/resources/img/antenna.svg) no-repeat center top;
        }
		@media screen and (min-width: 30.0625em) {
			#station-module {
				height: 650px;
			}
            .category-list-wrapper p {
                font-size: 1.5em;
            }
		}
		@media screen and (min-width: 50.0625em) {
			#station-module {
				height: 750px;
			}
            .category-list-wrapper p {
                font-size: 2em;
            }
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/29132048/HPMKids-Logo-11.png" alt="Houston Public Media Kids" id="head-logo">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164215/Cat-2.png" alt="Cat" id="head-cat">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164220/Kids-2.png" alt="Ready Jet Go Kids" id="head-kids">
			</header>
			<?php
				the_content( );
			endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
