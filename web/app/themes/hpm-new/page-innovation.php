<?php
/*
Template Name: Innovation-Sustainability
*/
	get_header(); ?>
	<style>
		#main > article {
			grid-column: 1 / -1 !important;
		}
		.page-template-page-innovation article {
			margin-top: 0;
			padding: 0;
		}
		.page-template-page-innovation article .entry-header {
			padding: 0;
			overflow: hidden;
		}
		.page-template-page-innovation article .entry-header img {
			padding: 1em;
			width: 100%;
		}
		.page-template-page-innovation article .entry-header .plan-colorbar {
			height: 0.25em;
			float: left;
		}
		.page-template-page-innovation article .entry-header .plan-colorbar.red {
			background-color: rgb(205,23,49);
			width: 10%;
			clear: both;
		}
		.page-template-page-innovation article .entry-header .plan-colorbar.blue {
			background-color: rgb(8,86,107);
			width: 25%;
		}
		.page-template-page-innovation article .entry-header .plan-colorbar.green {
			background-color: rgb(169,204,69);
			width: 45%;
		}
		.page-template-page-innovation article .entry-header .plan-colorbar.gold {
			background-color: rgb(239,177,66);
			width: 20%;
		}
		.page-template-page-innovation section.i-s-pad {
			padding: 2em 1em;
		}
		.page-template-page-innovation section {
			margin-bottom: 2em;
		}
		.page-template-page-innovation section h2 {
			color: var(--main-red);
			font-size: 225%;
			margin-bottom: 0;
			text-transform: uppercase;
		}
		.page-template-page-innovation section h2 span {
			font-weight: 900;
		}
		.page-template-page-innovation section p {
			font-size: 125%;
			margin-bottom: 1rem;
		}
		.page-template-page-innovation section.i-s-reach p,
		.page-template-page-innovation section.i-s-reach h2 {
			text-align: center;
		}
		.page-template-page-innovation section h3 {
			color: #00566C;
			font-weight: 900;
			font-size: 125%;
			text-align: center;
		}
		.page-template-page-innovation section .i-s-map h2 {
			color: var(--main-red);
			font-weight: 900;
			font-size: 125%;
			margin-bottom: 1.5em;
		}
		.page-template-page-innovation section .i-s-map p {
			font-size: 100%;
			padding-top: 0;
		}
		.page-template-page-innovation hr {
			margin: 0 12.5% 1em;
			width: 75%;
		}
		.page-template-page-innovation section .i-s-legend p {
			text-align: left;
			padding-left: 2.5em;
			display: block;
			position: relative;
		}
		.page-template-page-innovation section .i-s-legend p span {
			width: 2em;
			height: 1em;
			position: absolute;
			left: 0;
			top: 0.625em;
		}
		.page-template-page-innovation section .i-s-legend.i-s-legend-grade p {
			text-align: left;
			padding-left: 0;
			margin-bottom: 0.125em;
		}
		.page-template-page-innovation section .i-s-legend.i-s-legend-grade p strong {
			font-weight: 700;
		}
		.page-template-page-innovation section .i-s-legend.i-s-legend-grade p.i-s-legend-left {
			display: inline-block;
			float: left;
			padding: 0;
			font-weight: 900;
			font-size: 100%;
			margin-bottom: 0.125em;
		}
		.page-template-page-innovation section .i-s-legend.i-s-legend-grade p.i-s-legend-right {
			display: inline-block;
			float: right;
			padding: 0;
			font-weight: 900;
			font-size: 100%;
			margin-bottom: 0.25em;
		}
		.page-template-page-innovation section .i-s-legend.i-s-legend-grade div {
			clear: both;
			width: 100%;
			height: 2em;
			background: rgb(204,204,204);
			background: linear-gradient(to right,  rgba(204,204,204,1) 0%,rgba(204,204,204,1) 0%,rgba(0,0,0,1) 100%);
		}
		.page-template-page-innovation section.i-s-cost {
			text-align: center;
			position: relative;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-text {
			background-color: rgb(227,237,189);
			padding: 2em 1em 0.5em;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-savings {
			position: relative;
			overflow: hidden;
			padding-top: 9em;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-savings:before {
			content: "";
			background-color: rgb(227,237,189);
			transform: rotate(45deg);
			position: absolute;
			width: 66%;
			height: 0;
			padding-bottom: calc(66%/1);
			top: -11em;
			left: 17%;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-savings h2 {
			color: #A9CF38;
			font-weight: 900;
			font-size: 325%;
			text-transform: initial;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-savings h2 span:nth-child(2) {
			color: #6D6E71;
			font-weight: 100;
			font-style: italic;
			font-size: 60%;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-savings h2 span:nth-child(4) {
			color: #6D6E71;
			font-size: 90%;
			font-weight: 700;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-money p:nth-child(n+2) {
			text-align: left;
			padding-left: 4em;
			position: relative;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-money p span {
			font-weight: 700;
			font-size: 200%;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-money p:nth-child(2) span:before {
			content: "";
			position: absolute;
			width: 1.5em;
			height: 1.5em;
			top: 0;
			left: 0.125em;
			background-image: url(https://cdn.hpm.io/assets/images/large_moneybag_icon2x.png);
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		.page-template-page-innovation section.i-s-cost .i-s-cost-money p:nth-child(3) span:before {
			content: "";
			position: absolute;
			width: 1.25em;
			height: 1.25em;
			top: 0;
			left: 0.25em;
			background-image: url(https://cdn.hpm.io/assets/images/moneybag_icon2x.png);
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		.page-template-page-innovation section.i-s-tomorrow {
			padding: 2em 1em;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-title {
			text-align: center;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-img {
			padding: 0 1em 2em;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-invest {
			background-color: #00B0BC;
			padding: 1em;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-invest h3 {
			color: #D4E79B;
			text-align: left;
			margin-bottom: 0;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-invest p {
			color: white;
			text-align: left;
			font-weight: 100;
			margin: 0;
		}
		.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-invest p strong {
			font-weight: 700;
		}
		.page-template-page-innovation section.i-s-today {
			overflow: hidden;
			margin-bottom: 0;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column {
			background-color: #BFD5DA;
			text-align: center;
			float: left;
			width: 50%;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column-top {
			padding: 1em;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column:nth-child(2n) {
			background-color: #CADCE0;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column:nth-child(2n) .i-s-today-column-bottom {
			background-color: #FFF7E6;
		}
		.page-template-page-innovation section.i-s-today h2 {
			color: #006D93;
			font-size: 137%;
			font-family: 'Gotham',var(--hpm-font-main);
			font-weight: bolder;
		}
		.page-template-page-innovation section.i-s-today h3 {
			text-transform: uppercase;
			color: #808285;
			margin: 0;
			font-family: 'Gotham',var(--hpm-font-main);
			font-weight: bolder;
		}
		.page-template-page-innovation section.i-s-today h4 {
			text-transform: uppercase;
			color: #006D93;
			font-family: 'Gotham',var(--hpm-font-main);
			font-weight: bolder;
			font-size: 112.5%;
		}
		.page-template-page-innovation section.i-s-today img {
			width: 50%;
			margin: 0 25%;
		}
		.page-template-page-innovation section.i-s-today p {
			font-size: 110%;
			font-weight: 100;
		}
		.page-template-page-innovation section.i-s-today p span {
			font-weight: 700;
			font-size: 125%;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column-top {
			padding-top: 1em;
			height: 29em;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column-bottom {
			background-color: #FFEFC8;
			padding: 4em 1em 1em;
			height: 15em;
			position: relative;
			overflow: hidden;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column-bottom p {
			margin: 0;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column-bottom:before {
			content: "";
			background-color: #BFD5DA;
			transform: rotate(45deg);
			position: absolute;
			z-index: 0;
			width: 66%;
			height: 0;
			padding-bottom: calc(66%/1);
			top: -6.5em;
			left: 17%;
		}
		.page-template-page-innovation section.i-s-today .i-s-today-column:nth-child(2n) .i-s-today-column-bottom:before {
			background-color: #CADCE0;
		}
		.page-template-page-innovation section.i-s-projected {
			text-align: center;
			background-color: #E7F7F8;
		}
		.page-template-page-innovation section.i-s-projected .i-s-projected-source p {
			font-style: italic;
			font-size: 90%;
		}
		.page-template-page-innovation section.i-s-projected .i-s-projected-source p span {
			font-style: normal;
			font-weight: 700;
		}
		.page-template-page-innovation section.i-s-supporters {
			text-align: center;
		}
		.page-template-page-innovation section.i-s-supporters img {
			margin-bottom: 1em;
		}
		.page-template-page-innovation section.i-s-supporters .i-s-supporters-button {
			font-weight: 700;
			font-size: 1.25em;
			padding: 1em 0.5em;
			width: 100%;
			margin: 0 0 2em;
			background-color: #4FC4CD;
			display: block;
			color: white;
		}
		@media screen and (min-width: 34em) {
			.i-s-flex {
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				padding: 1em 0;
			}
			.page-template-page-innovation section .i-s-map,
			.page-template-page-innovation section .i-s-legend {
				width: 50%;
				padding: 1em;
				float: left;
			}
			.page-template-page-innovation hr {
				clear: both;
			}
			section.i-s-reach,
			section.i-s-tomorrow,
			section.i-s-projected {
				overflow: hidden;
			}
			.i-s-cost-title {
				text-align: right;
			}
			.i-s-cost-text div {
				max-width: 45%;
				padding: 0 1em;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings:before {
				width: 50%;
				padding-bottom: calc(50%/1);
				top: -22em;
				left: 25%;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings {
				padding-top: 8em;
			}
			.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-title {
				text-align: right;
				width: 50%;
				float: left;
				padding: 1em;
			}
			.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-invest {
				width: 50%;
				float: left;
			}
			.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-img {
				width: 50%;
				float: right;
				padding: 5em 1em 0;
			}
			.page-template-page-innovation section.i-s-today .i-s-today-column {
				width: 25%;
			}
			.i-s-projected-title {
				width: 45%;
				float: left;
				text-align: right;
				padding: 1em 1em 1em 0;
			}
			section.i-s-projected img,
			.i-s-projected-source {
				float: right;
				width: 55%;
				padding: 0.5em 0 0 0;
			}
			.page-template-page-innovation section.i-s-supporters img,
			.page-template-page-innovation section.i-s-supporters .i-s-flex p {
				max-width: 50%;
				padding: 0 1em;
				margin: 0;
			}
			.page-template-page-innovation section.i-s-supporters .i-s-flex p {
				text-align: left;
			}
			.page-template-page-innovation section.i-s-supporters .i-s-supporters-button {
				width: 66%;
				margin: 0 17% 2em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-template-page-innovation article .entry-header img {
				width: 75%;
				margin: 0 12.5%;
			}
			.page-template-page-innovation article {
				width: 100%;
				border-right: 0;
				float: none;
			}
			.page-template-page-innovation section.i-s-cost {
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-text {
				width: 60%;
				padding-right: 0;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-title {
				padding: 0;
				max-width: 53%;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-money {
				padding-right: 0;
				max-width: 47%;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings {
				padding: 3em 0 3em 9em;
				width: 40%;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings:before {
				width: 59%;
				padding-bottom: calc(59%/1);
				top: 2.25em;
				left: -8.75em;
			}
			.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-img {
				padding: 1em 4em;
			}
			.page-template-page-innovation section.i-s-today .i-s-today-column-bottom:before {
				top: -9.5em;
			}
			.page-template-page-innovation section.i-s-today .i-s-today-column-bottom {
				height: 12.5em;
			}
			.i-s-projected-title {
				width: 35%;
				padding: 0 1em 0 0;
			}
			section.i-s-projected img {
				width: 60%;
			}
			.i-s-projected-source {
				width: 35%;
				float: left;
				text-align: right;
				padding: 0.5em 1em 0 5em;
			}
			.page-template-page-innovation section.i-s-projected .i-s-projected-source p {
				padding: 0;
				margin: 0;
			}
			.page-template-page-innovation section.i-s-supporters.i-s-pad {
				padding: 2em 3em;
			}
			.page-template-page-innovation section.i-s-supporters img,
			.page-template-page-innovation section.i-s-supporters .i-s-flex p {
				padding: 0 2.5em;
			}
			.page-template-page-innovation section.i-s-supporters .i-s-supporters-button {
				width: 50%;
				margin: 0 25% 2em;
			}
		}
		@media screen and (min-width: 64.0625em) {
			.page-template-page-innovation article .entry-header img {
				width: 66%;
				margin: 0 17%;
			}
			.page-template-page-innovation section h2 {
				font-size: 275%;
			}
			.page-template-page-innovation section.i-s-pad,
			.page-template-page-innovation section.i-s-tomorrow {
				padding: 2em 7em;
			}
			.page-template-page-innovation section.i-s-today img {
				width: 40%;
				margin: 0 30%;
			}
			.page-template-page-innovation section.i-s-today .i-s-today-column-bottom:before {
				top: -11.5em;
			}
			.page-template-page-innovation section.i-s-pad.i-s-projected {
				padding: 2em 1em;
			}
			.page-template-page-innovation section.i-s-supporters.i-s-pad {
				padding: 2em 7em;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings {
				padding: 3em 1em 3em 10em;
			}
			.page-template-page-innovation section.i-s-cost .i-s-cost-savings:before {
				top: 3.25rem;
				left: -12em;
			}
			.page-template-page-innovation section.i-s-tomorrow .i-s-tomorrow-img {
				padding: 1em 0 1em 4em;
			}
			.page-template-page-innovation section.i-s-supporters img {
				padding: 0 4em;
			}
			.page-template-page-innovation section.i-s-supporters .i-s-flex.i-s-supporters-wrap {
				padding-bottom: 2em;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
						the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' );
						the_post_thumbnail( 'full' );
					?>
					<div class="plan-colorbar red"></div>
					<div class="plan-colorbar blue"></div>
					<div class="plan-colorbar green"></div>
					<div class="plan-colorbar gold"></div>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php echo get_the_content(); ?>
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

<?php get_footer(); ?>