<?php
/**
 * Plugin Name:        Dashboard Data Widegt
 * Plugin URI:         https://github.com/gooddaywp/dashboard-data-widget
 * Description:        React based dashboard widget showing a data graph.
 * Version:            1.0.0
 * Requires at least:  5.6
 * Requires PHP:       5.6
 * Author:             Marc Wiest
 * Author URI:         https://marcwiest.com
 * License:            GPL-2.0-or-later
 * Text Domain:        dbdw
 *
 * @package            dbdw
 */

namespace  dbdw\dashboard_data_widget;

defined('ABSPATH') || exit;

define( 'DBDW_PLUGIN_VERSION', '1.0.0' );
define( 'DBDW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'DBDW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DBDW_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'DBDW_OPTIONS_ID', 'dbdw_dashboard_data_widget' );

add_action( 'init'                  , __NAMESPACE__ . '\load_textdomain' );
add_action( 'admin_enqueue_scripts' , __NAMESPACE__ . '\admin_scripts' );

register_activation_hook(   __FILE__, __NAMESPACE__ . '\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_plugin' );
register_uninstall_hook(    __FILE__, __NAMESPACE__ . '\uninstall_plugin' );

require_once DBDW_PLUGIN_DIR . 'inc/helpers.php';
require_once DBDW_PLUGIN_DIR . 'inc/rest-api-endpoints.php';
require_once DBDW_PLUGIN_DIR . 'inc/class-dashboard-data-widget.php';
foreach ( glob( DBDW_PLUGIN_DIR . '/src/blocks/*/index.php' ) as $block_logic ) {
	require_once $block_logic;
}

/**
 * Loads the plugin language files.
 */
function load_textdomain() {
	load_plugin_textdomain( 'dbdw', false, DBDW_PLUGIN_DIR . '/languages/' );
}

/**
 * Enqueue admin scripts.
 */
function admin_scripts( $hook ) {

	if ( 'index.php' != $hook ) {
        return;
    }

	// Styles.
	wp_enqueue_style(
		'dbdw-dashboard-data-widget',
		DBDW_PLUGIN_URL . 'build/index.css',
		array(),
		dbdw_asset_file_data( 'index', 'version' )
	);

	// Scripts.
	wp_enqueue_script(
		'dbdw-dashboard-data-widget',
		DBDW_PLUGIN_URL . 'build/index.js',
		array_merge(
			dbdw_asset_file_data( 'index', 'dependencies' ),
			array( 'wp-api', 'wp-compose' )
		),
		dbdw_asset_file_data( 'index', 'version' ),
		true
	);
	wp_add_inline_script(
		'dbdw-dashboard-data-widget',
		'window.dbdw = ' . wp_json_encode( array(
			'wpRestUrl' => esc_url_raw( rest_url() ),
			'restNonce' => wp_create_nonce( 'wp_rest' ),
		) ),
		'before'
	);
}

/**
* Code that runs during plugin activation.
*/
function activate_plugin() {
	include DBDW_PLUGIN_DIR . 'inc/plugin-activation.php';
}

/**
* Code that runs during plugin deactivation.
*/
function deactivate_plugin() {
	include DBDW_PLUGIN_DIR . 'inc/plugin-deactivation.php';
}

/**
* Code that runs during plugin uninstallation.
*/
function uninstall_plugin() {
	include DBDW_PLUGIN_DIR . 'inc/plugin-uninstallation.php';
}
