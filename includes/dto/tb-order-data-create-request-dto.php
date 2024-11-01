<?php
/**
 * TrackBee order data create request DTO.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The order data create request DTO for TrackBee.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Order_Data_Create_Request_Dto implements \JsonSerializable {

	/**
	 * Whether the order should be processed.
	 * In WooCommerce, the order updated webhook is triggered multiple times when the order is created. This flag is used to catch duplicates early and prevent wasting resources.
	 */
	protected ?bool $shouldProcess;

  /**
   * The TrackBee identifier of the store.
   */
  protected int $storeId;

  /**
   * Unique external identifier of the store.
   */
  protected string $externalId;

  /**
   * An ISO-8601 representation of the time the order was created.
   */
  protected string $createdAt;

  /**
   * A unique identifier for the order.
   */
  protected string $id;

  /**
   * A unique identifier for the shopping cart that was ordered.
   */
  protected ?string $cartId;

  /**
   * A unique identifier for the checkout that is associated with the order.
   */
  protected string $checkoutId;

  /**
   * A ISO-4217 representation of the store's currency.
   */
  protected string $currency;

  /**
   * The financial status of the order.
   */
  protected string $financialStatus;

  /**
   * The URL for the page where the buyer landed when they entered the shop.
   */
  protected string $landingSiteUrl;

  /**
   * List of string representations of payment gateways used for the order.
   * e.g. CreditCard, Mollie, GiftCard etc.
   */
  protected ?array $paymentGateways;

  /**
   * The date and time (ISO 8601 format) when an order was processed.
   */
  protected ?string $processedAt;

  /**
   * The website from where the user clicked a link to the store.
   */
  protected ?string $referringSiteUrl;

  /**
   * The price of the order in the store's currency after discounts but before shipping, duties,
   * taxes, and tips. (in cents/pence etc.)
   */
  protected int $subtotalPrice;

  /**
   * The total discounts applied to the price of the order in the store's currency. (in cents/pence etc.)
   */
  protected ?int $totalDiscount;

  /**
   * The sum of all line item prices in the store's currency. (in cents)
   */
  protected ?int $totalItemPrice;

  /**
   * The total outstanding balance in the store's currency. (in cents)
   */
  protected ?int $totalUnpaid;

  /**
   * The total amount of taxes applied to the order, in the store's currency. (in cents)
   */
  protected ?int $totalTax;

  /**
   * The total amount of tips received in the order, in the store's currency. (in cents)
   */
  protected ?int $totalTips;

  /**
   * The date and time (ISO 8601 format) when the order was last modified.
   */
  protected ?string $updatedAt;

  /**
   * Representation of the client's details.
   */
  protected TB_Order_Client_Details_Dto $clientDetails;

  /**
   * Representation of the customer's details.
   */
  protected TB_Order_Customer_Details_Dto $customerDetails;

  /**
   * List of tax lines applied to the order.
   *
   * @var ?TB_Order_Tax_Line_Dto[]
   */
  protected array $taxLines;

  /**
   * List of products purchased in the order.
   *
   * @var TB_Order_Line_Item_Dto[]
   */
  protected array $lineItems;

  /**
   * Representation of the marketing platform attributes.
   */
  protected TB_Order_Attributes_Dto $marketingAttributes;

	/**
	 * Constructor.
	 *
	 * @param ?bool $shouldProcess
	 * @param int $storeId
	 * @param string $externalId
	 * @param string $createdAt
	 * @param string $id
	 * @param ?string $cartId
	 * @param string $checkoutId
	 * @param string $currency
	 * @param string $financialStatus
	 * @param string $landingSiteUrl
	 * @param ?array $paymentGateways
	 * @param ?string $processedAt
	 * @param ?string $referringSiteUrl
	 * @param int $subtotalPrice
	 * @param ?int $totalDiscount
	 * @param ?int $totalItemPrice
	 * @param ?int $totalUnpaid
	 * @param ?int $totalTax
	 * @param ?int $totalTips
	 * @param ?string $updatedAt
	 * @param TB_Order_Client_Details_Dto $clientDetails
	 * @param TB_Order_Customer_Details_Dto $customerDetails
	 * @param TB_Order_Tax_Line_Dto[] $taxLines
	 * @param TB_Order_Line_Item_Dto[] $lineItems
	 * @param TB_Order_Attributes_Dto $marketingAttributes
	 */
	public function __construct(
		?bool                         $shouldProcess,
		int                           $storeId,
		string                        $externalId,
		string                        $createdAt,
		string                        $id,
		?string                       $cartId,
		string                        $checkoutId,
		string                        $currency,
		string                        $financialStatus,
		string                        $landingSiteUrl,
		?array                        $paymentGateways,
		?string                       $processedAt,
		?string                       $referringSiteUrl,
		int                           $subtotalPrice,
		?int                          $totalDiscount,
		?int                          $totalItemPrice,
		?int                          $totalUnpaid,
		?int                          $totalTax,
		?int                          $totalTips,
		?string                       $updatedAt,
		TB_Order_Client_Details_Dto   $clientDetails,
		TB_Order_Customer_Details_Dto $customerDetails,
		array                         $taxLines,
		array                         $lineItems,
		TB_Order_Attributes_Dto       $marketingAttributes
	) {
		$this->shouldProcess = $shouldProcess;
		$this->storeId = $storeId;
		$this->externalId = $externalId;
		$this->createdAt = $createdAt;
		$this->id = $id;
		$this->cartId = $cartId;
		$this->checkoutId = $checkoutId;
		$this->currency = $currency;
		$this->financialStatus = $financialStatus;
		$this->landingSiteUrl = $landingSiteUrl;
		$this->paymentGateways = $paymentGateways;
		$this->processedAt = $processedAt;
		$this->referringSiteUrl = $referringSiteUrl;
		$this->subtotalPrice = $subtotalPrice;
		$this->totalDiscount = $totalDiscount;
		$this->totalItemPrice = $totalItemPrice;
		$this->totalUnpaid = $totalUnpaid;
		$this->totalTax = $totalTax;
		$this->totalTips = $totalTips;
		$this->updatedAt = $updatedAt;
		$this->clientDetails = $clientDetails;
		$this->customerDetails = $customerDetails;
		$this->taxLines = $taxLines;
		$this->lineItems = $lineItems;
		$this->marketingAttributes = $marketingAttributes;
	}

	/**
	 * Serialize the object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}

	/**
	 * Construct a TB_Order_Data_Create_Request_Dto with dummy data and shouldProcess = false.
	 *
	 * @param ?int $store_id
	 * @return TB_Order_Data_Create_Request_Dto
	 */
	public static function create_test_data( ?int $store_id ): TB_Order_Data_Create_Request_Dto
	{
		return new TB_Order_Data_Create_Request_Dto(
			false,
			$store_id ?: -1,
			'',
			'',
			'',
			null,
			'',
			'',
			'PENDING',
			'',
			null,
			null,
			null,
			0,
			null,
			null,
			null,
			null,
			null,
			null,
			TB_Order_Client_Details_Dto::get_dummy_data(),
			TB_Order_Customer_Details_Dto::get_dummy_data(),
			[TB_Order_Tax_Line_Dto::get_dummy_data()],
			[TB_Order_Line_Item_Dto::get_dummy_data()],
			TB_Order_Attributes_Dto::get_dummy_data()
		);
	}

}
