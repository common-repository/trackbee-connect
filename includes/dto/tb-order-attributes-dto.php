<?php
/**
 * TrackBee order attributes data.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.1.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The order attributes data for a TrackBee order.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Order_Attributes_Dto implements \JsonSerializable {

	protected ?string $fbc; // Contains a Facebook click id, this is found in the URL from a facebook redirect.

  protected ?string $fbp; // Contains a Facebook pixel id, this is found in the URL from a facebook redirect.

  protected ?string $ttclid; // Contains a TikTok click id, this is found in the URL from a TikTok redirect.

  protected ?string $ttp; // Contains a TikTok cookie id, this is found in the cookie on the website.

  protected ?string $epik; // Contains a Pinterest id, this is found in the URL from a Pinterest redirect.

  protected ?string $gclid; // Contains a Google click id, this is found in the URL from a GoogleAds redirect.

  protected ?string $wbraid; // Contains a Google wbraid parameter, this is found in the url from a GoogleAds redirect.

  protected ?string $gbraid; // Contains a Google gbraid parameter, this is found in the url from a GoogleAds redirect.

  protected ?string $kx; // Contains a Klaviyo click id, this is found in the URL from a Klaviyo redirect.

  /**
   * Campaign id found as utm parameter.
   */
  protected ?string $utmSource;

  /**
   * Campaign id found as utm parameter.
   */
  protected ?string $utmCampaign;

  /**
   * Ad group id found as utm parameter.
   */
  protected ?string $utmAdGroup;

  /**
   * Ad id found as utm parameter.
   */
  protected ?string $utmAd;

  /**
   * Google Ads consent status for user data.
   */
  protected ?string $userDataConsent;

  /**
   * Google Ads consent status for personalization.
   */
  protected ?string $personalizationConsent;

  /**
   * The Trackbee id.
   */
  protected ?string $tbId;

	/**
	 * Constructor.
	 *
	 * @param ?string $fbc Contains a Facebook click id, this is found in the URL from a facebook redirect.
	 * @param ?string $fbp Contains a Facebook pixel id, this is found in the URL from a facebook redirect.
	 * @param ?string $ttclid Contains a TikTok click id, this is found in the URL from a TikTok redirect.
	 * @param ?string $ttp Contains a TikTok cookie id, this is found in the cookie on the website.
	 * @param ?string $epik Contains a Pinterest id, this is found in the URL from a Pinterest redirect.
	 * @param ?string $gclid Contains a Google click id, this is found in the URL from a GoogleAds redirect.
	 * @param ?string $wbraid Contains a Google wbraid parameter, this is found in the url from a GoogleAds redirect.
	 * @param ?string $gbraid Contains a Google gbraid parameter, this is found in the url from a GoogleAds redirect.
	 * @param ?string $kx Contains a Klaviyo click id, this is found in the URL from a Klaviyo redirect.
	 * @param ?string $utmSource Campaign id found as utm parameter.
	 * @param ?string $utmCampaign Campaign id found as utm parameter.
	 * @param ?string $utmAdGroup Ad group id found as utm parameter.
	 * @param ?string $utmAd Ad id found as utm parameter.
	 * @param ?string $userDataConsent Google Ads consent status for user data.
	 * @param ?string $personalizationConsent Google Ads consent status for personalization.
	 * @param ?string $tbId The Trackbee id.
	 */
	public function __construct(
		?string $fbc,
		?string $fbp,
		?string $ttclid,
		?string $ttp,
		?string $epik,
		?string $gclid,
		?string $wbraid,
		?string $gbraid,
		?string $kx,
		?string $utmSource,
		?string $utmCampaign,
		?string $utmAdGroup,
		?string $utmAd,
		?string $userDataConsent,
		?string $personalizationConsent,
		?string $tbId
	) {
		if ( ! empty( $fbc ) ) {
			$this->fbc = $fbc;
		}
		if ( ! empty( $fbp ) ) {
			$this->fbp = $fbp;
		}
		if ( ! empty( $ttclid ) ) {
			$this->ttclid = $ttclid;
		}
		if ( ! empty( $ttp ) ) {
			$this->ttp = $ttp;
		}
		if ( ! empty( $epik ) ) {
			$this->epik = $epik;
		}
		if ( ! empty( $gclid ) ) {
			$this->gclid = $gclid;
		}

		if ( ! empty( $wbraid ) ) {
			$this->wbraid = $wbraid;
		}
		if ( ! empty( $gbraid ) ) {
			$this->gbraid = $gbraid;
		}
		if ( ! empty( $kx ) ) {
			$this->kx = $kx;
		}
		if ( ! empty( $utmSource ) ) {
			$this->utmSource = $utmSource;
		}
		if ( ! empty( $utmCampaign ) ) {
			$this->utmCampaign = $utmCampaign;
		}
		if ( ! empty( $utmAdGroup ) ) {
			$this->utmAdGroup = $utmAdGroup;
		}
		if ( ! empty( $utmAd ) ) {
			$this->utmAd = $utmAd;
		}
		if ( ! empty( $userDataConsent ) ) {
			$this->userDataConsent = $userDataConsent;
		}
		if ( ! empty( $personalizationConsent ) ) {
			$this->personalizationConsent = $personalizationConsent;
		}
		if ( ! empty( $tbId ) ) {
			$this->tbId = $tbId;
		}
	}

	/**
	 * Serialize the object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}

	/**
	 * Construct a TB_Order_Attributes_Dto with dummy data.
	 *
	 * @return TB_Order_Attributes_Dto
	 */
	public static function get_dummy_data(): TB_Order_Attributes_Dto
	{
		return new TB_Order_Attributes_Dto(
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
			null,
			null,
			null,
			'f7e7b872-6cb3-44b7-be9f-eda5cbfd08d8',
		);
	}

}
