<?php
/**
 * TrackBee order line item data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The order line item data for a TrackBee order.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Order_Line_Item_Dto implements \JsonSerializable {

	/**
	 * The taxes applied to the line item.
	 *
	 * @var ?TB_Order_Tax_Line_Dto[]
	 */
	protected ?array $taxLines;
	/**
	 * The unique ID of the line item.
	 */
	protected ?int $lineItemId;
  /**
   * The unique ID of the product.
   */
  protected ?int $productId;
  /**
   * The amount of items that were purchased.
   */
  protected ?int $quantity;
  /**
   * The name of the product variant. (e.g. T-Shirt - Purple)
   */
  protected ?string $name;
  /**
   * The title of the product. (e.g. T-Shirt)
   */
  protected ?string $title;
  /**
   * The total amount of the discount allocated to the line item in the shop currency. (in cents)
   */
  protected ?int $totalDiscount;
  /**
   * The ID of the product variant.
   */
  protected ?int $variantId;
  /**
   * The title of the product variant. (e.g. Purple)
   */
  protected ?string $variantTitle;
  /**
   * The price of the item before discounts have been applied in the shop currency.
   */
  protected ?int $price;

	/**
	 * Constructor.
	 *
	 * @param ?TB_Order_Tax_Line_Dto[] $taxLines The taxes applied to the line item.
	 * @param ?int $lineItemId The unique ID of the line item.
	 * @param ?int $productId The unique ID of the product.
	 * @param ?int $quantity The amount of items that were purchased.
	 * @param ?string $name The name of the product variant. (e.g. T-Shirt - Purple)
	 * @param ?string $title The title of the product. (e.g. T-Shirt)
	 * @param ?int $totalDiscount The total amount of the discount allocated to the line item in the shop currency. (in cents)
	 * @param ?int $variantId The ID of the product variant.
	 * @param ?string $variantTitle The title of the product variant. (e.g. Purple)
	 * @param int $price The price of the item before discounts have been applied in the shop currency.
	 */
	public function __construct(
		?array  $taxLines,
		?int    $lineItemId,
		?int    $productId,
		?int    $quantity,
		?string $name,
		?string $title,
		?int    $totalDiscount,
		?int    $variantId,
		?string $variantTitle,
		int     $price
	)	{
		$this->taxLines = $taxLines;
		$this->lineItemId = $lineItemId;
		$this->productId = $productId;
		$this->quantity = $quantity;
		$this->name = $name;
		$this->title = $title;
		$this->totalDiscount = $totalDiscount;
		$this->variantId = $variantId;
		$this->variantTitle = $variantTitle;
		$this->price = $price;
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
	 * Construct a TB_Order_Line_Item_Dto with dummy data.
	 *
	 * @return TB_Order_Line_Item_Dto
	 */
	public static function get_dummy_data(): TB_Order_Line_Item_Dto {
		return new TB_Order_Line_Item_Dto(
			null,
			1,
			1,
			1,
			'T-Shirt - Purple',
			'T-Shirt',
			0,
			1,
			'Purple',
			1000
		);
	}

}
