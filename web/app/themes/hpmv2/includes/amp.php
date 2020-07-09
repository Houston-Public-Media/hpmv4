<?php
/*
 * Set site icon URL on Google AMP
 */
add_filter( 'amp_post_template_data', 'hpm_amp_set_site_icon_url' );
function hpm_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
    $data[ 'site_icon_url' ] = 'https://cdn.hpm.io/assets/images/favicon/favicon-32.png';
    return $data;
}

/*
 * Modify the JSON metadata present on Google AMP
 */
add_filter( 'amp_post_template_metadata', 'hpm_amp_modify_json_metadata', 10, 2 );
function hpm_amp_modify_json_metadata( $metadata, $post ) {
	$metadata['@type'] = 'NewsArticle';

	$metadata['publisher']['logo'] = array(
		'@type' => 'ImageObject',
		'url' => 'https://cdn.hpm.io/wp-content/uploads/2019/01/20130758/HPM_podcast-tile.jpg'
	);
	if ( empty( $metadata['image'] ) ) :
		$metadata['image'] = array(
			'@type' => 'ImageObject',
			'url' => 'https://cdn.hpm.io/wp-content/uploads/2019/01/20130758/HPM_podcast-tile.jpg',
			'height' => 1600,
			'width' => 1600
		);
	endif;
	return $metadata;
}

/*
 * Add Google Analytics to AMP
 */
add_filter( 'amp_post_template_analytics', 'hpm_amp_add_custom_analytics' );
function hpm_amp_add_custom_analytics( $analytics ) {
	if ( ! is_array( $analytics ) ) :
		$analytics = [];
	endif;
	$analytics['hpm-googleanalytics'] = [
		'type' => 'googleanalytics',
		'attributes' => [
			// 'data-credentials' => 'include',
		],
		'config_data' => [
			'vars' => [
				'account' => "UA-3106036-13"
			],
			'triggers' => [
				'trackPageview' => [
					'on' => 'visible',
					'request' => 'pageview',
				],
			],
		],
	];
	return $analytics;
}

/*
 * Add footer code to Google AMP
 */
add_action( 'amp_post_template_footer', 'hpm_amp_add_footer' );
function hpm_amp_add_footer( $amp_template ) {
	$post_id = $amp_template->get( 'post_id' );
?>
<footer id="colophon" class="site-footer amp-wp-footer" role="contentinfo">
	<div class="site-info">
		<div class="foot-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>">
				<amp-img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-Color.png" width="300" height="80" class="amp-wp-footer-logo" id="AMP_foot"></amp-img>
			</a>
		</div>
		<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the University of Houston</p>
		<p>Copyright &copy; <?php echo date('Y'); ?> | <a href="http://www.uhsystem.edu/privacy-notice/">Privacy Policy</a></p>
	</div><!-- .site-info -->
</footer><!-- .site-footer -->
<?php
}

add_action( 'amp_post_template_css', 'hpm_amp_additional_css' );

function hpm_amp_additional_css( $amp_template ) {
	?>
	@font-face {
		font-family: 'MiloOT-Light';
		src: url('https://cdn.hpm.io/assets/fonts/MiloOT-Light.otf') format('opentype'), url('https://cdn.hpm.io/assets/fonts/MiloWeb-Light.woff') format('woff'), url('https://cdn.hpm.io/assets/fonts/MiloWeb-Light.eot') format('eot');
		font-weight: normal;
		font-style: normal;
	}
	.amp-wp-title {
		font: normal 2em/1.125em 'MiloOT-Light',helvetica,arial;
	}
	.amp-audio-wrap {
		text-align: center;
	}
	<?php
}