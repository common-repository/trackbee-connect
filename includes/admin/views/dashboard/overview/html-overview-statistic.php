<?php
/**
 * Overview View Statistic Item
 *
 * @package TrackBee_Connect
 * @subpackage Admin/Views/Dashboard/Overview
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="trackbee-overview-wrapper__content__item">
  <div class="trackbee-overview-wrapper__content__item__data">
	<div class="trackbee-overview-wrapper__content__item__data__icon">
	  <img src="<?php echo esc_url( $trackbee_statistic_icon ); ?>" alt="<?php echo esc_html( $trackbee_statistic_title . 'Icon' ); ?>" />
	</div>
	<div class="trackbee-overview-wrapper__content__item__data__text">
	  <?php if ( 'money' === $trackbee_statistic_type ) { ?>
		<h2 class="trackbee-heading-1"><?php echo $trackbee_statistic_value ? esc_html( $trackbee_currency ) . ' ' . esc_html( $trackbee_statistic_value ) : '-'; ?></h2>
	  <?php } else { ?>
		<h2 class="trackbee-heading-1"><?php echo $trackbee_statistic_value ? esc_html( $trackbee_statistic_value ) : '-'; ?></h2>
	  <?php } ?>
	  <h3 class="trackbee-heading-3"><?php echo esc_html( $trackbee_statistic_title ); ?></h3>
	</div>
  </div>
  <div class="trackbee-overview-wrapper__content__item__change">
	<?php if ( ! $trackbee_statistic_change ) { ?>
	  <div class="trackbee-change__label change__neutral">-</div>
	<?php } elseif ( $trackbee_statistic_change > 0 ) { ?>
	  <div class="trackbee-change__label change__positive">+<?php echo esc_html( $trackbee_statistic_change ); ?>%</div>
	<?php } else { ?>
	  <div class="trackbee-change__label change__negative"><?php echo esc_html( $trackbee_statistic_change ); ?>%</div>
	<?php } ?>
	<div class="trackbee-text-mute">Since yesterday</div>
  </div>
</div>
