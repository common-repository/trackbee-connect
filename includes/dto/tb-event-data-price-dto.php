<?php
/**
 * TrackBee event data price DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The price data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Price_Dto implements \JsonSerializable {

	/**
	 * The numerical amount of the price, represented in cents or the smallest unit of the currency.
	 */
	private ?int $amount;

  /**
   * The currency code as per ISO 4217 standard (e.g., 'USD', 'EUR') representing the currency of the price.
   */
  private ?string $currencyCode;

	/**
	 * Constructor.
	 *
	 * @param ?int $amount The numerical amount of the price, represented in cents or the smallest unit of the currency.
	 * @param ?string $currencyCode The currency code as per ISO 4217 standard (e.g., 'USD', 'EUR') representing the currency of the price.
	 */
	public function __construct(?int $amount, ?string $currencyCode ) {
		$this->amount = $amount;
		$this->currencyCode = $currencyCode;
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
