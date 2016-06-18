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
define( 'GRUENE_VERSION', '2.2.5' );

/**
 * Header image sizes 
 * 
 * Enter twice the size (HiDPI) of the header images for the party theme purpose
 */
define( 'GRUENE_CUSTOM_HEADER_WIDTH', 500 );
define( 'GRUENE_CUSTOM_HEADER_HEIGHT', 308);
define( 'GRUENE_ADDITIONAL_HEADER_WIDTH', 500 );
define( 'GRUENE_ADDITIONAL_HEADER_HEIGHT', 308 );

/**
 * Scaling ratio for header images for the politician theme purpose
 */
define( 'GRUENE_HEADER_IMAGE_SCALING_RATIO', 0.75 );

/**
 * Themes content width in pixels, based on the theme's design and stylesheet
 */
define( 'GRUENE_CONTENT_WIDTH', 649 );

/**
 * Holds the original query of a campaign page (template)
 */
$gruene_campaign_query = array();

if ( ! function_exists( 'gruene_update' ) ) :
/**
 * Upgrade routine
 */
function gruene_update() {
     $current_version = get_theme_mod( 'version_number', null );
			
     // if everything is up to date stop here
     if ( GRUENE_VERSION == $current_version ) {
          return; // BREAKPOINT
     }

     // run the upgrade routine for versions smaller 2.0.0
     if ( -1 == version_compare( $current_version, '2.0.0' ) ) {
          set_theme_mod( 'font_family', 'tahoma' );
          set_theme_mod( 'theme_purpose', 'party' );
          set_theme_mod( 'thumbnail_size', 'small' );
     }
     
     // run the upgrade routine for versions smaller 2.1.0
     if ( -1 == version_compare( $current_version, '2.1.0' ) ) {
          set_theme_mod( 'mobile_nav_style', 'classic' );
     }
     
     // run the upgrade routine for versions smaller 2.2.0
     if ( -1 == version_compare( $current_version, '2.2.0' ) ) {
          set_theme_mod( 'title_caps', 'title_caps_none' );
          set_theme_mod( 'title_length', 'normal' );
     }

     // set the current version number
    set_theme_mod( 'version_number', GRUENE_VERSION );
    
    // set initiatil version number
    $ini_version = null == $current_version ? GRUENE_VERSION : $current_version;
    update_site_option( 'gruene_ini_version', $ini_version );
}
endif;
add_action( 'after_setup_theme', 'gruene_update', 0 );


if ( ! function_exists( 'gruene_service_contract' ) ) :
/**
 * Show admin notice, if theme was updated without having a service contract.
 */
function gruene_service_contract() {
     // get initially installed version
     $ini_version = get_site_option( 'gruene_ini_version', null );

     // if were still running the initially installed version, stop here
     if ( GRUENE_VERSION == $ini_version ) {
          return; // BREAKPOINT
     }
     
     // this array holds the md5 hashes of the url's of the instances with a
     // service contract. Use the network_site_url.
     $service_contracts = array(
         /**
          * You think i'm making loads of money? My dev pages are in the list as well :(
          */
         '6abb6b7115b5b0a410e1f4d82cdf8923',
         '5078d6c827100ed03e2aa1739d900aed',
         'ac36db01e6358125b9f536d589770915',
         'b372cf968035ec4d3bd1f2c580c57c97',
         'a19081bf0c3f7c1b50e3cb8176334499',
         '78373e56b47e871ea69ffed79a229c9d',
     );
     
     // get current base url of the network
     $url = network_site_url();
     
     // don't show notice on localhosts
     if ( 1 === preg_match( '/localhost/', $url ) ) {
          return;
     }
     
     // 
     $clean_url = preg_replace( '/https?:\/\/(www\.|)/', '', $url );
     
     // get the md5 hash of the current sites url
     $clean_url_hash = md5( $clean_url );
     
     // return if site has a service contract
     if ( in_array( $clean_url_hash, $service_contracts ) ) {
          return; // BREAKPOINT
          
     } else { 
          // if user has updated whithout having a service contract
          // show admin notice
          $current_user = wp_get_current_user();
          
          $class = 'notice notice-warning';
          $message = sprintf( 
                  _x( "Hey %s, updating is great. Contributing also. It's quite time consuming".
                      " to keep your theme and its non 3rd party plugins up to date.".
                      " So please support this job by agreeing to a service contract.".
                      " It costs you 300.- CHF a year and ensures further compatibility of the".
                      " Gruene-Theme and it's non 3rd party plugins with future updates of the".
                      " WordPress core and the supported plugins. Please email me (cyrill.bolliger@gmail.com)".
                      " to get a service contract and hide this message. Thank you!", 'Users display name', 'gruene' ), 
                  $current_user->display_name 
          );

          printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
     }
}
endif;
add_action( 'admin_notices', 'gruene_service_contract' );


