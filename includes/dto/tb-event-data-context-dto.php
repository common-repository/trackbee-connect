<?php
/**
 * TrackBee event data browser context.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser context data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Context_Dto implements \JsonSerializable {

	/**
	 * Represents the current browser document.
	 *
	 * @var TB_Event_Data_Document_Dto
	 */
	protected TB_Event_Data_Document_Dto $document;

	/**
	 * Represents the current browser navigator.
	 *
	 * @var TB_Event_Data_Navigator_Dto
	 */
	protected TB_Event_Data_Navigator_Dto $navigator;

	/**
	 * Represents the current browser window.
	 *
	 * @var TB_Event_Data_Window_Dto
	 */
	protected TB_Event_Data_Window_Dto $window;

	/**
	 * Constructor.
	 *
	 * @param TB_Event_Data_Document_Dto $document The browser document data.
	 * @param TB_Event_Data_Navigator_Dto $navigator The browser navigator data.
	 * @param TB_Event_Data_Window_Dto $window The browser window data.
	 */
	public function __construct( TB_Event_Data_Document_Dto $document, TB_Event_Data_Navigator_Dto $navigator, TB_Event_Data_Window_Dto $window ) {
		$this->document = $document;
		$this->navigator = $navigator;
		$this->window = $window;
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
	 * Construct dto from context object.
	 *
	 * @param stdClass $context The context object.
	 */
	public static function from_context( stdClass $context ): self {
		return new self(
			TB_Event_Data_Document_Dto::from_document( $context->document ),
			TB_Event_Data_Navigator_Dto::from_navigator( $context->navigator ),
			TB_Event_Data_Window_Dto::from_window( $context->window )
		);

	}

	/**
	 * Getter for the window property.
	 *
	 * @return TB_Event_Data_Window_Dto
	 */
	public function get_window(): TB_Event_Data_Window_Dto
	{
		return $this->window;
	}

}
