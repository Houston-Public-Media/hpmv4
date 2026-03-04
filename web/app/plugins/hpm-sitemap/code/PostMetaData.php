<?php
namespace hpmSitemap;

include_once 'DataAccess.php';
include_once 'Helpers.php';

class PostMetaData {
	public static function addHooks(): void {
		add_action( 'save_post', [ __CLASS__, 'handlePostBack' ], 10, 2 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'addMetaBoxMenu' ] );
	}

	static function addMetaBoxMenu(): void {
		add_meta_box(
			'hpmXSG-meta',
			'XML Sitemap',
			[ __CLASS__, 'render' ],
			null,
			'side',
			'core'
		);
	}

	static function handlePostBack( $post_id, $post ): int {
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['hpmXSG_meta_nonce'] ) || !wp_verify_nonce( $_POST['hpmXSG_meta_nonce'], basename( __FILE__ ) ) ) {
			return 0;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		$settings = new MetaSettings();
		$settings->itemId = $post_id;
		$settings->itemType = "post";
		$settings->exclude = Helpers::getFieldValue( 'hpmXSG-Exclude', 0 );
		$settings->priority = Helpers::getFieldValue( 'hpmXSG-Priority', 1 );
		$settings->frequency = Helpers::getFieldValue( 'hpmXSG-Frequency', 1 );
		$settings->news = 0;

		DataAccess::saveMetaItem($settings);
		return $post_id;
	}

	static function render( $post ): void {
		$settings = DataAccess::getMetaItem( $post->ID, "post" );
		wp_nonce_field( basename( __FILE__ ), 'hpmXSG_meta_nonce' ); ?>
		<div class="components-panel__row">
			<div class="components-base-control">
				<div class="components-base-control__field">
					<label class="components-base-control__label" for="hpmXSG-Exclude">Sitemap inclusion</label><br />
					<select name="hpmXSG-Exclude" id="hpmXSG-Exclude"></select>
				</div>
			</div>
		</div>
		<div class="components-panel__row">
			<div class="components-base-control">
				<div class="components-base-control__field">
					<label class="components-base-control__label" for="hpmXSG-Priority">Relative priority</label><br />
					<select name="hpmXSG-Priority" id="hpmXSG-Priority"></select>
				</div>
			</div>
		</div>
		<div class="components-panel__row">
			<div class="components-base-control">
				<div class="components-base-control__field">
					<label class="components-base-control__label" for="hpmXSG-Frequency">Update frequency</label><br />
					<select name="hpmXSG-Frequency" id="hpmXSG-Frequency"></select>
				</div>
			</div>
		</div>
		<script>
			xsg_populate( "hpmXSG-Exclude", excludeSelect, <?php echo esc_attr( $settings->exclude ); ?> );
			xsg_populate( "hpmXSG-Priority", prioritySelect, <?php echo esc_attr( $settings->priority ); ?> );
			xsg_populate( "hpmXSG-Frequency", frequencySelect, <?php echo esc_attr( $settings->frequency ); ?> );
		</script><?php
	}
}