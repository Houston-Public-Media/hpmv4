<?php
	$HMArticles = hpm_showLatestArticlesbyShowID( 58 );
	$PPArticles = hpm_showLatestArticlesbyShowID( 11524 );
?>
<section class="section radio-list">
	<h2 class="title">
		<strong>THIS WEEK on <span>TALK RADIO</span></strong>
	</h2>

    <div class="row">
        <div class="col-sm-6">
            <h3 class="title-style2">
                <strong>HOUSTON <span>MATTERS</span></strong>
            </h3>
            <div class="row">
                <div class="col-sm-6">

                    <div class="image">
                        <a href="/shows/houston-matters/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Houston-Matters-with-Craig-Cohen-Logo.png.webp" alt="Houston Matters with Craig Cohen" /></a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ul class="list-none news-links">
                        <?php
                        foreach ( $HMArticles as $ka => $va ) {
                            $post = $va; ?>
                            <li style="padding-bottom: 10px; margin-bottom: 10px; font-size: 14px;">
                                <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-sm-6">
            <h3 class="title-style3">
                <strong>PARTY <span>POLITICS</span></strong>
            </h3>

            <div class="row">
                <div class="col-sm-6">

                    <div class="image">
                        <a href="/shows/party-politics/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Party-Politics-Logo.png.webp" alt="Party Politics" /></a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ul class="list-none news-links">
                        <?php
                        foreach ( $PPArticles as $ka => $va ) {
                            $post = $va; ?>
                            <li style="padding-bottom: 10px; margin-bottom: 10px; font-size: 14px;">
                                <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>

            </div>
        </div>

    </div>

	<!--<div class="row">
		<div class="col-sm-8">



		</div>
		<div class="col-sm-8">
			<h3 class="title-style3">
				<strong>PARTY <span>POLITICS</span></strong>
			</h3>
			<div class="image">
				<a href="/shows/party-politics/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Party-Politics-Logo.png.webp" alt="Party Politics" /></a>
			</div>
			<ul class="list-none news-links">
<?php
/*				foreach ( $PPArticles as $ka => $va ) {
					$post = $va; */?>
					<li>
						<a href="<?php /*echo get_the_permalink( $post ); */?>"><?php /*echo get_the_title( $post ); */?></a>
					</li>
<?php
/*				} */?>

			</ul>
		</div>

	</div>-->
</section>