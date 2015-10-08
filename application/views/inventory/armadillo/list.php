<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$do_not_carry = remove_query_arg( 'page' );
	$tmp_do_not_carry = remove_query_arg( 'certified' , $do_not_carry );
	
	$new_link = ( isset($rules['^(inventory)']) ) ? '/inventory/New/' : add_query_arg( array('saleclass' => 'New'), $tmp_do_not_carry );
	$used_link = ( isset($rules['^(inventory)']) ) ? '/inventory/Used/' : add_query_arg( array('saleclass' => 'Used') );
	$cert_link = ( isset($rules['^(inventory)']) ) ? add_query_arg('certified', 'yes', '/inventory/Used/') : add_query_arg( array('saleclass' => 'Used', 'certified' => 'yes') );

	$filters = array(
		'vehicleclass' => isset( $parameters[ 'vehicleclass' ] ) ? $parameters[ 'vehicleclass' ] : NULL,
		'price_to' => isset( $parameters[ 'price_to' ] ) ? $parameters[ 'price_to' ] : NULL,
		'price_from' => isset( $parameters[ 'price_from' ] ) ? $parameters[ 'price_from' ] : NULL,
		'certified' => isset( $parameters[ 'certified' ] ) ? $parameters[ 'certified' ] : NULL,
		'search' => isset( $parameters[ 'search' ] ) ? $parameters[ 'search' ] : NULL,
		'year_to' => isset( $parameters[ 'year_to' ] ) ? $parameters[ 'year_to' ] : NULL,
		'year_from' => isset( $parameters[ 'year_from' ] ) ? $parameters[ 'year_from' ] : NULL,
		'mileage_to' => isset( $parameters[ 'mileage_to' ] ) ? $parameters[ 'mileage_to' ] : NULL,
		'mileage_from' => isset( $parameters[ 'mileage_from' ] ) ? $parameters[ 'mileage_from' ] : NULL
	);
	foreach( $filters as $key => $filter ){ if( empty($filter) ){ unset($filters[$key]); } }

	$this->vms->tracer = 'Obtaining a list of makes.';
	if( empty($geo_params) || (count($dealer_geo) == 1 && $geo_params['key'] == 'state') ){
		if ( strcasecmp( $parameters[ 'saleclass' ], 'new') == 0 && !empty( $this->options['vehicle_management_system' ]['data']['makes_new'] ) ) { //Get Makes
			$makes = $this->options['vehicle_management_system' ]['data']['makes_new'];
		} else {
			$makes = $this->vms->get_makes()->please( array_merge( array( 'saleclass' => $parameters[ 'saleclass' ] ) , $filters ) );
		}
	} else {
		$geo_makes = $this->vms->get_geo_dealer_mmt('makes',$parameters['dealer_id'], array_merge( array( 'saleclass' => $parameters[ 'saleclass' ] ) , $filters));
		natcasesort($geo_makes);
		$makes = $geo_makes;
	}
	$make_count = count($makes); $model = $trim = $model_count = $trim_count = '';

	if( isset( $parameters[ 'make' ] ) && $parameters[ 'make' ] != 'all' ) { //Get Models
		if( empty($geo_params) || (count($dealer_geo) == 1 && $geo_params['key'] == 'state') ){
			$this->vms->tracer = 'Obtaining a list of models.';
			$tmp_do_not_carry = remove_query_arg( 'make' , $do_not_carry );
			$models = $this->vms->get_models()->please( array_merge( array('saleclass'=>$parameters[ 'saleclass' ],'make'=>$parameters[ 'make' ]),$filters));
			if( isset($parameters[ 'model' ]) ){
				if( !in_array( rawurldecode($parameters[ 'model' ]), $models ) && !empty($parameters[ 'model' ]) ){
					$search_error = 'The current model('.$parameters[ 'model' ].') could not be found with current search parameters. Reset search or adjust search parameters. ';
				}
			}
		} else {
			$geo_models = $this->vms->get_geo_dealer_mmt('models',$parameters['dealer_id'], array_merge( array('saleclass'=>$parameters[ 'saleclass' ],'make'=>$parameters[ 'make' ]),$filters));
			natcasesort($geo_models);
			$models = $geo_models;
		}
		$model_count = count($models);
		$model = isset( $parameters[ 'model' ] ) ? $parameters[ 'model' ] : 'all';
		$parameters[ 'model' ] = $model;
		$model_text = 'All Models';
	} else {
		$model_text = 'Select a Make';
	}

	if( isset( $parameters[ 'model' ] ) && $parameters[ 'model' ] != 'all' ) { //Get Trims
		if( empty($geo_params) || (count($dealer_geo) == 1 && $geo_params['key'] == 'state') ){
			$this->vms->tracer = 'Obtaining a list of trims.';
			$tmp_do_not_carry = remove_query_arg( array( 'make' , 'model' ) , $do_not_carry );
			$trims = $this->vms->get_trims()->please( array_merge( array( 'saleclass' => $parameters[ 'saleclass' ] , 'make' => $parameters[ 'make' ] , 'model' => $parameters[ 'model' ] ) , $filters ) );
			if( isset($parameters[ 'trim' ]) ){
				if( !in_array( rawurldecode($parameters[ 'trim' ]), $trims ) && !empty( $parameters[ 'trim' ] ) ){
					$search_error = 'The current trim('.$parameters[ 'trim' ].') could not be found with current search parameters. Reset search or adjust search parameters. ';
				}	
			}
		} else {
			$geo_trims = $this->vms->get_geo_dealer_mmt('trims',$parameters['dealer_id'], array_merge( array('saleclass'=>$parameters[ 'saleclass' ],'make'=>$parameters[ 'make' ],'model'=>$parameters[ 'model' ]),$filters));
			natcasesort($geo_trims);
			$trims = $geo_trims;
		}
		$trim_count = count($trims);
		$trim = isset( $parameters[ 'trim' ] ) ? $parameters[ 'trim' ]  : 'all';
		$parameters[ 'trim' ] = $trim;
		$trim_text = 'All Trims';
	} else {
		$trim_text = 'Select a Model';
	}

	$sort = isset( $_GET[ 'sort' ] ) ? $_GET[ 'sort' ] : NULL;
	switch( $sort ) {
		case 'year_asc': $sort_year_class = 'asc'; break;
		case 'year_desc': $sort_year_class = 'desc'; break;
		case 'price_asc': $sort_price_class = 'asc'; break;
		case 'price_desc': $sort_price_class = 'desc'; break;
		case 'mileage_asc': $sort_mileage_class = 'asc'; break;
		case 'mileage_desc': $sort_mileage_class = 'desc'; break;
		default: $sort_year_class = $sort_price_class = $sort_mileage_class = null; break;
	}
	$sort_year = $sort != 'year_asc' ? 'year_asc' : 'year_desc';
	$sort_mileage = $sort != 'mileage_asc' ? 'mileage_asc' : 'mileage_desc';
	$sort_price = $sort != 'price_asc' ? 'price_asc' : 'price_desc';

