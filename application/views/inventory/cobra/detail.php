<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$vehicle = itemize_vehicle($inventory);
	$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
	$vehicle['primary_price'] = $price['primary_price'];
	$loan_parameters = array('model'=>$vehicle['model']['name'], 'trim'=> $vehicle['trim']['name'], 'year'=>$vehicle['year'], 'saleclass'=>$vehicle['saleclass']);

	apply_gravity_form_hooks( $vehicle );
	
	$flex_wrapper = $flex_one = $flex_two = $flex_three = $flex_four = $flex_five = $flex_six = '';
	if( $theme_settings[ 'style' ][ 'flex' ][ 'toggle' ] ){
		$containers = $theme_settings[ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ];
		$flex_wrapper = ' style="display: flex; flex-flow: row wrap; justify-content: space-around; margin: 0 auto; width: 96%" ';
		$flex_one = ' style="order: '.$containers[ 'one' ][ 'order' ].'; max-width: '.$containers[ 'one' ][ 'max-width' ].'px;" ';
		$flex_two = ' style="order: '.$containers[ 'two' ][ 'order' ].'; max-width: '.$containers[ 'two' ][ 'max-width' ].'px;" ';
		$flex_three = ' style="order: '.$containers[ 'three' ][ 'order' ].'; max-width: '.$containers[ 'three' ][ 'max-width' ].'px;" ';
		$flex_four = ' style="order: '.$containers[ 'four' ][ 'order' ].'; max-width: '.$containers[ 'four' ][ 'max-width' ].'px;" ';
		$flex_five = ' style="order: '.$containers[ 'five' ][ 'order' ].'; max-width: '.$containers[ 'five' ][ 'max-width' ].'px;" ';
		$flex_six = ' style="order: '.$containers[ 'six' ][ 'order' ].'; max-width: '.$containers[ 'six' ][ 'max-width' ].'px;" ';
	}

