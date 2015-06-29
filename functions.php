<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Gruene Theme functions and definitions
 *
 * @package Gruene Theme
 */

/**
 * Version number of theme. Dont forget to change it also in the style.css file
 */
define( 'GRUENE_VERSION', '1.3.0' );

if ( ! function_exists( 'gruene_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gruene_content_width() {
	$GLOBALS['content_width'] = 649;
}
endif;
add_action( 'after_setup_theme', 'gruene_content_width', 0 );

if ( ! function_exists( 'gruene_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gruene_setup() {
	
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Gruene Theme, use a find and replace
	 * to change 'gruene' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'gruene', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, array( 'center', 'center' ) );
	
	/**
	 * Add image size for default post thumbnail
	 * 
	 * @see gruene_the_post_thumbnail()
	 * @see add_image_size()
	 */
	add_image_size( 'gruene_default_post_thumbnail', get_option( 'thumbnail_size_w' ), get_option( 'thumbnail_size_h' ), false );
	
	/**
	 * Add image size for large post thumbnails
	 * 
	 * @see add_image_size()
	 */
	add_image_size( 'gruene_large_post_thumbnail', $GLOBALS['content_width'] );
	
	// This theme uses wp_nav_menu() in several locations.
	register_nav_menus( array(
		'primary'     => esc_html__( 'Primary Menu', 'gruene' ),
		'meta'        => esc_html__( 'Meta Menu', 'gruene' ),
		'footer'      => esc_html__( 'Footer Menu', 'gruene' ),
		'language'    => esc_html__( 'Language Menu', 'gruene' ),
		'footer-meta' => esc_html__( 'Footer Meta Menu', 'gruene' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );
	
}
endif; // gruene_setup
add_action( 'after_setup_theme', 'gruene_setup', 10 );

if ( ! function_exists( 'gruene_widgets_init' ) ) :
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function gruene_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'gruene' ),
		'id'            => 'sidebar-widget-area',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'gruene' ),
		'id'            => 'footer-widget-area',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
endif;
add_action( 'widgets_init', 'gruene_widgets_init' );

if ( ! function_exists( 'gruene_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function gruene_scripts() {
	wp_enqueue_style( 'gruene-style', get_stylesheet_uri(), array(), GRUENE_VERSION );

	//wp_enqueue_script( 'gruene-navigation', get_template_directory_uri() . '/js/navigation.js', array(), GRUENE_VERSION, true );
	//wp_enqueue_script( 'gruene-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), GRUENE_VERSION, true );
	$deps = array(
		'jquery',
	);
	wp_enqueue_script( 'gruene-functions', get_template_directory_uri() . '/js/functions.js', $deps, GRUENE_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'gruene_scripts' );

if ( ! function_exists( 'gruene_custom_header_setup' ) ) :
/**
 * Set up the WordPress core custom header feature.
 */
function gruene_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'gruene_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/img/logo-default.png',
		'width'                  => 500, // make it two times bigger than expected to be retina compatible
		'height'                 => 308, // make it two times bigger than expected to be retina compatible
		'flex-height'            => true,
		'uploads'                => true,
	) ) );
}
endif;
add_action( 'after_setup_theme', 'gruene_custom_header_setup' );


if ( ! function_exists( 'gruene_add_custom_header_branding' ) ) :
/**
 * Adds the needed inline css to the show branding in the header
 */
function gruene_add_custom_header_branding() {
	
	$url = get_header_image();
	$width = esc_attr( get_custom_header()->width ) / 2; // divide by 2 to make it retina compatible
	$height = esc_attr( get_custom_header()->height ) / 2; // divide by 2 to make it retina compatible
	
	$branding = '.site-branding a '.
		"{ background-image: url('$url'); width: {$width}px; height: {$height}px; background-size: {$width}px {$height}px; }";
	
	wp_add_inline_style( 'gruene-style', $branding );
}
endif;
add_action( 'wp_enqueue_scripts', 'gruene_add_custom_header_branding' );


if ( ! function_exists( 'gruene_add_editor_style_sheet' ) ) :
/**
 * load custom tiny mce editor styles
 */
function gruene_add_editor_style_sheet() {
	add_editor_style( 'gruene-editor-stlyes.css' );
}
endif;
if ( is_admin() ) {
	add_action( 'after_setup_theme', 'gruene_add_editor_style_sheet' );
}


if ( ! function_exists( 'gruene_config_mce_buttons_1' ) ) :
/**
 * customize first line of editor buttons
 */
function gruene_config_mce_buttons_1( $buttons ) {
	
	// foreget the given buttons
	// show the following ones
	$buttons = array(
		'bold',
		'italic',
		'underline',
		'strikethrough',
		'superscript',
		'subscript',
		'bullist',
		'numlist',
		'blockquote',
		'link',
		'unlink',
		'wp_more',
		'spellchecker',
		'dfw',
		'wp_adv',
	);
	
	return $buttons;
}
endif;
if ( is_admin() ) {
	add_filter( 'mce_buttons', 'gruene_config_mce_buttons_1' );
}


if ( ! function_exists( 'gruene_config_mce_buttons_2' ) ) :
/**
 * customize second line of editor buttons
 */
function gruene_config_mce_buttons_2( $buttons ) {	
	//remove buttons
	$remove = array(
		'alignjustify',
		'forecolor',
		'underline',
	);
	
	foreach( $remove as $del_val ){
		if ( ( $key = array_search( $del_val, $buttons ) ) !== false ) {
			unset( $buttons[ $key ] );
		}
	}
	
	return $buttons;
}
endif;
if ( is_admin() ) {
	add_filter( 'mce_buttons_2', 'gruene_config_mce_buttons_2' );
}


if ( ! function_exists( 'gruene_mce_block_formats' ) ) :
/**
 * customize editor blockformats and presettings of buttons
 */
function gruene_mce_advanced_customizations( $settings ) {
	
	$settings['block_formats'] = "Heading 1=h1; Heading 2=h2; Heading 3=h3; Paragraph=p";
	 
	/**
	 * turns on paste as plain text by default
	 * @since 1.2.0
	 */
	$settings['paste_as_text'] = true;
	
	return $settings;
}
endif;
if ( is_admin() ) {
	add_filter( 'tiny_mce_before_init', 'gruene_mce_advanced_customizations');
}


/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Gruene Theme
 */

if ( ! function_exists( 'gruene_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function gruene_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
endif;
add_filter( 'body_class', 'gruene_body_classes' );


if ( ! function_exists( 'gruene_custom_excerpt_more' ) ) :
/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function gruene_custom_excerpt_more( $output ) {
	if ( ! is_attachment() ) {
		$output .= ' ' . gruene_get_read_more_link();
	}
	return $output;
}
endif;
add_filter( 'get_the_excerpt', 'gruene_custom_excerpt_more' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load TGM Plugin (only for single site blogs)
 */
require get_template_directory() . '/inc/tgm-plugin.php';