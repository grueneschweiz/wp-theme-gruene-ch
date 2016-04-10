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
 * Adds front page title
 * Adds front page category selection
 * Adds dynamic changing support for blogname, blogdescription and front_page_title
 * 
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gruene_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->remove_control( 'display_header_text' );
	
     gruene_add_theme_spezific_settings( $wp_customize );
     gruene_add_category_selection_for_front_page( $wp_customize );
	gruene_add_title_for_dynamic_front_page( $wp_customize );
	gruene_add_aditional_header_image( $wp_customize );
     gruene_add_header_text( $wp_customize );
     
	// add dynamic changing support
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
}

add_action( 'customize_register', 'gruene_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function gruene_customize_preview_js() {
	wp_enqueue_script( 'gruene_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), GRUENE_VERSION, true );
}
add_action( 'customize_preview_init', 'gruene_customize_preview_js' );

/**
 * Add section with theme spezific settings like font, size of logo etc.
 * 
 * @param type $wp_customize @see customize_register action hook
 */
function gruene_add_theme_spezific_settings( $wp_customize ) {
     // add section
	$wp_customize->add_section( 'gruene_theme_options', array(
		'title'          => __( 'Theme options', 'gruene' ),
		'description'    => __( 'Please keep in mind, that some options impair the use of others.' .
                                  ' Please read the notes to all options.', 'gruene' ),
		'priority'       => 55,
	) );
	
	// add setting
	$wp_customize->add_setting( 'font_family', array(
		'default'        => 'open_sans',
	) );
     
     // add setting
	$wp_customize->add_setting( 'title_caps', array(
		'default'        => 'title_caps_none',
	) );
     
     // add setting
	$wp_customize->add_setting( 'theme_purpose', array(
		'default'        => 'politician',
	) );
	
     // add setting
	$wp_customize->add_setting( 'thumbnail_size', array(
		'default'        => 'small',
	) );
	
     // add setting
     $wp_customize->add_setting( 'mobile_nav_style', array(
         'default'         => 'modern',
     ) );
     
	// add control
	$wp_customize->add_control( 'font_family', array(
          'type'       => 'select',
          'section'    => 'gruene_theme_options',
		'label'      => __( 'Choose your prefered font type', 'gruene' ),
		'description'=> __( "Open Sans is the free alternative to Sanuk. It's therefore the recommended font.", 'gruene' ),
		'choices'    => array( 
              'open_sans' => 'Open Sans', 
              'tahoma' => 'Tahoma' 
          ),
	) );
     
     // add control
	$wp_customize->add_control( 'title_caps', array(
          'type'       => 'select',
          'section'    => 'gruene_theme_options',
		'label'      => __( 'Choose if you want to capitalize all titles', 'gruene' ),
		'description'=> __( 'If you set this to "capitalize" all titles will appear in capital letters.'.
                             ' Else the titles will show up the way they were written.', 'gruene' ),
		'choices'    => array( 
              'title_caps_none' => "Don't capitalize titles", 
              'title_caps_all'  => "Capitalize titles",
          ),
	) );
     
     // add control
	$wp_customize->add_control( 'theme_purpose', array(
          'type'       => 'select',
          'section'    => 'gruene_theme_options',
		'label'      => __( 'Choose the themes purpose', 'gruene' ),
		'description'=> __( 'This theme comes with a version optimized to represent a politician'.
                              ' and with a version to represent the party. Several other settings depend'.
                              ' on the option chosen. Just give it a try and see what happens.', 'gruene' ) .
                              ' <strong>' . __( 'Please save and force reload the page after update.', 'gruene' ) . '</strong>',
		'choices'    => array( 
              'politician' => __( 'Represent a politician', 'gruene' ),
              'party'      => __( 'Represent the party', 'gruene' ),
          ),
	) );
     
     // add control
	$wp_customize->add_control( 'thumbnail_size', array(
          'type'       => 'select',
          'section'    => 'gruene_theme_options',
		'label'      => __( 'Choose a thumbnail size', 'gruene' ),
		'description'=> __( 'Small thumbnails will float on the left, large ones will use the full content width.', 'gruene' ) .
                              ' <strong>' . __( 'A switch might require you to manually regenerate the thumbnails.' .
                                                'You may use the "Regenerate Thumbnails" plugin to do that.', 'gruene' ) . '</strong>',
                              ' <strong>' . __( 'Please save and force reload the page after update.', 'gruene' ) . '</strong>',
		'choices'    => array( 
              'small' => __( 'Small', 'gruene' ), 
              'large' => __( 'Large', 'gruene' ), 
          ),
	) );
     
     // add control
	$wp_customize->add_control( 'mobile_nav_style', array(
          'type'       => 'select',
          'section'    => 'gruene_theme_options',
		'label'      => __( 'Choose your prefered style for the mobile navigation', 'gruene' ),
		'description'=> __( 'The classic style indents subpages and comes in a grayisch style. The mordern one is magenta based and distinguishes subpages by color.', 'gruene' ),
		'choices'    => array( 
              'classic' => __( 'Classic', 'gruene' ),
              'modern'  => __( 'Modern', 'gruene' ),
          ),
	) );
}

/**
 * Add additional header image
 * 
 * @param type $wp_customize @see customize_register action hook
 */
function gruene_add_aditional_header_image( $wp_customize ) {
     // add section
	$wp_customize->add_section( 'gruene_additional_header_image', array(
		'title'          => __( 'Additional header image', 'gruene' ),
		'description'    => __( "You might only use an additional header image, if you don't use the language switcher menu." .
		                        " Deactivate the language switcher menu, if the image doesn't appear.", 'gruene' ),
		'priority'       => 75,
	) );
	
	// add setting
	$wp_customize->add_setting( 'additional_header_image', array(
		'default'        => false,
	) );
	
     // define image size
     $mode = get_theme_mod( 'theme_purpose', 'politician' );
     
     $width  = GRUENE_ADDITIONAL_HEADER_WIDTH;
     $height = GRUENE_ADDITIONAL_HEADER_HEIGHT;
     
     if ( 'politician' == $mode ) {
          $width  = $width * GRUENE_HEADER_IMAGE_SCALING_RATIO;
          $height = $height * GRUENE_HEADER_IMAGE_SCALING_RATIO;
     }
     
	// add control
	$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'additional_header_image', array(
		'label'      => __( 'Choose or upload your additional header image.', 'gruene' ),
		'description'=> __( "Its minimal resolution should be 500 x 308 px.", 'gruene' ),
		'section'    => 'gruene_additional_header_image',
		'settings'   => 'additional_header_image',
		'width'      => $width,
		'height'     => $height,
	) ) );
}