if ( ! function_exists( 'gruene_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gruene_set_content_width() {
	$GLOBALS['content_width'] = GRUENE_CONTENT_WIDTH;
}
endif;
add_action( 'after_setup_theme', 'gruene_set_content_width', 0 );

if ( ! function_exists( 'gruene_get_full_image_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gruene_get_full_image_width() {
     return $GLOBALS['content_width'] - 20;
}
endif;

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
	
	/**
	 * Add image size for small post thumbnail
	 * 
	 * @see gruene_the_post_thumbnail()
	 * @see add_image_size()
	 */
	add_image_size( 'gruene_small_post_thumbnail', 150, 150, array( 'center', 'center' ) );
	
     /**
	 * Add image size for large post thumbnail
	 * 
	 * @see gruene_the_post_thumbnail()
	 * @see add_image_size()
	 */
	add_image_size( 'gruene_large_post_thumbnail', gruene_get_full_image_width(), 233, array( 'center', 'center' ) );
     
	/**
	 * Add image size for large post thumbnails
	 * 
	 * @see add_image_size()
	 */
	add_image_size( 'gruene_featured_image_size', gruene_get_full_image_width() );
     
     /*
	 * Enable support for Post Thumbnails on posts and pages.
      * 
      * Set the thumbnail size as chosen in the theme customizer
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
     global $_wp_additional_image_sizes;
     if ( 'small' == get_theme_mod( 'thumbnail_size', 'large' ) ) {
          $img_size = $_wp_additional_image_sizes['gruene_small_post_thumbnail'];
     } else {
          $img_size = $_wp_additional_image_sizes['gruene_large_post_thumbnail'];
     }
     add_theme_support( 'post-thumbnails' );
     set_post_thumbnail_size( (int) $img_size['width'], (int) $img_size['height'], $img_size['crop'] );
	
	
	// These menus will always be loaded.
	register_nav_menus( array(
		'primary'     => esc_html__( 'Primary Menu', 'gruene' ),
		'footer'      => esc_html__( 'Footer Menu', 'gruene' ),
          'footer-meta' => esc_html__( 'Footer Meta Menu', 'gruene' ),

	) );
     
     // Those will only load for party representation
	if ( 'party' == get_theme_mod( 'theme_purpose', 'politician' ) ) {
          register_nav_menus( array(
               'language'    => esc_html__( 'Language Menu', 'gruene' ),
               'meta'        => esc_html__( 'Meta Menu', 'gruene' ),
          ) );
     }

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
		'name'          => esc_html__( 'Upper Sidebar', 'gruene' ),
		'id'            => 'upper-sidebar-widget-area',
		'description'   => __( 'Put the "Search and Share" widget in here.', 'gruene' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'gruene' ),
		'id'            => 'sidebar-widget-area',
		'description'   => __( 'This is just the regular sidebar.', 'gruene' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'gruene' ),
		'id'            => 'footer-widget-area',
		'description'   => __( 'This is the footer zone. Example: drop a text widget with your contact details in here.', 'gruene' ),
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
	// enqueue style sheet
     wp_enqueue_style( 'gruene-style', get_stylesheet_uri(), array(), GRUENE_VERSION );
     
     // enqueue script
	$deps = array(
          'jquery',
          'jquery-ui-core',
          'jquery-ui-button',
          'jquery-ui-dialog',
	);
	wp_enqueue_script( 'gruene-functions', get_template_directory_uri() . '/js/functions.js', $deps, GRUENE_VERSION, true );
          
     // only load comments script if there is a possibility to comment
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
     $mode = get_theme_mod( 'theme_purpose', 'politician' );
     
     $width  = GRUENE_CUSTOM_HEADER_WIDTH;
     $height = GRUENE_CUSTOM_HEADER_HEIGHT;
     
     if ( 'politician' == $mode ) {
          $width  = $width * GRUENE_HEADER_IMAGE_SCALING_RATIO;
          $height = $height * GRUENE_HEADER_IMAGE_SCALING_RATIO;
     }
     
	add_theme_support( 'custom-header', apply_filters( 'gruene_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/img/logo-default.png',
		'width'                  => $width,
		'height'                 => $height,
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
     
     $font = get_theme_mod( 'font_family', 'open_sans' );
     if ( 'open_sans' == $font ) {
          add_editor_style( 'gruene-editor-font-open-sans.css' );
     }
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
	
	// forget the given buttons
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
	// remove buttons
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
	
     /**
      * add buttons
      * 
      * buttons will be prepended in reverse order
      * @since 2.0.0
      */
     $add = array(
         'styleselect'
     );
     foreach( $add as $add_val ) {
          array_unshift( $buttons, $add_val );
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

if ( ! function_exists( 'gruene_mce_style_formats' ) ) :
/**
 * add custom mce style formats
 * 
 * @since 2.0.0
 */
function gruene_mce_style_formats( $settings ) {
     
	$style_formats = array(  
	// Each array child is a format with it's own settings
		array(  
			'title'   => __( 'Green Heading 1', 'gruene'),
			'block'   => 'h1',
			'classes' => 'gruene-custom-heading gruene-green-heading',
		),
          array(  
			'title'   => __( 'Green Heading 2', 'gruene'),
			'block'   => 'h2',
			'classes' => 'gruene-custom-heading gruene-green-heading',
		),
          array(  
			'title'   => __( 'Green Heading 3', 'gruene'),
			'block'   => 'h3',
			'classes' => 'gruene-custom-heading gruene-green-heading',
		),
          array(  
			'title'   => __( 'Magenta Heading 1', 'gruene'),
			'block'   => 'h1',
			'classes' => 'gruene-custom-heading gruene-magenta-heading',
		),
          array(  
			'title'   => __( 'Magenta Heading 2', 'gruene'),
			'block'   => 'h2',
			'classes' => 'gruene-custom-heading gruene-magenta-heading',
		),
          array(  
			'title'   => __( 'Magenta Heading 3', 'gruene'),
			'block'   => 'h3',
			'classes' => 'gruene-custom-heading gruene-magenta-heading',
		),
	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$settings['style_formats'] = json_encode( $style_formats );
	
	return $settings;
}
endif;
add_filter( 'tiny_mce_before_init', 'gruene_mce_style_formats');

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
     
     // Adds title style class
     $classes[] = 'gruene-' . get_theme_mod( 'title_caps', 'title_caps_none' );
     
     // Adds the font family class
     $classes[] = 'gruene-font-' . get_theme_mod( 'font_family', 'open_sans' );

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
 * are shown.
 * 
 * @param WP_Query $query
 * 
 * @since 1.6.0
 */
function gruene_custom_home_category( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
          gruene_custom_category( $query );
	}
}
endif;
add_action( 'pre_get_posts', 'gruene_custom_home_category' );


if ( ! function_exists( 'gruene_custom_category' ) ) :
/**
 * Modifies the WP_Query object to output only the category selected for the
 * home page in the theme customizer. If no spezific category was choosen, 
 * all categories are shown.
 * 
 * @param WP_Query $query
 */
function gruene_custom_category( $query ) {
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
endif;


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


if ( ! function_exists( 'gruene_load_font_family' ) ) :
/**
 * Loads the open sans font, if required
 */
function gruene_load_font_family() {
	$font = get_theme_mod( 'font_family', 'open_sans' );
     if ( 'open_sans' == $font ) {
          echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,800" rel="stylesheet" type="text/css">';
     }
}
endif;
add_action( 'wp_head', 'gruene_load_font_family' );


if ( ! function_exists( 'gruene_handle_campaign' ) ) :
/**
 * This function handles the WP_Query object stuff of a campaign page
 * 
 * The main query is set to a fresh 'home' query, the originaly given query
 * is stored in a gobal variable so we can use it to display the campaign later
 * on. As a result of this function the campaign template file isn't used any
 * more. The home template will be loaded instead. A call of the
 * gruene_the_campaign() function in the home template loads the campaign.
 * 
 * @global array $gruene_campaign_query
 * @param object $query
 */
function gruene_handle_campaign( $query ) {
     // if were loading a campaign page
     if ( is_page_template() && $query->is_main_query() ) {
          // store the original query
          global $gruene_campaign_query;
          $gruene_campaign_query = $query->query;
          
          // set the 'home' query as the query
          $query->query( array() );
     }
}
endif;
add_action( 'pre_get_posts', 'gruene_handle_campaign', 9 );


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