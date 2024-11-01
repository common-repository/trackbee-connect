<?php
/**
 * TrackBee event data product DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The product data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Product_Dto implements \JsonSerializable {

	/**
	 * Unique identifier for the product.
	 */
	protected ?string $id;

  /**
   * The title or name of the product.
   */
  protected ?string $title;

  /**
   * The vendor or manufacturer of the product.
   */
  protected ?string $vendor;

	/**
	 * Constructor.
	 *
	 * @param ?string $id The unique identifier for the product.
	 * @param ?string $title The title or name of the product.
	 * @param ?string $vendor The vendor or manufacturer of the product.
	 */
	public function __construct(?string $id, ?string $title, ?string $vendor ) {
		$this->id = $id;
		$this->title = $title;
		$this->vendor = $vendor;
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
