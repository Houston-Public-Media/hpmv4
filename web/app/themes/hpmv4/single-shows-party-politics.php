<?php
/*
Template Name: Party Politics
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */

get_header(); ?>
	<style>
		body.single-shows #station-social {
			padding: 1em;
			background-color: var(--main-element-background);
			overflow: hidden;
			width: 100%;
		}
		body.single-shows .page-header {
			padding: 0;
		}
		body.single-shows .page-header .page-title {
			padding: 1rem;
		}
		body.single-shows .page-header.banner #station-social {
			margin: 0 0 1em 0;
		}
		body.single-shows #station-social h3 {
			font-size: 1.5em;
			font-family: var(--hpm-font-condensed);
			color: #3f1818;
			margin-bottom: 1rem;
		}
		#float-wrap aside {
			background-color: var(--main-element-background);
		}
		body.single-shows .podcast-badges {
			justify-content: flex-end;
		}
		.show-content > * + * {
			margin-top: 1rem;
		}
		#youtube-main .desc-wrap {
			max-height: 5rem;
			overflow: hidden;
			display: block;
			position: relative;
		}
		#youtube-main .desc-wrap .yt-readmore {
			position: absolute;
			bottom: 0;
			left: 0;
			background: rgb(255,255,255);
			background: linear-gradient(0deg, rgba(255,255,255,1) 50%, rgba(255,255,255,0) 100%);
			width: 100%;
			color: var(--accent-light-blue-1);
			font-weight: bold;
			padding: 0.5rem 0 0;
			outline: 0;
			border: 0;
			text-align: left;
		}
		#youtube-main .desc-wrap .yt-readmore:hover {
			cursor: pointer;
			text-decoration: underline;
		}
		dialog#yt-dialog {
			margin: auto;
			padding: 0;
			width: min(65ch, 100vw - 2rem);
			position: fixed;
			border-radius: 0.5rem;
			font-size: 1rem;
			background-color: white;
			border: 2px solid;
		}
		dialog#yt-dialog p button {
			display: none;
		}
		dialog#yt-dialog .yt-dialog-content {
			padding: clamp(1rem, 5%, 2rem);
		}

		dialog#yt-dialog::backdrop {
			background-color: rgba(0, 0, 0, 0.5);
			backdrop-filter: blur(10px);
		}
		dialog#yt-dialog .dialog-actions {
			list-style: none;
			padding: 0;
			margin: 1rem auto 0;
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 1rem;
		}
		dialog#yt-dialog .dialog-actions button {
			padding: 0.5em 1em;
			border: 1px solid gray;
			border-radius: 0.25rem;
			cursor: pointer;
			text-align: center;
		}
		dialog#yt-dialog .dialog-actions li {
			margin: 0;
		}
		dialog#yt-dialog [data-action=dismiss]:is(:focus, :hover) {
			background-color: #f4c7be;
		}
		@media screen and (min-width: 34em) {
			body.single-shows #station-social {
				display: grid;
				grid-template-columns: 1fr 1.25fr;
				align-items: center;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
			body.single-shows #station-social h3 {
				margin-bottom: 0;
			}
		}
		@media screen and (min-width: 52.5em) {
			body.single-shows #station-social {
				grid-template-columns: 2fr 3fr;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
		}
		[data-theme="dark"] body.single-shows #station-social h3 {
			color: var(--accent-red-4);
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
	while ( have_posts() ) {
		the_post();
		$show_id = get_the_ID();
		$show = get_post_meta( $show_id, 'hpm_show_meta', true );
		$show_title = get_the_title();
		$show_content = get_the_content();
		$episodes = HPM_Podcasts::list_episodes( $show_id );
		echo HPM_Podcasts::show_header( $show_id );
	}
	if ( !empty( $show['ytp'] ) ) {
		$c = 0; ?>
			<div id="shows-youtube">
				<div id="youtube-wrap">
<?php
		$json = hpm_youtube_playlist( $show['ytp'] );
		foreach ( $json as $tubes ) {
			$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
			if ( $c == 0 && strpos( $tubes['snippet']['title'], 'Private Video' ) === false ) { ?>
					<div id="youtube-main">
						<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<?php echo hpm_svg_output( 'play' ); ?>
						</div>
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
						<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						<div class="desc-wrap"><p class="desc"><?php echo str_replace( "\n", "<br />", $tubes['snippet']['description'] ); ?></p><button type="button" class="yt-readmore">Read More...</button></div>
						<dialog id="yt-dialog">
							<div class="yt-dialog-content">
								<h3></h3>
								<p></p>
								<ul class="dialog-actions">
									<li><button type="button" data-action="dismiss">Dismiss</button></li>
								</ul>
							</div>
						</dialog>
						<script>
							const dialog = document.getElementById("yt-dialog");
							const readMore = document.querySelector("#youtube-main .yt-readmore");

							readMore.addEventListener("click", ({ target }) => {
								var desc = document.querySelector("#youtube-main .desc");
								var title = document.querySelector("#youtube-main h2");
								dialog.querySelector("p").innerHTML = desc.innerHTML;
								dialog.querySelector("h3").textContent = title.textContent;
								dialog.showModal();
							});

							dialog.addEventListener("click", ({ target }) => {
								if (target.matches('dialog') || target.matches('[data-action="dismiss"]')) {
									dialog.close();
								}
							});
						</script>
					</div>
					<div id="youtube-upcoming">
						<h4>Past Shows</h4>
<?php
			} ?>
						<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdate="<?php echo date( 'F j, Y', $pubtime); ?>" data-ytdesc="<?php echo htmlentities( str_replace( "\n", "<br />", $tubes['snippet']['description'] ) ); ?>">
							<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
							<h2><?php echo $tubes['snippet']['title']; ?></h2>
							<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						</div>
<?php
			$c++;
		} ?>
					</div>
				</div>
			</div>
<?php
	}
	?>
			<div id="float-wrap">
				<aside class="column-right">
					<h3>About <?php echo $show_title; ?></h3>
					<div class="show-content">
						<?php echo apply_filters( 'the_content', $show_content ); ?>
					</div>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
							</script>
						</div>
					</div>
				</aside>
				<div class="article-wrap">
<?php
	$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
	$top =  get_post_meta( get_the_ID(), 'hpm_shows_top', true );
	$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
	$term = reset( $terms );
	$cat_args = [
		'cat' => $cat_no,
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 15,
		'ignore_sticky_posts' => 1
	];
	global $ka;
	$ka = 0;
	if ( !empty( $top ) && $top !== 'None' ) {
		$top_art = new WP_query( [ 'p' => $top ] );
		$cat_args['posts_per_page'] = 14;
		$cat_args['post__not_in'] = [ $top ];
		if ( $top_art->have_posts() ) {
			while ( $top_art->have_posts() ) {
				$top_art->the_post();
				get_template_part( 'content', get_post_type() );
				$ka += 2;
			}
			$post_num = 14;
		}
		wp_reset_query();
	}
	$cat = new WP_query( $cat_args );
	if ( $cat->have_posts() ) {
		while ( $cat->have_posts() ) {
			$cat->the_post();
			get_template_part( 'content', get_post_type() );
			$ka += 2;
		}
	} ?>
				</div>
			</div>
<?php
	if ( $cat->found_posts > 15 ) { ?>
			<div class="readmore">
				<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
			</div>
<?php
	} ?>
		</main>
	</div>
<?php get_footer(); ?>