<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$vehicle = itemize_vehicle($inventory);
	$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
	$vehicle['primary_price'] = $price['primary_price'];
	$parameters['saleclass'] = $vehicle['saleclass'];
	$loan_parameters = array('model'=>$vehicle['model']['name'], 'trim'=> $vehicle['trim']['name'], 'year'=>$vehicle['year'], 'saleclass'=>$vehicle['saleclass']);
	apply_gravity_form_hooks( $vehicle );

	$traffic_source = isset( $_COOKIE[ 'cardealerpress-traffic-source' ] ) ? $_COOKIE[ 'cardealerpress-traffic-source' ] : false;
	$traffic_source = $this->sanitize_inputs( $traffic_source );

	usort($vehicle['dealer_options'], 'sort_length' );

	$form_subject = $vehicle['year'] . ' ' . $vehicle['make']['name'] . ' ' . $vehicle['model']['name'] . ' ' . $vehicle['stock_number'];
	$form_submit_url = $this->options[ 'vehicle_management_system' ]['host'] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] . '/forms/create/';

?>

<div id="inventory-wrapper">
	<div id="inventory-detail">
		<div id="inventory-top"> <!-- inventory Top -->
			<div class="inventory-breadcrumbs">
			<?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?>
			</div>
			<div id="inventory-top-info">
				<div id="inventory-headline-wrap">
					<div id="inventory-main-headline">
						<h2>
							<span class="inventory-saleclass form-value" name="saleclass" style="display: none;"><?php echo $vehicle['saleclass']; ?></span>
							<span class="inventory-year form-value" name="year"><?php echo $vehicle['year']; ?></span>
							<span class="inventory-make form-value" name="make"><?php echo $vehicle['make']['name']; ?></span>
							<span class="inventory-model form-value" name="model"><?php echo $vehicle['model']['name']; ?></span>
							<span class="inventory-trim form-value" name="trim"><?php echo $vehicle['trim']['name']; ?></span>
							<span class="inventory-drive-train"><?php echo $vehicle['drive_train']; ?></span>
							<span class="inventory-transmission"><?php echo $vehicle['transmission']; ?></span>
							<span class="inventory-body-style"><?php echo $vehicle['body_style']; ?></span>
						</h2>
					</div>
					<div id="inventory-sub-headline">
						<h3>
							<span class="inventory-make-subheadline"><?php echo $vehicle['make']['name']; ?></span>
							<span class="inventory-model-subheadline"><?php echo $vehicle['model']['name']; ?></span>
							<span class="inventory-trim-subheadline"><?php echo $vehicle['trim']['name']; ?></span>
							<span class="inventory-city-subheadline"><?php echo $city; ?></span>,
							<span class="inventory-state-subheadline"><?php echo $state; ?></span>

						</h3>
						<h3>
							<span class="inventory-sh-vehicle-location"><?php echo $vehicle['contact_info']['location']; ?></span>
						</h3>
					</div>
				</div>
				<div id="inventory-top-price">
					<div class="inventory-price">
						<?php
						$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
						echo (!empty($price['ais_link'])) ? $price['ais_link'] : '';
						echo $price['compare_text'].$price['ais_text'].$price['primary_text'].$price['expire_text'].$price['hidden_prices'];
						
						echo get_loan_value($theme_settings['loan'], $vehicle['primary_price'], $loan_parameters);
						?>
						
					</div>
					<!--<div id="inventory-get-price">
						<div class="inventory-get-price-button" name="<?php #echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'Get Your ePrice'; ?>"><?php #echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'GET YOUR ePrice'; ?></div>
					</div>-->
					<?php
						if( $theme_settings[ 'display_tags' ] ){
							apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
							if( !empty( $vehicle['tags'] ) ){
								echo '<div class="inventory-detail-tags">';
									$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
									echo $tag_icons;
								echo '</div>';
							}
						}
					?>
				</div>
				<?php
					if( $vehicle['headline'] ){
						echo '<div id="inventory-custom-headline">'.$vehicle['headline'].'</div>';
					}
				?>
			</div>
		</div>
		<div id="inventory-content"> <!-- inventory Content -->
			<div id="inventory-content-top"> <!-- inventory Content Top -->
				<div id="inventory-content-headline">
					<span class="inventory-content-year"><?php echo $vehicle['year']; ?></span>
					<span class="inventory-content-make"><?php echo $vehicle['make']['name']; ?></span>
					<span class="inventory-content-model"><?php echo $vehicle['model']['name']; ?></span>
					<span class="inventory-text">Photos:</span>
					<a id="friendly-print" onclick="window.open('?print_page','popup','width=800,height=900,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=0,top=0'); return false">Print</a>
				</div>
			</div>
			<div id="inventory-content-center"> <!-- inventory Content Center -->
				<div id="inventory-content-detail-left"> <!-- inventory Content Detail Left -->
					<div id="inventory-image-wrapper"> <!-- inventory Image Wrapper -->
						<?php
							$image_tab = isset($theme_settings['default_image_tab']) ? $theme_settings['default_image_tab'] : '';
							echo get_photo_detail_display( $vehicle['photos'], $vehicle['video'], $image_tab );
						?>
					</div>
					<div id="inventory-vehicle-information"> <!-- inventory Vehicle Information -->
						<div class="inventory-vehicle-info-divider">
							<h4 class="inventory-divider-headline">
								<span class="inventory-info-year"><?php echo $vehicle['year']; ?></span>
								<span class="inventory-info-make"><?php echo $vehicle['make']['name']; ?></span>
								<span class="inventory-info-model"><?php echo $vehicle['model']['name']; ?></span>
								<span class="inventory-divider-text">Vehicle Details:</span>
							</h4>
							<div class="inventory-divider-content">
								<div id="inventory-stock-vin-wrapper">
									<span class="inventory-text-bold">Stock #:</span><span id="" class="inventory-stock-number inventory-text-space form-value" name="stock_number"><?php echo $vehicle['stock_number']; ?></span>
									<span class="inventory-text-bold">Vin #:</span><span id="" class="inventory-vin inventory-text-space form-value" name="vin"><?php echo $vehicle['vin']; ?></span>
								</div>
								<div id="inventory-vehicle-details-wrapper">
									<div id="inventory-vehicle-detail-left">
										<?php
											$vehicle_info = '';

											if ( !empty( $vehicle['saleclass'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-saleclass">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Condition:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-saleclass-value">'.$vehicle['saleclass'].'</div>';
												$vehicle_info .= '</div>';

											}

											if ( $vehicle['certified'] == 'true') {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-certified">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Certified:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-certified-value">Yes</div>';
												$vehicle_info .= '</div>';

											}

											if ( !empty( $vehicle['odometer'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-odometer">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Odometer:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-odometer-value">' . $vehicle['odometer'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['engine'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-engine">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Engine:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-engine-value">' . $vehicle['engine'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['transmission'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-transmission">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Transmission:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-transmission-value">' . $vehicle['transmission'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['drive_train'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-drivetrain">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Drivetrain:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-drivetrain-value">' . $vehicle['drive_train'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['exterior_color'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-exterior">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Exterior Color:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-exterior-value">' . $vehicle['exterior_color'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['interior_color'] ) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap vehicle-interior">';
												$vehicle_info .= '<div class="inventory-vehicle-overview-left">Interior color:</div>';
												$vehicle_info .= '<div class="inventory-vehicle-overview-right vehicle-interior-value">' . $vehicle['interior_color'] . '</div>';
												$vehicle_info .= '</div>';
											}

											if ( !empty( $vehicle['carfax']) ) {
												$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
												$vehicle_info .= '<a href="' . $vehicle['carfax'] . '" class="inventory-detail-carfax" target="_blank">Carfax</a>';
												$vehicle_info .= '</div>';
											}

											echo $vehicle_info;

											if( $vehicle['autocheck'] ){
												echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
											}

										?>
									</div>
									<div id="inventory-vehicle-detail-right">
										<?php
											$fuel_text = '<div id="inventory-fuel-headline">Fuel Economy:</div>';
											$fuel_text .= '<div id="inventory-fuel-economy">';
											$fuel_text .= get_fuel_economy_display( $vehicle['fuel_economy'], $country_code, 0, $this->vrs, $vehicle['acode'] );
											$fuel_text .= '</div>';
											echo $fuel_text;
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="inventory-vehicle-info-divider" id="inventory-content-description-wrapper">
							<?php 
								if( $vehicle['description'] ){
									echo '<h4 class="inventory-divider-headline"><span class="inventory-divider-text">Vehicle Comments:</span></h4>';
									echo '<div class="inventory-divider-content"><div id="inventory-content-description"><p>'.$vehicle['description'].'</p></div></div>';
								} 
							?>
						</div>

						<div class="inventory-vehicle-info-divider" id="inventory-content-features-wrapper">
							<?php
								if( !empty($vehicle['dealer_options']) ){
									echo '<h4 class="inventory-divider-headline"><span class="inventory-divider-text collapse-toggle" name="dealer_options">Vehicle Specifications and Features:</span></h4>';
									echo '<div class="inventory-divider-content collapse-divider dealer_options">';
									$inventory_value = '<div id="inventory-content-features"><ul>';
									sort($vehicle['dealer_options']);
									foreach( $vehicle['dealer_options'] as $option ) {
										$inventory_value .= '<li>' . $option . '</li>';
									}
									$inventory_value .= '</ul></div></div>';
									echo $inventory_value;
								}
							?>
						</div>

						<?php
							if( isset($vehicle['standard_equipment']) && !is_Empty_check($vehicle['standard_equipment']) && $theme_settings['show_standard_eq'] ){
						?>
							<div class="inventory-vehicle-info-divider" id="inventory-content-equipment-wrapper">
								<h4 class="inventory-divider-headline"><span class="inventory-divider-text collapse-toggle" name="standard_equipment">Vehicle Standard Equipment:</span></h4>
								<div class="inventory-divider-content collapse-divider standard_equipment"> <?php echo display_equipment( $vehicle['standard_equipment'] ); ?></div>
							</div>
						<?php
							}
						?>
					</div>
				</div>

				<div id="inventory-content-detail-right"> <!-- inventory Content Detail Right -->
					<div id="inventory-contact-information">
						<?php
							$contact_info_value = '<div id="inventory-contact-name">' . get_dealer_contact_name( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ) . '</div>';
							$contact_info_value .= '<div id="inventory-contact-vehicle-location">' . $vehicle['contact_info']['location'] . '</div>';
							$contact_info_value .= '<div id="inventory-contact-phone">'.get_dealer_contact_number( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ) . '</div>';
							$contact_info_value .= '<div id="inventory-contact-message">' . $vehicle['contact_info']['greeting'] . '</div>';
							echo $contact_info_value;
						?>

					</div>
					<div class="inventory-content-sidebar-wrapper">
						<div class="inventory-forms">
							<?php
								if( function_exists('gravity_form') && !empty($theme_settings['detail_gform_id']) ){
									$form = '<div class="form-wrapper"><div id="info-form-id-'.$theme_settings['detail_gform_id'].'" class="inventory-form form-display-wrap form-'.$theme_settings['detail_gform_id'].'" name="form-id-'.$theme_settings['detail_gform_id'].'">';
									$form .= do_shortcode('[gravityform id='.$theme_settings['detail_gform_id'].' title=true description=false]');
									$form .= '</div></div>';
									echo $form;			
								} else {
							?>
							
							<div class="inventory-form-headers active-form" name="form-info">
								Tell Us How We Can Help:
							</div>
							<div class="inventory-form-headers-sub" name="form-info-sub">
								(Check All That Apply)
							</div>
							<div id="inventory-form-info" class="inventory-form" name="active" style="display: block;">
								<form action="#" method="post" name="vehicle-inquiry" id="vehicle-inquiry">
									<input type="hidden" name="traffic_source" value="<?php echo $traffic_source; ?>"/>
									<input name="required_fields" type="hidden" value="name,email,privacy" />
									<input name="subject" type="hidden" value="Vehicle Inquiry - <?php echo $form_subject; ?>" />
									<input name="saleclass" type="hidden" value="<?php echo $vehicle['saleclass']; ?>" />
									<input name="vehicle" type="hidden" value="<?php echo $vehicle['year'].' '.$vehicle['make']['name'].' '.$vehicle['model']['name']; ?>" />
									<input name="year" type="hidden" value="<?php echo $vehicle['year']; ?>" />
									<input name="make" type="hidden" value="<?php echo $vehicle['make']['name']; ?>" />
									<input name="model_name" type="hidden" value="<?php echo $vehicle['model']['name']; ?>" />
									<input name="trim" type="hidden" value="<?php echo $vehicle['trim']['name']; ?>" />
									<input name="stock" type="hidden" value="<?php echo $vehicle['stock_number']; ?>" />
									<input name="vin" type="hidden" value="<?php echo $vehicle['vin']; ?>" />
									<input name="inventory" type="hidden" value="<?php echo $vehicle['id']; ?>" />
									<input name="price" type="hidden" value="<?php echo $vehicle['primary_price']; ?>" />
									<input name="name" type="hidden" id="vehicle-inquiry-name" value="" />
									<input name="comments" type="hidden" id="vehicle-inquiry-comments" value="" />
									<div class="inventory-form-table">
										<div class="inventory-form-top-checkboxes">
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-general-questions" id="vehicle-inquiry-checkbox-1" tabindex="4" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-1">General Questions</label>
												</div>
											</div>
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-email-coupon" id="vehicle-inquiry-checkbox-2" tabindex="5" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-2">Email Me a Coupon</label>
												</div>
											</div>
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-call-asap" id="vehicle-inquiry-checkbox-3" tabindex="6" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-3">Call Me ASAP</label>
												</div>
											</div>
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-price-drop" id="vehicle-inquiry-checkbox-4" tabindex="7" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-4">Email Me When Price Drops</label>
												</div>
											</div>
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-request-video" id="vehicle-inquiry-checkbox-5" tabindex="8" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-5">Request Walk-Through Video</label>
												</div>
											</div>
											<div class="inventory-checkbox-wrapper">
												<div class="inventory-checkbox-left">
													<input class="inventory-checkbox" name="inventory-checkbox-send-eprice" id="vehicle-inquiry-checkbox-6" tabindex="9" type="checkbox"  />
												</div>
												<div class="inventory-checkbox-right">
													<label for="vehicle-inquiry-checkbox-6">Send Me The ePrice</label>
												</div>
											</div>
										</div>
										<div class="inventory-form-full">
											<div class="required">
												<input maxlength="70" id="vehicle-inquiry-f-name" name="f_name" tabindex="10" type="text" alt="empty" value="First Name*" />
											</div>
										</div>
										<div class="inventory-form-full">
											<div class="required">
												<input maxlength="70" id="vehicle-inquiry-l-name" name="l_name" tabindex="11" type="text" alt="empty" value="Last Name*" />
											</div>
										</div>

										<div class="inventory-form-full">
											<div class="required">
												<input maxlength="255" id="vehicle-inquiry-email" name="email" tabindex="12" type="text" alt="empty" value="Email Address*"/>
											</div>
										</div>
										<div class="inventory-form-full">
											<div>
												<input maxlength="256" name="phone" id="vehicle-inquiry-phone" tabindex="13" type="text" alt="empty" value="Phone Number"/>
											</div>
										</div>
										<div class="inventory-form-full">
											<div>
												<textarea name="vehicle-inquiry-form-comments" id="vehicle-inquiry-form-comments" rows="4" tabindex="14" alt="empty">Comments</textarea>
											</div>
										</div>
										<div class="inventory-form-full">
											<div style="display:none">
												<input name="agree_sb" type="checkbox" value="Yes" /> I am a Spam Bot?
											</div>
											<div style="display:none">
												<label for="vehicle-inquiry-privacy" style="float:left; margin-right:10px;">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
												<input class="privacy" name="privacy" id="vehicle-inquiry-privacy" tabindex="15" type="checkbox" checked />
											</div>
										</div>
										<div class="inventory-form-button">
											<div>
												<input onclick="return inventory_process_forms(<?php echo '&#39;' . $form_submit_url . strtolower( $vehicle['saleclass'] ) . '_vehicle_inquiry&#39;'; ?> , '0' )" type="submit" value="Send Inquiry" class="submit" tabindex="16" />
											</div>
										</div>
										<div class="inventory-form-full">
											<div class="form-error" style="display: none;">
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php } ?>
						</div>
						<?php
							echo get_loan_calculator($theme_settings['loan'], $vehicle['primary_price'], TRUE, $loan_parameters);

							// GForm Buttons
							if( function_exists('gravity_form') && isset($theme_settings['forms']) ){
								echo get_gform_button_display( $theme_settings['forms'], $vehicle['saleclass'] );
							}
							
							//inventory Similar Vehicles
							if( $theme_settings['display_similar'] ){
								echo '<div id="detail-similar-wrapper">';
								echo get_similar_vehicles( $this->vms, $vehicle['vin'], $vehicle['saleclass'], $vehicle['vehicle_class'], $price['primary_price'], $vehicle['make']['name'], $this->options['vehicle_management_system' ]['data']['makes_new'], array( 'city' => $city, 'state' => $state) );
								echo '</div>';
							}
						?>
					</div>
				</div>
			</div>
			<div id="inventory-content-bottom"> <!-- inventory Content Bottom -->
			</div>
		</div>
		<div id="inventory-bottom"> <!-- inventory Bottom -->
			<div id="inventory-disclaimer">
				<?php echo '<p>' . $inventory->disclaimer . '</p>'; ?>
			</div>
		</div>

		<?php
			if ( is_active_sidebar( 'vehicle-detail-page' ) ) :
				echo '<div id="inventory-widget-area" class="sidebar">';
					dynamic_sidebar( 'vehicle-detail-page' );
				echo '</div>';
			endif;
		?>

		<div class="inventory-forms inventory-hidden-form" style="display: none;">
			<div class="inventory-form-headers active-form" name="form-info" tabindex="19">
			</div>
			<div class="inventory-form-headers-sub" name="form-info-sub">
			</div>
			<div id="inventory-form-info" class="inventory-form" name="active" style="display: block;">
				<form action="#" method="post" name="vehicle-inquiry" id="vehicle-inquiry-hidden">
					<input type="hidden" name="traffic_source" value="<?php echo $traffic_source; ?>"/>
					<input name="required_fields" type="hidden" value="name,email,privacy" />
					<input name="subject" type="hidden" id="vehicle-inquiry-subject-hidden" value="" />
					<input name="saleclass" type="hidden" value="<?php echo $vehicle['saleclass']; ?>" />
					<input name="vehicle" type="hidden" value="<?php echo $vehicle['year'].' '.$vehicle['make']['name'].' '.$vehicle['model']['name']; ?>" />
					<input name="year" type="hidden" value="<?php echo $vehicle['year']; ?>" />
					<input name="make" type="hidden" value="<?php echo $vehicle['make']['name']; ?>" />
					<input name="model_name" type="hidden" value="<?php echo $vehicle['model']['name']; ?>" />
					<input name="trim" type="hidden" value="<?php echo $vehicle['trim']['name']; ?>" />
					<input name="stock" type="hidden" value="<?php echo $vehicle['stock_number']; ?>" />
					<input name="vin" type="hidden" value="<?php echo $vehicle['vin']; ?>" />
					<input name="inventory" type="hidden" value="<?php echo $vehicle['id']; ?>" />
					<input name="price" type="hidden" value="<?php echo $vehicle['primary_price']; ?>" />
					<input name="name" type="hidden" id="vehicle-inquiry-name-hidden" value="" />
					<input name="subject-pre" type="hidden" id="vehicle-inquiry-subpre-hidden" value="" />
					<input name="subject-post" type="hidden" id="vehicle-inquiry-subpost-hidden" value="<?php echo $form_subject; ?>" />
					<div class="inventory-form-table">
						<div class="inventory-form-full">
							<div class="required">
								<input maxlength="70" id="vehicle-inquiry-f-name-hidden" name="f_name" tabindex="20" type="text" alt="empty" value="First Name*" />
							</div>
						</div>
						<div class="inventory-form-full">
							<div class="required">
								<input maxlength="70" id="vehicle-inquiry-l-name-hidden" name="l_name" tabindex="21" type="text" alt="empty" value="Last Name*" />
							</div>
						</div>

						<div class="inventory-form-full">
							<div class="required">
								<input maxlength="255" id="vehicle-inquiry-email-hidden" name="email" tabindex="22" type="text" alt="empty" value="Email Address*"/>
							</div>
						</div>
						<div class="inventory-form-full">
							<div>
								<input maxlength="256" name="phone" id="vehicle-inquiry-phone-hidden" tabindex="23" type="text" alt="empty" value="Phone Number"/>
							</div>
						</div>
						<div class="inventory-form-full">
							<div>
								<textarea name="comments" id="vehicle-inquiry-form-comments-hidden" rows="4" tabindex="24" alt="empty">Comments</textarea>
							</div>
						</div>
						<div class="inventory-form-full">
							<div style="display:none">
								<input name="agree_sb" type="checkbox" value="Yes" /> I am a Spam Bot?
							</div>
							<div style="display:none">
								<label for="vehicle-inquiry-privacy" style="float:left; margin-right:10px;">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
								<input class="privacy" name="privacy" id="vehicle-inquiry-privacy-hidden" type="checkbox" checked />
							</div>
						</div>
						<div class="inventory-form-button">
							<div>
								<input onclick="return inventory_process_forms(<?php echo '&#39;' . $form_submit_url . strtolower( $vehicle['saleclass'] ) . '_vehicle_inquiry&#39;'; ?> , '3' )" type="submit" value="Send Inquiry" class="submit" tabindex="25" />
							</div>
						</div>
						<div class="inventory-form-full">
							<div class="form-error" style="display: none;">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>



