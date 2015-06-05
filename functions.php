<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Gruene Theme functions and definitions
 *
 * @package Gruene Theme
 */

/**
 * Version number of theme. Dont forget to change it also in the style.css file
 */
define( 'GRUENE_VERSION', 1.0 );

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

if ( ! function_exists( 'gruene_slider' ) ) :
/**
 * Set up the slider action hook.
 */
function gruene_slider() {
	do_action( 'gruene_slider' );
}
endif;

if ( ! function_exists( 'gruene_add_fake_slider' ) ) :
/**
 * Add fake slider
 */
function gruene_add_fake_slider() {
	echo '<img src="'.get_stylesheet_directory_uri().'/img/fakeslider.jpg" style="width: 100%; height: auto;"/>';
}
endif;
add_action( 'gruene_slider', 'gruene_add_fake_slider' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/vendor/class-tgm-plugin-activation.php';

/**
 * Register the required plugins for this theme.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 * 
 * @package    TGM-Plugin-Activation
 * @uses       /vendor/class-tgm-plugin-activation.php
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */
function gruene_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		// REQUIRED PLUGIN from Github to allow automatic Updates of the Theme itself, that is hosted on github
		array(
			'name'               => 'GitHub updater', // The plugin name.
			'slug'               => 'github-updater', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/vendor/plugins/github-updater-4.5.4.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
		),
		// REQUIRED PLUGINS from the WordPress Plugin Repository.
		array(
			'name'      => 'Disable Comments',
			'slug'      => 'disable-comments',
			'required'  => false,
		),
	);
	
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'gruene' ),
			'menu_title'                      => __( 'Install Plugins', 'gruene' ),
			'installing'                      => __( 'Installing Plugin: %s', 'gruene' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'gruene' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'gruene' ), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'gruene' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'gruene' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'gruene' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'gruene' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'gruene' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'gruene' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'gruene' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'gruene' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'gruene' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'gruene' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'gruene' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'gruene' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
	    )
	);
	
	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'gruene_register_required_plugins' );