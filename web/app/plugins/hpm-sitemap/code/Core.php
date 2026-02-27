<?php
namespace hpmSitemap;
use Exception;

include_once 'DataAccess.php';
include_once 'SitemapDefaults.php';
include_once 'GlobalSettings.php';
include_once 'MetaSettings.php';
include_once 'Upgrader.php';
include_once 'Helpers.php';

define ( "XSG_PLUGIN_VERSION", "2.1.0" );
define ( "XSG_PLUGIN_NAME", "hpm-sitemap" );
// settings for general operation and rendering
class Core {
	public static function pluginFilename(): string {
		return plugin_basename( myPluginFile() );
	}

	public static function getGlobalSettings() {
		$globalSettings = get_option( "hpmXSG_global", new GlobalSettings() );

		// ensure when we read the global settings we have urls assigned
		$globalSettings->urlXmlSitemap = Helpers::safeRead2( $globalSettings, "urlXmlSitemap", "sitemap.xml" );
		$globalSettings->urlNewsSitemap = Helpers::safeRead2( $globalSettings, "urlNewsSitemap", "newssitemap.xml" );
		$globalSettings->urlRssSitemap = Helpers::safeRead2( $globalSettings, "urlRssSitemap", "rsssitemap.xml" );
		$globalSettings->urlRssLatest = Helpers::safeRead2( $globalSettings, "urlRssLatest", "rsslatest.xml" );
		$globalSettings->urlHtmlSitemap = Helpers::safeRead2( $globalSettings, "urlHtmlSitemap", "htmlsitemap.htm" );

		return $globalSettings;
	}
	
	// called for each site being activated.
	public static function doSiteActivation(): void {
		self::addDatabaseTable();
		Upgrader::doUpgrade();

		self::add_rewrite_rules();
		flush_rewrite_rules();

		add_option( "hpmXSG_MapId", uniqid( "", true ) );
	}

	public static function activatePlugin(): void {
		self::doSiteActivation();
	}

	// used to redirect the user to the plugin settings when activated manually.
	public static function activated( $plugin ): void {
		if ( $plugin == self::pluginFilename() ) {
			wp_redirect( admin_url( 'options-general.php?page=hpm-sitemap' ) );
			exit;
		}
	}

	public static function adminScripts(): void {
		wp_enqueue_script( 'xsgScripts', xsgPluginPath() . 'assets/scripts.js' , false );
	}

	public static function initialisePlugin(): void {
 		self::add_rewrite_rules();
		add_filter( 'query_vars', [ __CLASS__, 'add_query_variables' ], 1, 1 );
		add_filter( 'template_redirect', [ __CLASS__, 'templateRedirect' ], 1, 0 );

		// disable wordpress sitemap
		remove_action( 'init', 'wp_sitemaps_get_server' );
 
		// 2 is required for $file to be populated
		add_filter( 'plugin_row_meta', [ __CLASS__, 'filter_plugin_row_meta' ], 10, 2 );
		add_action( 'wp_head', [ __CLASS__, 'addRssLink' ], 100 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'adminScripts' ], 100 );

