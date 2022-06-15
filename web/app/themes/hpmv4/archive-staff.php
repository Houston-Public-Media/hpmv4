<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
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
		.staff-grid {
			width: 100%;
		}
		:is(.post-type-archive-staff,.tax-staff_category) #search-results {
			width: 100% !important;
			margin: 0 !important;
			float: none !important;
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
			display: flex;
			gap: 0;
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
		article.staff .post-thumbnail :is(img,picture) {
			aspect-ratio: 4/5;
		}
		@media screen and (min-width: 34rem) {
			.staff-grid {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 1rem;
			}
			.page-header {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 1rem;
				align-items: center;
			}
			:is(.post-type-archive-staff,.tax-staff_category) #search-results article + article {
				margin-top: 0;
			}
		}
		@media screen and (min-width: 52.55rem) {
			.staff-grid {
				grid-template-columns: 1fr 1fr 1fr;
			}
			article.staff .social-icon :is(a,button) {
				margin: 0 calc(var(--unit)/3) 0 0;
			}
			article.staff .social-icon:last-child :is(a,button) {
				margin: 0;
			}
		}
	</style>
	<section id="primary" class="content-area">
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
			<section id="search-results">
		<?php
			hpm_staff_echo( $wp_query );
		else :
			get_template_part( 'content', 'none' );
		endif; ?>
			</section>
		</main>
	</section>
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