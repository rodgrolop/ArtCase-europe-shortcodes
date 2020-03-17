<?php
/**
 * Plugin Name:       ArtsCase Europe Shortcodes
 * Plugin URI:        https://www.sponsorbrands.es/
 * Description:       Shortcodes for different uses at ArtsCase Europe Website.
 * Version:           1.0.0
 * Author:            Rodrigo Gross
 * Author URI:        https://www.sponsorbrands.es/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ArtsCase_Europe_Shortcodes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define global constants.
 */
// Plugin version.
if ( ! defined( 'ABS_VERSION' ) ) {
	define( 'ABS_VERSION', '1.0.0' );
}

if ( ! defined( 'ABS_NAME' ) ) {
	define( 'ABS_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'ABS_DIR' ) ) {
	define( 'ABS_DIR', WP_PLUGIN_DIR . '/' . ABS_NAME );
}

if ( ! defined( 'ABS_URL' ) ) {
	define( 'ABS_URL', WP_PLUGIN_URL . '/' . ABS_NAME );
}

/**
 * Link.
 */
if ( file_exists( ABS_DIR . '/shortcode/shortcode-cat-megamenu.php' ) ) {
	require_once( ABS_DIR . '/shortcode/shortcode-cat-megamenu.php' );
}

/**
 * Link.
 */
if ( file_exists( ABS_DIR . '/shortcode/shortcode-device.php' ) ) {
	require_once( ABS_DIR . '/shortcode/shortcode-device.php' );
}

/**
 * Admin Section
 */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here

add_action('admin_menu', 'solidmood_admin_menu_setup');
 
function solidmood_admin_menu_setup(){
        add_menu_page( 'New Case Solid Mood', 'New Case', 'manage_options', 'create-new-sm-case', 'display_solidmood_plugin_admin_page' );
}
 
function display_solidmood_plugin_admin_page(){
    if ( file_exists( ABS_DIR . '/admin/solidmood-admin-display.php' ) ) {
		require_once( ABS_DIR . '/admin/solidmood-admin-display.php' );
	}
}

function custom_option_tree_admin_scripts() {

	/**
 	* Register the JavaScript for the admin-facing side of the site.
 	*/
	wp_register_script( 'artcase-europe-shortcodes-admin', plugins_url('/js/artcase-europe-shortcodes-admin.js', __FILE__), array('jquery'), null, true);

	wp_enqueue_script( 'artcase-europe-shortcodes-admin' );

	wp_register_script( 'artcase-europe-shortcodes-fabric', plugins_url('/js/fabric.min.js', __FILE__), array('jquery'), null, true);

	wp_enqueue_script( 'artcase-europe-shortcodes-fabric' );

}

add_action('admin_enqueue_scripts', 'custom_option_tree_admin_scripts');

// Update CSS within in Admin

function admin_style() {

  wp_enqueue_style('admin-styles', plugin_dir_url( __FILE__ ) . 'css/admin.css', null , null );

}

add_action('admin_enqueue_scripts', 'admin_style');

/**
 * Woocommerce functions.
 */
if ( file_exists( ABS_DIR . '/woocommerce_functions/woocommerce_functions.php' ) ) {
	require_once( ABS_DIR . '/woocommerce_functions/woocommerce_functions.php' );
}

/**
 * Register the JavaScript for the public-facing side of the site.
 */
wp_register_script( 'artcase-europe-shortcodes', plugins_url('/js/artcase-europe-shortcodes.js', __FILE__), array('jquery'), null, true);

wp_enqueue_script( 'artcase-europe-shortcodes' );

/**
 * Register the CSS for the public-facing side of the site.
 */
wp_enqueue_style(  'artcase-europe-shortcodes', plugin_dir_url( __FILE__ ) . 'css/artcase-europe-shortcodes.css', null , null );

}


