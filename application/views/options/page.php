<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

class cdp_admin{

	private $options;
	private $company;
	private $vms;
	private $vrs;
	private $plugin_dir;

	function __construct( $options, $company, $vms, $vrs, $dir ) {
		$this->options = $options;
		$this->company = $company;
		$this->vms = $vms;
		$this->vrs = $vrs;
		$this->plugin_dir = $dir;
		$this->add_admin_menus();
		$this->register_admin_assets();
		$this->set_admin_variables();
		$this->load_ajax();
	}
	
	function add_admin_menus(){
		add_action( 'admin_menu', array( &$this, 'admin_menus') );
		if( $this->vms ){
			add_action( 'admin_menu', array( &$this, 'admin_vms_link') );
		}
	}
	
	function admin_menus(){
		if( $this->vms ){
			add_menu_page( 'CarDealerPress', 'CarDealerPress', 'manage_options', 'cardealerpress_status', array( &$this, 'get_feed_status'), cdp_get_image_source().'cdp_logo_23x20.png');
			add_submenu_page( 'cardealerpress_status', 'CDP Feed Status', 'Feed Status', 'manage_options', 'cardealerpress_status', array( &$this, 'get_feed_status') );
			if( !empty($this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]) ){
				add_submenu_page( 'cardealerpress_status', 'CDP Inventory', 'Inventory Settings', 'manage_options', 'cardealerpress_settings', array( &$this, 'get_inventory') );
			}
			add_submenu_page( 'cardealerpress_status', 'CDP ShortCodes', 'ShortCodes', 'manage_options', 'cardealerpress_shortcodes', array( &$this, 'get_shortcodes') );
			add_submenu_page( 'cardealerpress_status', 'CDP Admin', 'Admin Settings', 'manage_options', 'cardealerpress_admin', array( &$this, 'get_admin') );
			add_submenu_page( 'cardealerpress_status', 'CDP Help', 'Help', 'manage_options', 'cardealerpress_help', array( &$this, 'get_help') );
		} else {
			add_menu_page( 'CarDealerPress', 'CarDealerPress', 'manage_options', 'cardealerpress_admin', array( &$this, 'get_admin'), cdp_get_image_source().'cdp_logo_23x20.png');
			add_submenu_page( 'cardealerpress_admin', 'CDP Admin', 'Admin Settings', 'manage_options', 'cardealerpress_admin', array( &$this, 'get_admin') );
			add_submenu_page( 'cardealerpress_admin', 'CDP Help', 'Help', 'manage_options', 'cardealerpress_help', array( &$this, 'get_help') );
		}
	}
	
	function get_feed_status(){
		include_once( 'feed_status.php' );
	}
	
	function get_inventory(){
		include_once( 'inventory.php' );
	}
	
	function get_showcase(){
		include_once( 'showcase.php' );
	}
	
	function get_admin(){
		include_once( 'admin_settings.php' );
	}
	
	function get_shortcodes(){
		include_once( 'shortcode.php' );
	}
	
	function get_help(){
		include_once( 'help.php' );
	}
	
	function get_admin_header(){
		include_once( 'admin_header.php' );
	}
	function admin_vms_link(){
		global $submenu;
		$submenu['cardealerpress_status'][] = array( '<div id="cdp_menu_001">Manage Inventory</div>', 'manage_options' , 'http://manager.dealertrend.com' );
	}

	function register_admin_assets(){
		add_action('admin_enqueue_scripts', array($this,'cdp_admin_assets'));
	}
	
	function cdp_admin_assets(){
		wp_register_style( 'cdp_admin', cdp_get_css_source().'admin/cdp_admin.css' );
		wp_register_script( 'cdp_admin_js', cdp_get_js_source().'admin/cdp_admin.js', 
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ), FALSE, TRUE 
		);
	}
	
	function load_admin_assets(){
		
		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		}else{
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
		
		$script = admin_url('admin-ajax.php');
		wp_localize_script( 'cdp_admin_js', 'ajax_path', $script );
		wp_enqueue_script( 'cdp_admin_js' );
		wp_enqueue_style( 'cdp_admin' );
	}
	
	function set_admin_variables(){
		$rules = get_option( 'rewrite_rules' );
		$this->admin['link_new'] = isset($rules['^(inventory)']) ? '/inventory/New/' : '?taxonomy=inventory&saleclass=New';
		$this->admin['link_used'] = isset($rules['^(inventory)']) ? '/inventory/Used/' : '?taxonomy=inventory&saleclass=Used';
		$this->admin['link_site'] = '<span style="white-space:nowrap;"><a href="http://www.dealertrend.com" target="_blank" title="DealerTrend, Inc: Shift Everything">DealerTrend, Inc.</a></span>';
	}
	
	function load_ajax(){
		include_once( $this->plugin_dir.'/application/functions/fn_admin_ajax.php' );
		$this->admin_ajax = new Admin_ajax($this->options, $this->vms, $this->vrs);
		add_action('wp_ajax_cdp_admin_handle_request', array(&$this->admin_ajax, 'admin_handle_request') );
	}
	
	function admin_footer(){
		$content = '<div id="cdp-footer-wrapper">';
		if( $this->vms ){
			$content .= '<a href="http://manager.dealertrend.com/" class="vms-link" target="_blank">Manage Inventory</a>';
		}
		$content .= '<a href="http://www.dealertrend.com" class="dealertrend-link"><img src="'.cdp_get_image_source().'dealertrend_logo_390x80.png" /></a>';
		$content .= '</div>';
		echo $content;
	}

}
?>
