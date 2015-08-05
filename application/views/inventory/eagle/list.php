<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$this->vms->tracer = 'Calculating how many items were returned with the given parameters.';
	$vehicle_total_found = $this->vms->get_inventory()->please( array_merge( $parameters , array( 'per_page' => 1 , 'photo_view' => 1 , 'make_filters' =>  $this->options['vehicle_management_system' ]['data']['makes_new'] ) ) );
	$vehicle_total_found = ( isset($vehicle_total_found[ 'body' ]) ) ? json_decode( $vehicle_total_found[ 'body' ] ) : NULL;
	$vehicle_total_found = is_array( $vehicle_total_found ) && count( $vehicle_total_found ) > 0 ? $vehicle_total_found[ 0 ]->pagination->total : 0;

	$do_not_carry = remove_query_arg( 'page' );
	$tmp_do_not_carry = remove_query_arg( 'certified' , $do_not_carry );

	$new_link = ( isset($rules['^(inventory)']) ) ? '/inventory/New/' : add_query_arg( array('saleclass' => 'New'), $tmp_do_not_carry );
	$used_link = ( isset($rules['^(inventory)']) ) ? '/inventory/Used/' : add_query_arg( array('saleclass' => 'Used') );
	$cert_link = ( isset($rules['^(inventory)']) ) ? add_query_arg('certified', 'yes', '/inventory/Used/') : add_query_arg( array('saleclass' => 'Used', 'certified' => 'yes') );

	//echo generate_inventory_link( $url_rule, $parameters );

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
			$makes = json_decode( $makes[ 'body' ] );
		}
	} else {
		$geo_makes = $this->vms->get_geo_dealer_mmt('makes',$parameters['dealer_id'], array_merge( array( 'saleclass' => $parameters[ 'saleclass' ] ) , $filters));
		natcasesort($geo_makes);
		$makes = $geo_makes;
	}
	$make_count = count($makes); $model_count = 0; $trim_count = 0;

	if( isset( $parameters[ 'make' ] ) && $parameters[ 'make' ] != 'all' ) { //Get Models
		if( empty($geo_params) || (count($dealer_geo) == 1 && $geo_params['key'] == 'state') ){
			$this->vms->tracer = 'Obtaining a list of models.';
			$tmp_do_not_carry = remove_query_arg( 'make' , $do_not_carry );
			$models = $this->vms->get_models()->please( array_merge( array('saleclass'=>$parameters[ 'saleclass' ],'make'=>$parameters[ 'make' ]),$filters));
			$models = json_decode( $models[ 'body' ] );
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
			$trims = json_decode( $trims[ 'body' ] );
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
		case 'make_asc': $sort_make_class = 'asc'; break;
		case 'make_desc': $sort_make_class = 'desc'; break;
		default: $sort_year_class = $sort_price_class = $sort_mileage_class = null; break;
	}
	$sort_year = $sort != 'year_asc' ? 'year_asc' : 'year_desc';
	$sort_mileage = $sort != 'mileage_asc' ? 'mileage_asc' : 'mileage_desc';
	$sort_price = $sort != 'price_asc' ? 'price_asc' : 'price_desc';
	$sort_make = $sort != 'make_asc' ? 'make_asc' : 'make_desc';

	$traffic_source = isset( $_COOKIE[ 'cardealerpress-traffic-source' ] ) ? $_COOKIE[ 'cardealerpress-traffic-source' ] : false;
	$traffic_source = $this->sanitize_inputs( $traffic_source );

?>
<div id="inventory-wrapper">
	<div id="inventory-listing">
		<div id="inventory-top"> <!-- inventory Top -->
			<div class="inventory-breadcrumbs">
			<?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'], $parameters[ 'saleclass' ] ); ?>
			</div>
			<div id="inventory-top-search">
				<div class="inventory-search">
					<form action="#" method="GET" id="inventory-search-text" class="<?php echo $search_input_class;?>">
						<input id="inventory-search-box" class="text-search" name="search" value="<?php echo isset( $parameters[ 'search' ] ) ? $parameters[ 'search' ] : NULL; ?>" />
						<button id="inventory-search-submit">SEARCH</button>
					</form>
				</div>
				<div class="inventory-pager">
					<span>Page:</span><?php echo paginate_links( $args ); ?>
				</div>
			</div>
		</div>

		<div id="inventory-content">  <!-- inventory Content -->
			<div id="inventory-content-top"> <!-- inventory Content Top -->
				<div id="inventory-total-found"><span><?php echo $vehicle_total_found; ?></span> Vehicles Found</div>
				<div id="inventory-sorting-columns">
					<div>Sort by: </div>
					<div class="inventory-sorting-divider"><a class="<?php echo $sort_year_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_year ) , $do_not_carry ); ?>">Year</a></div>
					<div class="inventory-sorting-divider"><a class="<?php echo $sort_price_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_price ) , $do_not_carry ); ?>">Price</a></div>
					<div class="inventory-sorting-divider"><a class="<?php echo $sort_mileage_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_mileage ) , $do_not_carry ); ?>">Mileage</a></div>
					<div class="inventory-sorting-divider"><a class="<?php echo $sort_make_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_make ) , $do_not_carry ); ?>">Make</a></div>
				</div>
			</div>
			<div id="inventory-content-center"> <!-- inventory Content Center -->
				<div id="inventory-mobile-search-wrap" class="inactive"><div id="inventory-mobile-search-img"></div><div id="inventory-mobile-search-text">Search</div></div>
				<div id="inventory-content-left"> <!-- inventory Content Left -->
					<div id="inventory-content-left-wrapper">
						<div class="inventory-sidebar sidebar-new-used">
							<h3>Search New and Used:</h3>
							<div class="inventory-sidebar-content content-new-used">
								<h4 class="" name="condition">Condition</h4>
								<ul>
								<?php
								$hide_certified = !empty($theme_settings['hide_certified_saleclass']) ? TRUE: FALSE;
								switch( $this->options['vehicle_management_system']['saleclass'] ) {
									case 'all':
										echo '<li><a href="'.$new_link.'" title="View New Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'new'?'disabled': NULL).'">New</a></li>';
										echo '<li><a href="'.$used_link.'" title="View Used Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'used'?'disabled': NULL).'">Used</a></li>';
										echo !$hide_certified ? '<li><a href="'.$cert_link.'" title="View Certified Used Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'used'?'disabled':NULL).'">Certified</a></li>' : '';
										break;
									case 'new':
										echo '<li><a href="'.$new_link.'" title="View New Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'new'?'disabled':NULL).'">New</a></li>';
										break;
									case 'used':
										echo '<li><a href="'.$used_link.'" title="View Used Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'used'?'disabled':NULL).'">Used</a></li>';
										echo !$hide_certified ? '<li><a href="'.$cert_link.'" title="View Certified Used Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'used'?'disabled':NULL).'">Certified</a></li>' : '';
										break;
									case 'certified':
										echo '<li><a href="'.$cert_link.'" title="View Certified Used Inventory" class="'.(strtolower($parameters[ 'saleclass' ]) == 'used'?'disabled': NULL).'">Certified</a></li>';
										break;
								}
								?>
								</ul>
							</div>
						</div>
						<div class="inventory-sidebar sidebar-refine-search">
							<h3>Refine Your Search By:</h3>
							<?php if($theme_settings['display_geo']) { ?>
									<div id="list-geo-filter" class="inventory-sidebar-content content-geo">
										<h4>Vehicle Location</h4>
										<div id="geo-wrapper">
											<?php 
												$geo_output = build_geo_dropdown($dealer_geo, $geo_params, $theme_settings['add_geo_zip']);
												echo !empty( $geo_output['search'] ) ? $geo_output['search'] : ''; 
												echo !empty( $geo_output['dropdown'] ) ? $geo_output['dropdown'] : '';
												echo !empty( $geo_output['back_link'] ) ? $geo_output['back_link'] : '';
											?>
										</div>
									</div>	
							<?php } ?>
							
							<div class="inventory-sidebar-content content-bodystyle">
								<h4 class="" name="styles">Vehicle Class</h4>
								<?php $vehicleclass = isset($parameters['vehicleclass']) ? $parameters['vehicleclass']: ''; ?>
								<ul>
									<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'car')); ?>" <?php echo $vehicleclass == 'car' ? 'class="active"' : NULL; ?>>Car</a></li>
									<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'truck')); ?>" <?php echo $vehicleclass == 'truck' ? 'class="active"' : NULL; ?>>Truck</a></li>
									<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'sport_utility')); ?>" <?php echo $vehicleclass == 'sport_utility' ? 'class="active"' : NULL; ?>>SUV</a></li>
									<li><a href="<?php echo generate_inventory_link($url_rule,$parameters,array('vehicleclass'=>'van,minivan')); ?>" <?php echo $vehicleclass == 'van,minivan' ? 'class="active"' : NULL; ?>>Van</a></li>
								</ul>
							</div>

							<div class="inventory-sidebar-content content-make-model-trim">
								<?php
									if ( $trim_count != 0 ) {
										$sidebar_content = '<h4 class="" name="vehicles">Trims</h4>';
										$sidebar_content .= '<ul>';
										foreach( $trims as $trim ) {
											$trim_safe = str_replace( '/' , '%2F' , $trim );
											$link = generate_inventory_link($url_rule,$parameters,array('trim'=>$trim_safe));
											$sidebar_content .= '<li><a href="'.$link.'">'.$trim.'</a></li>';
										}
										$back_link = generate_inventory_link($url_rule,$parameters,'',array('model','trim'));
										$sidebar_content .= '<li><span class="no-style"><a href="'.$back_link.'" class="inventory-filter-prev" title="View '.$parameters['make'].' Models">&#60; View '. $parameters[ 'make' ].'</a></span></li>';
										$sidebar_content .= '</ul>';

									} else if ( $model_count != 0) {
										$sidebar_content = '<h4 class="" name="vehicles">Models</h4>';
										$sidebar_content .= '<ul>';
										foreach( $models as $model ) {
											$model_safe = str_replace( '/' , '%2F' , $model );
											$link = generate_inventory_link($url_rule,$parameters,array('model'=>$model_safe));
											$sidebar_content .= '<li><a href="'.$link.'">'.$model.'</a></li>';
										}
										$back_link = generate_inventory_link($url_rule,$parameters,'',array('make','model'));
										$sidebar_content .= '<li><span class="no-style"><a href="'.$back_link.'" class="inventory-filter-prev" title="View '.$parameters[ 'saleclass' ].' Vehicles">&#60; All '.$parameters[ 'saleclass' ].' Vehicles</a></span></li>';
										$sidebar_content .= '</ul>';
									} else if ( $make_count != 0) {
										$sidebar_content = '<h4 class="" name="vehicles">Makes</h4>';
										$sidebar_content .= '<ul>';
											foreach( $makes as $make ) {
												$make_safe = str_replace( '/' , '%2F' , $make );
												$link = generate_inventory_link($url_rule,$parameters,array('make'=>$make_safe));
												$sidebar_content .= '<li><a href="'.$link.'">'.$make.'</a></li>';
											}
										$sidebar_content .= '</ul>';
									} else {
										$sidebar_content = '<h3>No Makes Found</h3>';
									}
									echo $sidebar_content;
								?>
							</div>
							<div class="inventory-sidebar-content content-price-range">
								<h4 class="" name="price">Price Range</h4>
								<?php $pricefrom = isset($parameters['price_from']) ? $parameters['price_from']: ''; ?>
								<ul>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'1', 'price_to'=>'10000'));?>" <?php echo $pricefrom == "1" ? 'class="active"' : NULL; ?>>$1 &#38; $10,000</a></li>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'10001', 'price_to'=>'20000'));?>" <?php echo $pricefrom == 10001 ? 'class="active"' : NULL; ?>>$10,001 &#38; $20,000</a></li>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'20001', 'price_to'=>'30000')); ?>" <?php echo $pricefrom == 20001 ? 'class="active"' : NULL; ?>>$20,001 &#38; $30,000</a></li>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'30001', 'price_to'=>'40000')); ?>" <?php echo $pricefrom == 30001 ? 'class="active"' : NULL; ?>>$30,001 &#38; $40,000</a></li>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'40001', 'price_to'=>'50000')); ?>" <?php echo $pricefrom == 40001 ? 'class="active"' : NULL; ?>>$40,001 &#38; $50,000</a></li>
									<li><a rel="nofollow" href="<?php echo generate_inventory_link($url_rule,$parameters,array('price_from'=>'50001')); ?>" <?php echo $pricefrom == 50001 ? 'class="active"' : NULL; ?>>$50,001 &#38; &amp; Above</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div id="inventory-content-right"> <!-- inventory Content Right -->
					<div id="inventory-vehicle-listings">
						<?php
							if( empty( $inventory ) ) {
								echo '<div class="inventory-not-found"><h2><strong>Unable to find inventory items that matched your search criteria.</strong></h2><a onClick="history.go(-1)" title="Return to Previous Search" class="jquery-ui-button">Return to Previous Search</a></div>';
							} else {
								foreach( $inventory as $inventory_item ):
									$vehicle = itemize_vehicle($inventory_item);
									$form_subject = $vehicle['year'].' '.$vehicle['make']['name'].' '.$vehicle['model']['name'] . ' ' . $vehicle['stock_number'];
									$form_submit_url = $this->options['vehicle_management_system' ]['host'] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] . '/forms/create/';
									$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $state, 'city' => $city, 'vin' => $vehicle['vin'] );
									$link = generate_inventory_link($url_rule,$link_params,'','',1);
									$generic_vehicle_title = $vehicle['year'].' '.$vehicle['make']['name'].' '.$vehicle['model']['name'];

									?>
									<div class="inventory-vehicle" id="<?php echo $vehicle['vin']; ?>">
										<div class="inventory-listing-top"> <!-- inventory Listing Top -->
											<div class="inventory-listing-vehicle-headline"><?php echo $vehicle['headline']; ?></div>
											<div class="inventory-column-left">
												<div class="inventory-main-line">
													<a href="<?php echo $link; ?>" title="<?php echo $generic_vehicle_title; ?>" class="details">
														<span class="inventory-year form-value" name="year"><?php echo $vehicle['year']; ?></span>
														<span class="inventory-make form-value" name="make"><?php echo $vehicle['make']['name']; ?></span>
														<span class="inventory-model form-value" name="model"><?php echo $vehicle['model']['name']; ?></span>
														<span class="inventory-trim form-value" name="trim"><?php echo $vehicle['trim']['name']; ?></span>
														<span class="inventory-drive-train"><?php echo $vehicle['drive_train']; ?></span>
														<span class="inventory-body-style"><?php echo $vehicle['body_style']; ?></span>
														<span class="inventory-saleclass form-value" name="saleclass" style="display: none;"><?php echo $vehicle['saleclass']; ?></span>
													</a>
												</div>
												<div class="inventory-photo">
													<a href="<?php echo $link; ?>" title="<?php echo $generic_vehicle_title; ?>">
														<?php echo $vehicle['sold'] ? '<img class="marked-sold-overlay" src="'.cdp_get_image_source().'sold_overlay.png" />' : '' ?>
														<img class="list-image" src="<?php echo $vehicle['thumbnail']; ?>" alt="<?php echo $generic_vehicle_title; ?>" title="<?php echo $generic_vehicle_title; ?>" />
													</a>
												</div>
												<div class="inventory-listing-info">
													<?php
														if( $vehicle['prices']['retail_price'] > 0 && strtolower( $vehicle['saleclass'] ) == 'new' ) {
															echo '<div class="inventory-msrp" alt="'.$vehicle['prices']['retail_price'].'"><span>MSRP:</span> '.'$'.number_format( $vehicle['prices']['retail_price'] , 0 , '.' , ',' ).'</div>';
														}
														if ( $vehicle['odometer'] > 100 ) {
															echo '<div class="inventory-odometer"><span>Mileage:</span> ' . $vehicle['odometer'] . '</div>';
														}
														echo $vehicle['exterior_color'] != NULL ? '<div class="inventory-exterior-color"><span>Exterior:</span> '.$vehicle['exterior_color']. '</div>' : NULL;
														echo $vehicle['interior_color'] != NULL ? '<div class="inventory-interior-color"><span>Interior:</span> '.$vehicle['interior_color']. '</div>' : NULL;
														echo $vehicle['transmission'] != NULL ? '<div class="inventory-transmission"><span>Transmission:</span> '.$vehicle['transmission']. '</div>' : NULL;
													?>
												</div>
											</div>
											<div class="inventory-column-right">
												<div class="inventory-price">
													<?php
													$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
													echo (!empty($price['ais_link'])) ? $price['ais_link'] : '';
													echo $price['compare_text'].$price['ais_text'].$price['primary_text'].$price['expire_text'].$price['hidden_prices'];
													?>
												</div>
												<div class="inventory-detail-button inventory-show-form" name="<?php echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'Get Your ePrice'; ?>">
													<a href="<?php echo $link; ?>"><?php echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'GET YOUR ePrice'; ?></a>
												</div>

												<?php
													if( $vehicle['autocheck'] ){
														echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
													}
												?>
											</div>
										</div>
										<div class="inventory-listing-bottom"> <!-- inventory Listing Bottom -->
											<div class="listing-bottom-container-one">
												<div class="inventory-listing-buttons">
													<?php
														if( $gform_class ){
															$form_name = $theme_settings['list_form_button'] ? $theme_settings['list_form_button'] : 'Confirm Availability';
															echo '<div class="inventory-listing-button inventory-confirm-button '.$gform_class.'" name="'.$form_name.'" key="'.$vehicle['vin'].'">'.$form_name.'</div>';
														}
													?>
													<div class="inventory-listing-button inventory-details-button"><a href="<?php echo $link; ?>">View More Details</a></div>
												</div>
												<?php												
													if( $theme_settings[ 'display_tags' ] ){
														apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
														if( !empty( $vehicle['tags'] ) ){
															echo '<div class="inventory-listing-tags">';
																$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin'] );
																echo $tag_icons;
															echo '</div>';
														}
													}
												?>
											</div>
											<div class="listing-bottom-container-two">
												<div class="inventory-vin-wrapper">Vin #: <span class="inventory-vin form-value" name="vin"><?php echo $vehicle['vin']; ?></span></div>
												<div class="inventory-stock-wrapper">Stock #: <span class="inventory-stock-number form-value" name="stock_number"><?php echo $vehicle['stock_number']; ?></span></div>
												<div class="inventory-listing-dealer-info"><?php echo get_dealer_contact_info( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] ); ?></div>
											</div>
											<div class="inventory-mobile-bottom">
												<?php echo $price['primary_text']; ?>
												<div class="inventory-detail-button">
													<a href="<?php echo $link; ?>"><?php echo ( !empty($theme_settings['list_info_button']) ) ? $theme_settings['list_info_button'] : 'GET YOUR ePrice'; ?></a>
												</div>
											</div>
										</div>
										<div class="inventory-form-container"></div>
									</div>
								<?php
									flush();
									endforeach;
								}
							?>
					</div>
				</div>
			</div>
			<div id="inventory-content-bottom"> <!-- inventory Content Bottom -->
				<div class="inventory-content-bottom-wrapper">
					<div class="inventory-pager">
						<span>Page:</span>
						<?php echo paginate_links( $args ); ?>
					</div>
				</div>
			</div>
		</div>

		<div id="inventory-bottom">  <!-- inventory Bottom -->
			<div class="inventory-disclaimer">
				<?php echo !empty( $inventory ) ? '<p>' . $inventory[0]->disclaimer . '</p>' : NULL; ?>
			</div>
		</div>

		<?php
			if ( is_active_sidebar( 'vehicle-listing-page' ) ) :
				echo '<div id="detail-widget-area">';
					dynamic_sidebar( 'vehicle-listing-page' );
				echo '</div>';
			endif;
		?>
	</div>
</div>
