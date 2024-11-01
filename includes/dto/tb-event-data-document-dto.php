<?php
/**
 * TrackBee event data document.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser document data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Document_Dto implements \JsonSerializable {

	/**
	 * An object containing the browser location data.
	 *
	 * @var TB_Event_Data_Location_Dto
	 */
	protected TB_Event_Data_Location_Dto $location;

  /**
   * The URL of the document that linked to the current page.
   *
   * @var string
   */
  protected string $referrer;

  /**
   * The character encoding of the current document.
   *
   * @var string
   */
  protected string $characterSet;

  /**
   * The title of the current document.
   *
   * @var string
   */
  protected string $title;

	/**
	 * Constructor.
	 *
	 * @param TB_Event_Data_Location_Dto $location The browser location data.
	 * @param string $referrer The URL of the document that linked to the current page.
	 * @param string $characterSet The character encoding of the current document.
	 * @param string $title The title of the current document.
	 */
	public function __construct( TB_Event_Data_Location_Dto $location, string $referrer, string $characterSet, string $title ) {
		$this->location = $location;
		$this->referrer = $referrer;
		$this->characterSet = $characterSet;
		$this->title = $title;
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
	 * Construct dto from document object.
	 *
	 * @param stdClass $document The document object.
	 */
	public static function from_document( stdClass $document ): self {
		return new self(
			TB_Event_Data_Location_Dto::from_location( $document->location ),
			$document->referrer,
			$document->characterSet,
			$document->title
		);
	}

}
