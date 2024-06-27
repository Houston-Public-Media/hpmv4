<?php $HATSArticles = hpm_showLatestWeatherArticlesbyShowID( 2232 );
$PE2024Articles = hpm_showLatestPresidentialElectionArticlesbyShowID( 20 );?>
<section class="section">
    <h2 class="title">
        <strong>HOUSTON PUBLIC MEDIA'S <span>INTERACTIVES</span></strong>
    </h2>
    <div class="row">
        <div class="col-sm-5">
                <div class="card mb-3">
                    <div class="card-header">
                        Weather Exclusives
                    </div>
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://cdn.houstonpublicmedia.org/assets/images/Hurricane-and-storm-tracker-hpm-interactives.png.webp" alt="Hurricane and Tropical Storm Tracker" style="padding: 6px;">
                            <h4 class="text-center" style="color:#237bbd; font-size: 14px;">Hurricane & Tropical Strom Tracker</h4>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Other Storm Coverage</h5>
                                <ul class="list-none news-links card-text">
                                    <?php
                                    foreach ( $HATSArticles as $ka => $va ) {
                                        $post = $va; ?>
                                        <li style="padding-bottom: 10px; margin-bottom: 10px; font-size: 13px;">
                                            <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                                        </li>
                                        <?php
                                    } ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-sm-2" style="padding: 10px;">
            <h4 class="text-center" style="color:#237bbd; font-size: 14px;">HEAT TRACKER</h4>
            <div class="image" style="border: 1px solid #237bbd; padding: 5px;">
                    <img src="https://cdn.houstonpublicmedia.org/assets/images/Heat-Tracker-Interactive-Map.png.webp" alt="Heat Tracker Interactive Map" style="padding: 6px;">
            </div>


        </div>

        <div class="col-sm-5">
            <div class="card mb-3">
                <div class="card-header">
                    Election 2024
                </div>
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="https://cdn.houstonpublicmedia.org/assets/images/Presindetial-election-2024.png.webp" alt="Presidential Election 2024" style="padding: 6px;">
                        <h4 class="text-center" style="color:#237bbd; font-size: 14px;">Presidential Election 2024</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Other Election Coverage</h5>
                            <ul class="list-none news-links card-text">
                                <?php
                                foreach ( $PE2024Articles as $ka => $va ) {
                                    $post = $va; ?>
                                    <li style="padding-bottom: 10px; margin-bottom: 10px; font-size: 13px;">
                                        <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                                    </li>
                                    <?php
                                } ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>


