<?php
/**
 * TrackBee Connect Admin Class
 *
 * @package TrackBee_Connect
 * @subpackage Admin
 * @version 1.2.0
 */

namespace TrackBee_Connect\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Provides an admin interface for TrackBee Connect.
 *
 * @class TBC_Admin
 */
class TBC_Admin {
	/**
	 * The single instance of the class.
	 *
	 * @var TBC_Admin
	 */
	protected static $instance = null;

	/**
	 * Main TrackBee Connect Admin Instance.
	 *
	 * Ensures only one instance of TrackBee Connect Admin is loaded or can be loaded.
	 *
	 * @static
	 * @return TBC_Admin - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * TrackBee Connect Admin Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init_hooks();
		$this->define_constants();
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'start_app_pw_auth_flow' ) );
		add_action( 'admin_post_store_app_pw', array( $this, 'store_app_pw' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_post_trackbee_save_settings', array( $this, 'save_settings' ) );
	}

	/**
	 * Define TrackBee Connect Admin Constants.
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'TRACKBEE_ADMIN_PLUGIN_DIR', TRACKBEE_PLUGIN_DIR . '/includes/admin' );
		define( 'TRACKBEE_ADMIN_PLUGIN_URL', TRACKBEE_PLUGIN_URL . '/includes/admin' );
	}

	/**
	 * Start application password auth flow if it's not already set.
	 *
	 * @return void
	 */
	public function start_app_pw_auth_flow() {
		// Check if page is trackbee connect page.
		if ( ! isset( $_GET['page'] ) || 'trackbee-connect' !== $_GET['page'] ) {
			return;
		}
		// Check if trackbee_application_password option is set.
		$application_password_is_set = get_option( 'trackbee_application_password' );
		if ( $application_password_is_set ) {
			return;
		}
		// Send request to rest api root url. If error, die.
		$response = wp_remote_get( rest_url() );
		if ( is_wp_error( $response ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( $response, 'Error', array( 'back_link' => true ) );
		}
		$body = json_decode( $response['body'] );
		// Redirect to authorization url.
		$redirect_url = admin_url( '/admin-post.php?action=store_app_pw' );
		wp_safe_redirect( $body->authentication->{'application-passwords'}->endpoints->authorization . '?app_name=TrackBee+Connect&success_url=' . $redirect_url );
		exit;
	}

	/**
	 * Store application password.
	 *
	 * @return void
	 */
	public function store_app_pw() {
		// Check if user approved connection.
		if ( ! isset( $_GET['user_login'] ) || ! isset( $_GET['password'] ) ) {
			return;
		}
		/**
		 * Application password is base64 encoded user:password.
		 * The application password can be passed along to rest api requests using basic auth.
		 *  */
		// Store application password.
		$user_login = sanitize_user($_GET['user_login']);
		$password = sanitize_text_field($_GET['password']);
		$application_password = base64_encode( $user_login . ':' . $password );
		update_option( 'trackbee_application_password', $application_password );
		update_option( 'trackbee_site_url', sanitize_url($_GET['site_url']) );

		// Redirect to TrackBee Connect plugin.
		wp_safe_redirect( admin_url( 'admin.php?page=trackbee-connect' ) );
		exit;
	}

	/**
	 * Add TrackBee Connect menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		$trackbee_icon = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNzkiIGhlaWdodD0iNzIiIHZpZXdCb3g9IjAgMCA3OSA3MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNNDkuMDg2MSA2My42MzgxTDM0LjE3NDQgNzJMNy45MzE3IDU3LjMwMDdMMCA1OC4wOTI5TDQuNjY4NCA1MS44NDM1QzQuNjY4NCA0MS4zNjkyIDQuNjY4NCAzMS45OTUxIDQuNjY4NCAyMS41MjA4VjIxLjMwMDdIMTEuMjg1N1YyMS41MjA4QzExLjI4NTcgMzEuOTUxMSAxMS4yODU3IDQxLjMyNTIgMTEuMjg1NyA1MS43OTk1TDM0LjE3NDQgNjQuNjA2NEw0MC42NTU4IDYwLjk1MzVMMzIuNDk3NCA1Ni4zNzY1TDE3LjgxMjQgNDguMTQ2N1YzMS42NDNDMTcuODEyNCAyNC41MTM0IDE3LjgxMjQgMTguNDg0MSAxNy44MTI0IDExLjM5ODVWMTEuMTc4NUgyNC4zODQ0VjExLjM5ODVDMjQuMzg0NCAxOC41MjgxIDI0LjM4NDQgMjQuNTU3NSAyNC4zODQ0IDMxLjY4N1Y0NC40NDk5TDM1LjU3OTUgNTAuNzQzM0w0Ny4yNzMxIDU3LjMwMDdMNTMuNzU0NSA1My42NDc5TDQ1LjgyMjcgNDkuMjAyOUwzMC45MTExIDQwLjg0MTFWMzguOTkyN0MzMC45MTExIDI1Ljc0NTcgMzAuOTExMSAxMy41NTUgMzAuOTExMSAwLjI2NDA2VjBIMzcuNDgzMVYwLjIyMDA1QzM3LjQ4MzEgMTIuODUwOSAzNy40ODMxIDI0LjQyNTQgMzcuNDgzMSAzNy4xMDAyTDQ4Ljk5NTQgNDMuNTI1N0w1Ny4wNjMxIDQ4LjA1ODdWMjYuMDk3OEw0My45MTkxIDE4Ljc5MjJWMTEuMzk4NUw1MS41MzM2IDE1LjY2NzVDNTIuNTMwNyAxNC42NTUzIDUzLjI1NTkgMTMuMzc5IDUzLjYxODUgMTIuMDE0N0M1NC4yMDc3IDkuODE0MiA1My44OTA0IDcuNDgxNyA1Mi43MTIgNS41NDUyTDUyLjU3NiA1LjMyNTJMNTguMjg2OSAyLjExMjVMNTguNDIyOCAyLjMzMjVDNjAuNDYyNCA1Ljc2NTMgNjEuMDA2MyA5Ljg1ODIgNTkuOTYzOSAxMy42ODdDNTkuNDIgMTUuNTc5NSA1OC41NTg4IDE3LjM4MzkgNTcuMjg5NyAxOC45MjQyTDYzLjQ5OTEgMjIuNDAxVjI5LjM5ODVDNjUuNDkzNCAyOS4xMzQ1IDY3LjU3ODMgMjkuMjY2NSA2OS41MjczIDI5Ljc5NDZMNjkuNzUzOSAyOS44Mzg2QzczLjYwNjQgMzAuODk0OSA3Ni44Njk4IDMzLjM1OTQgNzguODY0IDM2Ljc0ODJMNzkgMzYuOTY4Mkw3My4yODkyIDQwLjE4MDlMNzMuMTUzMiAzOS45NjA5QzcyLjAyMDEgMzguMDI0NCA3MC4xNjE4IDM2LjYxNjEgNjcuOTQwOSAzNkw2Ny44NTAzIDM1Ljk1NkM2Ni40NDUyIDM1LjYwMzkgNjQuOTQ5NSAzNS41NTk5IDYzLjU0NDUgMzUuOTEyVjU1LjQ1MjNMNDkuMDg2MSA2My42MzgxWiIgZmlsbD0iI2YwZjZmYyIvPg0KPC9zdmc+';
		if ( ! get_option( 'trackbee_pixel_api_key' ) ) {
			// Add settings page.
			add_menu_page( 'TrackBee Connect', 'TrackBee', 'manage_options', 'trackbee-connect', array( $this, 'settings_page' ), $trackbee_icon, 59 );
		} else {
			// Add overview page.
			add_menu_page( 'TrackBee Connect', 'TrackBee', 'manage_options', 'trackbee-connect', array( $this, 'overview_page' ), $trackbee_icon, 59 );
		}
	}

	/**
	 * Display TrackBee Connect Settings Page.
	 *
	 * @return void
	 */
	public function settings_page() {
		// Add styles for settings page.
		wp_enqueue_style( 'trackbee-admin-settings', TRACKBEE_PLUGIN_URL . '/assets/css/admin/trackbee-settings.css', array(), TRACKBEE_VERSION );
		require_once TRACKBEE_ADMIN_PLUGIN_DIR . '/views/html-admin-page-settings.php';
	}

	/**
	 * Display TrackBee Connect Overview Page.
	 *
	 * @return void
	 */
	public function overview_page() {
		// Add styles for overview page.
		wp_enqueue_style( 'trackbee-admin-overview', TRACKBEE_PLUGIN_URL . '/assets/css/admin/trackbee-dashboard.css', array(), TRACKBEE_VERSION );
		require_once TRACKBEE_ADMIN_PLUGIN_DIR . '/views/html-admin-page-dashboard.php';
	}

	/**
	 * Enqueue scripts & styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		global $pagenow;
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'trackbee-connect' === $_GET['page'] ) {
			wp_enqueue_style( 'trackbee-connect-admin', TRACKBEE_PLUGIN_URL . '/assets/css/admin/trackbee.css', array(), TRACKBEE_VERSION );
			wp_enqueue_script( 'trackbee-connect-admin', TRACKBEE_PLUGIN_URL . '/assets/js/admin/trackbee-admin-settings.js', array(), TRACKBEE_VERSION, true );
		}
	}

	/**
	 * Get API key.
	 *
	 * @return string API key.
	 */
	public function get_api_key() {
		return get_option( 'trackbee_api_key', '' );
	}

	/**
	 * Get store ID from API key.
	 *
	 * @param string $api_key (optional) API key.
	 * @return int Store ID.
	 */
	public function get_store_id( $api_key = '' ) {
		if ( ! $api_key ) {
			$api_key = $this->get_api_key();
		}
		$decoded_api_key = base64_decode( $api_key );
		if ( count( explode( ':', $decoded_api_key ) ) === 3 ) {
			$decoded_api_key_array = explode( ':', $decoded_api_key );
			return (int) $decoded_api_key_array[1];
		}
		return -1;
	}

	/**
	 * Validate API key format.
	 *
	 * @param string $api_key API key.
	 * @return boolean True if valid, false otherwise.
	 */
	public function validate_api_key( $api_key ) {
		$decoded_api_key = base64_decode( $api_key );
		if ( count( explode( ':', $decoded_api_key ) ) === 3 ) {
			return true;
		}
		return false;
	}

	/**
	 * Set API key.
	 *
	 * @param string $api_key API key.
	 */
	public function set_api_key( $api_key ) {
		update_option( 'trackbee_api_key', $api_key );
	}

	/**
	 * Set Pixel API key.
	 *
	 * @param string $pixel_api_key Pixel API key.
	 */
	public function set_pixel_api_key( $pixel_api_key ) {
		update_option( 'trackbee_pixel_api_key', $pixel_api_key );
	}

	/**
	 * Save settings
	 *
	 * @return void
	 */
	public function save_settings() {
		// Check if nonce is set.
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'trackbee_settings' ) ) {
			wp_die( 'Security check failed' );
		}

