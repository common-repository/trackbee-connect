<?php
/**
 * TrackBee event data checkout started data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser checkout started data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Checkout_Started_Data_Dto extends TB_Event_Data_Dto implements \JsonSerializable {

	/**
	 * The currency code as per ISO 4217 standard (e.g., 'USD', 'EUR') used for the checkout.
	 */
	protected ?string $currencyCode;

  /**
   * A list of checkout line items, each containing the product variant data and quantity, for the
   * started checkout process.
   *
   * @var TB_Event_Data_Checkout_Line_Dto[]
   */
  protected ?array $lineItems;

	/**
	 * Constructor.
	 *
	 * @param ?string $currencyCode The currency code used for the checkout.
	 * @param ?array $lineItems The list of checkout line items for the started checkout process.
	 */
	public function __construct(?string $currencyCode, ?array $lineItems) {
		$this->currencyCode = $currencyCode;
		$this->lineItems = $lineItems;
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
