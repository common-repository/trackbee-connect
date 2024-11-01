<?php
/**
 * TrackBee event data variant DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use WC_Order_Item;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The variant data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Variant_Dto implements \JsonSerializable {

	/**
	 * The unique identifier for the product variant.
	 */
	protected ?string $id;

  /**
   * Price data for the product variant, including the amount and currency code.
   */
  protected ?TB_Event_Data_Price_Dto $price;

  /**
   * Product data associated with the product variant, including the product ID, title, and vendor.
   */
  protected ?TB_Event_Data_Product_Dto $product;

	/**
	 * Constructor.
	 *
	 * @param ?string $id The unique identifier for the product variant.
	 * @param ?TB_Event_Data_Price_Dto $price Price data for the product variant.
	 * @param ?TB_Event_Data_Product_Dto $product Product data associated with the product variant.
	 */
	public function __construct(?string $id, ?TB_Event_Data_Price_Dto $price, ?TB_Event_Data_Product_Dto $product ) {
		$this->id = $id;
		$this->price = $price;
		$this->product = $product;
	}

	/**
	 * Serialize object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}

}
