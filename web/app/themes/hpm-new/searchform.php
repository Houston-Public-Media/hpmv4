<form role="search" method="get" class="search-form" action="/search/">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="search" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
	</label>
	<button class="search-submit screen-reader-text"><span class="fas fa-search" aria-hidden="true"></span><span class="screen-reader-text">Search</span></button>
</form>