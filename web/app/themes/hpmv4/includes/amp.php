<?php
/*
 * Set site icon URL on Google AMP
 */
add_filter( 'amp_post_template_data', 'hpm_amp_set_site_icon_url' );
function hpm_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
    $data[ 'site_icon_url' ] = 'https://cdn.hpm.io/assets/images/favicon/favicon-32.png';
    return $data;
}

/*
 * Modify the JSON metadata present on Google AMP
 */
add_filter( 'amp_post_template_metadata', 'hpm_amp_modify_json_metadata', 10, 2 );
function hpm_amp_modify_json_metadata( $metadata, $post ) {
	$metadata['@type'] = 'NewsArticle';

	$metadata['publisher']['logo'] = [
		'@type' => 'ImageObject',
		'url' => 'https://cdn.hpm.io/wp-content/uploads/2019/01/20130758/HPM_podcast-tile.jpg'
	];
	if ( empty( $metadata['image'] ) ) :
		$metadata['image'] = [
			'@type' => 'ImageObject',
			'url' => 'https://cdn.hpm.io/wp-content/uploads/2019/01/20130758/HPM_podcast-tile.jpg',
			'height' => 1600,
			'width' => 1600
		];
	endif;
	if ( empty( $metadata['headline'] ) ) :
		if ( $post->post_type == 'attachment' && strpos( $post->post_mime_type, 'image' ) !== false ) :
			$headline = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			if ( !empty( $headline ) ) :
				$metadata['headline'] = $headline;
				return $metadata;
			endif;
		endif;
		$metadata['headline'] = $post->post_excerpt;
	endif;
	return $metadata;
}

/*
 * Add Google Analytics to AMP
 */
add_filter( 'amp_post_template_analytics', 'hpm_amp_add_custom_analytics' );
function hpm_amp_add_custom_analytics( $analytics ) {
	if ( ! is_array( $analytics ) ) :
		$analytics = [];
	endif;
	$analytics['hpm-googleanalytics'] = [
		'type' => 'googleanalytics',
		'attributes' => [
			// 'data-credentials' => 'include',
		],
		'config_data' => [
			'vars' => [
				'account' => "UA-3106036-13"
			],
			'triggers' => [
				'trackPageview' => [
					'on' => 'visible',
					'request' => 'pageview',
				],
			],
		],
	];
	$analytics['hpmwebamp-googleanalytics'] = [
		'type' => 'googleanalytics',
		'attributes' => [
			// 'data-credentials' => 'include',
		],
		'config_data' => [
			'vars' => [
				'account' => "UA-3106036-22"
			],
			'triggers' => [
				'trackPageview' => [
					'on' => 'visible',
					'request' => 'pageview',
				],
			],
		],
	];
	return $analytics;
}

add_action( 'amp_post_template_css', 'hpm_amp_additional_css' );

