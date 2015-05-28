<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

class sc_run {
	
	private $vms;
	private $vrs;
	private $company;
	private $options;
	private $plugin_url;
	
	private $sc_atts;
	private $js_atts;
	private $sc_style;
	private $sc_content;
	
	function __construct( $vms, $vrs, $company, $options, $plugin_url ){
		$this->vms = $vms;
		$this->vrs = $vrs;
		$this->company = $company;
		$this->options = $options;
		$this->plugin_url = $plugin_url;
	}
	
	function inventory_list( $atts ) {
		$atts = empty($atts) ? array() : $atts;
		
		//Shortcode Attributes
		$sc_defaults = array (
			'saleclass' => 'New',
			'make' => '',
			'model' => '',
			'trim' => '',
			'vehicleclass' => '',
			'price_from' => '',
			'price_to' => '',
			'certified' => '',
			'tag' => '', //icons
			'limit' => '10', //per_page
			'style' => 'newspaper',
			'dealer_id' => 0
		);
		extract( shortcode_atts( $sc_defaults, $atts ) );

		//Clean Arrays
		$sc_atts = array_merge( $sc_defaults, $atts );
		$this->sc_style = $sc_atts[ 'style' ];
		$sc_atts[ 'icons' ] = $sc_atts[ 'tag' ];
		$sc_atts[ 'per_page' ] = $sc_atts[ 'limit' ];
		unset( $sc_atts[ 'style' ] );
		unset( $sc_atts[ 'tag' ] );
		unset( $sc_atts[ 'limit' ] );
		foreach( $sc_atts as $key => $att ){
			if ( empty( $att ) ) {
				unset( $sc_atts[ $key ] );
			}
		}
		$this->sc_atts = $sc_atts;
		
		//Check and call File
		if( $this->vms ){
			$this->get_sc_file( 'sc_inventory_list.php' );	
		}

		//Display shortcode
		return $this->sc_content;
	}
	
	function inventory_detail( $atts ){
		$atts = empty($atts) ? array() : $atts;
		
		//Shortcode Attributes
		$sc_defaults = array (
			'saleclass' => 'New',
			'make' => '',
			'model' => '',
			'trim' => '',
			'vehicleclass' => '',
			'tag' => '', //icons
			'limit' => '10', //per_page
			'style' => 'detail',
			'dealer_id' => 0,
			'form_id' => 0,
			'search' => 0
		);
		extract( shortcode_atts( $sc_defaults, $atts ) );
		//Clean Arrays
		$sc_atts = array_merge( $sc_defaults, $atts );
		$this->sc_form = $sc_atts['form_id'];
		$this->sc_style = $sc_atts['style'];
		$this->sc_search = $sc_atts['search'];
		$sc_atts[ 'icons' ] = $sc_atts[ 'tag' ];
		$sc_atts[ 'per_page' ] = $sc_atts[ 'limit' ];
		unset( $sc_atts[ 'style' ] );
		unset( $sc_atts[ 'tag' ] );
		unset( $sc_atts[ 'limit' ] );
		unset( $sc_atts[ 'form_id'] );
		unset( $sc_atts[ 'search'] );
		foreach( $sc_atts as $key => $att ){
			if ( empty( $att ) ) {
				unset( $sc_atts[ $key ] );
			}
		}
		$this->sc_atts = $sc_atts;
		$this->sc_dealer_ID = isset($sc_atts['dealer_id']) ? $sc_atts['dealer_id'] : $this->options['vehicle_management_system']['company_information']['id'];

		if( $this->vms ){
			$ajax_script = admin_url('admin-ajax.php');
			$img_src = cdp_get_image_source();
			wp_register_script( 'cdp_shortcodes_js', cdp_get_js_source().'shortcodes/cdp-shortcodes.js', 'jQuery', FALSE, FALSE );	
			wp_localize_script( 'cdp_shortcodes_js', 'cdp_object', array( 'ajax_script' => $ajax_script, 'img_src' => $img_src ) );
			wp_enqueue_script( 'cdp_shortcodes_js' );
		
			//Check and call File
			$this->get_sc_file( 'sc_inventory_detail.php' );
		
			wp_enqueue_script( 'cdp_inventory_light_js' , cdp_get_js_source().'jquery-lightbox/1.0/js/jquery.lightbox.js' , array( 'jquery' ) , '0.5' , true );
			wp_enqueue_style( 'cdp_inventory_light', cdp_get_js_source().'jquery-lightbox/1.0/css/jquery.lightbox.css' );			
		}
		//Display shortcode
		return $this->sc_content;
	}
	
	function get_sc_file( $file_name ){
		if( $handle = opendir( dirname( __FILE__ ) ) ) {
			while( false != ( $file = readdir( $handle ) ) ) {
				if( $file == $file_name ) {
					include( dirname( __FILE__ ) . '/' . $file_name );
				}
			}
			closedir( $handle );
		} else {
			echo __FUNCTION__ . ' Could not open directory at: ' . dirname( __FILE__ ) . '/' . $file_name;
			return false;
		}
	}
}

?>