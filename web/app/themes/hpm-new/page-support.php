<?php
/*
Template Name: Support Page
*/
get_header(); ?>
<style>
#main > article {
	grid-column: 1 / -1 !important;
}
.page.support-ssac .entry-content .ssac-wrap {
	padding: 0 1em 2em;
	max-width: 55em;
	margin: 0 auto;
}
.page.support-ssac .entry-content .major-giving-societies p {
	margin: 0;
	padding: 0.5em 0;
}
.page.support-ssac .major-giving-societies {
	padding: 2em 0;
	overflow: hidden;
}
.page.support-ssac .alignleft img {
	width: 75%;
	margin: 0 12.5%;
}
.page.support-ssac section.ssac-engage .alignleft img {
	width: 15%;
	margin: 0;
	padding: 0 0.25em 0 0;
	position: relative;
	bottom: -5px;
}
.page.support-ssac h2 {
	font-weight: 700;
	font-size: 1.25rem;
	border-bottom: 1px solid #707070;
	margin-bottom: 1em;
	padding: 1em 0.5em 0.5em;
	text-align: center;
}
.support-ssac table {
	padding: 0 1em 1em;
	width: 750px;
}
.support-ssac .ssac-scroll {
	overflow-x: scroll;
	max-width: 100%;
}
.support-ssac th {
	border-bottom: 5px solid transparent;
	font-weight: 100;
	font-size: 1em;
	width: 15%;
}
.support-ssac th:first-child {
	width: 25%;
}
.support-ssac th span {
	font-weight: 700;
	font-size: 1em;
}
.support-ssac tbody tr:nth-child(2n-1) {
	background-color: #efefef;
}
.support-ssac th, .support-ssac td {
	text-align: center;
	color: rgb(89, 89, 91);
	padding: 0.75em 0.5em;
	vertical-align: middle;
}
.support-ssac th.ssac-table-5,
.support-ssac td.ssac-table-5 {
	color: #D8002B;
	border-bottom-color: #D8002B;
}
.support-ssac th.ssac-table-1,
.support-ssac td.ssac-table-1,
.support-ssac th.ssac-table-2,
.support-ssac td.ssac-table-2,
.support-ssac th.ssac-table-3,
.support-ssac td.ssac-table-3,
.support-ssac th.ssac-table-4,
.support-ssac td.ssac-table-4 {
	color: #00566C;
	border-bottom-color: #00566C;
}
.page.support-ssac article .entry-content .major-giving-contacts img {
	width: 50%;
	margin: 0 25%;
}
.page.support-ssac .major-giving-contacts .adv-contact {
	padding: 1em 0 2em;
}
.page.support-ssac h2.ssac-head {
	text-align: center;
	color: var(--main-red);
	border-bottom: 1px solid #59595B;
}
.page.support-ssac section h1 {
	font-weight: 500;
	font-size: 1.25em;
	margin: 0;
	color: #59595B;
}
.page.support-ssac section h2 {
	padding: 0;
	margin: 0 0 0.5em 0;
	font-weight: 100;
	font-size: 1.25em;
	color: #59595B;
	border: 0;
	text-align: left;
}
.page.support-ssac article .entry-content p {
	font-weight: 100;
	font-size: 1.125em;
	margin: 0;
	padding: 0 0 1em;
	color: #707070;
}
.page.support-ssac article .entry-content p a {
	color: #59595B;
	text-decoration: none;
}
.page.support-ssac article .entry-content p a.ss-give {
	color: white;
	font-weight: 500;
	font-size: 1.5em;
	background-color: var(--main-red);
	padding: 0.5em 0;
	width: 50%;
	display: block;
	margin: 0 25% 0.25em;
}
h2.ss-join {
	color: #11A6B3;
	text-transform: uppercase;
	text-align: center;
	border-bottom: 1px solid #11A6B3;
	margin-bottom: 1em;
	padding: 1em 0 0.5em;
}
.page.support-ssac section.ssac-engage {
	padding: 1em 0;
}
.page.support-ssac section.ssac-engage h2 {
	font-weight: 700;
	font-size: 1.5em;
	color: var(--accent-light-blue-1);
	background-image: url(https://cdn.hpm.io/assets/images/color_border2x.png);
	background-repeat: no-repeat;
	padding: 0 0 0.5em 0.5em;
	background-position: bottom;
	background-size: 100%;
}
.page.support-ssac section.ssac-engage.ssac-ss h2 {
	color: var(--main-red);
}
.page.support-ssac section.ssac-engage h2 span {
	font-weight: 100;
	font-size: 1em;
	color: #59595B;
}
.page.support-ssac section.ssac-engage ul {
	padding: 0;
	margin: 0;
	list-style: none;
	width: 100%;
}
.page.support-ssac section.ssac-engage ul li {
	padding: 1em;
	width: 100%;
}
.page.support-ssac section.ssac-engage h3 {
	color: var(--accent-light-blue-1);
	font-size: 150%;
}
.page.support-ssac section.ssac-engage p.ssac-aside {
	font-size: 100%;
	color: var(--main-red);
	font-style: italic;
	clear: both;
}
.page.support-ssac section.ssac-engage h4 {
	color: #00566C;
	font-size: 125%;
	font-weight: 700;
}
.page.support-ssac section.ssac-engage p.ssac-disclaim {
	font-size: 100%;
	color: #00566C;
	font-style: italic;
	padding: 0 1em 1em 1em;
}
.page.support-ssac article .entry-content p a.ss-give.ss-member {
	background-color: #016D94;
	width: 80%;
	margin: 0 10% 0.25em;
	font-size: 125%;
	clear: both;
}
.page.support-ssac article.support-members .entry-content {
	padding: 0 1em;
}
.page.support-ssac article.support-members .entry-content .column-span {
	margin: 0 0 1em 0;
	width: 100%;
}
.page.support-ssac article.support-members .entry-content ul {
	margin: 0;
	padding: 0;
	list-style: none;
}
.page.support-ssac article.support-members .entry-content ul li {
	margin: 0 0 0.5em;
	font-weight: 500;
	color: #59595B;
}
.page.support-ssac article.support-members .entry-content p {
	display: none;
}
.page.support-ssac article.support-members .entry-content h3 {
	font-size: 150%;
}
.page.support-ssac article.support-members .entry-content .ss-visionary-leader h3,
.page.support-ssac article.support-members .entry-content .ss-visionary-foundation h3 {
	color: var(--main-red);
}
.page.support-ssac article.support-members .entry-content .ss-foundation-champions h3 {
	color: #F2B233;
}
.page.support-ssac article.support-members .entry-content .ss-affinity-council h3,
.page.support-ssac article.support-members .entry-content .ss-members h3 {
	color: #016D94;
}
.page .year-select-wrap {
	flex-flow: row nowrap;
	justify-content: center;
	align-content: center;
	align-items: center;
	display: flex;
	border-bottom: 0.125em solid #808080;
	margin-bottom: 2em;
}
.page .year-select {
	padding: 0.5em 1em;
	border-bottom: 0.1675em solid transparent;
	color: #016D94;
	font-weight: 700;
	font-size: 1.5em;
	position: relative;
	bottom: -0.125em;
}
.page .year-select:hover {
	cursor: pointer;
	opacity: 0.75;
}
.page .year-select.active {
	border-bottom: 0.1675em solid #016D94;
}
.page .year-select.active:hover {
	opacity: 1;
	cursor: arrow;
}
.page .years {
	display: none;
}
.page .years.active {
	display: block;
}
@media screen and (min-width: 23em) {
	.page.support-ssac article .entry-content .adv-contact p {
		padding: 0 0 0 1.5em;
	}
	.page.support-ssac article .entry-content p.phone {
		background-image: url(https://cdn.hpm.io/assets/images/phone_icon2x.png);
		background-size: 1em;
		background-repeat: no-repeat;
		background-position: 0;
	}
	.page.support-ssac article .entry-content p.email {
		background-image: url(https://cdn.hpm.io/assets/images/email_icon2x.png);
		background-size: 1em;
		background-repeat: no-repeat;
		background-position: 0 8px;
	}
}
@media screen and (min-width: 34em) {
	.page.support-ssac section.ssac-engage .alignleft {
		width: 50%;
		margin: 0;
		float: left;
		padding: 0 2em 0 0;
	}
	.support-ssac table {
		width: 100%;
	}
	.support-ssac .ssac-scroll {
		overflow-x: visible;
	}
	.page.support-ssac .major-giving-contacts {
		overflow: hidden;
	}
	.page.support-ssac .major-giving-contacts .adv-contact {
		float: left;
		width: 50%;
		padding: 1em 1em 2em;
	}
	.page.support-ssac article .entry-content p a.ss-give.ss-member {
		width: 60%;
		margin: 0 20% 0.25em;
	}
	.page.support-ssac article.support-members .entry-content .column-third {
		float: left;
		margin: 0 0.75% 1em;
		width: 31.5%;
	}
	.page.support-ssac article.support-members .entry-content ul {
		margin: 0;
		padding: 0;
	}
	.page.support-ssac article.support-members .entry-content .column-span {
		margin: 0 0 1em 0;
		padding-left: 5em;
		position: relative;
		background-position: 1em 0.125em;
		background-size: 3.25em;
		background-repeat: no-repeat;
	}
	.page.support-ssac article.support-members .entry-content .ss-visionary-leader {
		background-image: url(https://cdn.hpm.io/assets/images/visionary_icon2x.png);
	}
	.page.support-ssac article.support-members .entry-content .ss-foundation-champions {
		background-image: url(https://cdn.hpm.io/assets/images/foundation_champions2x.png);
	}
}
@media screen and (min-width: 52.5em) {
	.page.support-ssac .major-giving-societies {
		padding: 2em;
	}
	.page.support-ssac .major-giving-contacts {
		padding: 0 2em;
	}
	.page.support-ssac .major-giving-contacts .adv-contact {
		padding: 1em 2em 2em;
	}
	.page.support-ssac section.ssac-engage .alignleft {
		width: 50% !important;
	}
	.page.support-ssac h2 {
		font-size: 175%;
	}
	.page.support-ssac section.ssac-engage .alignleft img {
		bottom: -7px;
	}
	h2.ss-join {
		font-size: 150%;
	}
	.page.support-ssac article .entry-content p a.ss-give {
		width: 25%;
		display: block;
		margin: 0 37.5% 0.5em;
	}
	.page.support-ssac h2.ss-contact {
		background-position-y: 26px;
		font-size: 150%;
	}
	.page.support-ssac article .entry-content p a.ss-give.ss-member {
		width: 55%;
		margin: 0 22.5% 0.25em;
	}
	.page.support-ssac article.support-members .entry-content {
		max-width: 65em;
		margin: 0 auto 1em;
	}
	.page .year-select {
		padding: 0.5em 2em;
	}
}
</style>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) :
		the_post();
		echo hpm_head_banners( get_the_ID() ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php
					the_content( sprintf(
						__( 'Continue reading %s', 'hpmv2' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );
				?>
			</div>
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
			</footer>
		</article>
	<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>