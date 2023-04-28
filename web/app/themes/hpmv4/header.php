<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" dir="ltr" prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb#">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'body_open' ); ?>