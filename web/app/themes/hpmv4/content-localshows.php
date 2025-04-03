<?php
$HMArticles = hpm_showLatestArticlesbyShowID( 58 );
$PPArticles = hpm_showLatestArticlesbyShowID( 11524 );
$HHArticles = hpm_showLatestArticlesbyShowID( 64721 );
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
					<li style="font-size: 0.9rem;">
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
					</li>
					<?php
				} ?>
			</ul>
		</div>
		<div class="col-sm-4">
			<h3 class="title-style4">
				<strong>HELLO <span>HOUSTON</span></strong>
			</h3>
			<div class="image">
				<a href="/shows/hello-houston/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Talk-Show-Web-MainPg-Show-Cover.png.webp" alt="Hello Houston: Where Houston Talks!" /></a>
			</div>
			<ul class="list-none news-links">
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
					<li style="font-size: 0.9rem;">
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
					</li>
					<?php
				} ?>
			</ul>
		</div>
	</div>
</section>