<?php
/**
 * Dashboard View Overview wrapper
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views/Dashboard
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trackbee_page_title = 'Overview';
include __DIR__ . '/html-dashboard-title.php';
include __DIR__ . '/html-dashboard-connected-state.php';
?>
<div class="trackbee-overview-wrapper">
  <h2 class="trackbee-heading-2">Tracked today</h2>
  <?php
	include __DIR__ . '/overview/html-overview-statistics-wrapper.php';
	?>
</div>