?>

	<div id="inventory-wrapper">
		<div id="inventory-listing">
			<div class="breadcrumb-wrapper">
				<div class="breadcrumbs"><?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'] ); ?></div>
				<div class="inventory-pager"><?php echo paginate_links( $args ); ?></div>
			</div>
			<div class="inventory-search-text-wrapper">
				<form onsubmit="return list_search_field(event, this.value);" name="list_search_form" id="inventory-search" class="<?php echo $search_input_class; ?>">
					<label for="search">Inventory Search:</label>
					<input id="inventory-search-box" class="text-search" name="search" value="<?php echo isset( $parameters[ 'search' ] ) ? $parameters[ 'search' ] : NULL; ?>" />
				</form>
			</div>
			<div id="inventory-list-top-wrapper">
				<div id="inventory-total-found" class="color-one"><?php echo $vehicle_total_found; ?> Cars Found</div>
				<div id="inventory-sort-wrapper">
					<div id="sort-label">Sort by:</div>
					<div class="sort-value"><a class="<?php echo $sort_year_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_year ) , $do_not_carry ); ?>">Year</a></div>
					<div class="sort-value"><a class="<?php echo $sort_price_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_price ) , $do_not_carry ); ?>">Price</a></div>
					<div class="sort-value"><a class="<?php echo $sort_mileage_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_mileage ) , $do_not_carry ); ?>">Mileage</a></div>
				</div>
			</div>

			<div id="inventory-list-sidebar">
				<h3 id="list-sidebar-label-mobile" class="">Refine Your Search</h3>
				<h3 id="list-sidebar-label" class="color-one">Refine Your Search</h3>
				<ul id="list-saleclass-filter">
					<li class="inventory-expanded">
						<div class="list-sidebar-label"><span>Sale Class</span></div>
						<ul>
							<?php 
							$hide_certified = !empty($theme_settings['hide_certified_saleclass']) ? TRUE: FALSE;
							switch( $this->options['vehicle_management_system']['saleclass'] ) {
								case 'all':
									echo '<li><span class="no-style"><a href="' . $new_link . '" title="View New Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'new' ? 'disabled' : NULL) . '">New</a></span></li>';
									echo '<li><span class="no-style"><a href="' . $used_link . '" title="View Used Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' ? 'disabled' : NULL) . '">Used</a></span></li>';
									echo !$hide_certified ? '<li><span class="no-style"><a href="' . $cert_link . '" title="View Certified Used Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' ? 'disabled' : NULL) . '">Certified</a></span></li>' : '';
									break;
								case 'new':
									echo '<li><span class="no-style"><a href="' . $new_link . '" title="View New Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'new' ? 'disabled' : NULL) . '">New</a></span></li>';
									break;
								case 'used':
									echo '<li><span class="no-style"><a href="' . $used_link . '" title="View Used Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' ? 'disabled' : NULL) . '">Used</a></span></li>';
									echo !$hide_certified ? '<li><span class="no-style"><a href="' . $cert_link . '" title="View Certified Used Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' ? 'disabled' : NULL) . '">Certified</a></span></li>' : '';
									break;
								case 'certified':
									echo '<li><span class="no-style"><a href="' . $cert_link . '" title="View Certified Used Inventory" class="list-button ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' ? 'disabled' : NULL) . '">Certified</a></span></li>';
									break;
							}
							?>
						</ul>
					</li>
				</ul>
				<?php if($theme_settings['display_geo']) { ?>
					<ul id="list-geo-filter">
						<li class="inventory-expanded">
							<div class="list-sidebar-label"><span>Vehicle Location</span></div>
							<div id="geo-wrapper">
								<?php
									$geo_output = build_geo_dropdown($dealer_geo, $geo_params, $theme_settings['add_geo_zip'] );
									echo !empty( $geo_output['search'] ) ? $geo_output['search'] : ''; 
									echo !empty( $geo_output['dropdown'] ) ? $geo_output['dropdown'] : '';
									echo !empty( $geo_output['back_link'] ) ? $geo_output['back_link'] : '';
								?>
							</div>
						</li>
					</ul>
				<?php } ?>
				<ul id="list-vehicleclass-filter">
					<li class="inventory-expanded">
						<div class="list-sidebar-label"><span>Vehicle Class</span></div>
						<ul>
							<?php $vehicleclass = isset($parameters[ 'vehicleclass' ]) ? $parameters[ 'vehicleclass' ]: '';?>
							<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'car')); ?>" <?php echo $vehicleclass == 'car' ? 'class="active"' : NULL; ?>>Car</a></li>
							<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'truck')); ?>" <?php echo $vehicleclass == 'truck' ? 'class="active"' : NULL; ?>>Truck</a></li>
							<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'sport_utility')); ?>" <?php echo $vehicleclass == 'sport_utility' ? 'class="active"' : NULL; ?>>SUV</a></li>
							<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'van,minivan')); ?>" <?php echo $vehicleclass == 'van,minivan' ? 'class="active"' : NULL; ?>>Van</a></li>
						</ul>
					</li>
				</ul>

				<?php //Make | Model | Trim Fitler
					if ( $trim_count != 0 ) {
						$sidebar_content = '<ul id="list-trim-filter"><li class="inventory-expanded"><div class="list-sidebar-label"><span>Trims</span></div><ul>';
						foreach( $trims as $trim ) {
							$trim_safe = str_replace( '/' , '%2F' , $trim );
							$link = generate_inventory_link($url_rule,$parameters,array('trim'=>$trim_safe));
							$sidebar_content .= '<li><a href="'.$link.'">'.$trim.'</a></li>';
						}
						$back_link = generate_inventory_link($url_rule,$parameters,'',array('model','trim'));
						$sidebar_content .= '<li><span class="no-style"><a href="'.$back_link.'" class="inventory-filter-prev" title="View '.$parameters['make'].' Models">&#60; View '. $parameters[ 'make' ].'</a></span></li>';
						$sidebar_content .= '</ul></li></ul>';

					} else if ( $model_count != 0) {
						$sidebar_content = '<ul id="list-model-filter"><li class="inventory-expanded"><div class="list-sidebar-label"><span>Models</span></div><ul>';
						foreach( $models as $model ) {
							$model_safe = str_replace( '/' , '%2F' , $model );
							$link = generate_inventory_link($url_rule,$parameters,array('model'=>$model_safe));
							$sidebar_content .= '<li><a href="'.$link.'">'.$model.'</a></li>';
						}
						$back_link = generate_inventory_link($url_rule,$parameters,'',array('make', 'model'));
						$sidebar_content .= '<li><span class="no-style"><a href="'.$back_link.'" class="inventory-filter-prev" title="View ' . $parameters[ 'saleclass' ] . ' Vehicles">&#60; All ' . $parameters[ 'saleclass' ] . ' Vehicles</a></span></li>';
						$sidebar_content .= '</ul></li></ul>';
					} else if ( $make_count != 0) {
						$sidebar_content = '<ul id="list-make-filter"><li class="inventory-expanded"><div class="list-sidebar-label"><span>Makes</span></div><ul>';
							foreach( $makes as $make ) {
								$make_safe = str_replace( '/' , '%2F' , $make );
								$link = generate_inventory_link($url_rule,$parameters,array('make'=>$make_safe));
								$sidebar_content .= '<li><a href="'.$link.'">'.$make.'</a></li>';
							}
						$sidebar_content .= '</ul></li></ul>';
					} else {
						$sidebar_content = '<ul id="list-filter-error"><li><div class="list-sidebar-label"><span>No Makes Found</span></div></li><ul>';
					}
					echo $sidebar_content;
				?>
				<ul id="list-price-filter">
					<li class="inventory-expanded">
						<div class="list-sidebar-label"><span>Price Range</span></div>
						<ul>
							<?php $price_range = isset($parameters['price_from']) ? $parameters['price_from'] : '';?>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'1', 'price_to'=>'10000'));?>" <?php echo $price_range == "1" ? 'class="active"' : NULL; ?>>$1 - $10,000</a></li>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'10001', 'price_to'=>'20000'));?>" <?php echo $price_range == 10001 ? 'class="active"' : NULL; ?>>$10,001 - $20,000</a></li>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'20001', 'price_to'=>'30000')); ?>" <?php echo $price_range == 20001 ? 'class="active"' : NULL; ?>>$20,001 - $30,000</a></li>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'30001', 'price_to'=>'40000')); ?>" <?php echo $price_range == 30001 ? 'class="active"' : NULL; ?>>$30,001 - $40,000</a></li>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'40001', 'price_to'=>'50000')); ?>" <?php echo $price_range == 40001 ? 'class="active"' : NULL; ?>>$40,001 - $50,000</a></li>
							<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'50001')); ?>" <?php echo $price_range == 50001 ? 'class="active"' : NULL; ?>>$50,001 - &amp; Above</a></li>
						</ul>
					</li>
				</ul>
			</div>
		
		<div id="inventory-listing-content">
			<div id="inventory-listing-items">
				<?php
					if( empty( $inventory ) ) {
						echo '<div class="inventory-not-found"><h2><strong>Unable to find inventory items that matched your search criteria.</strong></h2><a onClick="history.go(-1)" title="Return to Previous Search" class="">Return to Previous Search</a></div>';
					} else {
						foreach( $inventory as $inventory_item ):
							$vehicle = itemize_vehicle($inventory_item);
							$generic_vehicle_title = $vehicle['year'] . ' ' . $vehicle['make']['clean'] . ' ' . $vehicle['model']['clean'];
							$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $state, 'city' => $city, 'vin' => $vehicle['vin'] );
							$link = generate_inventory_link($url_rule,$link_params,'','',1);
							?>
							<div id="<?php echo $vehicle['vin'];?>" class="inventory-vehicle saleclass-<?php echo strtolower($vehicle['saleclass']);?>">
								<div class="inventory-content-wrapper">
									<div class="inventory-column-left">
										<div class="inventory-photo">
											<a class="vehicle-link" href="<?php echo $link;?>">
												<?php echo $vehicle['sold'] ? '<img class="marked-sold-overlay" src="'.cdp_get_image_source().'sold_overlay.png" />' : '' ?>
												<img class="list-image" src="<?php echo $vehicle['thumbnail']; ?>" alt="<?php echo $generic_vehicle_title; ?>" title="<?php echo $generic_vehicle_title; ?>" />
											</a>
										</div>
									</div>
									<div class="inventory-column-right">
										<div class="inventory-main-line" title="<?php echo $generic_vehicle_title; ?>">
											<a class="vehicle-link" href="<?php echo $link;?>">
												<span class="inventory-year form-value" name="year"><?php echo $vehicle['year']; ?></span>
												<span class="inventory-make form-make" name="make"><?php echo $vehicle['make']['name']; ?></span>
												<span class="inventory-model form-model" name="model"><?php echo $vehicle['model']['name']; ?></span>
												<span class="inventory-trim form-trim" name="trim"><?php echo $vehicle['trim']['name']; ?></span>
												<span class="inventory-drive-train"><?php echo $vehicle['drive_train']; ?></span>
												<span class="inventory-body-style"><?php echo $vehicle['body_style']; ?></span>
											</a>
										</div>
										<?php
											if( strlen( trim( $vehicle['headline'] ) ) > 0 ) {
												echo '<div class="inventory-headline">' . $vehicle['headline'] . '</div>';
											}
											$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'], array() );
										?>
										<div class="inventory-details-left">
											<?php
												echo ( !empty($price['msrp_text']) && strtolower($vehicle['saleclass']) == 'new' ) ? $price['msrp_text'] : '';
												echo $vehicle['interior_color'] != NULL ? '<span class="inventory-interior-color">Interior: '.$vehicle['interior_color'].'</span>' : '';
												echo $vehicle['exterior_color'] != NULL ? '<span class="inventory-exterior-color">Exterior: '.$vehicle['exterior_color'].'</span>' : '';
												echo $vehicle['transmission'] != NULL ? '<span class="inventory-transmission">Transmission: '.$vehicle['transmission'].'</span>' : '';
											?>
										</div>
										<div class="inventory-details-right">
											<span>Stock #: <span class="inventory-stock-number form-value" name="stock_number"><?php echo $vehicle['stock_number']; ?></span></span>
											<span class="inventory-odometer">Odometer: <?php echo $vehicle['odometer']; ?></span>
											<span>VIN: <span class="inventory-vin form-value" name="vin"><?php echo $vehicle['vin']; ?></span></span>
										</div>
										<?php
											if( $theme_settings['display_tags'] ){
												apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
												if( !empty( $vehicle['tags'] ) ){
													echo '<div class="inventory-listing-tags">';
														$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
														echo $tag_icons;
													echo '</div>';
												}
											}
										?>
										<div class="inventory-details-bottom">
											<div class="bottom-inner-container-one">
												<div class="inventory-price-wrapper">
												<?php
													echo (!empty($price['rebate_link'])) ? $price['rebate_link'] : ( (!empty($price['ais_link'])) ? $price['ais_link'] : '' );
													echo $price['compare_text'].( empty($price['rebate_link']) ? $price['ais_text'] : '' ).$price['primary_text'].$price['expire_text'].$price['hidden_prices'];
												?>
												</div>
												<div class="list-detail-button" title="More Information: <?php echo $generic_vehicle_title; ?>">
													<a class="vehicle-link" href="<?php echo $link;?>"><?php echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'More Information'; ?></a>
												</div>
											</div>
											<div class="bottom-inner-container-two">
												<div class="inventory-contact-information">
													<?php
														echo get_dealer_contact_info( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] );
													?>
												</div>
												<?php
													if( $gform_class ){
														$form_name = $theme_settings['list_form_button'] ? $theme_settings['list_form_button'] : 'Get E-Price';
														echo '<div class="list-detail-button"><div class="inventory-form-button '.$gform_class.'" name="'.$form_name.'" key="'.$vehicle['vin'].'">'.$form_name.'</div></div>';
													}
													
													if( $vehicle['autocheck'] ){
														echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
													}
												?>
											</div>
										</div>
										<div class="inventory-form-container"></div>
									</div>
								</div>
							</div>
					<?php
						flush();
						endforeach;
					}
				?>
			</div>
		</div>
		<div id="inventory-disclaimer">
			<?php echo !empty( $inventory ) ? '<p>' . $inventory[ 0 ]->disclaimer . '</p>' : NULL; ?>
		</div>
	</div>
	<div class="breadcrumb-wrapper">
		<div class="breadcrumbs"><?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'] ); ?></div>
		<div class="inventory-pager"><?php echo paginate_links( $args ); ?></div>
	</div>
	<a href="#inventory-top" title="Return to Top" class="inventory-return-to-top">Return to Top</a>
	<?php
		if ( is_active_sidebar( 'vehicle-listing-page' ) ) :
			echo '<div id="list-widget-area">';
				dynamic_sidebar( 'vehicle-listing-page' );
			echo '</div>';
		endif;
	?>
</div>

