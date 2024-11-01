<?php
/**
 * Dashboard View Navigation
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views/Dashboard
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="trackbee-nav">
  <a href="/wp-admin/admin.php?page=trackbee-connect" class="trackbee-nav-link active">
	<img src="<?php echo esc_url( TRACKBEE_PLUGIN_URL . '/assets/images/overview-icon.svg' ); ?>" alt="Overview Icon" />
	Overview
  </a>
</nav>
