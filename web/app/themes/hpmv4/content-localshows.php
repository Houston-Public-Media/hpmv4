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
                <a href="/shows/houston-matters/"><img src="<?php echo get_template_directory_uri(); ?>/images/Houston Matters with Craig Cohen.png" alt="" /></a>
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
                <a href="/shows/party-politics/"><img src="<?php echo get_template_directory_uri(); ?>/images/Party-Politics.png" alt="" /></a>
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
                <a href="https://iseeushow.org/"><img src="<?php echo get_template_directory_uri(); ?>/images/I SEE U with Eddie Robinson.png" alt="" /></a>
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