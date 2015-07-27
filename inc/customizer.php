<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Gruene Theme Theme Customizer
 *
 * @package Gruene Theme
 */

/**
 * Removes unused controls.
 * Removes navigation section: We dont want everybody to dig in there.
 * Adds front page title
 * Adds front page category selection
 * Adds dynamic changing support for blogname, blogdescription and front_page_title
 * 
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gruene_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->remove_control( 'display_header_text' );
	$wp_customize->remove_section( 'nav' );
	
	/**
	 * Add category selection for the front page
	 */
	// rename default
	$wp_customize->get_control( 'show_on_front' )->choices['posts'] = __( 'Ihre letzten Beiträge der Kategorie', 'gruene' );
	
	// add setting
	$wp_customize->add_setting( 'front_page_category', array(
		'default'           => 'all_categories',
		'sanitize_callback' => 'gruene_sanitize_front_page_category',
	) );
	
	// add control
	$wp_customize->add_control( 'front_page_category', array(
		'type'            => 'select',
		'section'         => 'static_front_page',
		'label'           => __( 'Category for front page', 'gruene' ),
		'choices'         => gruene_get_possible_front_page_gategories(),
		'description'     => __( 'If the fist option was choosen, the front page shows posts of the selected category.', 'gruene' ),
	) );
	
	/**
	 * Add title for dynamic front page
	 */
	// add setting
	$wp_customize->add_setting( 'front_page_title', array(
		'default'           => __( 'Home', 'gruene' ),
		'sanitize_callback' => 'esc_html',
	) );
	
	//add control
	$wp_customize->add_control( 'front_page_title', array(
		'type'            => 'text',
		'section'         => 'static_front_page',
		'label'           => __( 'Front page title', 'gruene' ),
		'description'     => __( 'If the fist option was choosen: the title shown on the front page.', 'gruene' ),
		'input_attrs' => array(
			'placeholder' => __( 'Home', 'gruene' ),
		),
	) );
	
	// add dynamic changing support
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'front_page_title' )->transport = 'postMessage';
}

add_action( 'customize_register', 'gruene_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function gruene_customize_preview_js() {
	wp_enqueue_script( 'gruene_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), GRUENE_VERSION, true );
}
add_action( 'customize_preview_init', 'gruene_customize_preview_js' );


if ( ! function_exists( 'gruene_get_possible_front_page_gategories' ) ) :
/**
 * get keyed array with passible categories for front page 
 */
function gruene_get_possible_front_page_gategories() {
	$categories = get_categories();
	
	$possible_categories = array(
		'all_categories'	=> __( 'All categories', 'gruene' ),
	);
	
	foreach ( $categories as $category ) {
		$slug = esc_attr( $category->slug );
		$name = esc_html( $category->name );
		$possible_categories[ $slug ] = $name;
	}
	
	return $possible_categories;
}
endif;


if ( ! function_exists( 'gruene_sanitize_front_page_category' ) ) :
/**
 * check if the given value is a category
 * if not return false
 * 
 * @param string $data the choosen category
 * 
 * @return null|string category slug, if the category exists, else null
 */
function gruene_sanitize_front_page_category( $data ) {
	$categories = get_categories();
	$categories_slugs = array();
	
	foreach ( $categories as $category ) {
		$categories_slugs[] = $category->slug;
	}
	
	if ( in_array( $data, $categories_slugs ) || 'all_categories' == $data ) {
		return $data;
	} else {
		return null;
	}
}
endif;