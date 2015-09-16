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
define( 'GRUENE_VERSION', '1.8.2' );

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
	add_image_size( 'gruene_large_post_thumbnail', 629 );
	
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


if ( ! function_exists( 'gruene_custom_home_category' ) ) :
/**
 * Sets the category chosen in the theme customizer for the home page
 * 
 * If the theme is set to blogs on the home page and a specific category
 * was choosen, then this functions filtes the WP_Query to output only
 * the chose category. If no spezific category was choosen, all categories
 * are shown
 * 
 * @since 1.6.0
 */
function gruene_custom_home_category( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
			// get the chosen category slug
			$cat_name = gruene_get_front_page_category();
			
			// if a category was choosen
			if ( is_string( $cat_name ) ) {
				$cat_ID = get_category_by_slug( $cat_name )->cat_ID;
				
				/**
				 * Hide sticky posts, which are not in the given category,
				 * make sticky posts unsticky. gruene_add_sticky_functionality()
				 * makes them sticky again. This is nessecary to exclude the
				 * sticky posts, which are not part of the home category, from
				 * the home query.
				 * 
				 * @since 1.8.2
				 */
				$query->set( 'ignore_sticky_posts', true );
				
				// filter the QP_Query
				$query->set( 'cat', $cat_ID );
			}
		}
	}
endif;
add_action( 'pre_get_posts', 'gruene_custom_home_category' );


if ( ! function_exists( 'gruene_get_front_page_category' ) ) :
/**
 * get the slug of the category choosen for the front page
 * if nothing was choosen return all post category slugs
 * 
 * @return string|array slug or array of slugs of the categories to display on the front page 
 */
function gruene_get_front_page_category() {
	$category = get_theme_mod( 'front_page_category' );
	
	// return all categories if set so or by default
	if ( 'all_categories' == $category || false == $category ) {
		$categories = get_categories();
		foreach ( $categories as $category ) {
			$return[] = $category->slug;
		}
		return $return;
	} else {
		// return given category
		return $category;
	}
}
endif;


if ( ! function_exists( 'gruene_add_sticky_functionality' ) ) :
/**
  * Places the sticky posts at the top of the list of posts for the category that is being displayed.
  *
  * @param	    array   $posts   The lists of posts to be displayed for the given category
  * @return	    array            The updated list of posts with the sticky posts moved to the beginning of the array
  *
  * @since      1.7.0
  */
function gruene_add_sticky_functionality( $posts, $query ) {
	
	$sticky_posts = array();
	
	// we only consider the main query of category pages
	/**
	 * also consider the home page
	 * 
	 * @since 1.8.2
	 */
	if ( $query->is_main_query() && ( is_category() || is_home() ) ) {
		
		// loop through the posts and move the sticky ones into an other array
		foreach( $posts as $post_index => $post ) {
			// if the post is sticky
			if ( is_sticky( $post->ID ) ) {
				// store it in the array $sticky_posts
				$sticky_posts[] = $post;
				// remove it from $posts so we don't duplicate its display
				unset( $posts[ $post_index ] );
			}
		}
		
	}
	// return an array with the sicky posts moved to the beginning
	return array_merge( $sticky_posts, $posts );
}
endif;
add_filter( 'the_posts', 'gruene_add_sticky_functionality', 10, 2 );


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
 * Load TGM Plugin (only for single site blogs – MU isn't supportet yet).
 */
require get_template_directory() . '/inc/tgm-plugin.php';

/**
 * Additional functionality, which basically also works theme independently.
 */
require get_template_directory() . '/inc/extras.php';