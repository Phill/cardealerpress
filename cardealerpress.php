<?php
/**
 * Plugin Name: CarDealerPress
 * Plugin URI: https://github.com/dealertrend/wordpress-plugin-inventory-api
 * Author: DealerTrend, Inc.
 * Author URI: http://www.dealertrend.com
 * Description: Provides access to the Vehicle Management System and Vehicle Reference System provided by <a href="http://www.dealertrend.com" target="_blank" title="DealerTrend, Inc: Shift Everything">DealerTrend, Inc.</a>
 * Version: 4.2.1506.01
 * License: GPLv2 or later
 */

require_once( dirname( __FILE__ ) . '/application/helpers/check_requirements.php' );

$helper = new CDP_Requirements();
if( $helper->has_been_checked() === false ) {
	$helper->set_master_file( __FILE__ );
	if( $helper->check_requirements() === false ) {
		return false;
	}
}

require_once( dirname( __FILE__ ) . '/plugin.php' );
$CDP = new Wordpress\Plugins\CarDealerPress\Inventory\Api\cdp_plugin();
?>
