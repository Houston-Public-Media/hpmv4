<?php
namespace hpmSitemap;

// settings for generating a map
class Settings {
	public static function addHooks(): void {
		add_action( 'admin_menu', [ __CLASS__, 'admin_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function admin_menu(): void {
		add_options_page( 'XML Sitemap settings', 'XML Sitemap', 'manage_options', XSG_PLUGIN_NAME, [  __CLASS__, 'render' ] );
	}
	
	public static function register_settings(): void {
		register_setting( XSG_PLUGIN_NAME, XSG_PLUGIN_NAME );
	}

	static function getPostTypes(): array {
		$args = [
			'public'   => true,
			'_builtin' => false
		];
		$output = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')
		return get_post_types( $args, $output, $operator );
	}

	static function postTypeDefault( $sitemapDefaults, $name ) {
		return ( $sitemapDefaults->{$name} ?? $sitemapDefaults->posts );
	}
	
	static function getDefaults( $name ): metasettings {
		$settings = new MetaSettings();
		$settings->exclude = Helpers::getFieldValue( $name . 'Exclude', 0 );
		$settings->priority = Helpers::getFieldValue( $name . 'Priority' , 0 );
		$settings->frequency = Helpers::getFieldValue( $name . 'Frequency', 0 );
		$settings->scheduled = Helpers::getFieldValue( $name . 'Scheduled', 0 );
		return $settings;
	}

	static function handlePostBack(): void {
        if ( strtoupper( Helpers::geServerValue( 'REQUEST_METHOD', '' ) ) != 'POST' ) {
			return;
		}

		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['hpmXSG_meta_nonce'] ) || !wp_verify_nonce( $_POST['hpmXSG_meta_nonce'], basename( __FILE__ ) ) ) {
			return ;
		}

		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}

		$globalsettings = new globalsettings();

		$globalsettings->newsMode = Helpers::getFieldValue( 'newsMode', 0 );
		$globalsettings->enableImages = Helpers::getFieldValue( 'enableImages', false );
		$globalsettings->addRssToHead  = Helpers::getFieldValue( 'addRssToHead', true );
		$globalsettings->urlXmlSitemap = Helpers::getFieldValue( 'urlXmlSitemap' , "sitemap.xml" );
		$globalsettings->urlNewsSitemap = Helpers::getFieldValue( 'urlNewsSitemap', "newssitemap.xml" );
		$globalsettings->urlRssSitemap = Helpers::getFieldValue( 'urlRssSitemap' , "rsssitemap.xml" );
		$globalsettings->urlRssLatest = Helpers::getFieldValue( 'urlRssLatest', "rsslatest.xml" );
		$globalsettings->urlHtmlSitemap = Helpers::getFieldValue( 'urlHtmlSitemap', "htmlsitemap.htm" );

		update_option( "hpmXSG_global" ,  $globalsettings , true );
		Core::add_rewrite_rules();
		flush_rewrite_rules();

		$sitemapDefaults = new SitemapDefaults();

		$sitemapDefaults->dateField = Helpers::getFieldValue( 'dateField', 'updated' );
		$sitemapDefaults->homepage = self::getDefaults( "homepage" );
		$sitemapDefaults->pages = self::getDefaults( "pages" );
		$sitemapDefaults->posts = self::getDefaults( "posts" );
		$sitemapDefaults->taxonomyCategories = self::getDefaults( "taxonomyCategories" );
		$sitemapDefaults->taxonomyTags = self::getDefaults( "taxonomyTags" );

		$sitemapDefaults->recentArchive = self::getDefaults( "recentArchive" );
		$sitemapDefaults->oldArchive  = self::getDefaults( "oldArchive" );
		$sitemapDefaults->authors  = self::getDefaults( "authors" );

		$sitemapDefaults->excludeRules = Helpers::getFieldValue( 'excludeRules',"" );

