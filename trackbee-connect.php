<?php
/**
 * TrackBee Connect
 *
 * @package TrackBee_Connect
 *
 * @wordpress-plugin
 * Plugin Name: TrackBee Connect
 * Plugin URI: https://trackbee.io
 * Description: Connect your WooCommerce site to TrackBee
 * Version: 2.10.0
 * Author: TrackBee
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: trackbee-connect
 * Requires Plugins: woocommerce
 */

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

// Create autoloader for TrackBee classes.
spl_autoload_register(
	function ( $class ) {
		$prefix = 'TrackBee_Connect\\';

		$base_dir = __DIR__ . '/includes/';

		$len = strlen( $prefix );

		// If the class does not use the prefix, return.
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		// Get the relative class name.
		$class = substr( $class, $len );
		// Transform to lower case.
		$class = strtolower( $class );
		// Replace underscores with dashes and double backslashes with one forward slash.
		$class = str_replace( array( '_', '\\' ), array( '-', '/' ), $class );
		// Split string into array.
		$class = explode( '/', $class );
		// If class is not a dto
		if ( ! in_array( 'dto', $class ) ){
			// Add class- prefix after the last slash.
			$class[ count( $class ) - 1 ] = 'class-' . $class[ count( $class ) - 1 ];
		}
		// Join string back together.
		$relative_class = implode( '/', $class );

		// Add base_dir .php file extension.
		$file = $base_dir . $relative_class . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Loads TrackBee Connect plugin
 *
 * @return void
 */
function trackbee_connect() {
	TrackBee_Connect\TrackBee_Connect::get_instance();
}
add_action( 'plugins_loaded', 'trackbee_connect' );

/**
 * Creates TrackBee API Key & TrackBee Pixel API Key options
 *
 * @return void
 */
function trackbee_activate() {
	add_option( 'trackbee_api_key' );
	add_option( 'trackbee_pixel_api_key' );
	add_option( 'trackbee_application_password' );
	add_option( 'trackbee_site_url' );
}
register_activation_hook( __FILE__, 'trackbee_activate' );

/**
 * Loads TrackBee javascript on front-end
 *
 * @return void
 */
function trackbee_enqueue_scripts() {
	wp_enqueue_script( 'trackbee-script', plugins_url( '/assets/js/front-end/trackbee.js', __FILE__ ), array( 'jquery' ), '1.6.0', false );
	wp_localize_script(
		'trackbee-script',
		'trackBee',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'trackbee_enqueue_scripts' );
