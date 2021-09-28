<?php
/*
Template Name: Support Sub
*/
get_header(); ?>
<style>
#main > article {
	grid-column: 1 / -1 !important;
}
.page-content {
	padding: 1rem;
}
.page-template-page-support-sub .page-header {
	background-color: var(--main-red);
	padding: 1em 1em 0 1em;
}
.page-template-page-support-sub .page-header .page-title {
	color: white;
	margin: 0;
	font-weight: 700;
}
.page-template-page-support-sub .page-header p {
	color: white;
	margin: 0;
}
.page-template-page-support-sub .page-header img {
	width: 75%;
	margin: 0 12.5%;
	padding: 0;
	line-height: 0;
	vertical-align: bottom;
}
.page-template-page-support-sub.matching-info .page-header img {
	width: 60%;
	margin: 0 20%;
	padding: 0 0 0.25em 0;
	line-height: 0;
}
.page-template-page-support-sub .page-content h2 {
	padding: 0;
	color: var(--main-red);
	font-weight: 700;
}
.page-template-page-support-sub .page-content ul,
ul.passport-drop {
	list-style: none;
	padding: 0 0 2em;
	margin: 0;
}
.page-template-page-support-sub .page-content ul li,
ul.passport-drop li {
	margin: 1em 0;
}
.page-template-page-support-sub .page-content ul li.passport-faq,
ul.passport-drop li.passport-faq {
	width: 100%;
	border-bottom: 1px solid #707070;
	font-weight: bolder;
	padding: 0 0.5em 0.25em;
}
.page-template-page-support-sub .page-content ul li.passport-faq:hover,
ul.passport-drop li.passport-faq:hover {
	cursor: pointer;
	opacity: 0.75;
	transition: opacity .2s ease-out;
}
.page-template-page-support-sub .page-content ul li.passport-faq:after,
ul.passport-drop li.passport-faq:after {
	content: '\f0da';
	display: inline-block;
	-webkit-font-smoothing: antialiased;
	font: 900 1em/1 'Font Awesome 5 Free';
	margin-left: 0.375em;
	color: rgb(0,170,235);
}
.page-template-page-support-sub .page-content ul li.passport-faq.passport-active:after,
ul.passport-drop li.passport-faq.passport-active:after {
	content: '\f0d7';
}
.page-template-page-support-sub .page-content ul li.passport-hidden,
ul.passport-drop li.passport-hidden {
	display: none;
	margin: 1em 0 2em;
	padding: 0 0.5em;
	color: #292929;
	font-weight: 100;
	font-size: 1.125em;
}
.page-template-page-support-sub .page-content .support-sub-block {
	padding: 1em;
	background-color: rgb(227,248,254);
	margin-bottom: 1em;
}
.page-template-page-support-sub .page-content .support-sub-block h3 {
	color: var(--main-red);
	text-align: center;
}
.page-template-page-support-sub .page-content .support-sub-block h4 {
	color: rgb(89,89,91);
	text-align: center;
}
.page-template-page-support-sub .page-content .support-sub-block ol li {
	color: rgb(89,89,91);
	font-weight: 100;
	font-size: 1em;
}
.support-buttons a {
	width: 47.5%;
	display: block;
	margin: 0 1.25% 1em;
	padding: 1em 0.25em;
	text-align: center;
	color: white;
	font-weight: 700;
	font-size: 1em;
	background-color: #59595B;
}
.matching-info .support-buttons a {
	margin: 0 27.5%;
}
.support-buttons {
	margin-bottom: 1em;
	overflow: hidden;
	display: flex;
	flex-flow: row wrap;
	justify-content: center;
}
.support-buttons a.vehicle-donate {
	background-color: #016D94;
}
.support-buttons a.vehicle-covid-faq {
	background-color: var(--main-red);
}
.page-template-page-support-sub #vehicle-donation-main p {
	color: #59595B;
	text-align:  center;
	font-weight: 100;
	font-size: 1.25em;
	margin-bottom: 1em;
}
.page-template-page-support-sub .page-content #vehicle-donation-main h2 {
	color: #52C2DD;
	padding: 1em 0 0.25em;
	margin-bottom: 1em;
	border-bottom: 2px solid #52C2DD;
	text-align: center;
	font-size: 1.5em;
}
.page-template-page-support-sub.matching-info .page-content #vehicle-donation-main h2 {
	color: #016D94;
	padding: 1em 0 0.25em;
	margin-bottom: 1em;
	border-bottom: 2px solid #016D94;
	text-align: center;
	font-size: 1.5em;
}
.page-template-page-support-sub .page-content #vehicle-donation-main .vehicle-contact p {
	text-align: left;
}
.vehicle-donation-how {
	overflow: hidden;
	flex-flow: row nowrap;
	align-content: center;
	align-items: center;
	display: flex;
}
.vehicle-donation-how .vehicle-how-wrap {
	padding: 1em;
	float: left;
	width: 70%;
}
.vehicle-donation-how .vehicle-how-wrap:first-child {
	width: 30%;
}
.page-template-page-support-sub #vehicle-donation-main .vehicle-donation-how .vehicle-how-wrap p,
.vehicle-donation-how .vehicle-how-wrap h3 {
	text-align: left;
}
.page-template-page-support-sub #vehicle-donation-main .vehicle-donation-how .vehicle-how-wrap p.vehicle-aside {
	color: #52C2DD;
	font-size: 90%;
}
.vehicle-donation-how .vehicle-how-wrap img {
	float: right;
	max-height: 6em;
}
.vehicle-donation-how .vehicle-how-wrap h3 {
	color: #52C2DD;
	font-size: 1.25em;
}
.vehicle-donation-help-wrap {
	overflow: hidden;
}
.vehicle-donation-help-wrap .vehicle-donation-help {
	padding: 1em;
}
.vehicle-donation-help-wrap .vehicle-donation-help img {
	width: 60%;
	margin: 0 20% 1em;
}
.vehicle-donation-help h3 {
	text-align: center;
	color: #52C2DD;
	text-transform: uppercase;
}
.page-template-page-support-sub.matching-info .alignleft {
	overflow: hidden;
}
.page-template-page-support-sub.matching-info .alignleft img {
	width: 25%;
	padding: 1em 1em 0 0;
	float: left;
}
.page-template-page-support-sub.matching-info #vehicle-donation-main .alignleft p {
	text-align: left;
}
.page-template-page-support-sub.matching-info .page-content #vehicle-donation-main .vehicle-contact h2 {
	background-position-y: 26px;
	margin: 0 0 0.5em 0;
	color: #52C2DD;
}
.page-template-page-support-sub.matching-info .vehicle-contact .contact-email {
	border: 0;
	padding: 0 0 0 30px;
	background-repeat: no-repeat;
	background-size: 25px;
	background-position-x: 0;
	background-position-y: 5px;
	background-image: url('https://cdn.hpm.io/assets/images/email_icon2x.png');
}
.page-template-page-support-sub.matching-info .vehicle-contact .contact-phone {
	border: 0;
	padding: 0 0 0 30px;
	background-repeat: no-repeat;
	background-size: 19px;
	background-position-x: 4px;
	background-position-y: 0px;
	background-image: url('https://cdn.hpm.io/assets/images/phone_icon2x.png');
}
@media screen and (min-width: 34em) {
	.support-buttons a {
		width: 35%;
		margin: 0 7.5% 1em;
	}
	.matching-info .support-buttons a {
		margin: 0 32.5%;
	}
	.page-template-page-support-sub .page-content #vehicle-donation-main h2 {
		width: 75%;
		margin: 0 12.5% 1em;
	}
	.page-template-page-support-sub.matching-info .page-content #vehicle-donation-main h2 {
		width: 80%;
		margin: 0 10% 1em;
	}
	.vehicle-donation-how {
		width: 80%;
		margin: 0 10%;
	}
	.page-template-page-support-sub .page-header {
		flex-flow: row nowrap;
		justify-content: center;
		align-content: center;
		align-items: center;
		display: flex;
	}
	.page-template-page-support-sub .page-header img {
		width: 30%;
		margin: 0;
		padding-top: 1em;
	}
	.page-template-page-support-sub .page-header-wrap {
		width: 70%;
		padding-bottom: 1em;
	}
	.page-template-page-support-sub.matching-info .page-header img {
		width: 25%;
		margin: 0;
		padding: 0 0 0.25em 0;
	}
	.page-template-page-support-sub.matching-info .page-header-wrap {
		width: 75%;
		padding-bottom: 1em;
	}
	.vehicle-donation-help-wrap {
		overflow: hidden;
	}
	.vehicle-donation-help-wrap .vehicle-donation-help {
		float: left;
		width: 33.33333%;
	}
	.vehicle-donation-how .vehicle-how-wrap {
		width: 60%;
	}
	.vehicle-donation-how .vehicle-how-wrap:first-child {
		width: 40%;
	}
	.vehicle-donation-how .vehicle-how-wrap img {
		max-width: 7em;
	}
	.vehicle-contact, .support-sub-block {
		width: 80%;
		margin: 0 10% 1em;
	}
	.page-template-page-support-sub .page-content #vehicle-donation-main .vehicle-contact h2 {
		margin: 0 0 1em 0;
	}
	.page-template-page-support-sub.matching-info .alignleft {
		float: left;
		width: 45%;
	}
	.page-template-page-support-sub.matching-info .alignleft img {
		width: 30%;
	}
}
@media screen and (min-width: 52.5em) {
	.page-template-page-support-sub article {
		border: 0;
		width: 100%;
	}
	.page-template-page-support-sub .page-header {
		flex-flow: row nowrap;
		justify-content: center;
		align-content: center;
		align-items: center;
		display: flex;
	}
	.page-template-page-support-sub .page-header img {
		width: 35%;
		margin: 0;
		padding: 0;
	}
	.page-template-page-support-sub .page-header-wrap {
		width: 50%;
		padding: 0;
	}
	.page-template-page-support-sub.matching-info .page-header-wrap {
		width: 65%;
	}
	.page-template-page-support-sub .page-content {
		width: 80%;
		margin: 0 10%;
	}
	.support-buttons {
		width: 100%;
		margin: 0 0 1em;
	}
	.support-buttons a {
		width: 25%;
		margin: 0 4% 1em;
	}
	.vehicle-contact {
		width: 80%;
		margin: 0 10% 1em;
	}
	.page-template-page-support-sub .page-content #vehicle-donation-main h2 {
		width: 60%;
		margin: 0 20% 1em;
	}
	.page-template-page-support-sub.matching-info .page-content #vehicle-donation-main h2 {
		width: 80%;
		margin: 0 10% 1em;
	}
	.page-template-page-support-sub.matching-info .page-content #vehicle-donation-main .vehicle-contact h2 {
		margin: 0 0 1em 0;
		width: 100%;
	}
}
@media screen and (min-width: 64.0625em) {
	.page-template-page-support-sub .page-header img {
		width: 30%;
	}
	.page-template-page-support-sub.matching-info .page-header img {
		width: 22%;
	}
	.page-template-page-support-sub.matching-info .page-header-wrap {
		width: 60%;
	}
	.page-template-page-support-sub.matching-info .alignleft img {
		width: 25%;
	}
}
</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 0;">
				<header class="page-header">
					<div class="page-header-wrap">
						<h1 class="page-title"><?php echo get_the_title(); ?></h1>
						<?php the_excerpt(); ?>
					</div>
					<?php the_post_thumbnail(); ?>
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