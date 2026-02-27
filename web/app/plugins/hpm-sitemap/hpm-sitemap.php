<?php
namespace hpmSitemap;
/*
Plugin Name:  HPM Sitemap Generator
Plugin URI: https://github.com/Houston-Public-Media/hpmv4
Description: HTML, RSS and Google XML Sitemap generator compatible with Google, Bing, Baidu, Yandex and more.
Version: 2.1.0
Author: HPM
Author URI: https://github.com/jwcounts
License: GPL2
*/

include 'code/Core.php';

function myPluginFile(): string {
	return __FILE__;
}
function xsgPluginPath(): string {
	return plugins_url() . "/" .  XSG_PLUGIN_NAME . "/";
}

if ( defined( 'ABSPATH' ) && defined( 'WPINC' ) ) {
	register_activation_hook( __FILE__, 'hpmSitemap\Core::activatePlugin' );
	add_action( "init", 'hpmSitemap\Core::initialisePlugin' );

	// used to redirect the user to the plugin settings when activated manually.
	add_action( 'activated_plugin', 'hpmSitemap\Core::activated' );

	add_action( 'upgrader_process_complete', 'hpmSitemap\Core::activatePlugin' );
}