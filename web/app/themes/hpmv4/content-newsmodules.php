<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
// Show Latest stories from selected categories

$catArrays = get_option( 'hpm_modules' );
$cat_args = [
	'include'  => $catArrays,
];
$categories = get_categories( $cat_args );
foreach( $categories as $category ) {
	$args = [
		'showposts' => 5,
		'category__in' => [ $category->term_id ],
		'caller_get_posts' => 1
	];
	$posts = get_posts( $args );
	if ( $posts ) {
		echo '<p>Category: <a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '">' . $category->name.'</a></p>';
		foreach ( $posts as $post ) {
			setup_postdata( $post ); ?>
			<p><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
			<?php
		}
	}
} ?>
<div class="col-6">
	<h2 class="title">
		<strong>Local <span>News</span></strong>
	</h2>
	<ul class="list-none news-links">
		<li>
			<a href="#"><strong>POLITICS</strong> Impeachment of Texas AG Ken Paxton exposes
				divisions in
				Collin County Republican Party</a>
		</li>
		<li>
			<a href="#"><strong>POLITICS</strong> Impeachment of Texas AG Ken Paxton exposes
				divisions in
				Collin County Republican Party</a>
		</li>
		<li>
			<a href="#"><strong>POLITICS</strong> Impeachment of Texas AG Ken Paxton exposes
				divisions in
				Collin County Republican Party</a>
		</li>
		<li>
			<a href="#"><strong>POLITICS</strong> Impeachment of Texas AG Ken Paxton exposes
				divisions in
				Collin County Republican Party</a>
		</li>
	</ul>
</div>