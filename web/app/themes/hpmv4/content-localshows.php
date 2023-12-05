<?php
	$HMArticles = hpm_showLatestArticlesbyShowID(58);
	$PPArticles = hpm_showLatestArticlesbyShowID(11524);
	$ISeeUArticles = hpm_showLatestArticlesbyShowID(46661);
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
            <div class="image">
                <a href="/shows/houston-matters/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Houston-Matters-with-Craig-Cohen-Logo.png.webp" alt="Houston Matters with Craig Cohen" /></a>
            </div>
            <ul class="list-none news-links">
<?php
				foreach ( $HMArticles as $ka => $va ) {
					$post = $va; ?>
                    <li>
                        <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                    </li>
<?php
				} ?>
            </ul>
        </div>
        <div class="col-sm-4">
            <h3 class="title-style3">
                <strong>PARTY <span>POLITICS</span></strong>
            </h3>
            <div class="image">
                <a href="/shows/party-politics/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Party-Politics-Logo.png.webp" alt="Party Politics" /></a>
            </div>
            <ul class="list-none news-links">
<?php
				foreach ( $PPArticles as $ka => $va ) {
					$post = $va; ?>
                    <li>
                        <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                    </li>
<?php
				} ?>

            </ul>
        </div>
        <div class="col-sm-4">
            <h3 class="title-style4">
                <strong>I SEE <span>U</span></strong>
            </h3>
            <div class="image">
                <a href="https://iseeushow.org/"><img src="https://cdn.houstonpublicmedia.org/assets/images/I-SEE-U-with-Eddie-Robinson-Logo.png.webp" alt="I SEE U with Eddie Robinson" /></a>
            </div>
            <ul class="list-none news-links">
<?php
				foreach ( $ISeeUArticles as $ka => $va ) {
					$post = $va; ?>
                    <li>
                        <a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
                    </li>
<?php
				} ?>

            </ul>
        </div>
    </div>
</section>