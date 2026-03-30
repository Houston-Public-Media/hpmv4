<?php
/*
Template Name: Space Landing Page
*/
	get_header();
$latestArticles = hpm_ShowElectionOtherStories( [33340, 59555] );
?>
<style>
    .btncountdown {
        border: solid .1em #222054;
        padding: .1em;
        background: #237bbd content-box;
        margin: 0 auto;
        width: 100%;
        height: 4.2em;
        color: #fff;
        font: 700 1.6em / 1.6em "watch-mn", sans serif;
        text-align: center;
        font-weight: bold;
        font-family: "watch-mn" !important;
        margin-bottom: 10px;
    }
    .card
    {
        border-radius: 0px;
        border-color: #237bbd;
    }
    .card-header
    {
        background-color: #237bbd;
        color:#fff;
        font-weight: bold;
        border-radius: 0px;
        /*min-height: 56px;*/
        text-align: center;
    }
    .card-header:first-child
    {
        border-radius: 0px;
    }
    .demspan
    {
        color:#0044c9;
        font-weight: bold;
    }
    .repspan
    {
        color:#C8102E;
        font-weight: bold;
    }

    .latest-news-img {
        padding: 0;
        height: 100%;
        max-height: 350px;
        position: relative;
        /*margin: 0 1rem;*/
    }
    .elections-main .latest-news-img {
       /* max-height: none;*/
        min-height: 340px;
    }
    .electionnews-listing {
        padding: 0;
        list-style: none;
    }
    .electionnews-listing ul {
        list-style: none;
    }
    .electionnews-listing li {
        border-bottom: solid 1px var(--black);
        padding: 1rem;
        padding-left: 0px;
        padding-right: 0px;
    }
    .electionnews-listing li:first-child {
        padding-right: 1rem;
    }
    .electionnews-listing a{
        text-decoration: none;
        color:#404040;
    }
    .elections-main h1 a
    {
        text-decoration: none;
        padding-top: 10px;
    }
    .sup{
        vertical-align: super ;
    }
    .sub{
        vertical-align:sub ;
    }
    .flex-row {
        display: flex;
        flex: 1;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .flex-col {
       /* margin: 6px;*/
        padding: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex: 1;
        flex-direction: column;
        color: white;
        box-sizing:border-box;
        max-height: 24px;
    }
	section.section {
		padding: 1rem;
	}
    .title::before
    {
        left: unset;
        width: 95%;
    }
    @media (max-width:767px) {
        .flex-col {
           /* flex-basis: calc(50% - 12px);*/
        }
    }

    @media (max-width:460px) {
        .flex-col {
            /*flex-basis: 100%;*/
        }
    }

</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) {
    	the_post();
?>
	    <?php the_content(); ?>
<?php  }
$other_ep_args = [
    'cat' => [33340, 59555],
    'tax_query' => [
        [
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => [64721, 64814, 64880, 58, 43214, 13764],
            'operator' => 'NOT IN',
        ],
    ],
    'post__not_in' => [541368],
    'orderby' => 'date',
    'order'   => 'DESC',
    'posts_per_page' => 1,
    'offset' => 4,
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish',
    'post_type' => 'post'
];
$cat = new WP_Query( $other_ep_args );
if ( $cat->have_posts() ) {
    while ($cat->have_posts()) {
        $cat->the_post();
    }
}
?>
            <section class="section">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <a style="text-decoration: none; color:#fff;" href="https://www.nasa.gov/missions/artemis/artemis-2/nasa-sets-coverage-for-artemis-ii-moon-mission/">Nasa's Artemis II Coverage</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <a style="text-decoration: none; color:#fff;" href="https://www.nasa.gov/2026-news-releases/">NASA's Latest News & Events</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <a style="text-decoration: none; color:#fff;" href="https://www.nasa.gov/gallery/artemis-ii-astronauts/">Artimiss II Astronauts</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <a style="text-decoration: none; color:#fff;" href="https://science.nasa.gov/moon/">The Moon Mission</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row elections-main">
                            <div class="col-sm-12">
                                <h2 class="title"> <strong><span>Latest ARTIMISS II </span> Coverage</strong> </h2>
                                <div class="row">
                                    <?php echo hpm_ShowElectionTopThreeArticles([33340, 59555]); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row section" style="padding-top: 25px;">
                            <div class="col-sm-12">
                                <h2 class="title"> <strong><span>ARTIMISS II </span> Live</strong> </h2>
                                <div class="iframe-embed">

                                <iframe width="560" height="315" src="https://www.youtube.com/embed/Tf_UjBMIzNo?si=1PSrPyfdjfygJYpn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="row section" style="padding-top: 25px;">
                            <h2 class="title"><strong><span>Other </span> Stories</strong> </h2>
                            <?php
                            foreach ( $latestArticles as $eka => $eva ) {
                                $post = $eva;
                                if ( $eka > 0 && $eka < 4 ) {
                                    get_template_part("content", "space");
                                }
                            } ?>
                        </div>
                    </div>

                </div>
            </section>

            <section class="section">
                <div class="row">
                        <?php
                        foreach ( $latestArticles as $eka => $eva ) {
                            $post = $eva;
                            if ( $eka > 4) {
                                if ( $eka == 7 ) { ?>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="sidebar-ad">
                                            <h4>Support Comes From</h4>
                                            <div id="div-gpt-ad-1394579228932-1">
                                                <script type='text/javascript'>
                                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else if ( $eka == 12 ) { ?>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="sidebar-ad">
                                            <h4>Support Comes From</h4>
                                            <div id="div-gpt-ad-1394579228932-2">
                                                <script type='text/javascript'>
                                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                get_template_part("content", "space");
                            }
                        } ?>
                </div>
            </section>
            <?php
            echo hpm_custom_pagination( $cat->max_num_pages, 4, "/topics/news/nasa/space/page/" ); ?>
            <p>&nbsp;</p>
        </main>
    </div>
<?php get_footer(); ?>