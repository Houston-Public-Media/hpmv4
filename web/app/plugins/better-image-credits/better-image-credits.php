<?php
/*
Plugin Name: Better Image Credits
Plugin URI: http://vdvn.me/pga
Description: Adds credits and link fields for media uploads to display image credits in your posts.
Version: 2.0.4
Author: Claude Vedovini and Jared Counts
Author URI: http://vdvn.me/
License: GPLv3
Text Domain: better-image-credits
Domain Path: /languages

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/


function bic_get_option( $option, $default ) {
	$options = get_option( 'better-image-credits-options' );
	if ( $options && is_array( $options ) ) {
		return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
	}
	return get_option( 'better-image-credits_' . $option, $default );
}


define( 'IMAGE_CREDITS_TEMPLATE', bic_get_option( 'template', '<a href="[link]" target="_blank">[source]</a>' ) );
define( 'IMAGE_CREDITS_SEP', bic_get_option( 'sep', ',&#32;' ) );
define( 'IMAGE_CREDITS_BEFORE', bic_get_option( 'before', '<p class="image-credits">' . __( 'Image Credits', 'better-image-credits' ) . ':&#32;' ) );
define( 'IMAGE_CREDITS_AFTER', bic_get_option( 'after', '.</p>' ) );
define( 'IMAGE_CREDITS_OVERLAY_COLOR', bic_get_option( 'overlay_color', '' ) );

const IMAGE_CREDIT_BEFORE_CONTENT = 'before';
const IMAGE_CREDIT_AFTER_CONTENT = 'after';
const IMAGE_CREDIT_OVERLAY = 'overlay';

add_action( 'plugins_loaded', [ 'BetterImageCreditsPlugin', 'get_instance' ] );

class BetterImageCreditsPlugin {
	private static $instance;

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {
		add_action( 'init', [ $this, 'init' ] );
		if ( is_admin() ) {
			require_once 'class-admin.php';
			$this->admin = new BetterImageCreditsAdmin($this);
		}
	}

	function init() {
		if ( !is_admin() ) {
			add_filter( 'the_content', [ $this, 'add_credits' ], 100000 );
			add_filter( 'wp_get_attachment_image_attributes', [ $this, 'filter_attachment_image_attributes' ], 10, 2 );
		}
	}

	function display_option( $option ) {
		$options = bic_get_option( 'display', [] );
		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}
		return in_array( $option, $options );
	}

	function get_image_credits( $template = IMAGE_CREDITS_TEMPLATE ) {
		global $post;
		$post_thumbnail_id = 0;
		$attachment_ids = [];
		$credits = [];

		// Check for post thumbnail and save its ID in an array
		if ( isset( $post->ID ) && function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post->ID ) ) {
			$attachment_ids[] = $post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		}

		// Next look in post content and check for instances of wp-image-[digits]
		if ( isset($post->post_content ) && preg_match_all( '/wp-image-(\d+)/i', $post->post_content, $matches ) ) {
			foreach ( $matches[1] as $id ) {
				if ( !in_array( $id, $attachment_ids ) ) {
					$attachment_ids[] = $id;
				}
			}
		}

		// Finally check for galleries
		$pattern = get_shortcode_regex();
		if ( isset( $post->post_content ) &&
			preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
			&& array_key_exists( 2, $matches )
			&& in_array( 'gallery', $matches[2] ) ) {
			foreach ( $matches[2] as $index => $tag ) {
				if ( $tag == 'gallery' ) {
					$params = shortcode_parse_atts( $matches[3][ $index ] );

					if ( isset( $params['ids'] ) ) {
						$ids = explode( ',', $params['ids'] );

						foreach ( $ids as $id ) {
							$id = (int)$id;
							if ( $id > 0 ) {
								$attachment_ids[] = $id;
							}
						}
					}
				}
			}
		}

		// Make sure the ids only exist once
		$attachment_ids = array_unique( $attachment_ids );

		// Go through all our attachments IDs and generate credits
		foreach ( $attachment_ids as $id ) {
			if ( $c = $this->get_single_image_credits( $id, $template ) ) {
				$credits[ $id ] = $c;
			}
		}

		return $credits;
	}

	function get_single_image_credits( $id, $template = IMAGE_CREDITS_TEMPLATE ) {
		$att = get_post( $id );
		$title = ( !empty( $att->post_title ) ? $att->post_title : '' );
		$source = esc_attr( get_post_meta( $id, '_wp_attachment_source_name', true ) );
		$link = esc_url( get_post_meta( $id, '_wp_attachment_source_url', true ) );
		$license = esc_attr( get_post_meta( $id, '_wp_attachment_license', true ) );
		$license_link = esc_attr( get_post_meta( $id, '_wp_attachment_license_url', true ) );

		if ( !empty( $source ) ) {
			return str_replace(
				[ '[title]', '[source]', '[link]', '[license]', '[license_link]', '{title}', '{source}', '{link}', '{license}', '{license_link}' ],
				[ $title, $source, $link, $license, $license_link, $title, $source, $link, $license, $license_link ],
				$template
			);
		}

		return false;
	}

	function format_credits( $credits, $sep = IMAGE_CREDITS_SEP, $before = IMAGE_CREDITS_BEFORE, $after = IMAGE_CREDITS_AFTER ) {
		if ( !empty( $credits ) ) {
			$credits = array_unique( $credits );
			$credits = implode( $sep, $credits );
			return $before . $credits . $after;
		}

		return '';
	}

	function add_credits( $content ) {
		$credits = $this->get_image_credits();
		$output = $this->format_credits( $credits );

		if ( $this->display_option( IMAGE_CREDIT_BEFORE_CONTENT ) ) {
			$content = $output . $content;
		}

		if ( $this->display_option(IMAGE_CREDIT_AFTER_CONTENT ) ) {
			$content .= $output;
		}

		if ( $this->display_option( IMAGE_CREDIT_OVERLAY ) ) {
			foreach ( $credits as $id => $credit ) {
				$img_id = 'wp-image-' . $id;
				preg_match( '/(<p>)?(<a.+>)?(<img[ a-z\=\'\"0-9\-,\.\/\:A-Z\(\)]+' . $img_id . '[ A-Za-z\=\'\"0-9\-,\.\/\:\(\)_]+ \/>)(<\/a>)?(<\/p>)?/', $content, $match );
				if ( !empty( $match[3] ) ) {
					preg_match( '/class="([a-zA-Z\-0-9 ]+)"/', $match[3], $class );
					if ( str_contains( $credit, '<a href="" title="">' ) ) {
						$credit = str_replace( [ '<a href="" title="">', '</a>' ], [ '', '' ], $credit );
					}
					$credit = '<div class="credits-overlay">' . $credit . '</div>';
					$replace = '<div class="credits-container ' . ( !empty( $class ) ? $class[1] : $img_id ) . '">' .
						( $match[2] ?? '' ) . $match[3] . ( $match[4] ?? '' ) . $credit . '</div>';
					$content = str_replace( $match[0], $replace, $content );
				}
			}
		}

		return $content;
	}

	function filter_attachment_image_attributes( $attr, $attachment ) {
		$attr['class'] = $attr['class'] . ' wp-image-' . $attachment->ID;
		return $attr;
	}
}