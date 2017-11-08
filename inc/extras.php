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

// Enable short codes for widgets
add_filter('widget_text','do_shortcode');