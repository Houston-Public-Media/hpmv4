<?php
/*
Template Name: Wide with Articles &amp; Poll
*/

get_header();
$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
echo $embeds['bottom']; ?>
	<style>
		#harriscounty {
			padding: 1em;
			margin: 0 0 1em 0;
		}
		#harriscounty table {
			width: 100%;
			margin: 0 0 2em 0;
		}
		#harriscounty tr:nth-child(2) th {
			text-align: center;
			width: 40%;
		}
		#harriscounty tr:nth-child(2) th:nth-child(2),
		#harriscounty tr:nth-child(2) th:nth-child(3),
		#harriscounty tr:nth-child(2) th:nth-child(4),
		#harriscounty tr td:nth-child(2),
		#harriscounty tr td:nth-child(3),
		#harriscounty tr td:nth-child(4),
		#harriscounty tr td:nth-child(5),
		#harriscounty tr td:nth-child(6),
		#harriscounty tr td:nth-child(7) {
			display: none;
		}
		#harriscounty tr:nth-child(2) th:nth-child(1) {
			width: 60%;
		}
		#harriscounty td:nth-child(n+2) {
			text-align: center;
		}
		#harriscounty tr:nth-child(1) th {
			text-align: left;
		}
		#harriscounty td,
		#harriscounty th {
			padding: 0.5em;
		}
	</style>
	<script type="text/javascript">
		function update() {
			jQuery.ajax({
				type: "POST",  
				url: "https://media.houstonpublicmedia.org/election/embed.php",  
				data: '',
				success: function(data)
				{
					jQuery('#harriscounty').html(data);
				}
			});
		}
		jQuery(document).ready(function($) {
			//update();
			//setInterval("update()", 60000);
			$(".acc-section").hide();
			$(".featured").show();
			$('h3').click(function(e) {
				$(this).next(".acc-section").slideToggle('slow');
			});
			$('#expand').click(function(e) {
				$(".acc-section").slideDown('slow');
			});
			$('#collapse').click(function(e) {
				$(".acc-section").slideUp('slow');
			});
		});
	</script>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="column-span">
		<?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
					<?php 						
						the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
					<div class="entry-content">
					<?php
						if ( has_post_thumbnail() ) :
					?>
						<div class="post-thumbnail">
						<?php 
							the_post_thumbnail( 'medium' );
							$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
							if (!empty($thumb_caption)) :
								echo "<p>".$thumb_caption."</p>";
							endif;
						?>
						</div><!-- .post-thumbnail -->
					<?PHP
						endif;
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
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
			</div>
			<?php
				$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				if ( !empty( $cat_no ) ) :
					$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
					$term = reset( $terms );
					$cat = new WP_query( array(
						'cat' => $cat_no,
						'orderby' => 'date',
						'order'   => 'DESC',
					) );
					if ( $cat->have_posts() ) : ?>
				<section id="search-results">
		<?php
						while ( $cat->have_posts() ) : $cat->the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
						wp_reset_postdata(); ?>
					<div class="readmore">
						<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
					</div>
				</section>
			<?php
					endif;
				endif; ?>

			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<script src="//apps.texastribune.org/extras/embed2.js"></script>
<?php get_footer(); ?>