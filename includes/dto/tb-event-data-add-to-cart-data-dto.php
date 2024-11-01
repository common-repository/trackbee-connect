<?php
/**
 * TrackBee event data add to cart data DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser add to cart data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Add_To_Cart_Data_Dto extends TB_Event_Data_Dto implements \JsonSerializable {

	/**
	 * The cost data associated with the add-to-cart event, including the total amount and currency
	 * code.
	 */
	protected ?TB_Event_Data_Cost_Dto $cost;

  /**
   * Merchandise data associated with the add-to-cart event, including ID, price, product, SKU, and
   * title.
   */
  protected ?TB_Event_Data_Merchandise_Dto $merchandise;

  /**
   * The quantity of the merchandise item being added to the cart.
   */
  protected ?int $quantity;


	/**
	 * Constructor.
	 *
	 * @param ?TB_Event_Data_Cost_Dto $cost The cost data associated with the add-to-cart event.
	 * @param ?TB_Event_Data_Merchandise_Dto $merchandise Merchandise data associated with the add-to-cart event.
	 * @param ?int $quantity The quantity of the merchandise item being added to the cart.
	 */
	public function __construct( ?TB_Event_Data_Cost_Dto $cost, ?TB_Event_Data_Merchandise_Dto $merchandise, ?int $quantity ) {
		$this->cost = $cost;
		$this->merchandise = $merchandise;
		$this->quantity = $quantity;
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
