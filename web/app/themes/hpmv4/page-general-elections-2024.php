<?php
/*
Template Name: General Elections 2024 Page
*/
	get_header();
?>
<style>
    .btncountdown {
        border: solid .1em #222054;
        padding: .1em;
        background: #237bbd content-box;
        margin: 0 auto;
        width: 100%;
        height: 4.4em;
        color: #fff;
        font: 700 1.6em / 1.6em "watch-mn", sans serif;
        text-align: center;
        font-weight: bold;
        font-family: "watch-mn" !important;
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
        padding: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex: 1;
        flex-direction: column;
        color: white;
        box-sizing:border-box;
        max-height: 27px;
    }

    @media (max-width:767px) {
        .flex-col {
            flex-basis: calc(50% - 12px);
        }
    }

    @media (max-width:460px) {
        .flex-col {
            flex-basis: 100%;
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
    'cat' => [21, 60140],
    'orderby' => 'date',
    'order'   => 'DESC',
    'posts_per_page' => 16,
    'offset' => 4,
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish',
    'post_type' => 'post'
];

?>

            <section class="section">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <a style="text-decoration: none; color:#fff;" href="#">Voters Guide</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <a style="text-decoration: none; color:#fff;" href="/articles/news/politics/election-2024/2024/03/04/478356/whats-on-my-2024-texas-primary-ballot/">What's in my Ballot?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <a style="text-decoration: none; color:#fff;" href="#">Presidential College Map</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <a style="text-decoration: none; color:#fff;" href="/articles/news/politics/election-2024/2024/03/05/479760/election-results-texas-harris-county-primary-super-tuesday-2024/">2024 Primary Election Results</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
            <section class="section">
                        <div class="row elections-main">
                            <div class="col-sm-12 col-lg-9">

                                <h2 class="title"> <strong><span>Latest Elections </span> Coverage</strong> </h2>
                                    <div class="row">
                                        <?php echo hpm_ShowElectionTopThreeArticles(); ?>
                                    </div>
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="btncountdown">
                                            <!--<div class="flex-row">
                                                <div class="flex-col" style="padding-top: 20px; border-bottom: 1px dashed; margin-right: 10px;">45</div>
                                                <div class="flex-col" style="padding-top: 20px; border-bottom: 1px dashed; margin-right: 10px;">24</div>
                                                <div class="flex-col" style="padding-top: 20px; border-bottom: 1px dashed; margin-right: 10px;">34</div>
                                            </div>-->
                                            <?php echo CalculateElectionCountdowndays();?>
                                            <div class="flex-row">
                                                <div class="flex-col" style="font-family: 'Open Sans', Arial, Helvetica, sans-serif; font-size: 16px;">Days</div>
                                                <div class="flex-col" style="font-family: 'Open Sans', Arial, Helvetica, sans-serif; font-size: 16px;">Hrs</div>
                                                <div class="flex-col" style="font-family: 'Open Sans', Arial, Helvetica, sans-serif; font-size: 16px;">Mins</div>
                                            </div>
                                            <span style="font-size:22px;font-family: 'Open Sans', Arial, Helvetica, sans-serif;">to Election Day!</span>
                                            <?php //echo CalculateElectionCountdowndays();?>

                                        </div>



                                    </div>


                                    <div class="col-sm-12" style="padding-top: 25px;">
                                        <div class="sidebar-ad">
                                            <h4>Support Comes From</h4>
                                            <div id="div-gpt-ad-1394579228932-1">
                                                <script type='text/javascript'>
                                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                                                </script>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
            </section>

            <!-- Other Stories  -->
            <section class="section" style="padding-top: 25px;">
                <div class="row">
                    <h2 class="title"> <strong><span>Other </span> Stories</strong> </h2>

                    <?php
                    global $ka;
                    $ka = 0;
                    $tag_ids = [];
                    $cat = new WP_Query( $other_ep_args );
                    $elmcounter = 0;
                    if ( $cat->have_posts() ) {
                        while ( $cat->have_posts() ) {
                            $cat->the_post();
                            if ( $elmcounter == 2 ) { ?>
                                <div class="col-sm-6 col-md-4 mb-4">
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
                            } /* else if ( $elmcounter == 7 ) { ?>
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
                            }*/
                            get_template_part( 'content', "elections" );
                            $elmcounter++;
                        }
                    } ?>


                </div>
            </section>

            <?php
            echo hpm_custom_pagination( $cat->max_num_pages, 4, "/topics/news/politics/election-2024/page/" ); ?>


            <p>&nbsp;</p>


        </main>
    </div>
<?php get_footer(); ?>