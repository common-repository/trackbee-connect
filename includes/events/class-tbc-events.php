<?php
/**
 * TrackBee Connect Events Class
 *
 * @package TrackBee_Connect
 * @subpackage Events
 * @version 2.9.0
 */

namespace TrackBee_Connect\Events;

use DateTime;
use DateTimeZone;
use Exception;
use TrackBee_Connect\Admin\TBC_Admin;
use TrackBee_Connect\API\TBC_API;
use TrackBee_Connect\Dto\TB_Event_Data_Add_To_Cart_Data_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Base_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Checkout_Line_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Checkout_Started_Data_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Context_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Cost_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Merchandise_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Payment_Info_Submitted_Checkout_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Payment_Info_Submitted_Data_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Price_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Product_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Product_View_Data_Dto;
use TrackBee_Connect\Dto\TB_Event_Data_Variant_Dto;
use TrackBee_Connect\Dto\TB_Event_Dto;
use TrackBee_Connect\Dto\TB_Order_Attributes_Dto;
use TrackBee_Connect\Dto\TB_Order_Client_Details_Dto;
use TrackBee_Connect\Dto\TB_Order_Customer_Details_Dto;
use TrackBee_Connect\Dto\TB_Order_Data_Create_Request_Dto;
use TrackBee_Connect\Dto\TB_Order_Line_Item_Dto;
use TrackBee_Connect\Dto\TB_Order_Tax_Line_Dto;
use WC_Order;
use WC_Product;
use WC_Product_Variation;
use WC_Tax;
use WP_Error;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Provides an interface for registering and handling events.
 *
 * @class TBC_Events
 */
