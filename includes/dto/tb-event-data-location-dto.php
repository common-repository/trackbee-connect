<?php
/**
 * TrackBee event data location.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser location data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Location_Dto implements \JsonSerializable {

	/**
	 * The entire URL of the current page, including the protocol, host, path, and query string.
	 *
	 * @var string
	 */
	protected string $href;

  /**
   * The URL fragment identifier (including the '#' symbol) of the current page.
   *
   * @var string
   */
  protected string $hash;

  /**
   * The full host portion of the current URL, including the hostname and port (if specified).
   *
   * @var string
   */
  protected string $host;

  /**
   * The domain name (hostname) of the current page.
   *
   * @var string
   */
  protected string $hostname;

  /**
   * The origin of the current page, including the protocol, hostname, and port (if specified).
   *
   * @var string
   */
  protected string $origin;

  /**
   * The path portion of the current URL, which includes everything after the domain but before the query string.
   *
   * @var string
   */
  protected string $pathname;

  /**
   * The port number (if specified) of the current page's URL.
   *
   * @var string
   */
  protected string $port;

  /**
   * The protocol (http: or https:) of the current page's URL.
   *
   * @var string
   */
  protected string $protocol;

  /**
   * The query string (including the '?' symbol) of the current page's URL.
   *
   * @var string
   */
  protected string $search;

	/**
	 * Constructor.
	 *
	 * @param string $href The entire URL of the current page, including the protocol, host, path, and query string.
	 * @param string $hash The URL fragment identifier (including the '#' symbol) of the current page.
	 * @param string $host The full host portion of the current URL, including the hostname and port (if specified).
	 * @param string $hostname The domain name (hostname) of the current page.
	 * @param string $origin The origin of the current page, including the protocol, hostname, and port (if specified).
	 * @param string $pathname The path portion of the current URL, which includes everything after the domain but before the query string.
	 * @param string $port The port number (if specified) of the current page's URL.
	 * @param string $protocol The protocol (http: or https:) of the current page's URL.
	 * @param string $search The query string (including the '?' symbol) of the current page's URL.
	 */
	public function __construct( string $href, string $hash, string $host, string $hostname, string $origin, string $pathname, string $port, string $protocol, string $search ) {
		$this->href = $href;
		$this->hash = $hash;
		$this->host = $host;
		$this->hostname = $hostname;
		$this->origin = $origin;
		$this->pathname = $pathname;
		$this->port = $port;
		$this->protocol = $protocol;
		$this->search = $search;
	}

	/**
	 * Serialize object to json.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}

	/**
	 * Construct dto from location object.
	 *
	 * @param stdClass $location The location object.
	 */
	public static function from_location( stdClass $location ): self {
		return new self(
			$location->href,
			$location->hash,
			$location->host,
			$location->hostname,
			$location->origin,
			$location->pathname,
			$location->port,
			$location->protocol,
			$location->search
		);
	}

}
