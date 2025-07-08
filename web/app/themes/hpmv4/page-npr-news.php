<?php
/*
Template Name: NPR News Page
*/

get_header(); ?>
<style>
    .page #main {
        background-color: transparent;
    }
    #search-results article {
        padding: 1rem;
        display: flex;
        flex-flow: row nowrap;
        align-items: center;
    }
    .post-thumbnail {
        width: 33%;
        flex: 1;
        align-self: center;
    }
    #search-results article .entry-header{
        flex: 2;
        min-width: 60%;
        align-self: center;
    }
    #search-results article .entry-header{
        padding-left: 1rem;
    }
    #search-results article .entry-summary{
        padding-left: 1rem;
    }
    /*.npr-pagination ul {
        display: flex;
        list-style: none;
        gap: 10px;
        padding-left: 0;
    }
    .npr-pagination li a {
        padding: 5px 10px;
        border: 1px solid #ccc;
        text-decoration: none;
    }
    .npr-pagination .current {
        background: #333;
        color: #fff;
        padding: 5px 10px;
    }*/
    .wp-pagenavi {
        display: flex;
        justify-content: center;
        padding: 10px;
    }
     ul.page-numbers{
        list-style: none;
        display: flex;
    }
    .page-numbers ul li{
        list-style: none;
    }

</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post(); ?>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>

<?php echo hpmnpr_nprapi_output(1002) ?>

			<aside class="column-right">
				<?php get_template_part( 'sidebar' ); ?>
			</aside>
	<?php
		} ?>
		</main>
	</div>
<?php get_footer(); ?>
