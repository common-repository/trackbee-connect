<?php
/**
 * TrackBee event data browser window.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The browser window data for a TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Data_Window_Dto implements \JsonSerializable {

	/**
	 * The inner height of the browser window's viewport, in pixels.
	 *
	 * @var int
	 */
	protected int $innerHeight;

  /**
   * The inner width of the browser window's viewport, in pixels.
   *
   * @var int
   */
  protected int $innerWidth;

  /**
   * The outer height of the browser window, including UI elements such as toolbars and scrollbars,
   * in pixels.
   *
   * @var int
   */
  protected int $outerHeight;

  /**
   * The outer width of the browser window, including UI elements such as toolbars and scrollbars,
   * in pixels.
   *
   * @var int
   */
  protected int $outerWidth;

  /**
   * The number of pixels that the document has been horizontally scrolled (from the left edge).
   *
   * @var int
   */
  protected int $pageXOffset;

  /**
   * The number of pixels that the document has been vertically scrolled (from the top edge).
   *
   * @var int
   */
  protected int $pageYOffset;

  /**
   * The x-coordinate of the browser window's top-left corner on the screen, in pixels.
   *
   * @var int
   */
  protected int $screenX;

  /**
   * The y-coordinate of the browser window's top-left corner on the screen, in pixels.
   *
   * @var int
   */
  protected int $screenY;

  /**
   * The horizontal scroll position, in pixels.
   *
   * @var int
   */
  protected int $scrollX;

  /**
   * The vertical scroll position, in pixels.
   *
   * @var int
   */
  protected int $scrollY;

	/**
	 * Constructor.
	 *
	 * @param int $innerHeight The inner height of the browser window's viewport, in pixels.
	 * @param int $innerWidth The inner width of the browser window's viewport, in pixels.
	 * @param int $outerHeight The outer height of the browser window, in pixels.
	 * @param int $outerWidth The outer width of the browser window, in pixels.
	 * @param int $pageXOffset The number of pixels that the document has been horizontally scrolled.
	 * @param int $pageYOffset The number of pixels that the document has been vertically scrolled.
	 * @param int $screenX The x-coordinate of the browser window's top-left corner on the screen.
	 * @param int $screenY The y-coordinate of the browser window's top-left corner on the screen.
	 * @param int $scrollX The horizontal scroll position.
	 * @param int $scrollY The vertical scroll position.
	 */
	public function __construct( int $innerHeight, int $innerWidth, int $outerHeight, int $outerWidth, int $pageXOffset, int $pageYOffset, int $screenX, int $screenY, int $scrollX, int $scrollY ) {
		$this->innerHeight = $innerHeight;
		$this->innerWidth = $innerWidth;
		$this->outerHeight = $outerHeight;
		$this->outerWidth = $outerWidth;
		$this->pageXOffset = $pageXOffset;
		$this->pageYOffset = $pageYOffset;
		$this->screenX = $screenX;
		$this->screenY = $screenY;
		$this->scrollX = $scrollX;
		$this->scrollY = $scrollY;
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
	 * Construct dto from window object.
	 *
	 * @param stdClass $window The window object.
	 */
	public static function from_window( stdClass $window ): self {
		return new self(
			$window->innerHeight,
			$window->innerWidth,
			$window->outerHeight,
			$window->outerWidth,
			$window->pageXOffset,
			$window->pageYOffset,
			$window->screenX,
			$window->screenY,
			$window->scrollX,
			$window->scrollY
		);

	}

	/**
	 * Getter for the outerHeight property.
	 *
	 * @return int
	 */
	public function get_outer_height(): int
	{
		return $this->outerHeight;
	}

	/**
	 * Getter for the outerWidth property.
	 *
	 * @return int
	 */
	public function get_outer_width(): int
	{
		return $this->outerWidth;
	}

}