/**
 * Add text to header
 * 
 * @param type $wp_customize @see customize_register action hook
 */
function gruene_add_header_text( $wp_customize ) {
     // add section
	$wp_customize->add_section( 'gruene_header_text', array(
		'title'          => __( 'Header text', 'gruene' ),
		'description'    => '<strong>' . __( 'Note:', 'gruene' ) . '</strong>' .
                              __( ' This will only show up if you chose the theme mode "Represent a politician".'.
                                  ' You can change the theme mode in the theme options here in the customizer.', 'gruene' ),
		'priority'       => 76,
	) );
	
	// add setting
	$wp_customize->add_setting( 'gruene_header_text_line1', array(
		'default'          => __( 'Your function', 'gruene' ),
          'sanitize_callback'=> 'esc_html',
	) );
     
     // add setting
	$wp_customize->add_setting( 'gruene_header_text_line2', array(
		'default'          => __( 'Your name', 'gruene' ),
          'sanitize_callback'=> 'esc_html',
	) );
     
	//add control
	$wp_customize->add_control( 'gruene_header_text_line1', array(
		'type'            => 'text',
		'section'         => 'gruene_header_text',
		'label'           => __( 'Enter your function, for example "National Councillor".', 'gruene' ),
		'input_attrs' => array(
			'placeholder' => __( 'Your function', 'gruene' ),
		),
	) );
     
     //add control
	$wp_customize->add_control( 'gruene_header_text_line2', array(
		'type'            => 'text',
		'section'         => 'gruene_header_text',
		'label'           => __( 'Enter your name, "firstname lastname" is best.', 'gruene' ),
		'input_attrs' => array(
			'placeholder' => __( 'Your name', 'gruene' ),
		),
	) );
}


/**
 * Add category selection for the front page
 * 
 * @param type $wp_customize @see customize_register action hook
 */
function gruene_add_category_selection_for_front_page( $wp_customize ) {
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
		'choices'         => gruene_get_possible_front_page_categories(),
		'description'     => __( 'If the fist option was choosen, the front page shows posts of the selected category.', 'gruene' ),
	) );
}

/**
 * Add title for dynamic front page
 * 
 * @param type $wp_customize @see customize_register action hook
 */
function gruene_add_title_for_dynamic_front_page( $wp_customize ) {
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
	$wp_customize->get_setting( 'front_page_title' )->transport = 'postMessage';
}

if ( ! function_exists( 'gruene_get_possible_front_page_categories' ) ) :
/**
 * get keyed array with passible categories for front page 
 */
function gruene_get_possible_front_page_categories() {
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