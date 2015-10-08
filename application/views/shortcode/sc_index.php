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
		$this->js_params = array( 'ajax_script' => admin_url('admin-ajax.php'), 'img_src' => cdp_get_image_source() );
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
		$sc_style = $sc_atts[ 'style' ];
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
			$this->sc_add_css_style( 'list', $sc_style );		
			$this->get_sc_file( 'sc_inventory_list.php' );	
		}

		//Display shortcode
		return $this->sc_content;
	}
	
	function inventory_detail( $atts ){
		$atts = empty($atts) ? array() : $atts;
		
		//Shortcode Attributes
		$sc_defaults = array (
			'saleclass' => 'all',
			'd_saleclass' => 'all',
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
		$sc_style = $sc_atts['style'];
		$this->sc_search = $sc_atts['search'];
		$this->sc_flag_saleclass = strtolower($sc_atts['saleclass']);
		$this->sc_default_saleclass = strtolower($sc_atts['saleclass']) == 'all' && strtolower($sc_atts['d_saleclass']) != 'all'? strtolower($sc_atts['d_saleclass']) : strtolower($sc_atts['saleclass']);
		$sc_atts['icons'] = $sc_atts['tag'];
		$sc_atts['per_page'] = $sc_atts['limit'];
		$sc_atts['saleclass'] = $this->sc_default_saleclass;
		unset( $sc_atts['d_saleclass'] );
		unset( $sc_atts['style'] );
		unset( $sc_atts['tag'] );
		unset( $sc_atts['limit'] );
		unset( $sc_atts['form_id'] );
		unset( $sc_atts['search'] );
		foreach( $sc_atts as $key => $att ){
			if ( empty( $att ) ) {
				unset( $sc_atts[ $key ] );
			}
		}
		$this->sc_atts = $sc_atts;
		$this->sc_dealer_ID = isset($sc_atts['dealer_id']) ? $sc_atts['dealer_id'] : $this->options['vehicle_management_system']['company_information']['id'];

		if( $this->vms ){
			wp_enqueue_script( 'cdp_inventory_light_js' , cdp_get_js_source().'jquery-lightbox/1.0/js/jquery.lightbox.js' , array( 'jquery' ) , '0.5' , true );
			wp_enqueue_style( 'cdp_inventory_light', cdp_get_js_source().'jquery-lightbox/1.0/css/jquery.lightbox.css' );
			
			$this->sc_add_js_script( $this->js_params );
			$this->sc_add_css_style( 'detail', $sc_style );
			//Check and call File
			$this->get_sc_file( 'sc_inventory_detail.php' );
		}
		//Display shortcode
		return $this->sc_content;
	}
	
	function inventory_slider( $atts ){
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
			'price' => '',
			'mileage' => '',
			'year' => '',
			'dealer_id' => '0',
			'is_slider' => true,
			//Slider
			'title' => '',
			'autoplay' => true,
			'center_mode' => false,
			'dots' => false,
			'infinite' => true,
			'autoplay_speed' => '3000'
		);
		extract( shortcode_atts( $sc_defaults, $atts ) );
		//Clean Arrays
		$sc_atts = array_merge( $sc_defaults, $atts );
		$sc_atts['icons'] = $sc_atts['tag']; unset($sc_atts['tag']);
		$sc_atts['per_page'] = $sc_atts['limit']; unset($sc_atts['limit']);
		$this->is_slider = $sc_atts['is_slider']; unset($sc_atts['is_slider']);
		foreach( $sc_atts as $key => $att ){
			if ( empty( $att ) ) {
				unset($sc_atts[$key]);
			}
		}
		//Extract price | mileage | year
		foreach( $sc_atts as $key => $value ){
			if( in_array($key, array('price','mileage','year')) ){
				$temp = explode(',', $value);
				if( count($temp) == 2 ){
					$sc_atts[$key.'_from'] = $temp[0]; $sc_atts[$key.'_to'] = $temp[1];
				}
				unset($sc_atts[$key]);
			}
		}
		
		$this->slick_params = $this->inventory_params = array();
		$inventory_param_list = array('saleclass', 'make', 'model', 'trim', 'vehicleclass', 'icons', 'per_page', 'price_from', 'price_to', 'year_from', 'year_to', 'mileage_to', 'mileage_from', 'dealer_id');
		$slick_param_list = array('title', 'autoplay', 'centerMode', 'dots', 'infinite', 'autoplaySpeed');
		
		foreach( $sc_atts as $key => $value ){
			if( in_array($key, $inventory_param_list) ){
				$this->inventory_params[$key] = $value;
			}
			$camelKey = strpos($key,'_') === false ? $key: explode('_',$key);
			if( in_array( (is_array($camelKey) ? $camelKey[0].ucfirst($camelKey[1]) : $camelKey) , $slick_param_list) ){
				$this->slick_params[(is_array($camelKey) ? $camelKey[0].ucfirst($camelKey[1]) : $camelKey)] = $value;
			}
		}
		$this->sc_content = '';
		if( $this->vms ){
			//wp_enqueue_style('cdp_slider_style', cdp_get_css_source().'shortcodes/sc_slider.css', false, 1.0);
			wp_enqueue_script( 'cdp_slick_js' , cdp_get_js_source().'jquery-slick/slick.js' , array( 'jquery' ) , '1.5.7' , true );
			wp_enqueue_style( 'cdp_slick_style', cdp_get_js_source().'jquery-slick/slick.css' );
			wp_enqueue_style( 'cdp_slick_theme_style', cdp_get_js_source().'jquery-slick/slick-theme.css' );
			$this->sc_add_css_style('slider','slider');
			$this->sc_add_js_script( $this->js_params );
			
			$this->get_sc_file( 'sc_inventory_slider.php' );
		}
		
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
	
	function sc_add_css_style( $sc, $style ){
		if( strtolower($style) != 'clear' ){
			wp_enqueue_style(
				'cdp-'.$sc.'-shortcode-'.$style,
				cdp_get_css_source().'shortcodes/sc_'.$style.'.css',
				false,
				1.0
			);
		}
	}
	
	function sc_add_js_script( $params ){
		wp_register_script( 'cdp_shortcodes_js', cdp_get_js_source().'shortcodes/cdp-shortcodes.js', 'jQuery', FALSE, FALSE );
		wp_localize_script( 'cdp_shortcodes_js', 'cdp_object', $params );
		wp_enqueue_script( 'cdp_shortcodes_js' );
	}
}

?>