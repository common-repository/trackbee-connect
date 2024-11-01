<?php
/**
 * TrackBee Connect API Class
 *
 * @package TrackBee_Connect
 * @subpackage API
 * @version 2.0.0
 */

namespace TrackBee_Connect\API;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use \TrackBee_Connect\Admin\TBC_Admin;

/**
 * Provides a communication interface with the TrackBee API.
 *
 * @class TBC_API
 */
class TBC_API {

	/**
	 * The single instance of the class.
	 *
	 * @var TBC_API
	 */
	protected static $instance = null;

	/**
	 * Base path for API routes.
	 *
	 * @var $api_base string
	 */
	public static $api_base;

	/**
	 * Constructor
	 */
	public function __construct() {
		self::set_api_base();
	}

	/**
	 * TrackBee API Instance.
	 *
	 * Ensures only one instance of TrackBee API is loaded or can be loaded.
	 *
	 * @static
	 * @return TBC_API - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set the API base.
	 *
	 * Allow devs to point the API base to a local API development or staging server.
	 * Define TRACKBEE_API_BASE in wp-config.php.
	 */
	public static function set_api_base() {
		// If TRACKBEE_API_BASE is defined, use that as the API base.
		if ( defined( 'TRACKBEE_API_BASE' ) ) {
			self::$api_base = untrailingslashit( TRACKBEE_API_BASE );
		} else {
			self::$api_base = 'https://api.trackbee.ai';
		}
	}

	/**
	 * Get store currency from API.
	 *
	 * @return string Store currency code e.g. EUR.
	 */
	public function get_store_currency() {
		$admin = TBC_Admin::get_instance();
		// Get API key from options.
		$api_key = $admin->get_api_key();
		// Get store ID from API key.
		$store_id = $admin->get_store_id( $api_key );
		if ( empty( $api_key ) || empty( $store_id ) ) {
			return '';
		}
		// Send API key to API.
		$response = wp_remote_get(
			self::$api_base . '/stores/' . $store_id,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY' => $api_key,
				),
			)
		);
		// If 200 response code, return store currency.
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                $admin->trackbee_set_tech_error_message( $error_message );
                wc_get_logger()->error( 'TrackBee Connect: Failed to get store currency from API with response: ' . $error_message );
			}
			return '';
		}
		return json_decode( $response['body'] )->currency;
	}

	/**
	 * Get store currency symbol.
	 *
	 * @return string Currency symbol e.g. '$'.
	 */
	public function get_store_currency_symbol() {
		$currency_code = $this->get_store_currency();
		$currency_symbols = array(
			// US Dollar.
			'USD' => '$',
			// Euro.
			'EUR' => '€',
			// Costa Rican Colón.
			'CRC' => '₡',
			// British Pound Sterling.
			'GBP' => '£',
			// Israeli New Sheqel.
			'ILS' => '₪',
			// Indian Rupee.
			'INR' => '₹',
			// Japanese Yen.
			'JPY' => '¥',
			// South Korean Won.
			'KRW' => '₩',
			// Nigerian Naira.
			'NGN' => '₦',
			// Philippine Peso.
			'PHP' => '₱',
			// Polish Zloty.
			'PLN' => 'zł',
			// Paraguayan Guarani.
			'PYG' => '₲',
			// Thai Baht.
			'THB' => '฿',
			// Ukrainian Hryvnia.
			'UAH' => '₴',
			// Vietnamese Dong.
			'VND' => '₫',
		);

		if ( isset( $currency_symbols[ $currency_code ] ) ) {
			return $currency_symbols[ $currency_code ];
		} else {
			return $currency_code;
		}
	}

	/**
	 * Validate API key with TrackBee API.
	 *
	 * @param string $api_key API key.
	 * @return array Array with a boolean to indicate if the API key is valid, a pixel API key and possibly an error message.
	 */
	public function validate_api_key( $api_key ) {
		// Get site url from options.
		$site_url = get_option( 'trackbee_site_url' );
		// Get application password from options.
		$application_password = get_option( 'trackbee_application_password' );
        // Get store id from API key
        $api_key_string = base64_decode($api_key);
        $id = intval(explode(":", $api_key_string)[1]);
        if ( $id <= 0 ) {
            return array(
                'valid' => false,
                'error' => 'The entered API key is not the correct format.'
            );
        }
        // Send API key to API.
        $response = wp_remote_post(
            self::$api_base . '/stores/'.$id.'/connect',
            array(
                'body' => wp_json_encode(
                    array(
                        'storeUrl' => $site_url,
                        'accessToken' => $application_password,
                        'ianaTimezone' => get_option( 'timezone_string' ),
                        'currency' => get_woocommerce_currency(),
                        'externalId' => $site_url
                    )
                ),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'X-API-KEY' => $api_key,
                ),
            )
        );
        // If 200 response code, return true.
        $response_body = json_decode($response['body']);
        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return array(
                'valid' => false,
                'pixelApiKey' => '',
                'error' => $response_body->message
            );
        }
		return array(
            'valid' => true,
            'pixelApiKey' => $response_body->eventApiKey,
            'error' => null
        );
	}

	/**
	 * Get store statistics from API.
	 *
	 * @return array $store_statistics Store statistics.
	 */
	public function get_store_statistics() {
		$admin = TBC_Admin::get_instance();
		// Get API key from options.
		$api_key = $admin->get_api_key();
		// Get store ID from API key.
		$store_id = $admin->get_store_id();
		// Send API key to API.
		$response = wp_remote_get(
			self::$api_base . '/statistics/store?storeId=' . $store_id,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY' => $api_key,
				),
			)
		);
		// If 200 response code, return store statistics.
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}
		$store_statistics = json_decode( $response['body'], true );
		$store_statistics['totalRevenue'] = number_format( ( (float) $store_statistics['totalRevenue'] / 100 ), 2, '.', ',' );
		$store_statistics['averageOrderValue'] = number_format( ( (float) $store_statistics['averageOrderValue'] / 100 ), 2, '.', ',' );
		return $store_statistics;
	}
}
