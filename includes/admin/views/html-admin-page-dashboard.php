<?php
/**
 * Admin View: Dashboard
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="trackbee-dashboard-page">
  <div class="trackbee-navigation">
	<div class="trackbee-logo-wrapper">
	  <img src="<?php echo esc_url( TRACKBEE_PLUGIN_URL . '/assets/images/navigation-logo.svg' ); ?>">
	</div>
	<?php include __DIR__ . '/dashboard/html-dashboard-navigation.php'; ?>
  </div>
  <div class="trackbee-dashboard-content">
	<?php include __DIR__ . '/dashboard/html-dashboard-overview-wrapper.php'; ?>
  </div>
</div>
