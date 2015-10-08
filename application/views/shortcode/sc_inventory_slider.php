<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$rules = get_option( 'rewrite_rules' );
	$url_rule = ( isset($rules['^(inventory)']) ) ? TRUE : FALSE;
	$slick = ''; $class = '';

	$this->vms->tracer = 'Obtaining requested sc inventory.';
	$inventory = $this->vms->get_inventory()->please( $this->inventory_params );

	$sc_content = '<div class="sc-slider-container">';
	if( isset($this->slick_params['title']) ){
		$sc_content .= '<div class="sc-slider-title">'.$this->slick_params['title'].'</div>';
	}
	if( empty( $inventory ) ) {
		$sc_content .= '<div class="sc-not-found"><h2><strong>Search criteria did not return any results.</strong></h2></div>';
	} else {
		if($this->is_slider){
			$slick .= 'data-slick=\'{'; $class="sc-slider-init";
			foreach($this->slick_params as $key => $value){
				$slick .= '"'.$key.'": '.( is_bool($value) ? (!empty($value)? 'true': 'false'): '"'.$value.'"').',';
			}
			$slick = rtrim($slick, ',').'}\'';
		} else {
			$class='sc-slider-flex';
		}
		$sc_content .= '<div class="sc-slider-inner-container"><div class="'.$class.'" '.$slick.'>';
		foreach( $inventory as $inventory_item ){
			$vehicle = itemize_vehicle($inventory_item);
			$sc_content .= get_sc_slider_view( $vehicle, 'sc-slider', $this->company, $this->options, $url_rule );
		}
		$sc_content .= '</div></div>';
	}
	$sc_content .= '</div>';
	
	$this->sc_content .= $sc_content;
?>