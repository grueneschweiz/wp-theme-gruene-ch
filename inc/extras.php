<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Extras for this theme.
 *
 * @package Gruene Theme
 */

/**
 * Enable short codes for widgets
 *
 * Since WordPress 4.9 this filter is part of the core.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.9', '<' ) ) {
	add_filter('widget_text','do_shortcode');
}