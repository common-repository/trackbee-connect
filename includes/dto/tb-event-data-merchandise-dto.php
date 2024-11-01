<?php
/**
 * TrackBee event data merchandise DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The merchandise data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Merchandise_Dto implements \JsonSerializable {

	/**
	 * Unique identifier for the product variant.
	 */
	protected ?string $variantId;

  /**
   * Price data for the merchandise item, including the amount and currency code.
   */
  protected ?TB_Event_Data_Price_Dto $price;

  /**
   * Product data associated with the merchandise item, including the product ID, title, and
   * vendor.
   */
  protected ?TB_Event_Data_Product_Dto $product;

  /**
   * Stock Keeping Unit (SKU) identifier for the merchandise item.
   */
  protected ?string $sku;

  /**
   * The title or name of the merchandise item.
   */
  protected ?string $title;

	/**
	 * Constructor.
	 *
	 * @param ?string $variantId Unique identifier for the product variant.
	 * @param ?TB_Event_Data_Price_Dto $price Price data for the merchandise item.
	 * @param ?TB_Event_Data_Product_Dto $product Product data associated with the merchandise item.
	 * @param ?string $sku Stock Keeping Unit (SKU) identifier for the merchandise item.
	 * @param ?string $title The title or name of the merchandise item.
	 */
	public function __construct( ?string $variantId, ?TB_Event_Data_Price_Dto $price, ?TB_Event_Data_Product_Dto $product, ?string $sku, ?string $title ) {
		$this->variantId = $variantId;
		$this->price = $price;
		$this->product = $product;
		$this->sku = $sku;
		$this->title = $title;
	}

	/**
	 * Serialize the object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}

}
