<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$vehicle = itemize_vehicle($inventory);
	$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
	$vehicle['primary_price'] = $price['primary_price'];
	$loan_parameters = array('model'=>$vehicle['model']['name'], 'trim'=> $vehicle['trim']['name'], 'year'=>$vehicle['year'], 'saleclass'=>$vehicle['saleclass']);

	apply_gravity_form_hooks( $vehicle );
	$flex_wrapper = ' style="display: flex; flex-flow: row wrap; justify-content: space-around; margin: 0 auto; width: 96%" ';

?>

	<div id="inventory-wrapper">
		<div id="inventory-detail" class="saleclass-<?php echo strtolower($vehicle['saleclass']); ?>">
			<div id="inventory-title">
				<span id="title-year inventory-year"><?php echo $vehicle['year']; ?></span>
				<span id="title-make inventory-make"><?php echo $vehicle['make']['name']; ?></span>
				<span id="title-model inventory-model"><?php echo $vehicle['model']['name']; ?></span>
				<span id="title-trim inventory-trim"><?php echo $vehicle['trim']['name']; ?></span>
			</div>
			<div class="breadcrumbs">
				<?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?>
				<a id="friendly-print" onclick="window.open('?print_page','popup','width=550,height=800,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=0,top=0'); return false">Print Page</a>
			</div>
			<div id="vehicle-dealer-info">
				<span class="vehicle-dealer-name"><?php echo get_dealer_contact_name( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></span> - 
				<span class="vehicle-dealer-phone"><?php echo get_dealer_contact_number( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></span>
				<span class="vehicle-dealer-location"><?php echo $vehicle['contact_info']['location']; ?></span>
				<span style="display: none;" class="vehicle-dealer-id"><?php echo $vehicle['contact_info']['dealer_id']; ?></span>
			</div>
			<div id="inventory-content-wrapper">
				<div id="content-inner-left">
					<?php
						echo get_photo_detail_display( $vehicle['photos'], $vehicle['video'], $theme_settings['default_image_tab'] );
					?>
				</div>

				<div id="content-inner-right">
					<div id="inventory-price-wrap">
						<div id="price-main" class="inventory-price">
							<?php echo $price['primary_text']; ?>
						</div>
						<div id="price-extra">
						<?php echo
							( !empty($price['msrp_text']) && strtolower($vehicle['saleclass']) == 'new' ? $price['msrp_text'] : '') . '
							'.$price['ais_text'].$price['compare_text'].$price['expire_text'].$price['hidden_prices'].'
							'. ( !empty($price['ais_link']) ? $price['ais_link'] : '');
							echo get_loan_value($theme_settings['loan'], $vehicle['primary_price'], $loan_parameters);
						?>
						</div>
					</div>
					<?php echo $vehicle['headline'] ? '<div id="inventory-detail-headline">'.$vehicle['headline'].'</div>' : ''; ?>
					<div id="inventory-vehicle-info">
						<?php
							echo'
								<div class="info-divider">Stock Number: <span id="info-stock-number" class="info-value inventory-stock-number">'.$vehicle['stock_number'].'</span></div>
								<div class="info-divider">VIN: <span id="info-vin" class="info-value inventory-vin">'.$vehicle['vin'].'</span></div>
								<div class="info-divider">Condition: <span id="info-saleclass" class="info-value iventory-saleclass">'.$vehicle['saleclass'].'</span></div>
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
					<?php
						echo get_fuel_economy_display( $vehicle['fuel_economy'], $country_code, 1, $this->vrs, $vehicle['acode'] );

						if( $theme_settings['display_tags'] ){
							apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
							if( !empty( $vehicle['tags'] ) ){
								echo '<div id="inventory-detail-tags">';
									$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
									echo $tag_icons;
								echo '</div>';
							}
						}

						if( $vehicle['autocheck'] ){
							echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
						}

						if( $vehicle['carfax'] ) {
		 					echo '<div class="carfax-wrapper"><a href="' . $vehicle['carfax'] . '" class="inventory-carfax" target="_blank"><img src="'.cdp_get_image_source().'carfax_192x46.jpg" /></a></div>';
			 			}

					?>
				</div>
				<?php
					if( function_exists('gravity_form') && !empty($theme_settings['detail_gform_id']) ){
						$form = '<div id="content-inner-form">';
						$form .= '<div class="form-wrapper"><div id="info-form-id-'.$theme_settings['detail_gform_id'].'" class="inventory-form form-display-wrap form-'.$theme_settings['detail_gform_id'].'" name="form-id-'.$theme_settings['detail_gform_id'].'">';
						$form .= do_shortcode('[gravityform id='.$theme_settings['detail_gform_id'].' title=true description=false]');
						$form .= '</div></div></div>';
						echo $form;			
					}
				?>
				<div id="content-inner-bottom">
					<?php
						$similar_output = '';
						if( $theme_settings['display_similar'] ) {
							$similar_output =  get_similar_vehicles( $this->vms, $vehicle['vin'], $vehicle['saleclass'], $vehicle['vehicle_class'], $price['primary_price'], $vehicle['make']['name'], $this->options['vehicle_management_system' ]['data']['makes_new'], array( 'city' => $city, 'state' => $state) );
						}
						if(!empty($vehicle['dealer_options']) && is_array($vehicle['dealer_options']) ){ sort($vehicle['dealer_options']); }

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
							'loan' => array( $theme_settings['loan'][strtolower($vehicle['saleclass'])]['default']['display_calc'], $theme_settings['loan'], $loan_parameters ),
							'similar' => array($theme_settings['display_similar'], $similar_output),
							'form' => array( (function_exists('gravity_form') && !empty($theme_settings['forms'])?1:0), $theme_settings['forms'] ),
							'values' => array('saleclass' => $vehicle['saleclass'], 'price' => $price['primary_price'] )
						);

						build_tab_display( $tab_buttons, $tab_data, $theme_settings['default_info_tab'] );

					?>
				</div>
				<div id="inventory-disclaimer">
					<?php echo $vehicle['disclaimer']; ?>
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