		$api_key = sanitize_text_field( $_POST['trackbee_api_key'] );

		// Validate API key format.
		if ( ! $this->validate_api_key( $api_key ) ) {
			$this->trackbee_set_error_message( 'Invalid API Key. Please copy the API Key from your TrackBee dashboard and try again.' );
			wp_redirect( add_query_arg( 'page', 'trackbee-connect', admin_url( 'admin.php' ) ) );
			return;
		}

		// Validate API key with TrackBee API.
		$api = \TrackBee_Connect\API\TBC_API::get_instance();
		$result = $api->validate_api_key( $api_key );

		// If API key is valid, save the API key and Pixel API key..
		if ( $result['valid'] ) {
			$this->set_api_key( $api_key );
			$this->set_pixel_api_key( $result['pixelApiKey'] );
		} else {
			if ( str_contains( 'ianaTimezone', $result['error'] ) ) {
				$this->trackbee_set_error_message( 'Please set your timezone in the WordPress settings to the closest city and try again.' );
			} else {
				$this->trackbee_set_error_message( 'Something went wrong. Please check your API key and try again.' );
			}
			$this->trackbee_set_tech_error_message($result['error']);
			wc_get_logger()->error( 'TrackBee Connect - Error during API key validation: ' . $result['error'] );
		}

