<?php
/**
 * TrackBee event dto.
 *
 * @package TrackBee_Connect
 * @subpackage Dto
 * @version 1.0.0
 */

namespace TrackBee_Connect\Dto;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A TrackBee event.
 *
 * @version 1.0.0
 * @package TrackBee_Connect
 * @subpackage Dto
 */
class TB_Event_Dto
{

	/**
	 * The event url.
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * The event data.
	 *
	 * @var TB_Event_Data_Base_Dto
	 */
	protected TB_Event_Data_Base_Dto $data;

	/**
	 * Constructor.
	 *
	 * @param string $url The event url.
	 * @param TB_Event_Data_Base_Dto $data The event data.
	 */
	public function __construct( string $url, TB_Event_Data_Base_Dto $data ) {
		$this->url = $url;
		$this->data = $data;
	}

	/**
	 * Get the event url.
	 *
	 * @return string
	 */
	public function get_url(): string { return $this->url; }

	/**
	 * Get the event data.
	 *
	 * @return TB_Event_Data_Base_Dto
	 */
	public function get_data(): TB_Event_Data_Base_Dto { return $this->data; }

}
