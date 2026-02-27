<?php
namespace hpmSitemap;

include_once 'DataAccess.php';
include_once 'Helpers.php';

class AuthorMetaData {
	public static function addHooks(): void {
		add_action( 'edit_user_profile',  [ __CLASS__, 'renderEdit' ] );
		add_action( 'profile_update', [ __CLASS__, 'save_metaData' ], 10, 2 );
	}

	static function save_metaData( $userId ): void {
		/* Verify the nonce before proceeding. */
	 	if ( !isset( $_POST['hpmXSG_meta_nonce'] ) || !wp_verify_nonce( $_POST['hpmXSG_meta_nonce'], basename( __FILE__ ) ) ) {
			 return;
		}

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( 'edit_user') ){
			return;
		}

		$settings = new MetaSettings();

		$settings->id = helpers::getFieldValue( 'hpmXSG-metaId', 0 );
	 	$settings->itemId = $userId;
		$settings->itemType = "author";
	 	$settings->exclude = Helpers::getFieldValue( 'hpmXSG-Exclude', 0 );
	 	$settings->priority = Helpers::getFieldValue( 'hpmXSG-Priority', 1 );
	  	$settings->frequency = Helpers::getFieldValue( 'hpmXSG-Frequency', 1 );
		$settings->inherit = Helpers::getFieldValue( 'hpmXSG-Inherit', 0 );
		DataAccess::saveMetaItem( $settings );
	}

	static function renderEdit( $WP_User ): void {
		$userId = $WP_User->ID;
		self::addHooks();
		$settings = DataAccess::getMetaItem( $userId, "author" );
		wp_nonce_field( basename( __FILE__ ), 'hpmXSG_meta_nonce' ); ?>
		<h3>Sitemap settings:</h3>
		<table class="form-table" aria-label="Sitemap settings">
			<tbody>
				<tr class="form-field form-required term-name-wrap">
					<th scope="row"><label for="hpmXSG-Exclude">Sitemap inclusion</label></th>
					<td>
						<select name="hpmXSG-Exclude" id="hpmXSG-Exclude"></select>
						<p> Exclude this category/tag from your sitemap.</p>
					</td>
				</tr>
				<tr class="form-field term-slug-wrap">
					<th scope="row"><label for="hpmXSG-Priority">Relative priority</label></th>
					<td>
						<select name="hpmXSG-Priority" id="hpmXSG-Priority"></select>
						<p>Relative priority for this category/tag and related posts.</p>
					</td>
				</tr>
				<tr class="form-field term-description-wrap">
					<th scope="row"><label for="hpmXSG-Frequency">Update frequency</label></th>
					<td>
						<select name="hpmXSG-Frequency" id="hpmXSG-Frequency"></select>
						<p>Sitemap update frequency for this category/tag.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			xsg_populate( "hpmXSG-Exclude", excludeSelect, <?php echo esc_attr( $settings->exclude ); ?> );
			xsg_populate( "hpmXSG-Priority", prioritySelect, <?php echo esc_attr( $settings->priority ); ?> );
			xsg_populate( "hpmXSG-Frequency", frequencySelect, <?php echo esc_attr( $settings->frequency ); ?> );
			xsg_populate( "hpmXSG-Inherit", inheritSelect, <?php echo esc_attr( $settings->inherit ); ?> );
		</script><?php
	}
}