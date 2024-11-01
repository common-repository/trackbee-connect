<?php
/**
 * TrackBee order customer details data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The customer details data for a TrackBee order.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Order_Customer_Details_Dto implements \JsonSerializable {

	/**
	 *  A unique identifier for the customer.
	 */
	protected string $customerId;

  /**
   * The customer's first name.
   */
  protected ?string $customerFirstName;

  /**
   * The customer's last name.
   */
  protected ?string $customerLastName;

  /**
   * The customer's email address.
   */
  protected ?string $customerEmail;

  /**
   *  The first line of the customer's mailing address.
   */
  protected ?string $customerAddress1;

  /**
   * An additional field for the customer's mailing address.
   */
  protected ?string $customerAddress2;

  /**
   * The customer's postal code, also known as zip, postcode, Eircode, etc.
   */
  protected ?string $customerPostalCode;

  /**
   * The customer's phone number at this address.
   */
  protected ?string $customerPhone;

  /**
   * The customer's city, town, or village.
   */
  protected ?string $customerCity;

  /**
   * The two-letter country code corresponding to the customer's country.
   */
  protected ?string $customerCountryCode;

  /**
   * The two-letter code for the customer's region.
   */
  protected ?string $customerProvinceCode;

  /**
   * The latitude of the customer's address.
   */
  protected ?string $latitude;

  /**
   * The longitude of the customer's address.
   */
  protected ?string $longitude;

	/**
	 * Constructor
	 *
	 * @param string $customerId A unique identifier for the customer.
	 * @param ?string $customerFirstName The customer's first name.
	 * @param ?string $customerLastName The customer's last name.
	 * @param ?string $customerEmail The customer's email address.
	 * @param ?string $customerAddress1 The first line of the customer's mailing address.
	 * @param ?string $customerAddress2 An additional field for the customer's mailing address.
	 * @param ?string $customerPostalCode The customer's postal code, also known as zip, postcode, Eircode, etc.
	 * @param ?string $customerPhone The customer's phone number at this address.
	 * @param ?string $customerCity The customer's city, town, or village.
	 * @param ?string $customerCountryCode The two-letter country code corresponding to the customer's country.
	 * @param ?string $customerProvinceCode The two-letter code for the customer's region.
	 * @param ?string $latitude The latitude of the customer's address.
	 * @param ?string $longitude The longitude of the customer's address.
	 */
	public function __construct(
		string  $customerId,
		?string $customerFirstName,
		?string $customerLastName,
		?string $customerEmail,
		?string $customerAddress1,
		?string $customerAddress2,
		?string $customerPostalCode,
		?string $customerPhone,
		?string $customerCity,
		?string $customerCountryCode,
		?string $customerProvinceCode,
		?string $latitude,
		?string $longitude
	) {
		$this->customerId = $customerId;
		$this->customerFirstName = $customerFirstName;
		$this->customerLastName = $customerLastName;
		$this->customerEmail = $customerEmail;
		$this->customerAddress1 = $customerAddress1;
		$this->customerAddress2 = $customerAddress2;
		$this->customerPostalCode = $customerPostalCode;
		$this->customerPhone = $customerPhone;
		$this->customerCity = $customerCity;
		$this->customerCountryCode = $customerCountryCode;
		$this->customerProvinceCode = $customerProvinceCode;
		$this->latitude = $latitude;
		$this->longitude = $longitude;
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
	 * Construct a TB_Order_Customer_Details_Dto with dummy data.
	 *
	 * @return TB_Order_Customer_Details_Dto
	 */
	public static function get_dummy_data(): TB_Order_Customer_Details_Dto
	{
		return new TB_Order_Customer_Details_Dto(
			'123456',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
		);
	}
}
