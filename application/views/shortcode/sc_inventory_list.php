<?php

	$rules = get_option( 'rewrite_rules' );
	$url_rule = ( isset($rules['^(inventory)']) ) ? TRUE : FALSE;
	/* echo '<pre>'; var_dump($sc_atts); echo '</pre>'; */

	$this->vms->tracer = 'Obtaining requested sc inventory.';
	$inventory_information = $this->vms->get_inventory()->please( $this->sc_atts );
	$inventory = isset( $inventory_information[ 'body' ] ) ? json_decode( $inventory_information[ 'body' ] ) : array();

	$state = $this->company->state;
	$city = $this->company->city;

	$site_url = site_url();

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );

	if( strtolower($this->sc_style) != 'clear' ){
		wp_enqueue_style(
			'cdp-shortcode-style',
			cdp_get_css_source().'shortcodes/sc_'.$this->sc_style.'.css',
			false,
			1.0
		);
	}

	$sc_content = '<div id="sc-inventory-wrapper" >';

	if( empty( $inventory ) ) {
		$sc_content .= '<div class="sc-not-found"><h2><strong>Shortcode criteria did not return any results.</strong></h2></div>';
	} else {
		foreach( $inventory as $inventory_item ){
			$vehicle = itemize_vehicle($inventory_item);
			$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $state, 'city' => $city, 'vin' => $vehicle['vin'] );
			$link = generate_inventory_link($url_rule,$link_params,'','',1);

			//wrapper -s
			$sc_content .= '<div class="sc-item-wrapper"><a href="'.$link.'">';
			//title
			$sc_content .= '<div class="sc-title-wrapper">';
			$sc_content .= '<span class="sc-year">' . $vehicle['year'] . '</span>';
			$sc_content .= '<span class="sc-make">' . $vehicle['make']['name'] . '</span>';
			$sc_content .= '<span class="sc-model">' . $vehicle['model']['name'] . '</span>';
			$sc_content .= '</div>';
			//image
			$sc_content .= '<div class="sc-img-wrapper">';
			$sc_content .= '<img src="'.$vehicle['thumbnail'].'" />';
			$sc_content .= '</div>';
			//price
			$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'short-code', $this->options['vehicle_management_system' ]['theme']['price_text'], array() );
			
			$sc_content .= '<div class="sc-price-wrapper">';
			if ( $price['primary_price'] > 0 ) {
				$sc_content .= '<div class="sc-price-value"><span class="sc-price-symbol">$</span>' .  number_format( $price['primary_price'] , 0 , '.' , ',' ) . '</div>';
			} else {
				$sc_content .= '<div class="sc-price-value-text">'.$price['primary_text'].'</div>';
			}
			$sc_content .= '</div>';
			//wrapper -e
			$sc_content .= '</a></div>';
			
		}
	}

	$sc_content .= '</div>';
	$this->sc_content = $sc_content;

?>
