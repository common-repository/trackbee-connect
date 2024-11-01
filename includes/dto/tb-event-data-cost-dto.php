<?php
/**
 * TrackBee event data cost DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The cost data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Cost_Dto implements \JsonSerializable {

	/**
	 * The total cost data, including the amount and currency code, associated with an event or item.
	 */
	protected ?TB_Event_Data_Price_Dto $totalAmount;

	/**
	 * Constructor.
	 *
	 * @param ?TB_Event_Data_Price_Dto $totalAmount The total cost data associated with an event or item.
	 */
	public function __construct( ?TB_Event_Data_Price_Dto $totalAmount ) {
		$this->totalAmount = $totalAmount;
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
