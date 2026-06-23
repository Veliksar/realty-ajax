<?php
/**
 * Plugin Name: My Realty Plugin
 * Plugin URI: https://github.com/Veliksar
 * Description: Plugin for creating and displaying real estate. Shortcode: [display_realty]
 * Version: 1.2
 * Author: Andrew Veliksar
 * Author URI: https://github.com/Veliksar
 * Text Domain: realty-plugin
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'REALTY_PLUGIN_VERSION', '1.2' );
define( 'REALTY_PLUGIN_FILE', __FILE__ );
define( 'REALTY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once REALTY_PLUGIN_PATH . 'includes/post-type.php';
require_once REALTY_PLUGIN_PATH . 'includes/taxonomy.php';
require_once REALTY_PLUGIN_PATH . 'includes/query.php';
require_once REALTY_PLUGIN_PATH . 'includes/listings-render.php';
require_once REALTY_PLUGIN_PATH . 'includes/catalog.php';
require_once REALTY_PLUGIN_PATH . 'includes/ajax.php';
require_once REALTY_PLUGIN_PATH . 'includes/rest-api.php';
require_once REALTY_PLUGIN_PATH . 'includes/shortcode.php';
require_once REALTY_PLUGIN_PATH . 'includes/widget.php';

register_activation_hook( __FILE__, 'realty_plugin_activate' );
register_deactivation_hook( __FILE__, 'realty_plugin_deactivate' );

/**
 * Flush rewrite rules after registering CPT and taxonomy.
 */
function realty_plugin_activate() {
	my_realty_register_post_type();
	my_realty_register_taxonomy();
	flush_rewrite_rules();
}

/**
 * Flush rewrite rules on deactivation.
 */
function realty_plugin_deactivate() {
	flush_rewrite_rules();
}

/**
 * Load plugin translations.
 */
function realty_plugin_load_textdomain() {
	load_plugin_textdomain(
		'realty-plugin',
		false,
		dirname( plugin_basename( REALTY_PLUGIN_FILE ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'realty_plugin_load_textdomain' );
