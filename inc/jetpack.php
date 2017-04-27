<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package Gruene Theme
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function gruene_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'gruene_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function gruene_jetpack_setup
add_action( 'after_setup_theme', 'gruene_jetpack_setup' );

function gruene_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function gruene_infinite_scroll_render