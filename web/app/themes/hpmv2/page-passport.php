<?php
/*
Template Name: Passport
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<style>
			.page-template-page-passport .page-header {
				padding: 0;
				height: 0;
				padding-bottom: calc(100%/2.5);
				background-size: cover;
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
				color: var(--main-red);
			}
			.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) {
				width: 100%;
			}
			.page-template-page-passport .page-content :is(.passport-donate,.passport-signin) a {
				font: 100 1.5em/1em var(--hpm-font-main);
				width: 60%;
				display: block;
				text-align: center;
				padding: 1em;
				margin: 1em 20%;
				color: white;
			}
			.page-template-page-passport .page-content .passport-donate a {
				background-color: var(--main-red);
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
				font-family: var(--hpm-font-main);
				color: #464646;
				font-size: 1em;
			}
			.page-template-page-passport .page-content .passport-example h3 {
				font-family: var(--hpm-font-main);
				font-weight: 100;
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
				width: 25%;
				padding: 0 1em;
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
				font-family: var(--hpm-font-main); font-weight: 100;
			}
			.page-template-page-passport .page-content form#passport-activate button {
				background-color: #0A145A;
			}
			.page-template-page-passport .page-content form#passport-lookup button {
				background-color: var(--main-red);
			}
			.page-template-page-passport .page-content form input[type="text"] {
				width: 70%;
				padding: 0.5em;
				border: 1px solid #464646;
				border-bottom: 1px solid #464646;
				font: 400 100%/1em var(--hpm-font-main);
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
		</style>
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 0;">
				<?PHP $header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
				<header class="page-header" style="background-image: url('<?php echo $header_back[0]; ?>');">
					<h1 class="page-title"><svg id="Layer_White" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 778.94 80"><defs><style>.cls-1,.cls-3{fill:#fff;}.cls-2{fill:#5680ff;}.cls-3{fill-rule:evenodd;}</style></defs><path class="cls-1" d="M535.93,18a14.42,14.42,0,0,1,6.34,5.46,15,15,0,0,1,2.15,8.08,16.46,16.46,0,0,1-2.36,8.88,15.5,15.5,0,0,1-6.55,5.85,21.78,21.78,0,0,1-9.63,2.05h-4.09V64H510.58V16.06h15.23A23.57,23.57,0,0,1,535.93,18Zm-5.11,19.28a7.37,7.37,0,0,0,2.11-5.39,6.38,6.38,0,0,0-2.15-5.15,8.44,8.44,0,0,0-5.67-1.83h-3.32V39.46h3A8.1,8.1,0,0,0,530.82,37.31Z"/><path class="cls-1" d="M574.35,31.46q3.92,3.84,3.91,10.68V64H569.8l-1.19-3a15.33,15.33,0,0,1-4.55,2.65,16.54,16.54,0,0,1-6.1,1.09,12.25,12.25,0,0,1-8.53-2.92,10.34,10.34,0,0,1-3.24-8.08q0-6.27,4.72-9.06a22.77,22.77,0,0,1,11.7-2.78,30.28,30.28,0,0,1,5.08.42,7.26,7.26,0,0,0-1.38-4.76q-1.37-1.65-5.6-1.65a25.32,25.32,0,0,0-5.64.67,27,27,0,0,0-5.64,1.93v-8.6a38.38,38.38,0,0,1,13-2.32Q570.43,27.62,574.35,31.46ZM558.17,55.61a4.65,4.65,0,0,0,3.17,1,11.38,11.38,0,0,0,6.35-2.11V48.84a25.8,25.8,0,0,0-3.31-.21,12.31,12.31,0,0,0-5.29,1A3.46,3.46,0,0,0,557,53,3.31,3.31,0,0,0,558.17,55.61Z"/><path class="cls-1" d="M584.33,62.73v-8.6a20.87,20.87,0,0,0,5,1.86,22.44,22.44,0,0,0,5.5.74,9.57,9.57,0,0,0,4.23-.77,2.41,2.41,0,0,0,1.55-2.19,2.57,2.57,0,0,0-.53-1.65,5.16,5.16,0,0,0-1.87-1.31c-.89-.42-2.28-1-4.16-1.62q-5.35-1.9-7.65-4.44a9.34,9.34,0,0,1-2.29-6.49,9.2,9.2,0,0,1,1.76-5.53A11.56,11.56,0,0,1,591,28.92a20.91,20.91,0,0,1,7.93-1.37,28.51,28.51,0,0,1,9.94,1.62v8.11a22.38,22.38,0,0,0-4.2-1.2,24.85,24.85,0,0,0-4.83-.5,10.74,10.74,0,0,0-4.19.67c-1,.45-1.52,1-1.52,1.73a2,2,0,0,0,.81,1.59,10.05,10.05,0,0,0,2.54,1.37c1.15.47,2.41.94,3.77,1.41q5.09,1.9,7.27,4.44a10.19,10.19,0,0,1,2.18,6.84,9.72,9.72,0,0,1-3.91,8.11q-3.91,3-11.11,3A29.22,29.22,0,0,1,584.33,62.73Z"/><path class="cls-1" d="M614.94,62.73v-8.6A21,21,0,0,0,620,56a22.44,22.44,0,0,0,5.5.74,9.57,9.57,0,0,0,4.23-.77,2.41,2.41,0,0,0,1.55-2.19,2.57,2.57,0,0,0-.53-1.65,5.16,5.16,0,0,0-1.87-1.31c-.89-.42-2.28-1-4.16-1.62q-5.35-1.9-7.65-4.44a9.34,9.34,0,0,1-2.29-6.49,9.2,9.2,0,0,1,1.76-5.53,11.56,11.56,0,0,1,5.12-3.81,20.91,20.91,0,0,1,7.93-1.37,28.51,28.51,0,0,1,9.94,1.62v8.11a22.38,22.38,0,0,0-4.2-1.2,24.93,24.93,0,0,0-4.83-.5,10.74,10.74,0,0,0-4.19.67c-1,.45-1.52,1-1.52,1.73a2,2,0,0,0,.81,1.59,10.05,10.05,0,0,0,2.54,1.37c1.15.47,2.41.94,3.77,1.41q5.08,1.9,7.27,4.44a10.19,10.19,0,0,1,2.18,6.84,9.72,9.72,0,0,1-3.91,8.11q-3.92,3-11.11,3A29.22,29.22,0,0,1,614.94,62.73Z"/><path class="cls-1" d="M676.83,32.34q4.88,4.72,4.87,13.75a22,22,0,0,1-2.08,9.76,15.36,15.36,0,0,1-5.92,6.59,16.94,16.94,0,0,1-9,2.33,18.26,18.26,0,0,1-7.12-1.27V78.66H647V30.51a51.37,51.37,0,0,1,16.22-2.89Q672,27.62,676.83,32.34Zm-8.31,20.59a11.09,11.09,0,0,0,2.18-7.19c0-3-.69-5.24-2.08-6.84A7.12,7.12,0,0,0,663,36.5a18.43,18.43,0,0,0-5.36.85V54.48a11,11,0,0,0,5,1.13A7.28,7.28,0,0,0,668.52,52.93Z"/><path class="cls-1" d="M694.61,62.52A16.51,16.51,0,0,1,688,56.06a19.73,19.73,0,0,1-2.4-10,19.56,19.56,0,0,1,2.4-9.94,16.18,16.18,0,0,1,6.66-6.38,20.55,20.55,0,0,1,9.63-2.22,20,20,0,0,1,9.44,2.22,16.26,16.26,0,0,1,6.63,6.34,18.51,18.51,0,0,1,2.4,9.49,20.85,20.85,0,0,1-2.4,10.25,16.35,16.35,0,0,1-6.66,6.63,20,20,0,0,1-9.62,2.29A19.77,19.77,0,0,1,694.61,62.52Zm15.06-9.28q2.12-2.57,2.11-7.43a10.41,10.41,0,0,0-2-6.84,6.78,6.78,0,0,0-5.5-2.47,6.93,6.93,0,0,0-5.54,2.43c-1.38,1.62-2.08,4-2.08,7.09s.71,5.51,2.12,7.22a7.08,7.08,0,0,0,10.93,0Z"/><path class="cls-1" d="M751.11,28.25V38.4c-.66-.18-1.35-.35-2.08-.49a12.11,12.11,0,0,0-2.29-.21,9,9,0,0,0-4.44,1.09,10,10,0,0,0-3.24,2.79V64H728.48V28.46h9.1l1.26,4.37a9.68,9.68,0,0,1,3.64-3.63,10.78,10.78,0,0,1,5.39-1.3A12.6,12.6,0,0,1,751.11,28.25Z"/><path class="cls-1" d="M769.7,36.29V50.81a6.3,6.3,0,0,0,1.13,4.06,4.69,4.69,0,0,0,3.81,1.37,12.85,12.85,0,0,0,4-.56v8a13.41,13.41,0,0,1-3.13.78,27.94,27.94,0,0,1-4.06.28q-6.19,0-9.27-3.14t-3.06-9V36.29h-5.5V28.46h5.5V20.71l10.57-2.89V28.46h9.24v7.83Z"/><circle class="cls-2" cx="454.58" cy="40" r="40"/><path class="cls-3" d="M418.66,40.17A35.81,35.81,0,0,1,454.53,4.49h0a35.68,35.68,0,1,1-35.86,35.68Zm28.88-7L454.39,10a30.23,30.23,0,0,0-30.15,30Zm-23.3,7.09,23.3,6.81,6.85,23.18A30.24,30.24,0,0,1,424.24,40.31Zm30.42,30,6.85-23.18,23.3-6.81A30.25,30.25,0,0,1,454.66,70.3ZM484.81,40h0a30.23,30.23,0,0,0-30.15-30l6.85,23.18L484.81,40Z"/><path class="cls-3" d="M473.34,21.45l-5.29,9.64-3.4-1-1-3.38Zm-9.69,32.18,1-3.39,3.4-1,5.29,9.64ZM441,49.25l-5.28,9.64,9.68-5.26-1-3.39Zm4.4-22.54-1,3.38-3.4,1-5.28-9.64Z"/><path class="cls-1" d="M0,16H11.19V35.19H30.76V16H41.89V63.84H30.76V44.41H11.19V63.84H0Z"/><path class="cls-1" d="M57.3,62.36a16.4,16.4,0,0,1-6.59-6.44,19.82,19.82,0,0,1-2.39-10A19.62,19.62,0,0,1,50.71,36a16.16,16.16,0,0,1,6.66-6.37A20.47,20.47,0,0,1,67,27.44a19.94,19.94,0,0,1,9.43,2.22A16.27,16.27,0,0,1,83,36a18.59,18.59,0,0,1,2.39,9.47A20.93,20.93,0,0,1,83,55.71a16.34,16.34,0,0,1-6.65,6.61,20,20,0,0,1-9.61,2.29A19.69,19.69,0,0,1,57.3,62.36Zm15-9.26q2.11-2.57,2.11-7.43a10.39,10.39,0,0,0-2-6.82,6.74,6.74,0,0,0-5.49-2.47,6.91,6.91,0,0,0-5.53,2.43c-1.38,1.62-2.08,4-2.08,7.08s.71,5.5,2.12,7.21a7.07,7.07,0,0,0,10.91,0Z"/><path class="cls-1" d="M124.15,28.36V63.84h-9l-.71-3.1a17.32,17.32,0,0,1-4.82,2.82,18.06,18.06,0,0,1-6.51,1q-5.85,0-9-3.27t-3.2-9.26V28.36h10.56V49.69q0,5.78,5.35,5.77A8.72,8.72,0,0,0,113.59,52V28.36Z"/><path class="cls-1" d="M130.6,62.57V54a21.2,21.2,0,0,0,5,1.87,22.86,22.86,0,0,0,5.49.74,9.5,9.5,0,0,0,4.23-.78,2.39,2.39,0,0,0,1.55-2.18,2.57,2.57,0,0,0-.53-1.65,5.28,5.28,0,0,0-1.87-1.31c-.89-.42-2.27-1-4.15-1.62q-5.35-1.89-7.64-4.43a9.33,9.33,0,0,1-2.29-6.48,9.23,9.23,0,0,1,1.76-5.53,11.65,11.65,0,0,1,5.11-3.8,20.86,20.86,0,0,1,7.92-1.37,28.39,28.39,0,0,1,9.92,1.62v8.1a22,22,0,0,0-4.19-1.2,24.72,24.72,0,0,0-4.82-.49,10.76,10.76,0,0,0-4.19.66c-1,.45-1.51,1-1.51,1.73a2,2,0,0,0,.81,1.58,9.93,9.93,0,0,0,2.53,1.38c1.15.47,2.41.94,3.77,1.4q5.07,1.91,7.25,4.44a10.09,10.09,0,0,1,2.18,6.83,9.7,9.7,0,0,1-3.9,8.09q-3.92,3-11.09,3A29.13,29.13,0,0,1,130.6,62.57Z"/><path class="cls-1" d="M174.93,36.17v14.5a6.25,6.25,0,0,0,1.13,4,4.64,4.64,0,0,0,3.8,1.37,12.67,12.67,0,0,0,4-.56v8a14.23,14.23,0,0,1-3.14.77,27.8,27.8,0,0,1-4.05.28q-6.19,0-9.25-3.13t-3.07-9V36.17h-5.49V28.36h5.49V20.61l10.56-2.88V28.36h9.23v7.81Z"/><path class="cls-1" d="M195.93,62.36a16.4,16.4,0,0,1-6.59-6.44A19.82,19.82,0,0,1,187,46,19.62,19.62,0,0,1,189.34,36,16.16,16.16,0,0,1,196,29.66a20.47,20.47,0,0,1,9.61-2.22A20,20,0,0,1,215,29.66,16.27,16.27,0,0,1,221.66,36a18.59,18.59,0,0,1,2.39,9.47,20.93,20.93,0,0,1-2.39,10.25A16.31,16.31,0,0,1,215,62.32a19.92,19.92,0,0,1-9.61,2.29A19.68,19.68,0,0,1,195.93,62.36Zm15-9.26c1.4-1.71,2.11-4.19,2.11-7.43a10.39,10.39,0,0,0-2-6.82,6.76,6.76,0,0,0-5.49-2.47A6.91,6.91,0,0,0,200,38.81q-2.08,2.43-2.08,7.08c0,3.09.71,5.5,2.11,7.21a6.76,6.76,0,0,0,5.5,2.57A6.69,6.69,0,0,0,211,53.1Z"/><path class="cls-1" d="M260,30.82q3.34,3.32,3.35,9.43V63.84H252.77V42.51q0-5.85-5.42-5.85a9.83,9.83,0,0,0-7,3.24V63.84H229.75V28.36h9.08l.84,3.23a18.09,18.09,0,0,1,5-3,16.64,16.64,0,0,1,6.24-1.06Q256.63,27.51,260,30.82Z"/><path class="cls-1" d="M315,17.94a14.34,14.34,0,0,1,6.34,5.45,15.08,15.08,0,0,1,2.15,8.06,16.49,16.49,0,0,1-2.36,8.87,15.5,15.5,0,0,1-6.55,5.85,21.66,21.66,0,0,1-9.61,2h-4.08V63.84h-11.2V16h15.21A23.48,23.48,0,0,1,315,17.94Zm-5.1,19.25A7.36,7.36,0,0,0,312,31.81a6.36,6.36,0,0,0-2.15-5.14,8.43,8.43,0,0,0-5.66-1.83h-3.31v14.5h3A8.13,8.13,0,0,0,309.93,37.19Z"/><path class="cls-1" d="M359.82,41.77a11,11,0,0,1,2.85,7.92A12.69,12.69,0,0,1,358,60q-4.65,3.8-13.24,3.8H328V16H343.6q7.74,0,11.86,3.06t4.12,9a11.09,11.09,0,0,1-1.76,6.34A9.54,9.54,0,0,1,353.38,38,12.45,12.45,0,0,1,359.82,41.77Zm-20.66-17.5V35.11h3.24a6.24,6.24,0,0,0,4.54-1.54,5.55,5.55,0,0,0,1.58-4.16,4.8,4.8,0,0,0-1.51-3.8,6.47,6.47,0,0,0-4.4-1.34ZM349.3,53.66a5.89,5.89,0,0,0,1.83-4.61,5.46,5.46,0,0,0-1.87-4.4q-1.86-1.57-5.66-1.58h-4.44V55.32h4.44Q347.47,55.32,349.3,53.66Z"/><path class="cls-1" d="M373,63.84a27.44,27.44,0,0,1-6.19-1.9V51.52A33,33,0,0,0,373,54a23.13,23.13,0,0,0,6.65,1,10.47,10.47,0,0,0,5.67-1.23,3.89,3.89,0,0,0,1.86-3.42,4.28,4.28,0,0,0-2-3.55,40.55,40.55,0,0,0-7.11-3.7q-6.12-2.67-8.9-6a12,12,0,0,1-2.79-8.06,12.3,12.3,0,0,1,2.29-7.43,14.37,14.37,0,0,1,6.3-4.78,23.57,23.57,0,0,1,9.09-1.66,38.32,38.32,0,0,1,6.54.46A43.17,43.17,0,0,1,395.76,17v9.72a29,29,0,0,0-10.28-1.9,11.48,11.48,0,0,0-5.35,1.05,3.4,3.4,0,0,0-2,3.17,3.15,3.15,0,0,0,.95,2.29A10.17,10.17,0,0,0,381.65,33q1.58.81,5.94,2.71,6,2.67,8.49,6.09a13.07,13.07,0,0,1,2.5,7.92,14.12,14.12,0,0,1-2.12,7.71,13.87,13.87,0,0,1-6.23,5.21,24.06,24.06,0,0,1-10,1.86A37.58,37.58,0,0,1,373,63.84Z"/></svg><?php echo str_replace( 'Houston PBS Passport', '', get_the_title()	); ?></h1>
					<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
				</header>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
				<footer class="page-footer">
					<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
		<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>