<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * TGM Plugin
 *
 * Will only be loaded for single site blogs (MU isn't supportet yet. Check https://github.com/TGMPA/TGM-Plugin-Activation for
 * more information. Most problably in v3 it will be supported.)
 *
 * @package    Gruene Theme
 * @package    TGM-Plugin-Activation
 * @uses       /vendor/class-tgm-plugin-activation.php
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 * 
 * @todo       update the TGM Plugin and remove the 'if not is_multisite()' condition as soon as the TGM Plugin supports MU.
 * 
 */

if ( ! is_multisite() ) :
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
			'source'             => get_stylesheet_directory() . '/vendor/plugins/github-updater.zip', // The plugin source.
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
		// RECOMMENDED PLUGINS from the WordPress Plugin Repository.
		array(
			'name'      => 'Wordpress Jetpack',
			'slug'      => 'jetpack',
			'required'  => false,
		),
		array(
			'name'      => 'The Events Calendar',
			'slug'      => 'the-events-calendar',
			'required'  => false,
		),
		array(
			'name'               => 'Politch', // The plugin name.
			'slug'               => 'politch', // The plugin slug (typically the folder name).
			'source'             => 'https://github.com/cyrillbolliger/politch/archive/master.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => 'https://github.com/cyrillbolliger/politch', // If set, overrides default API URL and points to an external URL.
		),
		array(
			'name'               => 'Cyboslider', // The plugin name.
			'slug'               => 'cyboslider', // The plugin slug (typically the folder name).
			'source'             => 'https://github.com/cyrillbolliger/cyboslider/archive/master.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => 'https://github.com/cyrillbolliger/cyboslider', // If set, overrides default API URL and points to an external URL.
		),
		array(
			'name'      => 'Meta Box',
			'slug'      => 'meta-box',
			'required'  => false,
		),
		array(
			'name'      => 'Meta Box Text Limiter',
			'slug'      => 'meta-box-text-limiter',
			'required'  => false,
		),
		array(
			'name'               => 'Cybosm', // The plugin name.
			'slug'               => 'cybosm', // The plugin slug (typically the folder name).
			'source'             => 'https://github.com/cyrillbolliger/cybosm/archive/master.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => 'https://github.com/cyrillbolliger/cybosm', // If set, overrides default API URL and points to an external URL.
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

endif;