class TBC_Events {
	/**
	 * The single instance of the class.
	 *
	 * @var ?TBC_Events
	 */
	protected static ?TBC_Events $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->register_events();
	}

	/**
	 * Main TrackBee Events Instance.
	 *
	 * Ensures only one instance of TrackBee Events is loaded or can be loaded.
	 *
	 * @static
	 * @return TBC_Events - Main instance.
	 */
	public static function get_instance(): TBC_Events
	{
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register events.
	 *
	 * @return void
	 */
	public function register_events(): void
	{
		add_action( 'wp_ajax_trackbee_page_loaded', array( $this, 'page_view' ), 10, 0 );
		add_action( 'wp_ajax_nopriv_trackbee_page_loaded', array( $this, 'page_view' ), 10, 0 );
		add_action( 'wp_ajax_trackbee_page_loaded', array( $this, 'product_view' ), 10, 0 );
		add_action( 'wp_ajax_nopriv_trackbee_page_loaded', array( $this, 'product_view' ), 10, 0 );
		add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart' ), 10, 6 );
		add_action( 'woocommerce_new_order', array( $this, 'checkout_started' ), 10, 2 );
		// For checkouts that use blocks
		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'payment_info_submitted' ), 10, 1);
		// For checkouts that use shortcodes
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'payment_info_submitted_shortcode' ), 10, 3 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta_data'), 10, 2 );
		add_action( 'woocommerce_webhook_payload', array( $this, 'change_order_webhook_payload' ), 10, 4 );
		add_filter( 'woocommerce_webhook_http_args', array( $this, 'add_api_key_header' ), 10, 1 );
	}

	/**
	 * Get base data for all events.
	 *
	 * @param string $event_type The event type to use in debug logs.
	 * @return ?TB_Event_Data_Base_Dto Event data base dto or null if no data is available.
	 */
	public function get_event_base_data( string $event_type ): ?TB_Event_Data_Base_Dto
	{
		if ( empty( $_COOKIE ) || empty( $_COOKIE['_tb_b'] ) ) return null;

		if ( ! isset( $_COOKIE['_tb_id'] ) ) {
			// This is a critical error, as the tb_id cookie is required for all events
			wc_get_logger()->error( 'TrackBee - No tb_id cookie found during ' . $event_type . ' event initialization' );
			return null;
		};

		$b64_event_base_data_string                = sanitize_text_field($_COOKIE['_tb_b']);
		$event_base_data_string                    = urldecode( base64_decode( $b64_event_base_data_string ) );
		$event_data                                = json_decode( $event_base_data_string );

		$context = TB_Event_Data_Context_Dto::from_context($event_data->context);

		// Get UTC timestamp
		try {
			$timestamp = gmdate('Y-m-d\TH:i:s.v\Z' );
			if ( ! $timestamp ) {
				throw new Exception('Invalid date format');
			}
		} catch (Exception $e) {
			// Log error and return
			wc_get_logger()->error('TrackBee - Error getting timestamp for event base data: ' . $e->getMessage());
			return null;
		}

		return new TB_Event_Data_Base_Dto(
			wp_generate_uuid4(),
			$event_data->clientId,
			$timestamp,
			$context,
			isset($_COOKIE['_epik']) ? sanitize_text_field($_COOKIE['_epik']) : null,
			isset($_COOKIE['_fbc']) ? sanitize_text_field($_COOKIE['_fbc']) : null,
			isset($_COOKIE['_fbp']) ? sanitize_text_field($_COOKIE['_fbp']) : null,
			isset($_COOKIE['_ttclid']) ? sanitize_text_field($_COOKIE['_ttclid']) : null,
			isset($_COOKIE['_ttp']) ? sanitize_text_field($_COOKIE['_ttp']) : null,
			isset($_COOKIE['_gclid']) ? sanitize_text_field($_COOKIE['_gclid']) : null,
			isset($_COOKIE['_wbraid']) ? sanitize_text_field($_COOKIE['_wbraid']) : null,
			isset($_COOKIE['_gbraid']) ? sanitize_text_field($_COOKIE['_gbraid']) : null,
			isset($_COOKIE['_kx']) ? sanitize_text_field($_COOKIE['_kx']) : null,
			isset($_COOKIE['_utm_source']) ? sanitize_text_field($_COOKIE['_utm_source']) : null,
			isset($_COOKIE['_utm_campaign']) ? sanitize_text_field($_COOKIE['_utm_campaign']) : null,
			isset($_COOKIE['_utm_ad_group']) ? sanitize_text_field($_COOKIE['_utm_ad_group']) : null,
			isset($_COOKIE['_utm_ad']) ? sanitize_text_field($_COOKIE['_utm_ad']) : null,
			'GRANTED',
			'GRANTED',
			sanitize_text_field($_COOKIE['_tb_id'])
		);
	}
	/**
	 * Get pixel API key.
	 *
	 * @return string Pixel API Key.
	 */
	public function get_pixel_api_key(): string
	{
		return get_option( 'trackbee_pixel_api_key' );
	}

	/**
	 * Get API key.
	 *
	 * @return string API Key.
	 */
	public function get_api_key(): string
	{
		return get_option( 'trackbee_api_key' );
	}

	/**
	 * Get event URL.
	 *
	 * @param string $event_name Event name.
	 * @return string Event URL.
	 */
	public function get_event_url( string $event_name ): string {
		$pixel_api_key = $this->get_pixel_api_key();
		return TBC_API::$api_base . '/events/data/' . $event_name . '?pak=' . $pixel_api_key;
	}

	/**
	 * Initiate event.
	 *
	 * @param string $endpoint Event endpoint.
	 * @return ?TB_Event_Dto Event.
	 */
	public function init_event( string $endpoint ): ?TB_Event_Dto
	{
		$event_url = $this->get_event_url($endpoint);
		$event_data = $this->get_event_base_data($endpoint);
		if (!$event_data) return null;

		return new TB_Event_Dto($event_url, $event_data);
	}

	/**
	 * Send event to TrackBee.
	 *
	 * @param TB_Event_Dto $event Event.
	 * @return void
	 */
	public function send_event( TB_Event_Dto $event ): void {
		wp_remote_post(
			$event->get_url(),
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body' => wp_json_encode($event->get_data()),
			)
		);
	}

	/**
	 * Page View event.
	 *
	 * @return void
	 */
	public function page_view(): void {
		if ( empty( $this->get_pixel_api_key() ) ) return;

		$event = $this->init_event( 'page-view' );
		if ( empty( $event ) ) return;

		$this->send_event( $event );
	}

	/**
	 * Product View event.
	 *
	 * @return void
	 */
	public function product_view(): void {
		if ( ! isset( $_REQUEST['productSlug'] ) ) return;
		if ( empty( $this->get_pixel_api_key() ) ) return;


		$product_slug = sanitize_title($_REQUEST['productSlug']);
		$product_post = get_page_by_path( $product_slug, OBJECT, 'product' );
		if ( ! $product_post instanceof WP_Post ) return;

		$product = wc_get_product( $product_post->ID );
		if ( ! $product instanceof WC_Product ) return;

		$event = $this->init_event( 'product-view' );
		if ( empty( $event ) ) return;

		$data = new TB_Event_Data_Product_View_Data_Dto(
			new TB_Event_Data_Variant_Dto(
				$product->get_id(),
				new TB_Event_Data_Price_Dto(
					round((float) $product->get_price() * 100),
					get_woocommerce_currency(),
				),
				new TB_Event_Data_Product_Dto(
					$product->get_id(),
					$product->get_name(),
					null,
				),
			),
		);

		$event->get_data()->set_data($data);
		$this->send_event( $event );
	}
	/**
	 * Add to Cart event.
	 *
	 * @param string $cart_item_key Cart item key.
	 * @param int $product_id Product ID.
	 * @param int $quantity Quantity.
	 * @param int $variation_id Variation ID, if applicable.
	 * @param array $variation Variation, if applicable.
	 * @param array $cart_item_data Cart item data.
	 * @return void
	 */
	public function add_to_cart(string $cart_item_key, int $product_id, int $quantity, int $variation_id, array $variation, array $cart_item_data ): void {
		if ( empty( $this->get_pixel_api_key() ) ) return;

		$event = $this->init_event( 'add-to-cart' );
		if ( empty( $event ) ) return;

		$product = wc_get_product( $product_id );
		$variant = new WC_Product_Variation( $variation_id );

		$currency = get_woocommerce_currency();

		$event_data = new TB_Event_Data_Add_To_Cart_Data_Dto(
			new TB_Event_Data_Cost_Dto(
				new TB_Event_Data_Price_Dto(
					round((float) $product->get_price() * $quantity * 100),
					$currency,
				),
			),
			new TB_Event_Data_Merchandise_Dto(
				$variation_id ?: $product_id,
				new TB_Event_Data_Price_Dto(
					round((float) $variant->get_price() * 100 ?: (float) $product->get_price() * 100),
					$currency,
				),
				new TB_Event_Data_Product_Dto(
					$product_id,
					$product->get_title(),
					null,
				),
				$variant->get_sku() ?: $product->get_sku(),
				$variant->get_name() ?: $product->get_title(),
			),
			$quantity,
		);

		$event->get_data()->set_data($event_data);
		$this->send_event( $event );
	}

	/**
	 * Checkout Started event.
	 *
	 * @param int $order_id Order ID.
	 * @param WC_Order $order WC_Order object.
	 * @return void
	 */
	public function checkout_started(int $order_id, WC_Order $order ): void {
		if ( empty( $this->get_pixel_api_key() ) ) return;

		$event = $this->init_event( 'checkout-started' );
		if ( empty( $event ) ) return;

		$line_items = array();
		foreach ( $order->get_items() as $item) {
			$line_item = TB_Event_Data_Checkout_Line_Dto::from_order_item($item);
			$line_items[] = $line_item;
		}

		$checkout_data = new TB_Event_Data_Checkout_Started_Data_Dto(
			get_woocommerce_currency(),
			$line_items,
		);

		$event->get_data()->set_data($checkout_data);
		$this->send_event( $event );
	}

	/**
	 * Payment Info Submitted event.
	 *
	 * @param WC_Order $order Order object.
	 */
	public function payment_info_submitted( $order ): void {
		if ( empty( $this->get_pixel_api_key() ) ) return;

		$event = $this->init_event( 'payment-info-submitted' );
		if ( empty( $event ) ) return;

		$currencyCode = get_woocommerce_currency();

		$line_items = array();
		foreach ( $order->get_items() as $item) {
			$line_item = TB_Event_Data_Checkout_Line_Dto::from_order_item($item);
			$line_items[] = $line_item;
		}

		$checkout_data = new TB_Event_Data_Payment_Info_Submitted_Data_Dto(
			new TB_Event_Data_Payment_Info_Submitted_Checkout_Dto(
				$currencyCode,
				$order->get_billing_email(),
				$line_items,
				$order->get_billing_phone(),
				$order->get_billing_first_name(),
				$order->get_billing_last_name(),
			),
		);

		$event->get_data()->set_data($checkout_data);
		$this->send_event( $event );
	}

	/**
	 * We need this extra method for the Payment Info Submitted events to handle the checkouts using shortcodes since those use a different hook with different arguments.
	 *
	 * @param int|WP_Error $order_id Order ID.
	 * @param array $posted_data Posted data.
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function payment_info_submitted_shortcode( $order_id, $posted_data, $order ): void {
		if (is_wp_error($order_id)) return;
		$this->payment_info_submitted($order);
	}

	/**
	 * Update order meta.
	 *
	 * @param int $order_id Order ID.
	 * @param array $posted_data Posted data.
	 * @return void
	 */
	public function update_order_meta_data(int $order_id, array $posted_data ) {
		wc_get_logger()->debug('TrackBee - Updating order meta data for order ' . $order_id);
		$order = wc_get_order( $order_id );
		$order->add_meta_data( '_tb_fbp', isset($_COOKIE['_fbp']) ? sanitize_text_field($_COOKIE['_fbp']) : '' );
		$order->add_meta_data( '_tb_fbc', isset($_COOKIE['_fbc']) ? sanitize_text_field($_COOKIE['_fbc']) : '' );
		$order->add_meta_data( '_tb_ttclid', isset($_COOKIE['_ttclid']) ? sanitize_text_field($_COOKIE['_ttclid']) : '' );
		$order->add_meta_data( '_tb_ttp', isset($_COOKIE['_ttp']) ? sanitize_text_field($_COOKIE['_ttp']) : '' );
		$order->add_meta_data( '_tb_epik', isset($_COOKIE['_epik']) ? sanitize_text_field($_COOKIE['_epik']) : '' );
		$order->add_meta_data( '_tb_gclid', isset($_COOKIE['_gclid']) ? sanitize_text_field($_COOKIE['_gclid']) : '' );
		$order->add_meta_data( '_tb_wbraid', isset($_COOKIE['_wbraid']) ? sanitize_text_field($_COOKIE['_wbraid']) : '' );
		$order->add_meta_data( '_tb_gbraid', isset($_COOKIE['_gbraid']) ? sanitize_text_field($_COOKIE['_gbraid']) : '' );
		$order->add_meta_data( '_tb_kx', isset($_COOKIE['_kx']) ? sanitize_text_field($_COOKIE['_kx']) : '' );
		$order->add_meta_data( '_tb_utm_source', isset($_COOKIE['_utm_source']) ? sanitize_text_field($_COOKIE['_utm_source']) : '' );
		$order->add_meta_data( '_tb_utm_campaign', isset($_COOKIE['_utm_campaign']) ? sanitize_text_field($_COOKIE['_utm_campaign']) : '' );
		$order->add_meta_data( '_tb_utm_ad_group', isset($_COOKIE['_utm_ad_group']) ? sanitize_text_field($_COOKIE['_utm_ad_group']) : '' );
		$order->add_meta_data( '_tb_utm_ad', isset($_COOKIE['_utm_ad']) ? sanitize_text_field($_COOKIE['_utm_ad']) : '' );
		$order->add_meta_data( '_tb_user_data_consent', 'GRANTED' );
		$order->add_meta_data( '_tb_personalization_consent', 'GRANTED' );
		$order->add_meta_data( '_tb_id', isset($_COOKIE['_tb_id']) ? sanitize_text_field($_COOKIE['_tb_id']) : '' );
		$order->add_meta_data( '_tb_b', isset($_COOKIE['_tb_b']) ? sanitize_text_field($_COOKIE['_tb_b']) : '' );
		$order->add_meta_data( '_tb_landing_site', isset($_COOKIE['_tb_l']) ? sanitize_text_field($_COOKIE['_tb_l']) : 'unknown' );
		$order->add_meta_data( '_tb_referring_site', isset($_COOKIE['_tb_r']) ? sanitize_text_field($_COOKIE['_tb_r']) : 'unknown' );

		$order->save();
	}

	/**
	 * Change order webhook payload.
	 *
	 * @param array $payload Payload.
	 * @param mixed $resource The resource.
	 * @param mixed $resource_id The resource ID.
	 * @param mixed $id The webhook ID.
	 * @return TB_Order_Data_Create_Request_Dto|array
	 */
	public function change_order_webhook_payload( array $payload, $resource, $resource_id, $id ) {
		// Get store ID
		$admin = TBC_Admin::get_instance();
		$store_id = $admin->get_store_id();

		// Get webhook
		try {
			$webhook = wc_get_webhook($id);

			$delivery_url = $webhook->get_delivery_url();
			$topic = $webhook->get_topic();

			// Check if this webhook is meant for TrackBee
			if ( $delivery_url != TBC_API::$api_base . '/order/data') {
				return $payload;
			}

			if (
				'order.updated' != $topic ||
				( empty( $payload['status'] ) || 'checkout-draft' == $payload['status'] )
			) {
				// Send test event to make sure it's not processed
				return TB_Order_Data_Create_Request_Dto::create_test_data($store_id);
			}
		} catch (Exception $e) {
			// Log error and return
			wc_get_logger()->error('TrackBee - Error getting webhook ' . $id . ': ' . $e->getMessage());
			return $payload;
		}

		$financial_status_array = array(
			'pending' => 'PENDING',
			'failed' => 'PENDING',
			'on-hold' => 'PENDING',
			'processing' => 'PAID',
			'completed' => 'PAID',
			'cancelled' => 'VOIDED',
			'refunded' => 'REFUNDED',
		);
		$financial_status = $financial_status_array[ $payload['status'] ];

		$payment_gateways = array( '0' => $payload['payment_method_title'] );

		// Create tax lines array in TrackBee format.
		$tax_lines = array();
		foreach ( $payload['tax_lines'] as $tax_line ) {
			$tax_line = new TB_Order_Tax_Line_Dto(
				round((float) $tax_line['tax_total'] * 100),
				round(((float) $tax_line['rate_percent'] / 100), 2),
				$tax_line['label'],
			);
			$tax_lines[] = $tax_line;
		}

		// Create line items array in TrackBee format.
		$line_items = array();
		foreach ( $payload['line_items'] as $line_item ) {
			$line_item_tax_lines = array();
			foreach ( $line_item['taxes'] as $tax_line ) {
				$tax_rate = WC_Tax::_get_tax_rate( $tax_line['id'] );
				$tax_line = new TB_Order_Tax_Line_Dto(
					round( (float) $tax_line['total'] * 100),
					round( ((float) $tax_rate['tax_rate'] / 100), 2 ),
					$tax_rate['tax_rate_name'],
				);
				$line_item_tax_lines[] = $tax_line;
			}

			// Get variant title in correct format
			$attributes = [];  // Array to hold parts of the title
			foreach ($line_item['meta_data'] as $meta_data) {
				$attributes[] = $meta_data['value'];  // Add each value to the array
			}
			$variant_title = implode(', ', $attributes);  // Join all parts with ', '

			$line_item = new TB_Order_Line_Item_Dto(
				$line_item_tax_lines,
				$line_item['id'],
				$line_item['product_id'],
				$line_item['quantity'],
				$line_item['name'],
				$line_item['parent_name'],
				round(((float) $line_item['subtotal'] - (float) $line_item['total']) * 100),
				$line_item['variation_id'],
				$variant_title,
				round($line_item['price'] * 100),
			);
			$line_items[] = $line_item;
		}

		try {
			$date_created = new DateTime($payload['date_created'], wp_timezone());
			$utc_date_created = gmdate('Y-m-d\TH:i:s.v\Z', $date_created->getTimestamp());
			$date_paid = isset($payload['date_paid']) ? new DateTime($payload['date_paid'], wp_timezone()) : null;
			$utc_date_paid = gmdate('Y-m-d\TH:i:s.v\Z', $date_paid->getTimestamp());
			$date_modified = new DateTime($payload['date_modified'], wp_timezone());
			$utc_date_modified = gmdate('Y-m-d\TH:i:s.v\Z', $date_modified->getTimestamp());
			if ( ! $utc_date_created || ! $utc_date_paid || ! $utc_date_modified ) {
				throw new Exception('Invalid date format');
			}
		} catch (Exception $e) {
			// Log error and return
			wc_get_logger()->error('TrackBee - Error parsing date for order ' . $resource_id . ': ' . $e->getMessage());
			return TB_Order_Data_Create_Request_Dto::create_test_data($store_id);
		}

		$order = wc_get_order($resource_id);

		$b64_event_base_data_string                = $order->get_meta('_tb_b');
		$event_base_data_string                    = urldecode( base64_decode( $b64_event_base_data_string ) );
		$event_data                                = json_decode( $event_base_data_string );

		$landing_site_url = $order->get_meta('_tb_landing_site') ?: 'unknown';
		$referring_site_url = $order->get_meta('_tb_referring_site') ?: 'unknown';

		$client_details = new TB_Order_Client_Details_Dto(
			$payload['customer_ip_address'],
			$event_data->clientId,
			$event_data->context->window->outerHeight,
			$event_data->context->window->outerWidth,
			$payload['customer_user_agent'],
		);

		$customer_details = new TB_Order_Customer_Details_Dto(
			$payload['customer_id'],
			$payload['billing']['first_name'],
			$payload['billing']['last_name'],
			$payload['billing']['email'],
			$payload['billing']['address_1'],
			$payload['billing']['address_2'],
			$payload['billing']['postcode'],
			$payload['billing']['phone'],
			$payload['billing']['city'],
			$payload['billing']['country'],
			$payload['billing']['state'],
			null,
			null
		);

		$marketing_attributes = new TB_Order_Attributes_Dto(
			sanitize_text_field($order->get_meta('_tb_fbc')),
			sanitize_text_field($order->get_meta('_tb_fbp')),
			sanitize_text_field($order->get_meta('_tb_ttclid')),
			sanitize_text_field($order->get_meta('_tb_ttp')),
			sanitize_text_field($order->get_meta('_tb_epik')),
			sanitize_text_field($order->get_meta('_tb_gclid')),
			sanitize_text_field($order->get_meta('_tb_wbraid')),
			sanitize_text_field($order->get_meta('_tb_gbraid')),
			sanitize_text_field($order->get_meta('_tb_kx')),
			sanitize_text_field($order->get_meta('_tb_utm_source')),
			sanitize_text_field($order->get_meta('_tb_utm_campaign')),
			sanitize_text_field($order->get_meta('_tb_utm_ad_group')),
			sanitize_text_field($order->get_meta('_tb_utm_ad')),
			sanitize_text_field($order->get_meta('_tb_user_data_consent')),
			sanitize_text_field($order->get_meta('_tb_personalization_consent')),
			sanitize_text_field($order->get_meta('_tb_id')),
		);

		return new TB_Order_Data_Create_Request_Dto(
			null,
			$store_id,
			get_bloginfo('name') ?: '',
			$utc_date_created,
			$resource_id,
			$payload['cart_hash'] ?: '',
			$payload['order_key'] ?: '',
			get_woocommerce_currency(),
			$financial_status,
			$landing_site_url,
			$payment_gateways,
			$utc_date_paid,
			$referring_site_url,
			round((float) $payload['total'] * 100) - ((float) $payload['total_tax'] * 100),
			round((float) $payload['discount_total'] * 100),
			round((float) $payload['total'] * 100),
			null,
			round((float) $payload['total_tax'] * 100),
			null,
			$utc_date_modified,
			$client_details,
			$customer_details,
			$tax_lines,
			$line_items,
			$marketing_attributes
		);
	}

	/**
	 * Add API key header to order webhook.
	 *
	 * @param array $http_args The HTTP arguments used in the webhook request.
	 * @return array
	 */
	public function add_api_key_header( array $http_args ): array {
		// Ensure headers are set in the array
		if ( ! isset( $http_args['headers'] ) ) {
			$http_args['headers'] = array();
		}

		// Retrieve the API key
		$api_key = $this->get_api_key();

		// Set the API key header
		$http_args['headers']['X-API-KEY'] = $api_key;

		return $http_args;
	}
}
