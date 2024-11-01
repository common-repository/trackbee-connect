<?php
/**
 * TrackBee event data product view data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The product view data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Product_View_Data_Dto extends TB_Event_Data_Dto implements \JsonSerializable {

	/**
	 * Variant data for the viewed product, including the product variant's price and associated
	 * product information.
	 */
	protected ?TB_Event_Data_Variant_Dto $variant;

	/**
	 * Constructor.
	 *
	 * @param TB_Event_Data_Variant_Dto $variant The product variant data for the product view.
	 */
	public function __construct( TB_Event_Data_Variant_Dto $variant ) {
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
}
