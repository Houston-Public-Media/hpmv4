<?php
namespace hpmSitemap;

include_once 'DataAccess.php';

class CategoryMetaData {
	public static function addHooks(): void {
		$taxonomy = Helpers::getRequestValue( 'taxonomy', '0' );
		add_action( $taxonomy . '_edit_form', [ __CLASS__, 'renderEdit' ] );
	    add_action( $taxonomy . '_add_form_fields', [ __CLASS__, 'renderAdd' ] );
		add_action( 'created_' . $taxonomy, [ __CLASS__, 'save_metaData' ], 10, 2 );
		add_action( 'edited_' . $taxonomy, [ __CLASS__, 'save_metaData' ], 10, 2 );
	}

	static function save_metaData( $term_id ): void {
		/* Verify the nonce before proceeding. */
	 	if ( !isset( $_POST['hpmXSG_meta_nonce'] ) || !wp_verify_nonce( $_POST['hpmXSG_meta_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( 'manage_categories' ) ) {
			return;
		}

		$settings = new MetaSettings();

		$settings->id = Helpers::getFieldValue( 'hpmXSG-metaId', '0' );
	 	$settings->itemId = $term_id;
		$settings->itemType = "taxonomy";
	 	$settings->exclude = Helpers::getFieldValue( 'hpmXSG-Exclude', '0' );
	 	$settings->priority = Helpers::getFieldValue( 'hpmXSG-Priority', 'default' );
	  	$settings->frequency = Helpers::getFieldValue( 'hpmXSG-Frequency', 'default' );
		$settings->inherit =Helpers::getFieldValue( 'hpmXSG-Inherit', 0 );
		$settings->news =Helpers::getFieldValue( 'hpmXSG-News', 0 );
		DataAccess::saveMetaItem( $settings );
	}

 	static function renderAdd( $term_id ): void {
		self::addHooks();

		$settings = new MetaSettings( 1, 1, 1, 0 );
		$globalSettings = Core::getGlobalSettings();
		wp_nonce_field( basename( __FILE__ ), 'hpmXSG_meta_nonce' ); ?>
		<h3>Sitemap settings</h3>
		<p>Sitemap settings can be setup for individual categories/tags overriding the global settings. Category/tag settings will be inherited by related posts.</p>
		<p>&nbsp;</p>
		<div class="form-field term-description-wrap">
			<label for="hpmXSG-Exclude">Sitemap inclusion</label>
			<select name="hpmXSG-Exclude" id="hpmXSG-Exclude"></select>
			<p>Exclude this category/tag from your sitemap.</p>
		</div>
		<div class="form-field term-description-wrap">
			<label for="hpmXSG-Priority">Relative priority</label>
			<select name="hpmXSG-Priority" id="hpmXSG-Priority"></select>
			<p>Relative priority for this category/tag.</p>
		</div>
		<div class="form-field term-description-wrap">
			<label for="hpmXSG-Frequency">Update frequency</label>
			<select name="hpmXSG-Frequency" id="hpmXSG-Frequency"></select>
			<p>Sitemap update frequency for this category/tag.</p>
		</div>
		<div class="form-field term-description-wrap">
			<label for="hpmXSG-Inherit">Posts inheritance</label>
			<select name="hpmXSG-Inherit" id="hpmXSG-Inherit"></select>
			<p>Immediate child posts/pages inherit these settings.</p>
		</div>
		<?php if ( $globalSettings->newsMode == "2" ) { ?>
		<div class="form-field term-description-wrap">
		 	<label for="hpmXSG-News">Include in news</label>
			<select name="hpmXSG-News" id="hpmXSG-News"></select>
			<p>Include this category/tag in news feeds.</p>
		</div>
		<?php } ?>
		<script>
			xsg_populate( "hpmXSG-Exclude", excludeSelect, <?php echo esc_attr( $settings->exclude ); ?> );
			xsg_populate( "hpmXSG-Priority", prioritySelect, <?php echo esc_attr( $settings->priority ); ?> );
			xsg_populate( "hpmXSG-Frequency", frequencySelect, <?php echo esc_attr( $settings->frequency ); ?> );
			xsg_populate( "hpmXSG-Inherit", inheritSelect, <?php echo esc_attr( $settings->inherit ); ?> );
			xsg_populate( "hpmXSG-News", newsSelect, <?php echo esc_attr( $settings->news ); ?> );
		</script><?php
	}

	static function renderEdit( $tag ): void {
		$term_id = $tag->term_id;
		self::addHooks();
		$settings = DataAccess::getMetaItem( $term_id, "taxonomy" );
		$globalSettings = Core::getGlobalSettings();
		wp_nonce_field( basename( __FILE__ ), 'hpmXSG_meta_nonce' ); ?>
		<h3>Sitemap settings:</h3>
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required term-name-wrap">
					<th scope="row"><label for="hpmXSG-Exclude">Sitemap inclusion</label></th>
					<td>
						<select name="hpmXSG-Exclude" id="hpmXSG-Exclude"></select>
						<p>Exclude this category/tag from your sitemap.</p>
					</td>
				</tr>
				<tr class="form-field term-slug-wrap">
					<th scope="row"><label for="hpmXSG-Priority">Relative priority</label></th>
					<td>
						<select name="hpmXSG-Priority" id="hpmXSG-Priority"></select>
						<p>Relative priority for this category/tag and related posts.</p>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="hpmXSG-Frequency">Update frequency</label></th>
					<td>
						<select name="hpmXSG-Frequency" id="hpmXSG-Frequency"></select>
						<p>Sitemap update frequency for this category/tag.</p>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="hpmXSG-Inheri">Post inheritance</label></th>
					<td>
						<select name="hpmXSG-Inherit" id="hpmXSG-Inherit"></select>
						<p>Immediate child posts/pages inherit these settings.</p>
					</td>
				</tr>
				<?php if ( $globalSettings->newsMode == "2") { ?>
				<tr class="form-field">
					<th scope="row"><label for="description">Include in news</label></th>
					<td>
						<select name="hpmXSG-News" id="hpmXSG-News"></select>
						<p>Include this category/tag in news feeds.</p>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<script>
			xsg_populate( "hpmXSG-Exclude", excludeSelect, <?php echo esc_attr( $settings->exclude ); ?> );
			xsg_populate( "hpmXSG-Priority", prioritySelect, <?php echo esc_attr( $settings->priority); ?> );
			xsg_populate( "hpmXSG-Frequency", frequencySelect, <?php echo esc_attr( $settings->frequency ); ?> );
			xsg_populate( "hpmXSG-Inherit", inheritSelect, <?php echo esc_attr( $settings->inherit ); ?> );
			xsg_populate( "hpmXSG-News", newsSelect, <?php echo esc_attr( $settings->news ); ?> );
		</script><?php
	}
}