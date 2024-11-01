<?php
/**
 * TrackBee event data checkout class for payment info submitted events.
 *
 * @package TrackBee_Connect
 * @subpackage Events
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The payment info submitted checkout data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Payment_Info_Submitted_Checkout_Dto implements \JsonSerializable {

	/**
	 * The currency code as per ISO 4217 standard (e.g., 'USD', 'EUR') used for the checkout.
	 */
	protected ?string $currencyCode;

  /**
   * The email address associated with the checkout.
   */
  protected ?string $email;

  /**
   * A list of checkout line items, each containing the product variant data and quantity.
   *
   * @var ?TB_Event_Data_Checkout_Line_Dto[]
   */
  protected ?array $lineItems;

  /**
   * The phone number associated with the checkout.
   */
  protected ?string $phone;

  /**
   * The first name of the customer associated with the checkout.
   */
  protected ?string $firstName;

  /**
   * The last name of the customer associated with the checkout.
   */
  protected ?string $lastName;

	/**
	 * Constructor.
	 *
	 * @param ?string $currencyCode The currency code used for the checkout.
	 * @param ?string $email The email address associated with the checkout.
	 * @param ?array $lineItems The list of checkout line items.
	 * @param ?string $phone The phone number associated with the checkout.
	 * @param ?string $firstName The first name of the customer associated with the checkout.
	 * @param ?string $lastName The last name of the customer associated with the checkout.
	 */
	public function __construct( ?string $currencyCode, ?string $email, ?array $lineItems, ?string $phone, ?string $firstName, ?string $lastName ) {
		$this->currencyCode = $currencyCode;
		$this->email = $email;
		$this->lineItems = $lineItems;
		$this->phone = $phone;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
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
