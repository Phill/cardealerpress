<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$parameters = $this->parameters;
	$parameters[ 'saleclass' ] = isset( $parameters[ 'saleclass' ] ) ? ucwords( $parameters[ 'saleclass' ] ) : 'All';
	$theme_settings = $this->options[ 'vehicle_management_system' ][ 'theme' ];
	
	$rules = get_option( 'rewrite_rules' ); $url_rule = ( isset($rules['^(inventory)']) ) ? TRUE : FALSE;
	$this->page_url = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$this->page_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']: '';
	setcookie('cdp_page_url',$this->page_url, 0, '/' );

	$this->vms->tracer = 'Obtaining requested inventory from theme index';
	$inventory = $this->vms->get_inventory()->please( array_merge( $parameters , array( 'photo_view' => 1 , 'make_filters' =>  $this->options['vehicle_management_system' ]['data']['makes_new'] ) ) );

	$site_url = site_url();
	//Redirect if sold
	if( empty($inventory) && isset($parameters['vin']) ){
		$redirect_class = $this->options['vehicle_management_system']['saleclass'] == 'new' ? 'New' : 'Used';
		wp_redirect( $site_url.'/inventory/'.$redirect_class, 301 );
		exit;
	}

	//Log if debug is on if the inventory is empty
	if ( empty($inventory) ){
		if( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
			error_log('Version: '.self::$plugin_information['Version'].' | CDP Inv Error: Inventory returned empty | Front URL: '.$this->page_url. (!empty($this->page_referer) ? ' | Referer: '.$this->page_referer : '') );
			foreach( $this->vms->request_stack as $call ){ error_log('API: '.( is_array($call) ? $call[0] .' | Request: '.$call[1] : $call) ); }
		}
	}
	
	$generic_error_message = '<h2 style="font-family:Helvetica,Arial; color:red;">Unable to display inventory. Please contact technical support.</h2><br class="clear" />';
	switch( $this->vms->request_code ) {
		case 200:
			break;
		case 404:
			if( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
				error_log('Version: '.self::$plugin_information['Version'].' | CDP Inv 404 Error - Front URL: '.$this->page_url);
				foreach( $this->vms->request_stack as $call ){ error_log('API: '.( is_array($call) ? $call[0] .' | Request: '.$call[1] : $call) ); }
			}
			break;
		case 503:
			if( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
				error_log('Version: '.self::$plugin_information['Version'].' | CDP Inv 503 Error - Front URL: '.$this->page_url);
				foreach( $this->vms->request_stack as $call ){ error_log('API: '.( is_array($call) ? $call[0] .' | Request: '.$call[1] : $call) ); }
			}
			echo $generic_error_message;
			echo '<p>We were unable to establish a connection to the API. Refreshing the page may resolve this.</p>';
		default:
			if( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
				error_log('Version: '.self::$plugin_information['Version'].' | CDP Inv '.$this->vms->request_code.' Error - Front URL: '.$this->page_url);
				foreach( $this->vms->request_stack as $call ){ error_log('API: '.( is_array($call) ? $call[0] .' | Request: '.$call[1] : $call) ); }
			}
			get_header(); get_footer();
			return false;
			break;
	}
	
	$dealer_geo = array(); $geo_params = array();
	if( isset($theme_settings['display_geo']) ? $theme_settings['display_geo'] : FALSE ){
		$dealer_geo = $this->vms->get_automall_geo_data();
		decode_geo_query( $dealer_geo, $parameters, $geo_params );
	}
	
	$company_zip = $this->company->zip;
	$city = $this->company->city;
	$state = $this->company->state;
	$company_name = strtoupper( $this->company->name );
	$country_code =	$this->company->country_code;
	
	//The seo_hack was in the inital setup
	$seo_hack = array( 'city' => $this->company->seo->city , 'state' => $this->company->seo->state , 'country_code' => $country_code );
	$this->seo_headers->apply_headers( $parameters, $seo_hack );

	$type = isset( $inventory->vin ) ? 'detail' : 'list';
	
	$default_tag_names = get_default_tag_names();
	$custom_tag_icons = $this->options[ 'vehicle_management_system' ][ 'tags' ][ 'data' ];

	$ajax_script = admin_url('admin-ajax.php');	
	$dependent_scripts = array('jquery','jquery-ui-core','jquery-ui-tabs','jquery-ui-dialog','jquery-ui-slider' );
	foreach( $dependent_scripts as $dependent ) {
		wp_enqueue_script( $dependent );
	}

	wp_enqueue_style( 'cdp_jquery_ui_style', self::$plugin_information[ 'PluginURL' ] . '/application/assets/css/jquery-css/jquery-css.css' );
	
	$gform_class = '';
	switch( $type ) {
		case 'detail':
			wp_enqueue_script( 'cdp_inventory_cycle_js' , self::$plugin_information[ 'PluginURL' ] . '/application/assets/js/jquery-cycle/cycle2.js' , array( 'jquery' ) , '2.1.5' , true );
			wp_enqueue_script( 'cdp_inventory_light_js' , self::$plugin_information[ 'PluginURL' ] . '/application/assets/js/jquery-lightbox/1.0/js/jquery.lightbox.js' , array( 'jquery' ) , '0.5' , true );
			wp_enqueue_style( 'cdp_inventory_light', self::$plugin_information[ 'PluginURL' ].'/application/assets/js/jquery-lightbox/1.0/css/jquery.lightbox.css' );
			wp_enqueue_script( 'cdp_inventory_calc_js', self::$plugin_information[ 'PluginURL' ] . '/application/assets/js/loan-calculator.js', 'jquery', self::$plugin_information[ 'Version' ] );
			if( function_exists('gravity_form_enqueue_scripts') ){
				if( $theme_settings['detail_gform_id'] ){
					$gform_element = '<div style="display: none;" id="inventory-gform-id" form="'.$theme_settings['detail_gform_id'].'"></div>';
					$gform_class = 'inventory_get_gform';
					gravity_form_enqueue_scripts($theme_settings['detail_gform_id'], true);
				}				
			}
			break;
		case 'list':
			$search_input_class = '';
			if( $this->options[ 'vehicle_management_system' ]['keywords']['enable'] ){
				wp_enqueue_script( 'cdp_inventory_typeahead_js' , self::$plugin_information[ 'PluginURL' ] . '/application/assets/js/jquery-typeahead/typeahead.js' , array( 'jquery' ) , '0.11.1' );
				wp_register_script( 'cdp_inventory_general_js' , self::$plugin_information[ 'PluginURL' ] . '/application/assets/js/inventory/general.js' , array( 'jquery' ) , '1.0', true );
				$typeahead_keywords = explode(',', $this->options[ 'vehicle_management_system' ]['keywords']['pot']);
				wp_localize_script( 'cdp_inventory_general_js', 'cdp_keywords', $typeahead_keywords );
				wp_enqueue_script('cdp_inventory_general_js');
				$search_input_class = 'search-typeahead';
			}
			
			$this->vms->tracer = 'Calculating how many items were returned with the given parameters.';
			$vehicle_total_found = $this->vms->get_inventory()->please( array_merge( $parameters , array( 'per_page' => 1 , 'photo_view' => 1 , 'make_filters' =>  $this->options['vehicle_management_system' ]['data']['makes_new'] ) ) );
			$vehicle_total_found = is_array( $vehicle_total_found ) && count( $vehicle_total_found ) > 0 ? $vehicle_total_found[ 0 ]->pagination->total : 0;
			
			$on_page = isset( $inventory[ 0 ]->pagination->on_page ) ? $inventory[ 0 ]->pagination->on_page : 0;
			$page_total = isset( $inventory[ 0 ]->pagination->total ) ? $inventory[ 0 ]->pagination->total : 0;

			$args = array(
				'base' => add_query_arg( 'page' , '%#%' ),
				'current' => $on_page,
				'total' => $page_total,
				'next_text' => __( 'Next &raquo;' ),
				'prev_text' => __( '&laquo; Previous' ),
				'show_all' => false,
				'type' => 'plain',
				'add_args' => false
			);
			if( function_exists('gravity_form_enqueue_scripts') ){
				if( $theme_settings['list_gform_id'] ){
					$gform_element = '<div style="display: none;" id="inventory-gform-id" form="'.$theme_settings['list_gform_id'].'"></div>';
					$gform_class = 'inventory_get_gform';
					gravity_form_enqueue_scripts($theme_settings['list_gform_id'], true);
				}
			}
			break;
	}

	$inventory_style_url = self::$plugin_information[ 'PluginURL' ].'/application/assets/css/inventory/'.$current_theme.'.css';
	$inventory_style_url = apply_filters('cdp_inventory_style_url',$inventory_style_url);
	$inventory_script_url = self::$plugin_information[ 'PluginURL' ].'/application/assets/js/inventory/'.$current_theme.'.js';
	$inventory_script_url = apply_filters('cdp_inventory_script_url',$inventory_script_url);

	wp_enqueue_style( 'cdp_inventory', $inventory_style_url );	
	wp_register_script( 'cdp_inventory_js', $inventory_script_url, $dependent_scripts, FALSE, TRUE );	
	wp_localize_script( 'cdp_inventory_js', 'ajax_path', $ajax_script );
	wp_enqueue_script( 'cdp_inventory_js' );

	if( $this->options[ 'vehicle_management_system' ]['scripts']['data'] ){
		cdp_apply_setting_scripts( $this->options[ 'vehicle_management_system' ]['scripts']['data'] ,$type, $parameters[ 'saleclass' ] );
	}
	if( $this->options[ 'vehicle_management_system' ]['styles']['data'] ){
		cdp_apply_setting_styles( $this->options[ 'vehicle_management_system' ]['styles']['data'] ,$type, $parameters[ 'saleclass' ] );
	}
	
	get_header();
	flush();
	
	echo "\n".'<!--'.'[CDP Version] => '.self::$plugin_information['Version'].'-->'."\n";
	echo "\n".'<!--'.'[CDP Status Code] => '.$this->vms->request_code.'-->'."\n";
	echo '<div id="cardealerpress-inventory" class="'.$current_theme.'-theme-wrapper '.( has_filter('cro_link_display')?'cro-active':'').'" theme="'.$current_theme.'" saleclass="'.$parameters[ 'saleclass' ].'" page="'.(isset($parameters['page']) ? $parameters['page'] : '0').'">';
	echo !empty($gform_element) ? $gform_element: '';
		include( $theme_path . '/' . $type . '.php' );
	echo '</div>';

	if( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
		echo "\n" . '<!--' . "\n";
		echo '########' . "\n";
		//echo print_r( $this , true ) . "\n";
		if( isset( $dynamic_site_headers ) ) {
			//echo print_r( $dynamic_site_headers , true ) . "\n";
			echo '[SEO Helper] => ' . $dynamic_site_headers->request_stack[0];
		}
		echo print_r( $this->company , true ) . "\n";
		echo print_r( $this->vms , true ) . "\n";
		echo "\n" . '########' . "\n";
		echo '-->' . "\n";
	}
	
	flush();
	get_footer();

?>
