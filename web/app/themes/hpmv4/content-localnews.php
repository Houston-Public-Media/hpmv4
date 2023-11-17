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
        if ( $catCounter <= 1 ) {
			$args = [
				'showposts' => 5,
				'category__in' => [ $category->term_id ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => 4,
				'post__not_in' => $excludedIds
			];
			$posts = get_posts( $args );
    ?>
    <div class="col-sm-6">
        <h2 class="title">
            <strong><?php echo $category->name; ?></strong>
        </h2>
        <ul class="list-none news-links">
            <?php
				if ( $posts ) {
					foreach ( $posts as $post ) {
                		setup_postdata( $post );  ?>
            <li>
                <a href="<?php the_permalink(); ?>"><span class="cat-title"><?php echo hpm_top_cat( get_the_ID() ) ?></span> <?php the_title() ?></a>
            </li>
<?php
					}
				} ?>
        </ul></div>
<?php
        	$rowCount++;
        	if ( $rowCount % 2 == 0 ) echo '</div><div class="row">';
		}
        $catCounter++;
	}
?>