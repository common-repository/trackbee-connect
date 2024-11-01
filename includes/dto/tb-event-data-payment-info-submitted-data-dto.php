<?php
/**
 * TrackBee event data payment info submitted data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The payment info submitted data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Payment_Info_Submitted_Data_Dto extends TB_Event_Data_Dto implements \JsonSerializable {

	/**
	 * Checkout data associated with the payment info submission, including currency code, email, and
	 * checkout line items.
	 */
	protected ?TB_Event_Data_Payment_Info_Submitted_Checkout_Dto $checkout;

	/**
	 * Constructor.
	 *
	 * @param ?TB_Event_Data_Payment_Info_Submitted_Checkout_Dto $checkout Checkout data associated with the payment info submission.
	 */
	public function __construct( ?TB_Event_Data_Payment_Info_Submitted_Checkout_Dto $checkout ) {
		$this->checkout = $checkout;
	}

	/**
	 * Serialize the object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}

}
