<?php
$HMArticles = hpm_showLatestArticlesbyShowID( 58 );
$PPArticles = hpm_showLatestArticlesbyShowID( 11524 );
$HHArticles = hpm_showLatestArticlesbyShowID(64721);
?>
<section class="section radio-list">
    <h2 class="title">
        <strong>THIS WEEK on <span>TALK RADIO</span></strong>
    </h2>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title-style2">
                <strong>HOUSTON <span>MATTERS</span></strong>
            </h3>
            <div class="row">
                <div class="col-sm-5">
                    <div class="image">
                        <a href="/shows/houston-matters/"><img src="https://cdn.houstonpublicmedia.org/wp-content/uploads/2017/07/09164012/HMCC_podcast-tile.png.webp" alt="Houston Matters with Craig Cohen" /></a>
                    </div>
                </div>
                <div class="col-sm-7" style="padding-left: 0px; padding-right: 0px;">
                    <ul class="list-none news-links" style="margin-top: 0;">
                        <?php
                        foreach ( $HMArticles as $ka => $va ) {
                            $post = $va; ?>
                            <li style="font-size: 0.9rem;">
                                <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <h3 class="title-style3">
                <strong>HELLO <span>HOUSTON</span></strong>
            </h3>
            <div class="row">
                <div class="col-sm-5">
                    <div class="image">
                        <a href="/shows/hello-houston/"><img src="https://cdn.houstonpublicmedia.org/assets/images/HH_Social-Profile_Red-1.png.webp" alt="Hello Houston" /></a>
                    </div>
                </div>
                <div class="col-sm-7" style="padding-left: 0px; padding-right: 0px;">
                    <ul class="list-none news-links" style="margin-top: 0;">
                        <?php
                        foreach ( $HHArticles as $ka => $va ) {
                            $post = $va; ?>
                            <li style="font-size: 0.9rem;">
                                <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <h3 class="title-style3">
                <strong>PARTY <span>POLITICS</span></strong>
            </h3>
            <div class="row">
                <div class="col-sm-5">
                    <div class="image">
                        <a href="/shows/party-politics/"><img src="https://cdn.houstonpublicmedia.org/wp-content/uploads/2021/09/27123037/PartyPol-21_Podcast-Art_2000x2000.jpg.webp" alt="Party Politics" /></a>
                    </div>
                </div>
                <div class="col-sm-7" style="padding-left: 0px; padding-right: 0px;">
                    <ul class="list-none news-links" style="margin-top: 0;">
                        <?php
                        foreach ( $PPArticles as $ka => $va ) {
                            $post = $va; ?>
                            <li style="font-size: 0.9rem;">
                                <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                            </li>
                            <?php
                        } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>