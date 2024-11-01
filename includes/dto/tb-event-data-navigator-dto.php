<?php
/**
 * TrackBee event data navigator.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use stdClass;
use WC_Geolocation;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser navigator data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Navigator_Dto implements \JsonSerializable {

	/**
	 * The primary language of the browser.
	 *
	 * @var string
	 */
	protected string $language;

  /**
   * Indicates whether cookies are enabled in the browser.
   *
   * @var bool
   */
  protected bool $cookieEnabled;

  /**
   * A list of languages supported by the browser, in priority order.
   *
   * @var array
   */
  protected array $languages;

  /**
   * The user agent string for the browser.
   *
   * @var string
   */
  protected string $userAgent;

  /**
   * The IP address of the browser.
   *
   * @var string
   */
  protected string $browserIp;

	/**
	 * Constructor.
	 *
	 * @param string $language The primary language of the browser.
	 * @param bool $cookieEnabled Indicates whether cookies are enabled in the browser.
	 * @param array $languages A list of languages supported by the browser, in priority order.
	 * @param string $userAgent The user agent string for the browser.
	 * @param string $browserIp The IP address of the browser.
	 */
	public function __construct( string $language, bool $cookieEnabled, array $languages, string $userAgent, string $browserIp ) {
		$this->language = $language;
		$this->cookieEnabled = $cookieEnabled;
		$this->languages = $languages;
		$this->userAgent = $userAgent;
		$this->browserIp = $browserIp;
	}

	/**
	 * Serialize object to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}

	/**
	 * Construct dto from navigator object.
	 *
	 * @param stdClass $navigator The navigator object.
	 */
	public static function from_navigator( stdClass $navigator ): self {
		return new self(
			$navigator->language,
			$navigator->cookieEnabled,
			$navigator->languages,
			$navigator->userAgent,
			WC_Geolocation::get_ip_address()
		);
	}

}
