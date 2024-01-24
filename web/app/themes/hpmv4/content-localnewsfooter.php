<?php
	$excludedIds = get_option( 'hpm_priority' )['homepage'];
	$catArrays = get_option( 'hpm_modules' );
	$cat_args = [
		'include' => $catArrays['homepage'],
		'orderby' => 'include'
	];
	$categories = get_categories( $cat_args );
	$rowCount = 0;
	$catCounter = 0;
	foreach ( $categories as $category ) {
		if ( $catCounter > 3 ) {
			$args = [
				'showposts' => 4,
				'category__in' => [ $category->term_id ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => 4,
				'post__not_in' => $excludedIds
			];
			$posts = get_posts( $args );
?>
	<div class="col-sm-12">
		<h2 class="title">
			<strong><?php echo $category->name; ?></strong>
		</h2>
		<ul class="list-none news-links link-thumb">
<?php
			if ( $posts ) {
				foreach ( $posts as $post ) {
					setup_postdata( $post );  ?>
			<!--<li>
				<a href="<?php /*the_permalink(); */?>"><span class="cat-title"><?php /*echo hpm_top_cat( get_the_ID() ); */?></span> <?php /*the_title(); */?></a>
			</li>-->
                    <li><a href="<?php the_permalink(); ?>" rel="bookmark"><span><?php the_title(); ?></span></a></li>
<?php
				}
			} ?>
		</ul></div>
<?php
			$rowCount++;

		}
		$catCounter++;
	}
?>