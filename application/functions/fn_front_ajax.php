<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

class cdp_front_ajax{
	
	private $options;
	private $vms;
	private $vrs;
	private $company;
	
	function __construct( $options, $vms, $vrs, $company ) {
		$this->options = $options;
		$this->vms = $vms;
		$this->vrs = $vrs;
		$this->company = $company;
	}
	
	function cdp_front_handle_request(){
		switch($_REQUEST['fn']){
			case 'get_gform':
				$output['id'] = $_REQUEST['params']['key'];
				$this->apply_ajax_gravity_hooks( $_REQUEST['params']['hooks'] );
				$form = gravity_form( $_REQUEST['params']['form'], $display_title = false, $display_description = false, $display_inactive = false, $field_values = false, $ajax = true, $tabindex = 0, $echo = false );
				//$output['content'] = '<div class="list-form-wrapper active">'.do_shortcode('[gravityform id='.$_REQUEST['params']['form'].' title=false description=false ajax=true]').'</div>';
				$output['content'] = '<div class="list-form-wrapper active">'.$form.'</div>';
				break;
			case 'get_vehicle_details':
				$output['id'] = $_REQUEST['params']['key'];
				$params = array('vin' => $_REQUEST['params']['key']);
				$vehicle_data = $this->get_vehicle_inventory( $params );
				$vehicle = $vehicle_data ? itemize_vehicle( $vehicle_data ) : array();
				switch($_REQUEST['params']['tag']){
					case 'info':
						$flags = array( 'show_eq' => $this->options['vehicle_management_system']['theme']['show_standard_eq'] );
						$output['content'] = get_sc_detail_vehicle_info_display( $vehicle, $flags );
						break;
					case 'images':
						$output['content'] = get_sc_detail_images_display( $vehicle );
						break;
					default:
						$output['content'] = 'ERROR: Requesting selected inventory. Vehicle may be sold or is no longer in the system.';
						break;
				}
				
				break;
			case 'get_more_vehicles':
				$atts = $_REQUEST['params']['atts'];
				$form = isset($atts['form']) ? $atts['form'] : '';
				unset($atts['form']);
				if($atts['saleclass'] == 'certified'){ $atts['saleclass'] = 'used'; $atts['certified'] = 'yes'; }
				$vehicle_data = $this->get_vehicle_inventory( $atts );
				$output['content'] = '';
				if( is_array($vehicle_data) && !empty($vehicle_data) ){
					foreach( $vehicle_data as $data){
						$vehicle = itemize_vehicle( $data );
						$output['content'] .= get_sc_detail_vehicles( $vehicle, $this->vrs, $this->company, $this->options, $this->options[ 'vehicle_management_system' ]['custom_contact'], $form );
					}
				} else if ( empty($vehicle_data) ){
					$output['content'] = '<div id="cdp-ajax-end">All Vehicles have been loaded.</div>';
				} else {
					$output['content'] = 'ERROR: Requesting selected inventory. Vehicle may be sold or is no longer in the system.';
				}
				break;
			case 'update_dropdowns':
				$atts = $_REQUEST['params']['atts'];
				if($atts['saleclass'] == 'certified'){ $atts['saleclass'] = 'used'; $atts['certified'] = 'yes'; }
				switch( $_REQUEST['params']['filter'] ){
					case 'saleclass':
						$stack = array('make', 'model', 'trim');
						break;
					case 'make':
						$stack = array('model', 'trim');
						break;
					case 'model':
						$stack = array('trim');
						break;
					default:
						$stack = array(); //Trim Doesn't require a DD Update
						break;
				}
				$output = $this->dropdown_handler($stack, $atts); 
				//$output['content'] = 'test';
			break;
			default:
				$output['id'] = '';
				$output['error'] = 'That is not a valid FN parameter. Please check your string and try again.';
				break;
		}
		
		$output = json_encode($output);
		echo $output;
		wp_die();
	}
	
	function apply_ajax_gravity_hooks( $data ){
		$hooks = $this->set_ajax_gravity_hooks( $data );
		foreach( $hooks as $key => $result ){
			add_filter("gform_field_value_".$key, 
				function($value) use ($result) {
					$value=$result;
					return $value;
				}
			);
		}
	}

	function set_ajax_gravity_hooks( $data ){
		$result = array();
		foreach($data as $key => $value){
			$result['dt_'.$key] = $value;
		}
		return $result;
	}
	
	function get_vehicle_inventory( $params ){
		$this->vms->tracer = 'Obtaining requested inventory. Ajax from SC Detail.';
		$inventory = $this->vms->get_inventory()->please( $params );
		
		return $inventory;
	}
	
	function dropdown_handler( $requests, $atts ){
		$alt_id = isset($atts['dealer_id']) ? $atts['dealer_id'] : 0;
		if( !empty($requests) ){
			foreach( $requests as $request){
				switch($request){
					case 'make':
						$data = cdp_generate_make_options( $this->vms, $atts['saleclass'], $this->options['vehicle_management_system']['data']['makes_new'], FALSE, $alt_id );
						if( isset($data['val']) ){ $atts['make'] = $data['val']; $results['make']['att'] = $data['val']; }
						$results['make']['display'] = $data['display'];
						break;
					case 'model':
						$data = cdp_generate_model_options( $this->vms, $atts['saleclass'], $atts['make'], FALSE, $alt_id );
						if( isset($data['val']) ){ $atts['model'] = $data['val']; $results['model']['att'] = $data['val']; }
						if( isset($data['disabled']) ){ $results['model']['disabled'] = $data['disabled']; }
						$results['model']['display'] = $data['display'];
						break;
					case 'trim':
						$data = cdp_generate_trim_options( $this->vms, $atts['saleclass'], $atts['make'], $atts['model'], FALSE, $alt_id );
						if( isset($data['val']) ){ $atts['trim'] = $data['val']; $results['trim']['att'] = $data['val']; }
						if( isset($data['disabled']) ){ $results['trim']['disabled'] = $data['disabled']; }
						$results['trim']['display'] = $data['display'];
						break;
				}
			}
		}
		//cdp_generate_make_options( $this->vms, $atts['saleclass'], $this->options['vehicle_management_system']['data']['makes_new'], FALSE );
		return $results;
	}
	
}
	
	
?>