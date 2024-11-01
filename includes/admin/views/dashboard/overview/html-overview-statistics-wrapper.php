<?php
/**
 * Overview View Statistics Wrapper
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views/Dashboard/Overview
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="trackbee-overview-wrapper__content"> 
  <?php
	$api = TrackBee_Connect\API\TBC_API::get_instance();

	$trackbee_statistics = $api->get_store_statistics();
	$trackbee_currency = $api->get_store_currency_symbol();

	$trackbee_statistic_title = 'Total Revenue';
	$trackbee_statistic_value = isset( $trackbee_statistics['totalRevenue'] ) ? $trackbee_statistics['totalRevenue'] : 0;
	$trackbee_statistic_change = isset( $trackbee_statistics['totalRevenuePercentageChange'] ) ? $trackbee_statistics['totalRevenuePercentageChange'] : 0;
	$trackbee_statistic_icon = TRACKBEE_PLUGIN_URL . '/assets/images/revenue-icon.svg';
	$trackbee_statistic_type = 'money';
	include __DIR__ . '/html-overview-statistic.php';
	?>
  <?php
	$trackbee_statistic_title = 'Average Order Value';
	$trackbee_statistic_value = isset( $trackbee_statistics['averageOrderValue'] ) ? $trackbee_statistics['averageOrderValue'] : 0;
	$trackbee_statistic_change = isset( $trackbee_statistics['averageOrderValuePercentageChange'] ) ? $trackbee_statistics['averageOrderValuePercentageChange'] : 0;
	$trackbee_statistic_icon = TRACKBEE_PLUGIN_URL . '/assets/images/aov-icon.svg';
	$trackbee_statistic_type = 'money';
	include __DIR__ . '/html-overview-statistic.php';
	?>
  <?php
	$trackbee_statistic_title = 'Total Orders';
	$trackbee_statistic_value = isset( $trackbee_statistics['totalOrders'] ) ? $trackbee_statistics['totalOrders'] : 0;
	$trackbee_statistic_change = isset( $trackbee_statistics['totalOrdersPercentageChange'] ) ? $trackbee_statistics['totalOrdersPercentageChange'] : 0;
	$trackbee_statistic_icon = TRACKBEE_PLUGIN_URL . '/assets/images/orders-icon.svg';
	$trackbee_statistic_type = 'number';
	include __DIR__ . '/html-overview-statistic.php';
	?>
</div>