		foreach ( self::getPostTypes() as $post_type ) {
			$sitemapDefaults->{$post_type} = self::getDefaults( $post_type );
		}
		update_option( "hpmXSG_sitemapDefaults", $sitemapDefaults, false );
	}
 
	static function RenderDefaultSection( $title, $name, $defaults, $scheduled ): void { ?>
		<tr>
			<td><?php echo esc_html__( $title ); ?></td>
			<td><select name="<?php echo esc_attr( $name ); ?>Exclude" id="<?php echo esc_attr( $name ); ?>Exclude"></select></td>
			<td><select name="<?php echo esc_attr( $name ); ?>Priority" id="<?php echo esc_attr( $name ); ?>Priority"></select></td>
			<td><select name="<?php echo esc_attr( $name ); ?>Frequency" id="<?php echo esc_attr( $name ); ?>Frequency"></select></td>
		<?php if ( $scheduled ) { ?>
			<td><input type="checkbox" name="<?php echo esc_attr( $name ); ?>Scheduled" id="<?php echo esc_attr( $name ); ?>Scheduled"<?php if ( $defaults->scheduled ) { echo ' checked="checked"'; } ?> /></td>
		<?php } else { ?>
			<td></td>
		<?php } ?>
		</tr>
		<script>
			xsg_populate("<?php echo esc_attr( $name ); ?>Exclude", excludeDefaults, <?php echo esc_attr( $defaults->exclude ); ?>);
			xsg_populate("<?php echo esc_attr( $name ); ?>Priority", priorityDefaults, <?php echo esc_attr( $defaults->priority ); ?>);
			xsg_populate("<?php echo esc_attr( $name ); ?>Frequency", frequencyDefaults, <?php echo esc_attr( $defaults->frequency ); ?>);
		</script><?php
	}

	public static function renderSitemapLink( $globalsettings, $property, $name ): void {
		$blogUrl = get_bloginfo( 'url' );
		$fileUrl = Helpers::safeRead( $globalsettings, $property );
		if ( strlen( $fileUrl ) > 0 ) {
			echo '<li><a target="_blank" href="' . esc_url( $blogUrl .'/' . $fileUrl )  . '">' . esc_attr( $name ) .  '</a></li>';
		} else {
			echo '<li>' . esc_attr( $name ) . ' (disabled)</li>';
		}
	}

	public static function render(): void {
		self::handlePostBack();
		$globalsettings = Core::getGlobalsettings();
		$sitemapDefaults = get_option( "hpmXSG_sitemapDefaults", new SitemapDefaults() ); ?>
		<style>
			.defaultsList li { padding: 5px; }
		</style>
		<form method="post">
			<?php wp_nonce_field( basename( __FILE__ ), 'hpmXSG_meta_nonce' ); ?>
			<div class="wrap">
				<h2>HPM Sitemap</h2>
				<p>Here you can edit your admin settings and defaults. You can override categories, tags, pages and posts when adding and editing them.</p>
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<div class="inner-sidebar">
						<div class="meta-box-sortabless ui-sortable" style="position: relative;">
							<div class="postbox">
								<h3 class="hndle"><span>Sitemap related urls</span></h3>
								<div class="inside">
									<p>Pages that are created or modified by HPM Sitemap</p>
									<ul><?php
										self::renderSitemapLink( $globalsettings, "urlXmlSitemap", "XML Sitemap" );
										self::renderSitemapLink( $globalsettings, "urlRssSitemap", "RSS Sitemap" );
										self::renderSitemapLink( $globalsettings, "urlRssLatest", "RSS New Pages" );
										self::renderSitemapLink( $globalsettings, "urlHtmlSitemap", "HTML Sitemap" );
									?></ul>
								</div>
							</div>
							<div class="postbox">
								<h3 class="hndle"><span>Webmaster tools</span></h3>
								<div class="inside">
									<p>It is highly recommended you register your sitemap with webmaster tools to obtain performance insights.</p>
									<ul>
										<li><a href="https://www.google.com/webmasters/tools/">Google Webmaster tools</a></li>
										<li><a href="https://www.bing.com/toolbox/webmaster">Bing Webmaster tools</a></li>
										<li><a href="https://zhanzhang.baidu.com/">Baidu Webmaster tools</a></li>
										<li><a href="https://webmaster.yandex.com/">Yandex Webmaster tools</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="has-sidebar">
						<div id="post-body-content" class="has-sidebar-content">
							<div class="meta-box-sortabless">
								<div class="postbox">
									<h3 class="hndle"><span>Output urls</span></h3>
									<div class="inside">
										<p>You can change the URL for the various sitemap files using the settings below. Set it to an empty string to disable.</p>
										<p>Caution should be take to avoid conflicts with other plugins which might output similar files. Please ensure it is a simple filename with no slashes and only one dot.</p>
										<table>
											<tr>
												<td>
													<p><label for="urlXmlSitemap">XML Sitemap URL</label><br /><input type="text" name="urlXmlSitemap" id="urlXmlSitemap" size="40" value="<?php echo Helpers::safeRead( $globalsettings, "urlXmlSitemap" ); ?>" /></p>
													<p><label for="urlRssSitemap">RSS Sitemap URL</label><br /><input type="text" name="urlRssSitemap" id="urlRssSitemap" size="40" value="<?php echo Helpers::safeRead( $globalsettings, "urlRssSitemap" ); ?>" /></p>
													<p><label for="urlHtmlSitemap">HTML Sitemap URL</label><br /><input type="text" name="urlHtmlSitemap" id="urlHtmlSitemap" size="40" value="<?php echo Helpers::safeRead( $globalsettings, "urlHtmlSitemap" ); ?>" /></p>
												</td>
												<td>&nbsp;</td>
												<td style="vertical-align:top">
													<p><label for="urlNewsSitemap">XML News Sitemap URL</label><br /><input type="text" name="urlNewsSitemap" id="urlNewsSitemap" size="40" value="<?php echo Helpers::safeRead( $globalsettings, "urlNewsSitemap" ); ?>" /></p>
													<p><label for="urlRssLatest">RSS Latest URLs</label><br /><input type="text" name="urlRssLatest" id="urlRssLatest" size="40" value="<?php echo Helpers::safeRead( $globalsettings, "urlRssLatest" ); ?>" /></p>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="postbox">
									<h3 class="hndle"><span>General settings</span></h3>
									<div class="inside">
										<ul class="defaultsList">
											<li>
												<label for="newsMode">News sitemap : <input type="radio" name="newsMode" value="0" <?php checked( $globalsettings->newsMode, '0' ); ?> />
													Disabled &nbsp;<input type="radio" name="newsMode" value="1" <?php checked( $globalsettings->newsMode, '1' ); ?> />
													Include all posts &nbsp;<input type="radio" name="newsMode" value="2" <?php checked($globalsettings->newsMode, '2'); ?> /> Selected tags / categories</label>
											</li>
											<li><input type="checkbox" name="enableImages" id="enableImages" value="1" <?php checked( $globalsettings->enableImages, '1' ); ?> /> <label for="enableImages">Enable images in sitemap</label></li>
											<li><input type="checkbox" name="addRssToHead" id="addRssToHead" value="1" <?php checked( $globalsettings->addRssToHead, '1' ); ?> /> <label for="addRssToHead">Add latest pages / post RSS feed to head tag</label><br></li>
										</ul>
									</div>
								</div>
								<div class="postbox">
									<h3 class="hndle"><span>Sitemap defaults</span></h3>
									<div class="inside">
										<p>Set the defaults for your sitemap here.</p>
										<ul>
											<li>
												<select name="dateField" id="dateField">
													<option value="created" <?php echo ( $sitemapDefaults->dateField == "created" ? 'selected="selected"' : '' ); ?>>Created</option>
													<option value="updated" <?php echo ( $sitemapDefaults->dateField == "updated" ? 'selected="selected"' : '' ); ?>>Updated</option>
												</select> <label for="dateField">date field to use for modified date / recently updated.</label>
											</li>
										</ul>
										<p>You can override the sitemap default settings for taxonomy items (categories, tags, etc), pages and posts when adding and editing them.</p>
										<table class="wp-list-table widefat fixed striped tags" style="clear: none;" aria-label="General sitemap defaults">
											<thead>
												<tr>
													<th scope="col">Page / area</th>
													<th scope="col">Exclude</th>
													<th scope="col">Relative priority</th>
													<th scope="col">Update frequency</th>
													<th scope="col">Include scheduled</th>
												</tr>
											</thead>
											<tbody id="the-list">
												<?php
													self::RenderDefaultSection( "Home page", "homepage", $sitemapDefaults->homepage, false );
													self::RenderDefaultSection( "Regular page", "pages", $sitemapDefaults->pages, true );
													self::RenderDefaultSection( "Post page", "posts", $sitemapDefaults->posts, true );
													self::RenderDefaultSection( "Taxonomy - categories", "taxonomyCategories", $sitemapDefaults->taxonomyCategories, false );
													self::RenderDefaultSection( "Taxonomy - tags", "taxonomyTags", $sitemapDefaults->taxonomyTags, false );

													self::RenderDefaultSection( "Archive - recent", "recentArchive", $sitemapDefaults->recentArchive, false );
													self::RenderDefaultSection( "Archive - old", "oldArchive", $sitemapDefaults->oldArchive, false );
													self::RenderDefaultSection( "Authors", "authors", $sitemapDefaults->authors, false );
												?>
											</tbody>
										</table>
										<p>Custom post types</p>
										<table class="wp-list-table widefat fixed striped tags" style="clear: none;" aria-label="Custom posts sitemap defaults">
											<thead>
												<tr>
													<th scope="col">Page / area</th>
													<th scope="col">Exclude</th>
													<th scope="col">Relative priority</th>
													<th scope="col">Update frequency</th>
													<th scope="col">Include scheduled</th>
												</tr>
											</thead>
											<tbody>
												<?php
													foreach ( self::getPostTypes() as $post_type ) {
														self::RenderDefaultSection( $post_type, $post_type, self::postTypeDefault( $sitemapDefaults, $post_type ), true );
													} ?>
											</tbody>
										</table>
									</div>
								</div>
								<?php submit_button(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form><?php
	}
}