?>

	<div id="inventory-wrapper">
		<div id="inventory-detail" class="saleclass-<?php echo strtolower($vehicle['saleclass']); ?>">
			<div class="breadcrumbs">
				<?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?>
				<a id="friendly-print" onclick="window.open('?print_page','popup','width=550,height=800,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=0,top=0'); return false">Print Page</a>
			</div>
			<div id="inventory-top">
				<?php echo $vehicle['headline'] ? '<div id="inventory-detail-headline">'.$vehicle['headline'].'</div>' : ''; ?>
				<div id="top-inner-wrapper">
					<div id="top-inner-left">
						<div id="top-vehicle-info">
							<span id="top-year"><?php echo $vehicle['year']; ?></span>
							<span id="top-make"><?php echo $vehicle['make']['name']; ?></span>
							<span id="top-model"><?php echo $vehicle['model']['name']; ?></span>
							<span id="top-trim"><?php echo $vehicle['trim']['name']; ?></span>
						</div>
						<hr class="inventory-hr-half">
						<div id="top-dealer-info">
							<span id="dealer-name"><?php echo get_dealer_contact_name( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></span> -
							<span id="dealer-phone"><?php echo get_dealer_contact_number( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></span>
							<span style="display: none;" id="dealer-id"><?php echo $vehicle['contact_info']['dealer_id']; ?></span>
						</div>
					</div>
					<div id="top-inner-right">
						<div id="inventory-price-wrap">
							<?php echo
								( !empty($price['msrp_text']) && strtolower($vehicle['saleclass']) == 'new' ? $price['msrp_text'] : '') . '
								'.$price['primary_text'].$price['ais_text'].$price['compare_text'].$price['expire_text'].$price['hidden_prices'].'
								'. ( !empty($price['ais_link']) ? $price['ais_link'] : '');
								echo get_loan_value($theme_settings['loan'], $vehicle['primary_price'], $loan_parameters);
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="inventory-content-wrapper" class="theme-container-wrapper" <?php echo $flex_wrapper; ?> >
				<div class="container-wrap container-one" <?php echo $flex_one; ?> >
					<?php
						echo get_photo_detail_display( $vehicle['photos'], $vehicle['video'], $theme_settings['default_image_tab'], $flex_wrapper );
					?>
				</div>
				<div class="container-wrap container-two" <?php echo $flex_two; ?> >
					<div id="inventory-vehicle-info">
						<div id="info-headline">Vehicle Information</div>
					<?php
						echo'
							
							<div class="info-divider">Stock Number: <span id="info-stock-number" class="info-value">'.$vehicle['stock_number'].'</span></div>
							<div class="info-divider">VIN: <span id="info-vin" class="info-value">'.$vehicle['vin'].'</span></div>
							<div class="info-divider">Condition: <span id="info-saleclass" class="info-value">'.$vehicle['saleclass'].'</span></div>
							'.
							( $vehicle['certified'] != 'false' ? '<div class="info-divider">Certified: <span id="info-certified" class="info-value">Yes</span></div>' : '' ).
							( !empty($vehicle['odometer']) ? '<div class="info-divider">Mileage: <span id="info-mileage" class="info-value">'.$vehicle['odometer'].'</span></div>' : '' ).
							( !empty($vehicle['exterior_color']) ? '<div class="info-divider">Exterior: <span id="info-exterior" class="info-value">'.$vehicle['exterior_color'].'</span></div>' : '' ).
							( !empty($vehicle['interior_color']) ? '<div class="info-divider">Interior: <span id="info-interior" class="info-value">'.$vehicle['interior_color'].'</span></div>' : '' ).
							( !empty($vehicle['engine']) ? '<div class="info-divider">Engine: <span id="info-engine" class="info-value">'.$vehicle['engine'].'</span></div>' : '' ).
							( !empty($vehicle['transmission']) ? '<div class="info-divider">Transmission: <span id="info-transmission" class="info-value">'.$vehicle['transmission'].'</span></div>' : '' ).
							( !empty($vehicle['drivetrain']) ? '<div class="info-divider">Drivetrain: <span id="info-drivetrain" class="info-value">'.$vehicle['drivetrain'].'</span></div>' : '' ).
							( !empty($vehicle['doors']) ? '<div class="info-divider">Doors: <span id="info-doors" class="info-value">'.$vehicle['doors'].'</span></div>' : '' ).
							( !empty($vehicle['body_style']) ? '<div class="info-divider">Body: <span id="info-body" class="info-value">'.$vehicle['body_style'].'</span></div>' : '' )

						;
					?>
						
					</div>
				</div>
				<div class="container-wrap container-three" <?php echo $flex_three; ?> >
					<?php
					if( function_exists('gravity_form') && !empty($theme_settings['detail_gform_id']) ){
						echo '<div class="form-wrapper">';
						echo '<div id="info-form-id-'.$theme_settings['detail_gform_id'].'" class="form-display-wrap form-'.$theme_settings['detail_gform_id'].'" name="form-id-'.$theme_settings['detail_gform_id'].'">';
						echo do_shortcode('[gravityform id='.$theme_settings['detail_gform_id'].' title=true description=false]');
						echo '</div></div>';
					}
					?>
				</div>
				<div class="container-wrap container-four" <?php echo $flex_four; ?> >
					<?php
						echo get_fuel_economy_display( $vehicle['fuel_economy'], $country_code, 2, $this->vrs, $vehicle['acode'] );
						
						echo get_loan_calculator($theme_settings['loan'], $vehicle['primary_price'], TRUE, $loan_parameters);
						
						if( $vehicle['autocheck'] ){
							echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
						}

						if( $vehicle['carfax'] ) {
		 					echo '<div class="carfax-wrapper"><a href="' . $vehicle['carfax'] . '" class="inventory-carfax" target="_blank"><img src="'.cdp_get_image_source().'carfax_192x46.jpg" /></a></div>';
			 			}
						
						if( $theme_settings['display_tags'] ){
							apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
							if( !empty( $vehicle['tags'] ) ){
								echo '<div id="inventory-detail-tags">';
									$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
									echo $tag_icons;
								echo '</div>';
							}
						}
						
					?>
				</div>
				<div class="container-wrap container-five" <?php echo $flex_five; ?> >
					<?php
						if( function_exists('gravity_form') && isset($theme_settings['forms']) ){
							//get_form_button_display( $theme_settings['forms'], $vehicle['saleclass'] );
						}
					
						$tab_buttons = array(
							0 => array('options', 'Vehicle Options'),
							1 => array('description', 'Description'),
							2 => array('equipment', 'Standard Equipment'),
							3 => array('loan', 'Loan Calculator'),
							4 => array('similar', 'Similar'),
							5 => array('form', '')
						);

						$tab_data = array(
							'options' => array( (count($vehicle['dealer_options']) > 0 ? 1 : 0), $vehicle['dealer_options'] ),
							'description' => array( (strlen($vehicle['description']) > 0 ? 1 : 0), $vehicle['description'] ),
							'equipment' => array( ($theme_settings['show_standard_eq'] && !is_Empty_check($vehicle['standard_equipment']) ? 1 : 0 ), $vehicle['standard_equipment'] ),
							'loan' => array( 0, ''),
							'similar' => array(0, ''),
							'form' => array( (function_exists('gravity_form') && !empty($theme_settings['forms'])?1:0), $theme_settings['forms'] ),
							'values' => array('saleclass' => $vehicle['saleclass'], 'price' => $price['primary_price'] )
						);

						build_tab_display( $tab_buttons, $tab_data, $theme_settings['default_info_tab'], $flex_wrapper );
					?>
				</div>
				<div class="container-wrap container-six" <?php echo $flex_six; ?> >
					<?php
						if( $theme_settings['display_similar'] ) {
							echo get_similar_vehicles( $this->vms, $vehicle['vin'], $vehicle['saleclass'], $vehicle['vehicle_class'], $price['primary_price'], $vehicle['make']['name'], $this->options['vehicle_management_system' ]['data']['makes_new'], array( 'city' => $city, 'state' => $state), $flex_wrapper );
						}
					?>
				</div>
				<div id="inventory-disclaimer">
					<?php echo !empty( $vehicle['disclaimer'] ) ? '<p>' . $vehicle['disclaimer'] . '</p>' : NULL; ?>
				</div>
			</div>
			<?php
				if ( is_active_sidebar( 'vehicle-detail-page' ) ) :
					echo '<div id="detail-widget-area">';
						dynamic_sidebar( 'vehicle-detail-page' );
					echo '</div>';
				endif;
			?>
		</div>
	</div>

