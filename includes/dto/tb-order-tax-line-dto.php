<?php
/**
 * TrackBee order tax line data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The order tax line data for a TrackBee order.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Order_Tax_Line_Dto implements \JsonSerializable {

	/**
	 * The amount of tax charged in the shop currency. (in cents)
	 */
	protected ?int $price;

  /**
   * The rate of tax to be applied. (e.g. 0.21)
   */
  protected ?float $rate;

  /**
   * The name of the tax. (e.g. BTW Hoog 21%)
   */
  protected ?string $title;

	/**
	 * Constructor.
	 *
	 * @param ?int $price The amount of tax charged in the shop currency. (in cents)
	 * @param ?float $rate The rate of tax to be applied. (e.g. 0.21)
	 * @param ?string $title The name of the tax. (e.g. BTW Hoog 21%)
	 */
	public function __construct(
		?int    $price,
		?float  $rate,
		?string $title
	)	{
		$this->price = $price;
		$this->rate = $rate;
		$this->title = $title;
	}

	/**
	 * Serialize the object properties to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}

	/**
	 * Construct a TB_Order_Tax_Line_Dto with dummy data.
	 *
	 * @return TB_Order_Tax_Line_Dto
	 */
	public static function get_dummy_data(): TB_Order_Tax_Line_Dto {
		return new TB_Order_Tax_Line_Dto(
			1000,
			0.21,
			'BTW Hoog 21%'
		);
	}

}
