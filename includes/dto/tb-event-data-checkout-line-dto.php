<?php
/**
 * TrackBee event data checkout line.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.1
 */

namespace TrackBee_Connect\Dto;

use WC_Order_Item;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser checkout line data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Checkout_Line_Dto implements \JsonSerializable {

	/**
	 * The quantity of the product variant in the checkout line.
	 */
	protected ?int $quantity;

  /**
   * Variant data for the product in the checkout line, including the product variant's price and
   * associated product information.
   */
  protected ?TB_Event_Data_Variant_Dto $variant;

	/**
	 * Constructor.
	 *
	 * @param ?int $quantity The quantity of the product variant in the checkout line.
	 * @param ?TB_Event_Data_Variant_Dto $variant The product variant data for the checkout line.
	 */
	public function __construct( ?int $quantity, ?TB_Event_Data_Variant_Dto $variant ) {
		$this->quantity = $quantity;
		$this->variant = $variant;
	}

	/**
	 * Serialize object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}

	/**
	 * Create a new checkout line object from a WC_Order_Item.
	 *
	 * @param WC_Order_Item $orderItem The WooCommerce order item.
	 * @return TB_Event_Data_Checkout_Line_Dto
	 */
	public static function from_order_item( WC_Order_Item $orderItem ): TB_Event_Data_Checkout_Line_Dto {
		$quantity = $orderItem->get_quantity();
		$data = ! empty($orderItem->get_changes()) ? $orderItem->get_changes() : $orderItem->get_data();
		$variant = new TB_Event_Data_Variant_Dto(
			$data['variation_id'] ?? $data['product_id'],
			new TB_Event_Data_Price_Dto(
				round((float) $data['total'] * 100),
				get_woocommerce_currency()
			),
			new TB_Event_Data_Product_Dto(
				$data['product_id'],
				$orderItem->get_name(),
				$orderItem->get_meta('_vendor')
			)
		);
		return new TB_Event_Data_Checkout_Line_Dto($quantity, $variant);
	}

}