function hpm_amp_additional_css( $amp_template ) {
	?>
	:root {
		--hpm-font-main: 'PBS-Sans',helvetica,arial,sans-serif;
		--hpm-font-condensed: 'PBS-Sans-Condensed',helvetica,arial,sans-serif;
		--max-width: 75rem;
	}
	html {
		--main-red: #C8102E;
		--main-black: #000000;
		--main-blue: #00566C;
		--main-background: #F5F5F5;
		--main-headline: #404040;
		--main-text: #000000;
		--secondary-text: #757575;
		--main-element-background: #FFFFFF;
		--accent-black-1: #404040;
		--accent-black-2: #808080;
		--accent-black-3: #BFBFBF;
		--accent-black-4: #E5E5E5;
		--accent-light-blue-1: #14B0BC;
		--accent-light-blue-2: #4FC4CD;
		--accent-dark-blue-1: #00566C;
		--accent-dark-blue-2: #408091;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.ttf') format('truetype');
		font-display: auto;
		font-weight: 400;
		font-style: normal;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-It.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-It.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-It.ttf') format('truetype');
		font-display: auto;
		font-weight: 400;
		font-style: italic;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium.ttf') format('truetype');
		font-display: auto;
		font-weight: 500;
		font-style: normal;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium-It.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium-It.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Medium-It.ttf') format('truetype');
		font-display: auto;
		font-weight: 500;
		font-style: italic;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light.eot') format('truetype');
		font-display: auto;
		font-weight: 100;
		font-style: normal;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light-It.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light-It.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Light-It.ttf') format('truetype');
		font-display: auto;
		font-weight: 100;
		font-style: italic;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src:url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold.ttf') format('truetype');
		font-display: auto;
		font-weight: 700;
		font-style: normal;
	}
	@font-face {
		font-family: 'PBS-Sans';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold-It.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold-It.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Bold-It.ttf') format('truetype');
		font-display: auto;
		font-weight: 700;
		font-style: italic;
	}
	@font-face {
		font-family: 'PBS-Sans-Condensed';
		src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Cond.woff2') format('woff2'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Cond.woff') format('woff'),
		url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans-Cond.ttf') format('truetype');
		font-display: auto;
		font-weight: 400;
		font-style: normal;
	}

	/* Reset */
	*, *::before, *::after {
		box-sizing: border-box;
	}
	body, h1, h2, h3, h4, p, figure, blockquote, dl, dd {
		margin: 0;
	}
	ul[role=list], ol[role=list] {
		list-style: none;
	}
	html:focus-within {
		scroll-behavior: smooth;
	}
	body {
		min-height: 100vh;
		text-rendering: optimizeSpeed;
		line-height: 1.25;
		width: 100%;
	}
	a:not([class]) {
		text-decoration-skip-ink: auto;
	}
	img, picture {
		max-width: 100%;
		display: block;
		height: auto;
	}
	input, button, textarea, select {
		font: inherit;
	}
	@media (prefers-reduced-motion: reduce) {
		html:focus-within {
			scroll-behavior: auto;
		}
		*, *::before, *::after {
			animation-duration: 0.01ms !important;
			animation-iteration-count: 1 !important;
			transition-duration: 0.01ms !important;
			scroll-behavior: auto !important;
		}
	}
	html {
		scrollbar-color: var(--main-blue) var(--accent-black-4);
	}
	::-webkit-scrollbar {
		background-color: var(--accent-black-4);
	}
	::-webkit-scrollbar-thumb {
		background-color: var(--main-blue);
		color: var(--main-blue);
		border-radius: 10px;
		border: 3px solid var(--accent-black-4);
	}
	::-webkit-scrollbar-corner {
		background-color: var(--accent-black-3);
	}
	:root {
		accent-color: var(--main-blue);
	}
	:focus-visible {
		outline-color: var(--main-blue);
	}
	::selection {
		background-color: var(--main-blue);
		color: white;
	}
	::marker {
		color: var(--main-blue);
	}
	:is(::-webkit-calendar-picker-indicator,::-webkit-clear-button,::-webkit-inner-spin-button,::-webkit-outer-spin-button) {
		color: var(--main-blue);
	}
	small {
		font-size: 80%;
	}
	sub, sup {
		font-size: 75%;
		line-height: 0;
		position: relative;
		vertical-align: baseline;
	}
	sub {
		bottom: -0.25rem;
	}
	sup {
		top: -0.5rem;
	}
	button {
		transition: opacity 0.2s ease-out;
	}
	button:hover {
		opacity: 0.75;
		cursor: pointer;
	}
	body {
		font-weight: 400;
		font-size: 16px;
		font-family: var(--hpm-font-main);
		background-color: var(--main-background);
		min-height: 100vh;
		accent-color: var(--main-red);
	}
	input[type=search] {
	-webkit-appearance: none;
	}
	#page {
		width: 100%;
	}
	#main {
		background-color: var(--main-element-background);
		padding: 0;
		flex-flow: row wrap;
		justify-content: center;
		align-content: flex-start;
		display: flex;
		margin-top: 1rem;
	}
	#content {
		position: relative;
		max-width: var(--max-width);
		margin: 0 auto;
	}
	a {
		text-decoration: none;
		color: var(--accent-light-blue-1);
		font-weight: 500;
	}
	a:hover {
		opacity: 0.75;
		transition: opacity 0.2s ease-out;
		text-decoration: underline;
	}
	a:focus {
		outline: 2px solid rgba(51, 51, 51, 0.3);
	}
	a:hover, a:active {
		outline: 0;
	}
	h1, h2, h3, h4, h5 {
		color: var(--main-text);
		font-weight: 500;
	}
	h1 {
		font-weight: 100;
		font-size: 2.5em;
	}
	h2 {
		font-size: 1.25em;
	}
	h3 {
		font-size: 1.125em;
	}
	h4 {
		font-size: 1em;
	}
	:is(h1,h2) a {
		color: var(--main-headline);
	}
	p {
		color: var(--main-text);
		font-weight: 400;
		font-size: 1em;
		line-height: 1.375;
	}

	ol {
		list-style-type: decimal;
	}
	ol[type="a"] {
		list-style-type: lower-alpha;
	}
	ol[type="A"] {
		list-style-type: upper-alpha;
	}
	ol[type="I"] {
		list-style-type: upper-roman;
	}
	ol[type="i"] {
		list-style-type: lower-roman;
	}
	blockquote {
		padding: 0.25em 0 0.25em 1.5em;
		margin: 0 0 1em 0;
		border-left: 0.125em solid var(--main-background);
	}
	blockquote * {
		padding: 0;
		margin: 0;
	}
	.wide-table {
		width: 100%;
		margin-bottom: 1em;
		border: 1px solid var(--main-background);
	}
	.wide-table td, .wide-table th {
		text-align: center;
		vertical-align: middle;
		padding: 0.5em;
	}
	.wide-table th {
		border: 1px solid var(--main-background);
		border-bottom-width: 2px;
	}
	.wide-table td {
		border: 1px solid var(--main-background);
	}
	details {
		background-color: var(--main-background);
		padding: 0.5rem;
		width: 100%;
	}
	details summary {
		font-weight: 700;
		font-size: 1.25em;
	}
	details summary time {
		font-weight: 400;
	}
	details summary::marker {
		color: var(--main-red);
	}
	details summary:hover {
		cursor: pointer;
	}
	details + details {
		margin-top: 1rem;
	}
	details > * + * {
		margin-top: 1rem;
	}
	details > summary + * {
		margin-top: 1rem;
	}
	.hidden,
	[hidden] {
		display: none !important;
	}
	.sr-only,
	.screen-reader-text,
	.says {
		border: 0;
		clip: rect(0, 0, 0, 0);
		height: 1px;
		margin: -1px;
		overflow: hidden;
		padding: 0;
		position: absolute;
		white-space: nowrap;
		width: 1px;
	}
	.sr-only.focusable:active,
	.sr-only.focusable:focus,
	.screen-reader-text.focusable:active,
	.screen-reader-text.focusable:focus,
	.says.focusable:active,
	.says.focusable:focus {
		clip: auto;
		height: auto;
		margin: 0;
		overflow: visible;
		position: static;
		white-space: inherit;
		width: auto;
	}
	.invisible {
		visibility: hidden;
	}
	.clearfix::before,
	.clearfix::after {
		content: " ";
		display: table;
	}
	.clearfix::after,
	.clear {
		clear: both;
	}
	svg:not(:root) {
		overflow: visible;
	}
	@-ms-viewport {
		width: device-width;
	}
	@viewport {
		width: device-width;
	}

	/* Social Icons */
	.social-wrap {
		display: grid;
		grid-template-columns: repeat(5, 3rem);
		align-items: center;
		justify-content: start;
		gap: 1rem;
		margin-bottom: 1rem;
	}
	.social-wrap :is(h1,h2,h3,h4,h5) {
		margin: 0;
		padding-right: 1rem;
	}
	.social-icon {
		--unit: 3rem;
	}
	.social-icon :is(a,button) {
		display: block;
		background-color: var(--accent-black-3);
		padding: 0.25rem;
		border: 0;
		width: var(--unit);
		height: var(--unit);
		text-align: center;
		border-radius: 0.5rem;
	}
	.social-icon :is(a,button) svg {
		fill: white;
	}
	.social-icon.facebook :is(a,button) {
		background: rgb(59, 89, 152);
	}
	.social-icon.twitter :is(a,button) {
		background: rgb(29, 161, 242);
	}
	.social-icon.youtube :is(a,button) {
		background: rgb(234, 50, 35);
	}
	.social-icon.instagram :is(a,button) {
		background: rgb(81, 91, 212);
		background: linear-gradient(135deg, rgb(81, 91, 212) 0%, rgb(129, 52, 175) 20%, rgb(221, 42, 123) 50%, rgb(254, 218, 119) 70%, rgb(245, 133, 41) 90%);
	}
	.social-icon.linkedin :is(a,button) {
		background: rgb(40, 103, 178);
	}
	.social-icon:last-child :is(a,button) {
		margin-right: 0;
	}
	.podcast-badges {
		list-style: none !important;
		margin: 0;
		padding: 0;
		display: flex;
		flex-flow: row wrap;
		gap: 0.5rem;
		justify-content: flex-start;
	}
	.podcast-badges li {
		width: 3rem;
		margin-top: 0 !important;
	}
	.podcast-badges .social-icon {
		--unit: 3rem;
	}
	.podcast-badges .social-icon a,
	.podcast-badges .social-icon button {
		margin: 0;
	}
	.single:not(.single-shows) .podcast-badges {
		justify-content: flex-start;
	}
	.podcast-episode-info {
		background-color: #eee;
		padding: 1rem;
		width: 100%;
	}
	.podcast-episode-info > * + * {
		margin-top: 1rem;
	}
	@media screen and (min-width: 52.5em) {
		.social-wrap {
			align-items: start;
			margin-bottom: 0;
		}
		.social-wrap :is(h1,h2,h3,h4,h5) {
			padding: 0;
			grid-column: 1/-1;
		}
		.social-icon {
			--unit: 3.5rem;
		}
		.site-footer .social-icon {
			--unit: 3rem;
		}
	}

	/* iFrame Sizing */
	.iframe-embed {
		position: relative;
		width: 100%;
		padding: 0 !important;
		padding-bottom: calc(100% / 1.777778) !important;
		display: block;
		margin-bottom: 1em;
	}
	.iframe-embed-tall {
		position: relative;
		width: 100%;
		padding: 0 !important;
		padding-bottom: calc(100% / 1.25) !important;
		display: block;
		margin-bottom: 1em;
	}
	.iframe-embed-vert {
		position: relative;
		width: 100%;
		padding: 0 !important;
		padding-bottom: calc(100% / 0.5625) !important;
		display: block;
		margin-bottom: 1em;
	}
	.iframe-embed-pbs {
		position: relative;
		width: 100%;
		padding: 0 !important;
		padding-bottom: calc(100% / 1.425) !important;
		display: block;
		margin-bottom: 1em;
	}
	:is(.iframe-embed,.iframe-embed-tall,.iframe-vert,.iframe-embed-pbs) iframe {
		display: block;
		top: 0;
		left: 0;
		position: absolute;
		width: 100%;
		height: 100%;
		border: 0;
	}

	/* Misc Utilities */
	.table-striped > tbody > tr:nth-of-type(odd) {
		background-color: var(--main-background);
	}
	.table-striped th {
		text-align: center;
		font-size: 85%;
	}
	.table-striped tbody tr td:nth-child(n+2) {
		text-align: center;
	}
	.table-striped th, .table-striped td {
		padding: 0.5em;
	}
	.table-striped {
		width: 100%;
	}
	article .entry-content ul.timeline,
	ul.timeline {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	article .entry-content ul.timeline li,
	ul.timeline li {
		padding: 0 0 1em 1.125em;
		position: relative;
		border-left: 0.0625em solid rgba(0,0,0,0.25);
		margin: 0
	}
	article .entry-content ul.timeline li:last-child,
	ul.timeline li:last-child {
		border-left: 0;
	}
	article .entry-content ul.timeline li:before,
	ul.timeline li:before {
		content: '';
		position: absolute;
		left: -0.5em;
		top: 0.25em;
		background-color: #C2C2C2;
		transition: background-color 0.5s;
		width: 1rem;
		height: 1rem;
		border-radius: 1rem;
	}
	article .entry-content ul.timeline li:hover:before,
	ul.timeline li:hover:before {
		background-color: #808080;
		transition: background-color 0.5s;
	}
	article .entry-content ul.timeline li .timeline-date,
	ul.timeline li .timeline-date {
		font-weight: 700;
		font-size: 1.25em;
	}

	/* Masthead and Navigation */
	#masthead {
		width: 100%;
		margin: 0 auto;
	}
	#masthead .site-branding {
		background-color: var(--main-red);
		display: grid;
		grid-template-columns: 1fr 5rem;
	}
	svg.hpm-logo .hpm-logo-text {
		font-family: var(--hpm-font-main);
		font-size: 91px;
		font-weight: 500;
		letter-spacing: -1px;
	}
	svg.hpm-logo .hpm-logo-service {
		font-family: var(--hpm-font-main);
		font-size: 35.5px;
		font-weight: 500;
		letter-spacing: 4px;
	}
	#masthead .site-branding .site-logo {
		padding: 0 1rem;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	#masthead .site-branding .site-logo a {
		display: block;
		width: 100%;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo {
		max-height: 3.5rem;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo path {
		fill: white;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo :is(.hpm-logo-text,.hpm-logo-service) {
		fill: white;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo .cls-2 {
		fill: #da252b;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo .cls-3 {
		fill: #1e7fc3;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo :is(.cls-4,.cls-6) {
		fill: #fff;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo .cls-5 {
		fill: white;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo :is(.cls-5,.cls-6) {
		fill-rule: evenodd;
	}
	#masthead .site-branding .site-logo a svg.hpm-logo .cls-6 {
		fill: var(--main-blue);
	}
	#masthead .site-branding :is(div,section):not(.site-logo) svg {
		fill: white;
	}
	#masthead .top-mobile-text {
		font-size: 75%;
		text-transform: uppercase;
		position: relative;
		top: -0.625rem;
	}
	#masthead #top-donate > a {
		height: 5rem;
		width: 5rem;
		background-color: #A40E26;
		text-align: center;
		color: #fff;
		padding: 0.5rem 1rem;
		display: block;
	}
	#masthead section {
		justify-content: center;
		align-items: center;
		width: 100%;
		grid-column: 1/span 3;
		grid-row: 3;
		display: grid;
		grid-template-columns: 33.3333% 33.3333% 33.3333%;
	}
	#masthead section button {
		display: flex;
		flex-flow: row nowrap;
		gap: 0 0.5rem;
		align-items: center;
		justify-content: center;
		color: white;
		font-size: 1.25rem;
		font-family: var(--hpm-font-condensed);
		padding: 0.5rem 0.375rem;
		width: 100%;
		background-color: var(--accent-black-1);
		border: 0;
		border-radius: 0;
		margin: 0;
	}
	#masthead section button svg {
		width: 1.5rem;
		overflow: visible;
	}
	#masthead #top-watch button {
		background-color: var(--accent-dark-blue-1);
	}
	#masthead #top-schedule button {
		background-color: var(--accent-light-blue-1);
	}
	#masthead #top-schedule {
		position: relative;
	}
	#masthead #top-schedule .top-schedule-link-wrap {
		position: absolute;
		top: 100%;
		width: 300%;
		justify-content: center;
		align-items: center;
		transform: rotateX(-90deg);
		transform-origin: top center;
		opacity: 0.3;
		transition: 280ms all 120ms ease-out;
		display: flex;
		z-index: 50;
	}
	#masthead #top-schedule:hover .top-schedule-link-wrap, #masthead #top-schedule:focus-within .top-schedule-link-wrap {
		opacity: 1;
		transform: rotateX(0);
		visibility: visible;
	}
	#masthead #top-schedule .top-schedule-links {
		width: 25%;
		text-align: center;
	}
	#masthead #top-schedule .top-schedule-links a {
		font-size: 0.925rem;
		font-family: var(--hpm-font-condensed);
		color: white;
		display: block;
		padding: 0.25rem;
		border-left: 1px solid white;
		background-color: var(--accent-dark-blue-1);
	}
	#masthead #top-schedule .top-schedule-links:nth-child(1) a {
		border-left: 0;
	}
	#masthead #top-schedule div.top-schedule-label {
		width: 100%;
		display: block;
		text-align: center;
	}

	/* Footer */
	footer#colophon {
		width: 100%;
		background-color: #404040;
		display: flex;
		justify-content: center;
		flex-flow: column nowrap;
	}
	footer#colophon section {
		max-width: var(--max-width);
		margin: 0 auto;
		padding: 1rem;
		display: flex;
		flex-flow: row wrap;
		justify-content: space-between;
	}
	footer#colophon section > div {
		width: 100%;
		margin-top: 2rem;
	}
	footer#colophon section > * + * {
		margin-top: 2rem;
	}
	footer#colophon .foot-logo a {
		width: 75%;
		margin: 0 auto;
		display: block;
	}
	footer#colophon .foot-logo a svg.hpm-logo path {
		fill: white;
	}
	footer#colophon .foot-logo a svg.hpm-logo :is(.hpm-logo-text,.hpm-logo-service) {
		fill: white;
	}
	footer#colophon .foot-logo a svg.hpm-logo .cls-2 {
		fill: #da252b;
	}
	footer#colophon .foot-logo a svg.hpm-logo .cls-3 {
		fill: #1e7fc3;
	}
	footer#colophon .foot-logo a svg.hpm-logo :is(.cls-5,.cls-6) {
		fill-rule: evenodd;
	}
	footer#colophon .foot-logo a svg.hpm-logo .cls-6 {
		fill: var(--main-blue);
	}
	footer#colophon .social-wrap {
		justify-content: center;
		margin-bottom: 1rem;
	}
	footer#colophon p {
		padding: 0 0 1rem;
		margin: 0;
		color: #fff;
	}
	footer#colophon p a {
		font-weight: 700;
		color: #fff;
	}
	footer#colophon .foot-tag {
		padding: 1rem 2rem 0;
		text-align: center;
		border-top: 1px solid #fff;
	}
	footer#colophon .foot-contact {
		border-top: 1px solid rgba(255, 255, 255, 0.25);
		padding-top: 1.5rem;
		width: 100%;
		text-align: center;
	}
	footer#colophon .foot-contact p {
		padding: 0 0 1rem;
	}
	footer#colophon .foot-contact p.foot-button {
		margin-bottom: 0.5rem;
		font-size: 1rem;
	}
	footer#colophon .foot-contact p.foot-button a {
		padding: 0.5rem;
		background-color: var(--main-red);
		font-size: 1.125rem;
	}
	footer#colophon .foot-newsletter a {
		color: var(--accent-light-blue-2);
	}
	footer#colophon h3 {
		color: white;
		border-bottom: 1px solid #fff;
		padding-bottom: 0.25rem;
		margin-bottom: 0.5rem;
		font-size: 1.25rem;
	}
	footer#colophon h4 {
		margin-bottom: 0;
	}
	footer#colophon ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	footer#colophon ul li {
		color: white;
		padding: 0.25rem 0;
	}
	footer#colophon ul li a {
		color: white;
		text-decoration: none;
		display: block;
		padding: 0.25rem 0;
	}
	@media screen and (min-width: 34em) {
		footer#colophon section > div {
			width: 32%;
			margin: 1rem 0;
		}
		footer#colophon section > div.foot-logo {
			width: 100%;
		}
		footer#colophon .foot-logo a {
			width: 50%;
		}
		footer#colophon #footer-social {
			margin-bottom: 0;
		}
	}
	@media screen and (min-width: 52.5em) {
		footer#colophon section > div {
			width: 23%;
		}
		footer#colophon section > div.foot-logo {
			width: 23%;
		}
		footer#colophon section > div.foot-logo a {
			width: 90%;
		}
		footer#colophon .foot-contact {
			width: 100%;
			margin: 0 auto;
			align-items: center;
			display: grid;
			gap: 1rem;
			grid-template-columns: 1fr 1fr 1fr;
		}
		footer#colophon .foot-contact p, footer#colophon .foot-contact p.foot-button {
			padding: 0;
			margin: 0;
		}
		footer#colophon .foot-contact p.foot-button {
			text-align: left;
		}
		footer#colophon .foot-contact .social-wrap {
			margin: 0;
			justify-content: end;
		}
		footer#colophon .foot-contact .social-wrap .social-icon a {
			margin-bottom: 0;
		}
	}

	/* Sidebar Highlights */
	.highlights {
		margin-bottom: 1rem;
	}
	.highlights h4 {
		background-color: var(--main-red);
		display: inline-block;
		padding: 0.3125rem;
		text-transform: uppercase;
		color: white;
		font-weight: 700;
		margin: 0 0 1rem 0;
	}
	.highlights h4 a {
		color: white;
		text-decoration: underline;
	}
	.highlights ul {
		margin: 0 0 0 1rem;
		padding: 1rem;
	}
	.highlights ul h2 {
		margin-bottom: 0.75rem;
		font-size: 1.125rem;
	}
	.highlights h2 {
		margin-bottom: 1rem;
		font-size: 1.25rem;
	}
	.highlights h2 a {
		font-weight: 100;
	}
	.highlights article {
		display: flex;
		flex-flow: row nowrap;
		align-items: center;
		justify-content: flex-start;
		padding: 1rem;
		gap: 1rem;
		background-color: var(--main-element-background);
	}
	.highlights article .post-thumbnail {
		display: block;
	}
	.highlights article .card-content {
		display: flex;
		flex-flow: column nowrap;
		justify-content: center;
		flex: 1 3 auto;
		min-width: 60%;
	}
	.highlights article .card-content .entry-header {
		padding: 0;
	}
	.highlights article .card-content .entry-header h3 {
		font-size: 1rem;
	}
	.highlights article.card .entry-header h2 {
		margin: 0;
	}
	.highlights article + article {
		margin-top: 1rem;
	}
	.highlights ul.nav-menu li {
		padding: 0.5rem 0;
	}
	.highlights ul.nav-menu li a {
		color: rgb(131, 133, 133);
		font-weight: 500;
		font-size: 1.25rem;
	}

	/* Article Styling */
	article {
		margin: 0;
		width: 100%;
		padding: 1em;
		background-color: white;
	}
	article .entry-header {
		padding: 1em 0;
		width: 100%;
	}
	article:not(.type-staff) :is(.entry-header,.entry-content,.card-content):not(.article-player-wrap) > * + * {
		margin-top: 1.25rem;
	}
	article:not(.type-staff) :is(.entry-header,.entry-content,.card-content):not(.article-player-wrap) > :is(div,section) > * + * {
		margin-top: 1.25rem;
	}
	article .entry-header h3 {
		background-color: transparent;
		color: var(--secondary-text);
		display: inline-block;
		text-transform: uppercase;
		font-weight: 700;
	}
	article .entry-header h1 {
		font-family: var(--hpm-font-condensed);
	}
	article .entry-header p {
		font-weight: 500;
		font-size: 1.25em;
		color: #646464;
	}
	article .entry-header .byline-date {
		font-size: 1em;
		padding: 0.25em 0;
		text-transform: uppercase;
		color: #646464;
	}
	article .entry-header .byline-date .posted-on {
		padding-left: 0.125em;
	}
	article .entry-header .byline-date .byline {
		padding-right: 0.125em;
	}
	article .entry-header .byline-date a {
		font-weight: 500;
	}
	article .entry-header .byline-date address {
		display: inline-block;
		font-style: normal;
	}
	article .entry-content img, .mceMediaCreditOuterTemp {
		margin: 0;
		max-width: 100%;
	}
	article .entry-content #map-canvas :is(img,picture) {
		max-width: initial;
	}
	article .entry-content .caption {
		background-color: #eeeeee;
		padding: 0.75em;
		font-style: italic;
		font-size: 0.925em;
		text-align: center;
	}
	article .entry-content :is(p.correction,.npr-transcript) {
		padding: 1em;
		background-color: var(--main-background);
		margin-bottom: 1em;
	}
	article .entry-content p a {
		text-decoration: underline;
	}
	:is(article .entry-content,.page-content,.show-content) ul {
		list-style: disc outside none;
	}
	:is(article .entry-content, .page-content) :is(ol li, ul li) {
		clear: both;
		margin: 0.5em 0;
	}
	article .entry-content blockquote.pullquote {
		padding: 0;
		margin: 2.5em;
	}
	article .entry-content blockquote.pullquote p {
		font-size: 1.5em;
		font-family: var(--hpm-font-condensed);
		margin: 0 0 1em 0;
		padding: 0;
		color: var(--secondary-text);
	}
	article .entry-content blockquote.pullquote p a {
		font-style: italic;
		font-weight: 700;
		font-size: 1em;
	}
	article .post-thumbnail {
		display: block;
	}
	article .post-thumbnail :is(img,picture) {
		height: max(18vh, 12rem);
		object-fit: cover;
		width: 100%;
	}
	@supports (aspect-ratio: 1) {
		article .post-thumbnail :is(img,picture) {
			aspect-ratio: 3/2;
			height: auto;
		}
		article.podcasts .post-thumbnail :is(img,picture) {
			aspect-ratio: 1;
		}
	}
	article .entry-footer .tags-links a {
		font-weight: 100;
		padding: 0.625em;
		background-color: rgb(244,244,244);
		text-transform: capitalize;
		float: left;
		margin: 0 0.625em 0.5em 0;
	}
	figure {
		width: 100% !important;
		margin: 0 auto 1em;
		max-width: 100%;
		clear: both;
		background-color: var(--main-background);
	}
	figure img, figure iframe {
		max-width: 100%;
	}
	#embeds {
		margin: 0 0 2em 0;
	}
	figure figcaption {
		font-size: 0.9em;
		color: #404144;
		padding: 1em;
		margin: 0 !important;
	}
	figure figcaption cite {
		display: block;
		text-align: right;
		font-style: italic;
		padding-top: 0.25em;
	}
	figure.npr-container,
	figure.wp-block-embed {
		padding: 1em;
	}
	.single #main article.post {
		order: 1;
	}
	.single #main aside {
		order: 3;
		padding: 1em;
	}
	.single #main #author-wrap {
		order: 2;
	}
	aside > * + * {
		margin-top: 1rem;
	}
	.highlights article .entry-summary,
	article.card .entry-summary {
		border: 0;
		clip: rect(0, 0, 0, 0);
		height: 1px;
		margin: -1px;
		overflow: hidden;
		padding: 0;
		position: absolute;
		white-space: nowrap;
		width: 1px;
	}
	.credits-overlay {
		display: none;
	}
	.credits-container {
		position: relative;
		margin: 0 !important;
		padding: 0 !important;
	}
	.credits-container .credits-overlay {
		margin: 0;
		padding: 0 0 0.25em 0;
		background-color: transparent;
		text-align: right;
		box-sizing: border-box;
		display: block;
		overflow: hidden;
		font: italic 0.75em/1.125em var(--hpm-font-main);
		color: #404144;
		max-width: 100% !important;
	}
	.credits-container:hover .credits-overlay {
		opacity: 0.9;
	}
	.credits-container:hover .credits-overlay * {
		opacity: 1;
	}
	.credits-container .credits-overlay p {
		margin: 0;
	}
	.image-credits {
		clear: both;
	}
	.credits-container .credits-overlay a {
		color: #404144;
		text-decoration: none;
	}
	.credits-container .credits-overlay a:hover {
		text-decoration: underline;
	}
	body.single #author-wrap .author-other-stories ul {
		margin: 0 0 1rem 1rem;
	}
	body.single #author-wrap .author-other-stories h2 a {
		color: var(--accent-black-1);
		font-weight: 100;
		font-size: 1.125rem;
	}
	#author-wrap {
		padding: 1rem 0;
		background-color: var(--main-background);
		width: 100%;
	}
	#author-wrap h4 {
		display: inline-block;
		padding: 0.25em 0;
		text-transform: uppercase;
		color: rgb(85,86,90);
		font-size: 1.25em;
	}
	#author-wrap h3 {
		text-transform: uppercase;
	}
	#author-wrap p {
		font-size: 1rem;
	}
	#author-wrap p + p {
		margin-top: 1rem;
	}
	#author-wrap .author-inner-wrap {
		background-color: var(--main-element-background);
		padding: 1rem;
	}
	#author-wrap .author-inner-wrap + .author-inner-wrap {
		margin-top: 1.5rem;
	}
	#author-wrap .author-image {
		width: 66%;
		margin: 0 auto 1rem;
	}
	#author-wrap .author-info {
		padding-bottom: 1rem;
	}
	#author-wrap .author-info > * + * {
		margin: 0.5rem 0 0 0;
	}
	#author-wrap .author-thumb {
		width: 66%;
		padding: 0.75em 1em 0.5em 0;
	}
	@media screen and (min-width: 34em) {
		.column-left,
		.column-span,
		.column-third,
		.column-right {
			width: 97.5%;
			margin: 0 1.25%;
			overflow: hidden;
		}
		aside section {
			width: 47.5%;
			margin-left: 1.25%;
			margin-right: 1.25%;
		}
		.highlights article.card .entry-header h2 {
			font-size: 1.125rem;
		}
		:is(.column-left,.article-wrap) article.card {
			width: 47.5%;
			margin-left: 1.25%;
			margin-right: 1.25%;
		}
		#author-wrap .author-inner-wrap {
			display: flex;
			gap: 1rem;
		}
		#author-wrap .author-image {
			display: block;
			width: 33%;
			padding: 0;
		}
		#author-wrap .author-info {
			min-width: 66%;
			flex: 1;
			padding: 0;
		}
		#author-wrap .author-image img {
			width: 100%;
			max-width: 100%;
		}
		#author-wrap .author-info-wrap {
			display: flex;
			flex-flow: row-reverse nowrap;
			gap: 1rem;
		}
		#author-wrap .author-other-stories {
			width: 40%;
		}
		#author-wrap .author-info-wrap {
			width: 60%;
		}
		#author-wrap .author-thumb {
			width: 100%;
		}
	}
	@media screen and (min-width: 840px) {
		.column-third {
			float: left;
			margin: 0 1% 1rem;
			width: 31%
		}
		aside, .column-right {
			width: 33%;
			margin: 0;
			float: right;
			padding: 1rem;
		}
		aside section {
			width: 100%;
			margin-left: 0;
			margin-right: 0;
		}
		:is(.single,.page-template-page-npr-articles) #main > article.post {
			width: 65%;
			margin: 0 2% 0 0;
		}
		:is(.single,.page-template-page-npr-articles) #main aside {
			order: 2;
			border-left: 1px solid var(--main-background);
		}
		:is(.single,.page-template-page-npr-articles) #main #author-wrap {
			order: 3;
			padding: 1rem 0;
		}
		#author-wrap .author-thumb {
			width: 100%;
		}
		#author-wrap .author-inner-wrap {
			padding: 2rem;
			gap: 2rem;
		}
		.alignleft {
			float: left;
			width: 47.5% !important;
			margin: 0 2.5% 1rem 0;
		}
		figure.wp-caption.alignleft {
			float: left;
			width: 47.5% !important;
			margin: 0 2.5% 1rem 0;
		}
		.alignright,
		figure.wp-caption.alignright {
			float: right;
			width: 47.5% !important;
			margin: 0 0 1rem 2.5%;
		}
		.aligncenter,
		figure.wp-caption.aligncenter {
			margin: 0 auto 1em;
			text-align: center;
		}
		:is(.alignright,.alignleft,.aligncenter,.alignnone) :is(img,picture) {
			margin: 0 auto;
			max-width: 100%;
		}
		.alignright .alignright,
		.alignleft .alignleft,
		.aligncenter .aligncenter {
			float: none;
			margin: 0;
			width: 100% !important;
		}

	}

	/* Revue Embed */
	#revue-embed {
		margin: 3em 0;
		padding: 1em;
		background-color: var(--main-background);
		font-size: 90%;
	}
	#revue-embed h2 {
		padding: 0;
		color: var(--main-red);
	}
	#revue-embed .revue-small {
		display: inline-block;
		float: left;
		font-style: italic;
		font-size: 95%;
	}
	#revue-embed #revue-form {
		overflow: hidden;
	}
	#revue-embed #revue-form .revue-form-group {
		width: 100%;
		padding-bottom: 0.5em;
		display: flex;
		flex-flow: row nowrap;
		align-content: center;
		align-items: center;
	}
	#revue-embed #revue-form label {
		padding-right: 0.5em;
	}
	#revue-embed #revue-form input {
		flex-grow: 2;
	}
	#revue-embed #revue-form input[type="submit"] {
		border: 0;
		outline: 0;
		background-color: var(--main-red);
		color: white;
		font-weight: bolder;
		font-size: 125%;
		padding: 0.5em;
		float: right;
	}
	#revue-embed * + * {
		margin-top: 0;
	}
	#revue-embed :is(h2,p,form) {
		margin-top: 1rem;
	}
	@media screen and (min-width: 34em) {
		#revue-embed #revue-form .revue-form-group:nth-child(2) {
			width: 50%;
			float: left;
			padding-right: 0.5em;
		}
		#revue-embed #revue-form .revue-form-group:nth-child(3) {
			width: 50%;
			float: left;
			padding-left: 0.5em;
		}
	}
	<?php
}