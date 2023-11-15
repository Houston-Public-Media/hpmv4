<?php

$root_dir = dirname( __DIR__ );
define( 'SITE_ROOT', $root_dir );

$webroot_dir = $root_dir . '/web';

/**
 * Expose global env() function from oscarotero/env
 */
Env::init();

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv( $root_dir );
if ( file_exists( $root_dir . '/.env' ) ) {
    $dotenv->load();
    $dotenv->required( [ 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL' ] );
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define( 'WP_ENV', env( 'WP_ENV' ) ?: 'production' );

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if ( file_exists( $env_config ) ) {
    require_once $env_config;
}

/**
 * URLs
 */
// define('WP_CACHE', true);
if ( empty( $_SERVER['HTTP_HOST'] ) && WP_ENV == 'development' ) {
	$_SERVER['HTTP_HOST'] = 'dev.houstonpublicmedia.org';
}
if ( empty( $_SERVER['HTTP_X_FORWARDED_HOST'] ) && !empty( $_SERVER['HTTP_HOST'] ) ) {
	$_SERVER['HTTP_X_FORWARDED_HOST'] = $_SERVER['HTTP_HOST'];
}
if ( !empty( $_SERVER['HTTP_HOST'] ) && $_SERVER['HTTP_HOST'] === 'dev.houstonpublicmedia.org' && str_contains( $_SERVER['HTTP_X_FORWARDED_HOST'], 'ngrok.io' ) ) {
	define( 'WP_HOME', 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'] );
	define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'] . '/wp' );
} else {
	define( 'WP_HOME', env( 'WP_HOME' ) );
	define( 'WP_SITEURL', env( 'WP_SITEURL' ) );
}

/**
 * Custom Content Directory
 */
const CONTENT_DIR = '/app';
define( 'WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR );
const WP_CONTENT_URL = WP_HOME . CONTENT_DIR;

/**
 * DB settings
 */
define( 'DB_NAME', env('DB_NAME' ) );
define( 'DB_USER', env( 'DB_USER' ) );
define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
define( 'DB_HOST', env( 'DB_HOST' ) ?: '127.0.0.1' );
const DB_CHARSET = 'utf8mb4';
const DB_COLLATE = 'utf8mb4_unicode_ci';
$table_prefix = env( 'DB_PREFIX' ) ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

/**
 * Custom Settings
 */
const FORCE_SSL_ADMIN = false;
const WP_AUTO_UPDATE_CORE = false;
const AUTOMATIC_UPDATER_DISABLED = true;
define( 'DISABLE_WP_CRON', env( 'DISABLE_WP_CRON' ) ?: false );
const DISALLOW_FILE_EDIT = true;
const EMPTY_TRASH_DAYS   = 30;
const WP_POST_REVISIONS  = 7;
const WP_MAX_MEMORY_LIMIT = '1024M';
define( 'AWS_ACCESS_KEY_ID', env( 'AWS_ACCESS_KEY_ID' ) );
define( 'AWS_SECRET_ACCESS_KEY', env( 'AWS_SECRET_ACCESS_KEY' ) );
define( 'HPM_SFTP_PASSWORD', env( 'HPM_SFTP_PASSWORD' ) );
define( 'HPM_PBS_TVSS', env( 'HPM_PBS_TVSS' ) );
define( 'HPM_MVAULT_ID', env( 'HPM_MVAULT_ID' ) );
define( 'HPM_MVAULT_SECRET', env( 'HPM_MVAULT_SECRET' ) );
define( 'WP_CACHE_KEY_SALT', env( 'WP_HOME' ) );
define( 'HPM_TW_CONSUMER_KEY', env( 'TWITTER_CONSUMER_KEY' ) );
define( 'HPM_TW_CONSUMER_SECRET', env( 'TWITTER_CONSUMER_SECRET' ) );
define( 'HPM_TW_BEARER_TOKEN', env( 'TWITTER_BEARER_TOKEN' ) );
define( 'HPM_TW_ACCESS_TOKEN', env( 'TWITTER_ACCESS_TOKEN' ) );
define( 'HPM_TW_ACCESS_TOKEN_SECRET', env( 'TWITTER_ACCESS_TOKEN_SECRET' ) );
define( 'HPM_FB_PAGE_ID', env( 'FACEBOOK_PAGE_ID' ) );
define( 'HPM_FB_ACCESS_TOKEN', env( 'FACEBOOK_ACCESS_TOKEN' ) );
define( 'HPM_FB_APPSECRET', env( 'FACEBOOK_APPSECRET' ) );
define( 'HPM_YT_API_KEY', env( 'YT_API_KEY' ) );
define( 'HPM_MASTODON_BEARER', env( 'MASTODON_BEARER' ) );
const EWWW_IMAGE_OPTIMIZER_DEFER_S3 = true;

/**
 * Bootstrap WordPress
 */
if ( !defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', $webroot_dir . '/wp/' );
}
