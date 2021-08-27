<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
get_header(); ?>
	<style>
		select#hpm-staff-cat {
			outline: 0;
			background-color: rgb(243,244,244);
			color: #00b0bc;
			font-weight: 500;
			font-size: 1.25rem;
			padding: 0.5rem;
			margin: 0.5rem 0;
			max-width: 100%;
		}
		#main section {
			padding: 1rem;
		}
		#main section > * + * {
			margin-top: 1rem;
		}
		article.staff {
			display: flex;
			align-items: center;
			flex-flow: row nowrap;
			position: relative;
		}
		article.staff .card-content {
			min-width: 70%;
			flex: 1;
			padding-bottom: 2rem;
		}
		article.staff .post-thumbnail img {
			aspect-ratio: initial;
			height: auto;
			padding-right: 1rem;
		}
		article.staff .social-icon {
			--unit: 2rem;
		}
		article.staff .social-wrap {
			margin: 0;
			position: absolute;
			bottom: 0.5rem;
			right: 0.5rem;
		}
		article.staff .entry-summary p {
			font-size: 1rem;
		}
		article.staff h2 {
			font-size: 1.25rem;
		}
		article.staff .entry-header {
			padding: 0;
		}
		@media screen and (min-width: 34rem) {
			#main > section {
				gap: 1rem;
				grid-column: 1 / -1;
			}
			#main > section > article {
				grid-column: auto;
			}
			#main > section > h2 {
				grid-column: 1 / -1;
				margin: 0;
				padding: 0;
			}
			#main section > * + * {
				margin-top: 0;
			}
			.page-header {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 1rem;
				align-items: center;
			}
		}
		@media screen and (min-width: 64.25rem) {
			#main > section {
				gap: 1rem;
				grid-template-columns: 1fr 1fr 1fr;
			}
			article.staff .social-icon a,
			article.staff .social-icon button {
				margin: 0 calc(var(--unit)/3) 0 0;
			}
			article.staff .social-icon:last-child a,
			article.staff .social-icon:last-child button {
				margin: 0;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php echo ( is_tax() ? 'Staff: ' . $wp_query->queried_object->name  : 'Staff Directory' ) ?></h1>
				<?php wp_dropdown_categories([
						'show_option_all'	=> __("Select Category"),
						'taxonomy'			=> 'staff_category',
						'name'				=> 'hpm-staff-cat',
						'orderby'			=> 'name',
						'selected'			=> ( is_tax() ? $wp_query->queried_object->slug : '' ),
						'hierarchical'		=> true,
						'depth'				=> 3,
						'show_count'		=> false,
						'hide_empty'		=> true,
						'value_field'		=> 'slug'
					]); ?>
			</header>
			<section>
		<?php
			hpm_staff_echo( $wp_query );

			// Previous/next page navigation.
			the_posts_pagination( [
				'prev_text' => __( '&lt;', 'hpmv2' ),
				'next_text' => __( '&gt;', 'hpmv2' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv2' ) . ' </span>',
			 ] );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>
			</section>
		</main>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			var staffCat = document.querySelector('select#hpm-staff-cat')
			staffCat.addEventListener('change', (e) => {
				if (staffCat.value == 0) {
					window.location.href = '/staff/';
				} else {
					window.location.href = "/staff-category/"+staffCat.value+"/";
				}
			});
		});
	</script>
<?php get_footer(); ?>