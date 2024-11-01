<?php
/**
 * TrackBee Connect Class
 *
 * @package TrackBee_Connect
 * @version 1.0.0
 */

namespace TrackBee_Connect;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main TrackBee Connect class
 *
 * @class TrackBee_Connect
 */
final class TrackBee_Connect {
	/**
	 * The single instance of the class.
	 *
	 * @var TrackBee_Connect
	 */
	protected static $instance = null;

	/**
	 * TrackBee Connect API Instance.
	 *
	 * @var API\TBC_API
	 */
	public $api;

	/**
	 * TrackBee Connect Admin Instance.
	 *
	 * @var Admin\TBC_Admin
	 */
	public $admin;

	/**
	 * TrackBee Connect Events Instance.
	 *
	 * @var Events\TBC_Events
	 */
	public $events;

	/**
	 * Main TrackBee Connect Instance.
	 *
	 * Ensures only one instance of TrackBee Connect is loaded or can be loaded.
	 *
	 * @static
	 * @return TrackBee_Connect - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * TrackBee Connect Constructor.
	 */
	public function __construct() {
		$this->define_constants();

		// Get the class instances.
		$this->api = API\TBC_API::get_instance();
		$this->admin = Admin\TBC_Admin::get_instance();
		$this->events = Events\TBC_Events::get_instance();
	}

	/**
	 * Define TrackBee Connect Constants.
	 */
	private function define_constants() {
		define( 'TRACKBEE_VERSION', '1.0.0' );
		define( 'TRACKBEE_PLUGIN_DIR', WP_PLUGIN_DIR . '/trackbee-connect' );
		define( 'TRACKBEE_PLUGIN_URL', WP_PLUGIN_URL . '/trackbee-connect' );
	}
}