		// Redirect to the overview page.
		wp_redirect( add_query_arg( 'page', 'trackbee-connect', admin_url( 'admin.php' ) ) );
	}

	/**
	 * Set an error message.
	 * @param $message
	 * @return void
	 */
	public function trackbee_set_error_message( $message ): void
	{
		set_transient( 'trackbee_error', $message, 10 * MINUTE_IN_SECONDS );
	}

	/**
	 * Get and clear an error message.
	 * @return string
	 */
	public function trackbee_get_error_message(): string
	{
		$error_message = get_transient( 'trackbee_error' );
		if ( $error_message ) {
			delete_transient( 'trackbee_error' );
			return $error_message;
		}
		return '';
	}

	/**
	 * Set a technical error message to log to the console.
	 * @param $message
	 * @return void
	 */
	public function trackbee_set_tech_error_message( $message ): void
	{
		set_transient( 'trackbee_tech_error', $message, 10 * MINUTE_IN_SECONDS );
	}

	/**
	 * Get and clear a technical error message to log to the console.
	 * @return string
	 */
	public function trackbee_get_tech_error_message(): string
	{
		$error_message = get_transient( 'trackbee_tech_error' );
		if ( $error_message ) {
			delete_transient( 'trackbee_tech_error' );
			return $error_message;
		}
		return '';
	}
}
