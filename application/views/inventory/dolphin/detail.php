<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$vehicle = itemize_vehicle($inventory);
	$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
	$vehicle['primary_price'] = $price['primary_price'];
	$parameters['saleclass'] = $vehicle['saleclass'];
	apply_gravity_form_hooks( $vehicle );

	$traffic_source = isset( $_COOKIE[ 'cardealerpress-traffic-source' ] ) ? $_COOKIE[ 'cardealerpress-traffic-source' ] : false;
	$traffic_source = $this->sanitize_inputs( $traffic_source );

	usort($vehicle['dealer_options'], 'sort_length' );

	$form_subject = $vehicle['year'] . ' ' . $vehicle['make']['name'] . ' ' . $vehicle['model']['name'] . ' ' . $vehicle['stock_number'];
	$form_submit_url = $this->options[ 'vehicle_management_system' ]['host'] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] . '/forms/create/';
?>



<div id="inventory-wrapper"> <!-- 2nd Wrapper -->
	<div id="inventory-detail"> <!-- Detail Body -->
		<div id="inventory-top"> <!-- Detail Top -->
			<div class="inventory-breadcrumb-wrapper"> <!-- SEO/Taxonomy -->
				<div class="breadcrumbs"><?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></div> <!-- Breadcrumbs -->
				<div class="inventory-print"> <!-- Print -->
					<a id="friendly-print" onclick="window.open('?print_page','popup','width=800,height=900,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=0,top=0'); return false">Print</a>
				</div>
			</div>
			<div id="inventory-headline"> <!-- Headline -->
				<h2>
					<span class="inventory-saleclass"><?php echo $vehicle['saleclass']; ?></span>
					<span class="inventory-year"><?php echo $vehicle['year']; ?></span>
					<span class="inventory-make"><?php echo $vehicle['make']['name']; ?></span>
					<span class="inventory-model"><?php echo $vehicle['model']['name']; ?></span>
					<span class="inventory-trim"><?php echo $vehicle['trim']['name']; ?></span>
					<span class="inventory-drive-train"><?php echo $vehicle['drive_train']; ?></span>
					<span class="inventory-body-style"><?php echo $vehicle['body_style']; ?></span>

				</h2>
				<?php
					if ( !empty( $vehicle['headline'] ) ){
						$value = '<div id="inventory-headline-text">' . $vehicle['headline'] . '</div>';
						echo $value;
					}
				?>

			</div>
		</div> <!-- Detail Top End-->
		<div id="inventory-media-wrapper">
			<div id="inventory-contact-information"> <!-- Contact Information -->
				<?php
					$value = '<div id="inventory-contact-details">';
					$value .= '<span id="inventory-contact-greeting">'.$vehicle['contact_info']['greeting'].'</span><br/><strong id="inventory-contact-dealer">'.get_dealer_contact_name( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ).' </strong>';
					$value .= ' <span id="inventory-contact-phone">: <strong>'.get_dealer_contact_number( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ).'</strong></span>';
					$value .= '</div>';
					echo $value;
				?>
			</div> <!-- Contact Information End -->
			<!--
			<div id="inventory-detail-share"> 
				<div id="inventory-share-text">Share:</div>
				<ul id="inventory-share-buttons">
					<li id="inventory-facebook"><a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php //echo $share_link; ?>" >facebook</a></li>
					<li id="inventory-google"><a target="_blank" href="https://plus.google.com/share?url=<?php //echo $share_link; ?>" >google plus</a></li>
					<li id="inventory-twitter"><a target="_blank" href="http://twitter.com/share?text=Found%20at%20<?php //echo $share_dealer; ?>&url=<?php //echo $share_link; ?> ">twitter</a></li>
				</ul>
			</div>
			-->
		</div>
		<div id="inventory-detail-information"> <!-- Detail Information -->
			<div id="inventory-column-left"> <!-- Column Left -->
				<div id="inventory-slideshow-text">
					Click Image to Enlarge
				</div>
				<div id="inventory-slideshow">
					<?php
						echo get_photo_detail_display( $vehicle['photos'], $vehicle['video'], $theme_settings['default_image_tab'] );
						if( count( $vehicle['photos'] ) > 1 ) {
							echo '<div id="inventory-nav-button" class="'.( count($vehicle['photos']) > 6 ? 'active' : 'hidden').'">View All Images</div>';
						}
					?>
				</div>
				<?php
					if( $theme_settings['display_tags'] ){
						apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
						if( !empty( $vehicle['tags'] ) ){
							echo '<div class="inventory-detail-tags">';
								$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
								echo $tag_icons;
							echo '</div>';
						}
					}
				?>

				<?php
					if( $vehicle['carfax'] ) {
						echo '<div id="inventory-carfax"><a href="'.$vehicle['carfax'].'" target="_blank"><img src="'.cdp_get_image_source().'carfax_192x46.jpg" /></a></div>';
					}

					if( $vehicle['autocheck'] ){
						echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
					}

				?>
			</div> <!-- Column Left End -->
			<div id="inventory-column-middle"> <!-- Column Middle -->
				<div id="inventory-price-wrapper">
					<?php
						echo $price['hidden_prices'];
						echo ( !empty($price['ais_link']) ) ? $price['ais_link'] : '';
						echo $price['primary_text'].$price['ais_text'].$price['compare_text'].$price['msrp_text'].$price['expire_text'];
					?>
				</div>
				<div id="inventory-vehicle-overview">
					<?php
						$vehicle_info = '';
						if ( $vehicle['certified'] != 'false' ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Certified:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">Yes</div>';
							$vehicle_info .= '</div>';

						}

						if ( !empty( $vehicle['body_style'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Body Style:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['body_style'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['exterior_color'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Ext. Color:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['exterior_color'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['interior_color'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Int. color:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['interior_color'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['engine'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Engine:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['engine'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['transmission'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Transmission:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['transmission'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['drive_train'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Drivetrain:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['drive_train'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['odometer'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Odometer:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['odometer'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle_info ) ) { $vehicle_info .= '<br>'; }

						if ( !empty( $vehicle['vin'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Vin:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['vin'] . '</div>';
							$vehicle_info .= '</div>';
						}

						if ( !empty( $vehicle['stock_number'] ) ) {
							$vehicle_info .= '<div class="inventory-vehicle-overview-wrap">';
							$vehicle_info .= '<div class="inventory-vehicle-overview-left">Stock #:</div>';
							$vehicle_info .= '<div class="inventory-vehicle-overview-right">' . $vehicle['stock_number'] . '</div>';
							$vehicle_info .= '</div>';
						}

						echo $vehicle_info;

					?>
				</div>
				<?php
					$fuel_info = get_fuel_economy_display( $vehicle['fuel_economy'], $country_code, 1, $this->vrs, $vehicle['acode'] );
					if( !empty($fuel_info) ){
						echo $fuel_info;
					}
				
				?>
			</div> <!-- Column Middle End -->
			<div id="inventory-column-right"> <!-- Column Right -->
				<div class="inventory-forms">
					<?php
						if( function_exists('gravity_form') && !empty($theme_settings['detail_gform_id']) ){
							$form = '<div class="form-wrapper"><div id="info-form-id-'.$theme_settings['detail_gform_id'].'" class="inventory-form form-display-wrap form-'.$theme_settings['detail_gform_id'].'" name="form-id-'.$theme_settings['detail_gform_id'].'">';
							$form .= do_shortcode('[gravityform id='.$theme_settings['detail_gform_id'].' title=true description=false]');
							$form .= '</div></div></div>';
							echo $form;							
						} else {
					?>
					<div class="inventory-form-headers active-form" name="form-info">
						Request Information
					</div>
					<div id="inventory-form-info" class="inventory-form" name="active" style="display: block;">
						<form action="#" method="post" name="vehicle-inquiry" id="vehicle-inquiry">
							<input type="hidden" name="traffic_source" value="<?php echo $traffic_source; ?>"/>
							<input name="required_fields" type="hidden" value="name,email,privacy" />
							<input name="subject" type="hidden" value="Vehicle Inquiry - <?php echo $vehicle['headline']; ?>" />
							<input name="saleclass" type="hidden" value="<?php echo $vehicle['saleclass']; ?>" />
							<input name="vehicle" type="hidden" value="<?php echo $form_subject; ?>" />
							<input name="year" type="hidden" value="<?php echo $vehicle['year']; ?>" />
							<input name="make" type="hidden" value="<?php echo $vehicle['make']['name']; ?>" />
							<input name="model_name" type="hidden" value="<?php echo $vehicle['model']['name']; ?>" />
							<input name="trim" type="hidden" value="<?php echo $vehicle['trim']['name']; ?>" />
							<input name="stock" type="hidden" value="<?php echo $vehicle['stock_number']; ?>" />
							<input name="vin" type="hidden" value="<?php echo $vehicle['vin']; ?>" />
							<input name="inventory" type="hidden" value="<?php echo $vehicle['id']; ?>" />
							<input name="price" type="hidden" value="<?php echo $vehicle['primary_price']; ?>" />
							<input name="name" type="hidden" id="vehicle-inquiry-name" value="" />
							<div class="inventory-form-table">
								<div class="inventory-form-one-half">
									<div class="required">
										<label for="vehicle-inquiry-f-name">First Name*</label>
										<input maxlength="70" id="vehicle-inquiry-f-name" name="f_name" tabindex="10" type="text" />
									</div>
									<div class="required">
										<label for="vehicle-inquiry-l-name">Last Name*</label>
										<input maxlength="70" id="vehicle-inquiry-l-name" name="l_name" tabindex="11" type="text" />
									</div>
								</div>
								<div class="inventory-form-one-half">
									<div class="required">
										<label for="vehicle-inquiry-email">Email Address*</label>
										<input maxlength="255" id="vehicle-inquiry-email" name="email" tabindex="12" type="text" />
									</div>
									<div>
										<label for="vehicle-inquiry-phone">Phone Number</label>
										<input maxlength="256" name="phone" id="vehicle-inquiry-phone" tabindex="13" type="text" />
									</div>
								</div>
								<div class="inventory-form-full">
									<div class="required">
										<label for="vehicle-inquiry-comments">Questions/Comments</label>
										<textarea name="comments" id="vehicle-inquiry-comments" rows="4" tabindex="14"></textarea>
									</div>
								</div>
								<div class="inventory-form-full">
									<div style="display:none">

										<input name="agree_sb" type="checkbox" value="Yes" /> I am a Spam Bot?
									</div>
									<div>
										<label for="vehicle-inquiry-privacy" style="float:left; margin-right:10px;">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
										<input class="privacy" name="privacy" id="vehicle-inquiry-privacy" tabindex="15" type="checkbox" checked />
									</div>
									<div>
										<input onclick="inventory_detail_forms(<?php echo '&#39;' . $form_submit_url . strtolower( $vehicle['saleclass'] ) . '_vehicle_inquiry&#39;'; ?> , '0' )" type="submit" value="Send Inquiry" class="submit" tabindex="16" />
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
				<?php
					}
					
					if( !empty($theme_settings['forms']) ){
						$gform_buttons = get_gform_button_display( $theme_settings['forms'], $vehicle['saleclass'] );
						echo $gform_buttons;
					}
					if( $theme_settings['loan']['display_calc'] ){
						echo get_loan_calculator($theme_settings['loan'], $vehicle['primary_price'], TRUE);	
					}
				?>

			</div> <!-- Column Right End -->
			<div id="inventory-detail-specs"> <!-- Detail Specs -->
				<?php
				echo get_vehicle_detail_display( $vehicle['dealer_options'], $vehicle['description'], $theme_settings['show_standard_eq'], $vehicle['standard_equipment'], $theme_settings['default_info_tab']);
				?>
			</div>  <!-- Detail Specs End -->

			<?php //inventory Similar Vehicles
				if( $theme_settings['display_similar'] ){
					echo '<div id="detail-similar-wrapper">';
					echo get_similar_vehicles( $this->vms, $vehicle['vin'], $vehicle['saleclass'], $vehicle['vehicle_class'], $price['primary_price'], $vehicle['make']['name'], $this->options['vehicle_management_system' ]['data']['makes_new'], array( 'city' => $city, 'state' => $state) );
					echo '</div>';
				}
			?>

			<div id="inventory-disclaimer">
				<?php echo '<p>' . $inventory->disclaimer . '</p>'; ?>
			</div>

			<?php
				if ( is_active_sidebar( 'vehicle-detail-page' ) ) :
					echo '<div id="inventory-widget-area" class="sidebar">';
						dynamic_sidebar( 'vehicle-detail-page' );
					echo '</div>';
				endif;
			?>
		</div> <!-- Detail Information End -->
	</div> <!-- Detail Body End-->
</div>  <!-- 2nd Wrapper End -->
