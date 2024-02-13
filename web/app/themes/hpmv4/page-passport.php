<?php
/*
Template Name: Passport
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<style>
			.page-template-page-passport .page-header {
				height: 0;
				padding: 0 0 calc(100%/2.5) 0;
				background-size: contain;
				background-position: top center;
				position: relative;
				margin: 0;
			}
			.page.page-template-page-passport #main > article {
				margin: 0 0 1em 0;
			}
			.page-template-page-passport .page-header .page-title {
				position: absolute;
				bottom: 0;
				left: 0;
				width: 100%;
				padding: 0.5em 0.5em 0.25em 0.5em;
				font-size: 1.5em;
				font-weight: 700;
				margin: 0;
				background-color: rgba(10,20,90,0.75);
				color: white;
			}
			.page-template-page-passport .page-header .page-title svg {
				height: 1.22em;
				padding: 0 1rem 0 0;
			}
			.page-template-page-passport.passport-faqs .page-header .page-title {
				top: 0;
				flex-flow: row wrap;
				justify-content: left;
				align-content: center;
				align-items: center;
				display: flex;
			}
			.page-template-page-passport .page-content {
				padding: 1.5em 0 0;
				text-align: center;
			}
			.page-template-page-passport .page-content a {
				color: #0A145A;
			}
			.page-template-page-passport.passport-faqs .page-content {
				padding: 1.5em 1em 1em;
				text-align: left;
			}
			.page-template-page-passport .page-content :is(p,h2) {
				padding-left: 1em;
				padding-right: 1em;
			}
			.page-template-page-passport.passport-faqs .page-content h2 {
				padding: 2rem 0 1rem;
				color: var(--main-blue);
			}
			.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) {
				width: 100%;
			}
			.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) a {
				width: 60%;
				display: block;
				text-align: center;
				padding: 1em;
				margin: 1em 20%;
				color: white;
			}
			.page-template-page-passport .page-content .passport-donate a {
				background-color: var(--main-blue);
			}
			.page-template-page-passport .page-content .passport-signin a {
				background-color: #0A145A;
			}
			.page-template-page-passport .page-content .passport-example {
				background-color: #f5f5f5;
				display: flex;
				align-items: center;
				align-content: center;
				justify-content: center;
				justify-items: center;
				margin-bottom: 1em;
			}
			.page-template-page-passport .page-content .passport-buttons {
				flex-flow: row wrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
			}
			.page-template-page-passport .page-content .passport-example :is(.passport-example-text,.passport-example-image) {
				text-align: left;
				padding: 1em;
			}
			.page-template-page-passport .page-content .passport-example p {
				padding-right: 0;
				padding-left: 0;
				color: #464646;
				font-size: 1em;
			}
			.page-template-page-passport .page-content .passport-example h3 {
				color: #464646;
				font-size: 1.25em;
			}
			.page-template-page-passport .page-content ul.passport-options {
				flex-flow: row wrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				list-style: none;
				margin: 0;
				padding: 0 1em 1em;
			}
			.page-template-page-passport .page-content ul.passport-options li {
				padding: 0 1em;
			}
			.passport-app {
				border-top: 1rem solid #f5f5f5;
				padding: 1rem 0 0;
				background: #0A145A;
				background: linear-gradient(90deg, var(--main-blue) 0%, #0A145A 100%);
			}
			.page-template-page-passport .page-content h2.device-options a {
				color: white;
			}
			.passport-app .passport-options a {
				background-color: #fff;
				display: block;
				width: 3rem;
				height: 3rem;
				-webkit-mask-repeat: no-repeat;
				-webkit-mask-position: center;
				-webkit-mask-size: contain;
				mask-repeat: no-repeat;
				mask-position: center;
				mask-size: contain;
			}
			.passport-app .passport-options a.passport-ios {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/iOS@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/iOS@2x.png);
			}
			.passport-app .passport-options a.passport-appletv {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/apple_tv@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/apple_tv@2x.png);
				width: 4rem;
			}
			.passport-app .passport-options a.passport-roku {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/roku_big@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/roku_big@2x.png);
				width: 5rem;
			}
			.passport-app .passport-options a.passport-android {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/android@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/android@2x.png);
				width: 3.5rem;
			}
			.passport-app .passport-options a.passport-androidtv {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/androidtv@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/androidtv@2x.png);
				width: 5.5rem;
			}
			.passport-app .passport-options a.passport-firetv {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/amazonfireTV_big@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/amazonfireTV_big@2x.png);
				width: 3.5rem;
			}
			.passport-app .passport-options a.passport-chromecast {
				-webkit-mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/chromecast@2x.png);
				mask-image: url(https://cdn.houstonpublicmedia.org/assets/images/icons/chromecast@2x.png);
				width: 7rem;
			}
			.page-template-page-passport.passport-faqs .page-content #passport-devices {
				flex-flow: row wrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				list-style: none;
				margin: 0;
				padding: 0 1em;
				border-bottom: 1px solid #707070;
			}
			.page-template-page-passport.passport-faqs .page-content #passport-devices li {
				width: 25%;
				padding: 0 1em 0.5em;
				opacity: 0.5;
				position: relative;
				bottom: -2px;
				margin: 0;
				height: 60px;
				flex-flow: row wrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				border-bottom: 3px solid transparent;
			}
			.page-template-page-passport.passport-faqs .page-content #passport-devices li img {
				max-height: 100%;
			}
			.page-template-page-passport.passport-faqs .page-content #passport-devices li:hover {
				opacity: 1;
				transition: opacity .2s ease-out;
				cursor: pointer;
			}
			.page-template-page-passport.passport-faqs .page-content #passport-devices li.passport-active {
				opacity: 1;
				border-bottom: 3px solid #0A145A;
			}
			.page-template-page-passport.passport-faqs .page-content .passport-device {
				padding: 1em 0;
				width: 100%;
				display: none;
			}
			.page-template-page-passport.passport-faqs .page-content .passport-device#passport-pc {
				display: block;
			}
			.page-template-page-passport.passport-faqs .page-content .passport-device p {
				padding: 0;
				margin: 0 0 1em 0;
			}
			.page-template-page-passport.passport-faqs .page-content .passport-device ul {
				list-style: disc;
				padding: 0 0 1em 1em;
				margin: 0;
				width: 100%;
			}
			.page-template-page-passport .page-content form {
				width: 90%;
				margin: 0.5em 5% 1.75em;
			}
			.page-template-page-passport .page-content form button {
				width: 30%;
				padding: 0.5em;
				border: 1px solid #464646;
				color: white;
			}
			.page-template-page-passport .page-content form#passport-activate button {
				background-color: #0A145A;
			}
			.page-template-page-passport .page-content form#passport-lookup button {
				background-color: var(--main-blue);
			}
			.page-template-page-passport .page-content form input[type="text"] {
				width: 70%;
				padding: 0.5em;
				border: 1px solid #464646;
			}
			@media screen and (min-width: 34em) {
				.page-template-page-passport .page-header {
					padding-bottom: calc(100%/3.6676);
				}
				.page-template-page-passport.passport-faqs .page-header {
					padding-bottom: calc(100%/6);
				}
				.page-template-page-passport .page-header .page-title {
					padding: 0.5em 1em 0.25em 1em;
					font-size: 2.5em;
				}
				.page-template-page-passport .page-content p {
					font-size: 1.0625em;
				}
				.page-template-page-passport .page-content h2 {
					font-size: 1.75em;
				}
				.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) {
					width: 40%;
				}
				.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) a {
					width: 90%;
					margin: 1em 5%;
				}
				.page-template-page-passport .page-content .passport-example .passport-example-text {
					max-width: 40%;
				}
				.page-template-page-passport .page-content .passport-example p {
					font-size: 1.125em;
				}
				.page-template-page-passport .page-content .passport-example h3 {
					font-size: 1.5em;
				}
				.page-template-page-passport .page-content .passport-buttons {
					padding: 1em 0;
				}
				.page-template-page-passport .page-content ul.passport-options {
					flex-flow: row nowrap;
				}
				.page-template-page-passport .page-content ul.passport-options li {
					width: auto;
				}
				.page-template-page-passport .page-content form {
					width: 70%;
					margin: 0.5em 15% 1.75em;
				}
				.page-template-page-passport.passport-faqs .page-content {
					width: 80%;
					margin: 0 10%;
				}
				.page-template-page-passport.passport-faqs .page-content #passport-devices {
					margin: 0 10%;
					padding: 0 2em;
				}
				.page-template-page-passport.passport-faqs .page-content #passport-devices li {
					padding: 0.5em 1.5em;
				}
			}
			@media screen and (min-width: 52.5em) {
				.page.page-template-page-passport #main > article {
					float: none;
					width: 100%;
					margin: 0;
				}
				.page-template-page-passport .page-content .passport-example .passport-example-text {
					max-width: 30%;
				}
				.page-template-page-passport .page-content p {
					font-size: 1.25em;
				}
				.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) {
					width: 30%;
				}
				.page-template-page-passport .page-content ul.passport-options {
					width: 80%;
					margin: 0 10%;
				}
				.page-template-page-passport .page-content form {
					width: 50%;
					margin: 0.5em 25% 1.75em;
				}
				.page-template-page-passport.passport-faqs .page-content #passport-devices {
					margin: 0 15%;
				}
				.page-template-page-passport.passport-faqs .page-content #passport-devices li {
					padding: 1em 1.5em;
					height: 70px;
				}

			}
			@media screen and (min-width: 64.0625em) {
				.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) {
					width: 25%;
				}
				.page-template-page-passport .page-content ul.passport-options {
					width: 70%;
					margin: 0 15%;
				}
			}
			[data-theme="dark"] .page-template-page-passport .page-content a {
				color: #5680ff;
			}
		</style>
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) {
				the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 0;">
				<?PHP $header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
				<header class="page-header" style="background-image: url('<?php echo $header_back[0]; ?>');">
					<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
				</header>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
				<footer class="page-footer">
					<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
		<?php } ?>
		</main>
	</div>
<?php get_footer(); ?>