		// only include admin files when necessary.
		if ( is_admin() && !is_network_admin() ) {
			include_once 'Settings.php';
			include_once 'PostMetaData.php';
			include_once 'CategoryMetaData.php';
			include_once 'AuthorMetaData.php';

			settings::addHooks();
			CategoryMetaData::addHooks();
			PostMetaData::addHooks();
			AuthorMetaData::addHooks();
			add_action( 'admin_notices', [ __CLASS__, 'showWarnings' ] );
		}
	}

	public static function addRewriteUrl( $property, $newUrl ): void {
		$url = self::getGlobalProperty( $property );
		if ( strlen( $url ) > 0 ) {
			$url = str_replace(".","\.",$url) . '$';
			add_rewrite_rule($url, $newUrl, 'top');
		}
	}

	public static function add_rewrite_rules(): void {
		self::addRewriteUrl( "urlXmlSitemap", 'index.php?xsg-format=xml&xsg-provider=index&xsg-type=index&xsg-page=1' );
		self::addRewriteUrl( "urlNewsSitemap",'index.php?xsg-format=news&xsg-provider=news&xsg-type=news&xsg-page=1' );
		self::addRewriteUrl( "urlRssSitemap", 'index.php?xsg-format=rss&xsg-provider=index&xsg-type=index&xsg-page=1' );
		self::addRewriteUrl( "urlRssLatest", 'index.php?xsg-format=rss&xsg-provider=latest&xsg-type=latest&xsg-page=1' );
		self::addRewriteUrl( "urlHtmlSitemap", 'index.php?xsg-format=htm&xsg-provider=index&xsg-type=index&xsg-page=1' );
		add_rewrite_rule( "sitemap-files/([a-z]+)/([a-z]+)/([^/]+)/([0-9]+)/?", 'index.php?xsg-format=$matches[1]&xsg-provider=$matches[2]&xsg-type=$matches[3]&xsg-page=$matches[4]&', 'top' );
	}

	public static function add_query_variables( $vars ): array {
		$vars[] = 'xsg-format';
		$vars[] = 'xsg-provider';
		$vars[] = 'xsg-type';
		$vars[] = 'xsg-page';
		return $vars;
	}

	static function showWarnings(): void {
		$screen = get_current_screen();
		if ( $screen->base == 'settings_page_hpm-sitemap' ) {
			$warnings = "";
			$blog_public = get_option( 'blog_public' );
			if ( $blog_public == "0" ) {
				$warnings = "<p>Your website is hidden from search engines. Please check your <i>Search Engine Visibility in <a href=\"options-reading.php\">Reading Settings</a></i>.</p>";
			}

			if ( !get_option( 'permalink_structure' ) ) {
				$warnings = $warnings . "<p>Permalinks are not enabled. Please check your <i><a href=\"options-permalink.php\">Permalink Settings</a></i> are NOT set to <i>Plain</i>.</p>";
			}

			if ( $warnings ) {
				echo '<div id="sitemap-warnings" class="error fade"><p><strong>Problems that will prevent your sitemap working correctly: </strong></p>' .  $warnings . '</div>';
			}
		}
	}

	static function addDatabaseTable(): void {
		try {
			DataAccess::createMetaTable();
			update_option( "hpmXSG_databaseUpgraded", 1, false );
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );
		}
	}
 
	private static function readQueryVar( $name ) {
		global $wp_query;
		if ( !empty( $wp_query->query_vars[$name] ) ) {
			return $wp_query->query_vars[$name];
		}
		return null;
	}

	public static function templateRedirect(): void {
		$format = self::readQueryVar( "xsg-format" );
		$provider = self::readQueryVar( "xsg-provider" );
		$type = self::readQueryVar( "xsg-type" );
		$page = self::readQueryVar( "xsg-page" );

		if ( $format != null && $provider != null && $type != null && $page !=null ) {
			global $wp_query;
			global $wp;
			$wp_query->is_404 = false;
			$wp_query->is_feed = false;
			self::render( $format, $provider,  $type,  $page );
			exit;
		}
	}

	public static function render( $format, $provider, $type, $page ): void {
		include_once 'renderers/CoreRenderer.php';
		include_once 'providers/CoreProvider.php';

		$providerInstance = SitemapProvider::getInstance( $provider );
		$renderer = SitemapRenderer::getInstance( $format );
		if ( $providerInstance == null || $renderer == null ) {
			echo 'XML Sitemap Generator Error. <br />no provider or renderer loaded';
			exit;
		}

		$providerInstance->setFormat( $format );
		$urls = $providerInstance->getPage( $type, $page );

		if ( $provider == "index" ) {
			$renderer->renderIndex( $urls );
		} else {
			$renderer->renderPages( $urls );
		}
	}

	public static function addRssLink(): void {
		$globalSettings = self::getGlobalSettings();
		if ( $globalSettings->addRssToHead ) {
			$base = trailingslashit( get_bloginfo( 'url' ) );
			$url = $base . "rsslatest.xml";
			echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="' .  esc_url( $url ) . '" />';
		}
	}

	public static function getGlobalProperty( $property ) {
		$globalSettings = self::getGlobalSettings();
		return Helpers::safeRead( $globalSettings, $property );
	}

	static function filter_plugin_row_meta( $links, $file ) {
		$plugin = self::pluginFilename();
		if ( $file == $plugin ) {
			$links = array_merge( $links, [ '<a href="options-general.php?page=' .  XSG_PLUGIN_NAME . '">settings</a>' ] );
		}
		return $links;
	}
}