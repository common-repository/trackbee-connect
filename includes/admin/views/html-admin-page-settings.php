<?php
/**
 * Admin View: Add API key page
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views
 * @version 1.0.0
 */

use TrackBee_Connect\Admin\TBC_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$admin_instance = TBC_Admin::get_instance();

// Retrieve the error message
$error_message = $admin_instance->trackbee_get_error_message();
$tech_error_message = $admin_instance->trackbee_get_tech_error_message();
if ( $tech_error_message ) {
    echo '<script>console.log("' . $tech_error_message . '")</script>';
}
?>
 <div class="trackbee-page">
 <div class="trackbee-header">
   <h1>TrackBee Connect</h1>
 </div>
 <div class="wrap">
   <div class="trackbee-container">
   <div class="trackbee-logo-wrapper">
	 <img src="<?php echo esc_url( TRACKBEE_PLUGIN_URL . '/assets/images/logo.svg' ); ?>" alt="TrackBee Logo" />
   </div>
   <?php
	// Display error message if any.
	if ( $error_message ) {
		echo '<div class="trackbee-warning"><div class="dashicons dashicons-warning"></div>' . esc_html($error_message) . '</div>';
	}
	?>
   <div class="trackbee-card">
	 <div class="trackbee-columns">
	 <div class="trackbee-column">
	   <h2 class="trackbee-heading-1">Add your API key</h2>
	   <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=trackbee_save_settings' ) ); ?>">
	   <?php wp_nonce_field( 'trackbee_settings' ); ?>
	   <div class="trackbee-input-group">
		 <input type="hidden" name="action" value="trackbee_save_settings">
		 <label for="trackbee_api_key">API</label>
		 <input type="text" class="trackbee-input" id="trackbee_api_key" name="trackbee_api_key" value="<?php echo esc_attr( get_option( 'trackbee_api_key' ) ); ?>" />
		 <button type="submit" class="trackbee-submit-button" id="trackbeeSubmitButton">
		 <span class="trackbee-submit-text">Connect</span>
		 </button>
	   </div>
	   </form>
	   <div class="trackbee-instructions">
	   <h2 class="trackbee-heading-2">How to get it?</h2>
	   <p class="trackbee-paragraph-med">
		 You can get your API key in the TrackBee dashboard.
		 <br />
		 Note: Your API key is for one store only.
	   </p>
	   </div>
	 </div>
	 <div class="trackbee-column">
	   <?php include TRACKBEE_PLUGIN_DIR . '/assets/images/api-key-indicator.svg'; ?>
	 </div>
	 </div>
	 </div>
   </div>
 </div>
 </div>
