<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;


	$this->vms->tracer = 'Calculating how many items were returned with the given parameters.';
	$vehicle_total_found = $this->vms->get_inventory()->please( array_merge( $parameters , array( 'per_page' => 1 , 'photo_view' => 1 , 'make_filters' =>  $this->options['vehicle_management_system' ]['data']['makes_new'] ) ) );
	$vehicle_total_found = ( isset($vehicle_total_found[ 'body' ]) ) ? json_decode( $vehicle_total_found[ 'body' ] ) : NULL;
	$vehicle_total_found = is_array( $vehicle_total_found ) && count( $vehicle_total_found ) > 0 ? $vehicle_total_found[ 0 ]->pagination->total : 0;

	$do_not_carry = remove_query_arg( 'page' );
	$tmp_do_not_carry = remove_query_arg( 'certified' , $do_not_carry );

	$filters = array(
		'vehicleclass' => isset( $this->parameters[ 'vehicleclass' ] ) ? $this->parameters[ 'vehicleclass' ] : NULL,
		'price_to' => isset( $this->parameters[ 'price_to' ] ) ? $this->parameters[ 'price_to' ] : NULL,
		'price_from' => isset( $this->parameters[ 'price_from' ] ) ? $this->parameters[ 'price_from' ] : NULL,
		'certified' => isset( $this->parameters[ 'certified' ] ) ? $this->parameters[ 'certified' ] : NULL,
		'search' => isset( $this->parameters[ 'search' ] ) ? $this->parameters[ 'search' ] : NULL,
		'year_to' => isset( $this->parameters[ 'year_to' ] ) ? $this->parameters[ 'year_to' ] : NULL,
		'year_from' => isset( $this->parameters[ 'year_from' ] ) ? $this->parameters[ 'year_from' ] : NULL,
		'mileage_to' => isset( $this->parameters[ 'mileage_to' ] ) ? $this->parameters[ 'mileage_to' ] : NULL,
		'mileage_from' => isset( $this->parameters[ 'mileage_from' ] ) ? $this->parameters[ 'mileage_from' ] : NULL
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
	$make_count = count($makes);

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
		default: $sort_year_class = $sort_price_class = $sort_mileage_class = null; break;
	}
	$sort_year = $sort != 'year_asc' ? 'year_asc' : 'year_desc';
	$sort_mileage = $sort != 'mileage_asc' ? 'mileage_asc' : 'mileage_desc';
	$sort_price = $sort != 'price_asc' ? 'price_asc' : 'price_desc';

?>

	<div id="inventory-wrapper">
		<div id="inventory-listing">
			<div class="breadcrumbs"><?php echo display_breadcrumb( $parameters, $this->company, $this->options['vehicle_management_system' ]['custom_contact'] ); ?></div>
			<hr class="full-divider">

			<div id="inventory-quick-links">
				<?php
					$back_links = '';
					$search_params = '';
					$link_params = array( 'saleclass' => $parameters[ 'saleclass' ] );
					if( isset($parameters[ 'make' ]) && $parameters[ 'make' ] != 'all' ){
						$link = generate_inventory_link($url_rule,$parameters,'',array('make','model','trim'));
						$back_links = '<div class="quick-back-link"><a href="'.$link.'">View all Makes</a></div>';
						$link_params['make'] = $parameters[ 'make' ];
						$search_params = '<input id="search-make" type="hidden" readonly value="'.$parameters[ 'make' ].'" />';
						if( isset($parameters[ 'model' ]) && $parameters[ 'model' ] != 'all' ){
							$link = generate_inventory_link($url_rule,$parameters,'',array('model','trim'));
							$back_links .= '<div class="quick-back-link"><a href="'.$link.'">View all Models</a></div>';
							$link_params['model'] = $parameters['model'];
							$search_params .= '<input id="search-model" type="hidden" readonly value="'.$parameters[ 'model' ].'" />';
							if( isset($parameters[ 'trim' ]) && $parameters[ 'trim' ] != 'all' ){
								$link = generate_inventory_link($url_rule,$parameters,'',array('trim'));
								$back_links .= '<div class="quick-back-link"><a href="'.$link.'">View all Trims</a></div>';
								$search_params .= '<input id="search-trim" type="hidden" readonly value="'.$parameters[ 'trim' ].'" />';
							} else {
								echo '<div id="trim-wrapper" class="quick-link-items">';
									foreach( $trims as $trim ){
										$trim_safe = str_replace( '/' , '%2F' , $trim );
										$link = generate_inventory_link($url_rule,$parameters,array('trim'=>$trim_safe));
										echo '<div class="quick-link-item"><a href="'.$link.'">'.$trim.'</a></div>';
									}
								echo '</div>';
							}
						} else {
							echo '<div id="model-wrapper" class="quick-link-items">';
								foreach( $models as $model ){
									$model_safe = str_replace( '/' , '%2F' , $model );
									$link = generate_inventory_link($url_rule,$parameters,array('model'=>$model_safe));
									echo '<div class="quick-link-item"><a href="'.$link.'">'.$model.'</a></div>';
								}
							echo '</div>';
						}
					} else {
						echo '<div id="make-wrapper" class="quick-link-items">';
							foreach( $makes as $make ){
								$make_safe = str_replace( '/' , '%2F' , $make );
								$link = generate_inventory_link($url_rule,$parameters,array('make'=>$make_safe));
								echo '<div class="quick-link-item"><a href="'.$link.'">'.$make.'</a></div>';
							}
						echo '</div>';
					}
					echo ( $back_links ) ? '<div id="quick-back-links">'.$back_links.'</div>' : '';
					echo ( $search_params ) ? '<div id="hidden-search-params">'.$search_params.'</div>' : '';
				?>
			</div>
			<hr class="full-divider">

			<div id="inventory-search-wrapper">
				<div class="inventory-search-text-wrapper">
					<div id="inventory-search-text" class="<?php echo $search_input_class; ?>">
						<input id="inventory-search-box" class="text-search" name="search" value="<?php echo isset( $parameters[ 'search' ] ) ? $parameters[ 'search' ] : NULL; ?>" />
						<div id="inventory-search-submit">Search</div>
					</div>
				</div>
				<form action="#" method="POST" id="inventory-search"> <!-- Vehicle Search -->
					<input type="hidden" id="hidden-rewrite" value="<?php if ( isset($rules['^(inventory)']) ) { echo 'true'; } ?>" name="h_taxonomy" />
					<input type="hidden" id="hidden-saleclass" value="<?php echo ucwords( strtolower( $parameters[ 'saleclass' ] ) ) ?>" name="h_saleclass" />
					<div id="inventory-search-advance" style="display: none;">
						<div id="price-range-wrapper" class="search-wrapper">
							<div id="price-range-text" class="search-text">
								<?php $range_flag = ( isset( $parameters[ 'price_from' ] ) && isset( $parameters[ 'price_to' ] ) ) ? 'true' : 'false'; ?>
								<label for="price-range-values">Price Range:</label>
								<input type="text" id="price-range-values" class="search-values" readonly/>
								<input type="hidden" value="<?php echo $range_flag; ?>" id="price-range-flag" />
							</div>
							<div id="price-range"></div>
							<?php
								$price_from = isset($parameters[ 'price_from' ]) ? $parameters[ 'price_from' ] : '0'; $price_to = isset($parameters[ 'price_to' ]) ? $parameters[ 'price_to' ] : '0';
								$range_data = array('type' => 'price', 'step' => 1000, 'default' => array( 1000, 150000), 'search' => array($price_from, $price_to) );
								echo build_slider_script( $range_data );
							?>
						</div>
						<div id="year-range-wrapper" class="search-wrapper">
							<div id="year-range-text" class="search-text">
								<?php $range_flag = ( isset( $parameters[ 'year_from' ] ) && isset( $parameters[ 'year_to' ] ) ) ? 'true' : 'false'; ?>
								<label for="year-range-values">Year Range:</label>
								<input type="text" id="year-range-values" class="search-values" readonly/>
								<input type="hidden" value="<?php echo $range_flag; ?>" id="year-range-flag" />
							</div>
							<div id="year-range"></div>
							<?php
								$year_from = isset($parameters[ 'year_from' ]) ? $parameters[ 'year_from' ] : '0'; $year_to = isset($parameters[ 'year_to' ]) ? $parameters[ 'year_to' ] : '';
								$range_data = array('type' => 'year', 'step' => 1, 'default' => array( 1970, date("Y") + 1), 'search' => array($year_from, $year_to) );
								echo build_slider_script( $range_data );
							?>
						</div>
						<div id="odometer-range-wrapper" class="search-wrapper">
							<div id="odometer-range-text" class="search-text">
								<?php $range_flag = ( isset( $parameters[ 'mileage_from' ] ) && isset( $parameters[ 'mileage_to' ] ) ) ? 'true' : 'false'; ?>
								<label for="odometer-range-values">Odometer Range:</label>
								<input type="text" id="odometer-range-values" class="search-values" readonly/>
								<input type="hidden" value="<?php echo $range_flag; ?>" id="odometer-range-flag" />
							</div>
							<div id="odometer-range"></div>
							<?php
								$mileage_from = isset($parameters[ 'mileage_from' ]) ? $parameters[ 'mileage_from' ] : '0'; $mileage_to = isset($parameters[ 'mileage_to' ]) ? $parameters[ 'mileage_to' ] : '0';
								$range_data = array('type' => 'odometer', 'step' => 100, 'default' => array( 0, 200000), 'search' => array($mileage_from, $mileage_to) );
								echo build_slider_script( $range_data );
							?>
						</div>
						<hr class="inventory-hr">
						<div class="inventory-advance-param">
							<label class="inventory-label">Vehicle Class: </label>
							<select id="inventory-vehicleclass" class="inventory-select">
								<option value="">All</option>
								<option value="car" <?php echo $vehicleclass == 'car' ? 'selected' : NULL; ?>>Car</option>
								<option value="truck" <?php echo $vehicleclass == 'truck' ? 'selected' : NULL; ?>>Truck</option>
								<option value="sport_utility" <?php echo $vehicleclass == 'sport_utility' ? 'selected' : NULL; ?>>SUV</option>
								<option value="van,minivan" <?php echo $vehicleclass == 'van,minivan' ? 'selected' : NULL; ?>>Van</option>
							</select>
						</div>
						<div class="inventory-advance-param">
							<label class="inventory-label">Sale Class: </label>
							<select id="inventory-saleclass" class="inventory-select">
								<?php
									switch( $this->options['vehicle_management_system']['saleclass'] ) {
										case 'all':
											echo '<option value="All" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'all' ? 'selected' : NULL) . ' >All Vehicles</option>';
											echo '<option value="New" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'new' ? 'selected' : NULL) . ' >New Vehicles</option>';
											echo '<option value="Used" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' && empty( $filters['certified'] ) ? 'selected' : NULL) . ' >Pre-Owned Vehicles</option>';
											echo '<option value="Certified" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' && !empty( $filters['certified'] ) ? 'selected' : NULL) . ' >Certified Pre-Owned</option>';
											break;
										case 'new':
											echo '<option value="New" selected >New Vehicles</option>';
											break;
										case 'used':
											echo '<option value="Used" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' && empty( $filters['certified'] ) ? 'selected' : NULL) . ' >Pre-Owned Vehicles</option>';
											echo '<option value="Certified" ' . (strtolower( $parameters[ 'saleclass' ] ) == 'used' && !empty( $filters['certified'] ) ? 'selected' : NULL) . ' >Certified Pre-Owned</option>';
											break;
										case 'certified':
											echo '<option value="Certified" selected >Certified Pre-Owned</option>';
											break;
									}
								?>
							</select>
						</div>
						<?php if($theme_settings['display_geo']) { ?>
							<div id="geo-wrapper" class="inventory-advance-param">
								<label class="inventory-label">Vehicle Location: </label>
								<?php 
									$geo_output = build_geo_dropdown($dealer_geo, $geo_params, $theme_settings['add_geo_zip']);
									echo !empty( $geo_output['dropdown'] ) ? $geo_output['dropdown'] : '';
								?>
							</div>
						<?php } ?>
						<div class="inventory-advance-param">
							<div class="reset-search"><a href="<?php echo !empty($parameters[ 'saleclass' ]) ? '/inventory/' .$parameters[ 'saleclass' ]. '/' : '/inventory/'; ?>">Reset Search</a></div>
						</div>
					</div>
					<input id="search-form-submit" style="display: none;" type="submit" value="go" />
				</form>
				<div id="inventory-advance-show" name="hidden">
					Advanced Search
				</div>
			</div>

			<hr class="full-divider">
			<div id="inventory-total-found">Found <span><?php echo $vehicle_total_found; ?></span> Results <?php echo !empty( $geo_output['search']) ? 'in '.$geo_output['search'] : ''; ?></div>
			<div class="inventory-pager"><?php echo paginate_links( $args ); ?></div>

			<div id="inventory-inventory-wrapper">
				<?php
					if( empty( $inventory ) ) {
						echo '<div class="not-found"><h2><strong>Unable to find inventory items that matched your search criteria.</strong></h2></div>';
					} else {
						foreach( $inventory as $inventory_item ) {
							$vehicle = itemize_vehicle($inventory_item);
							$generic_vehicle_title = $vehicle['year'] . ' ' . $vehicle['make']['clean'] . ' ' . $vehicle['model']['clean'];
							$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $state, 'city' => $city, 'vin' => $vehicle['vin'] );
							//$link = get_inventory_link( $rules['^(inventory)'], $link_params, 1);
							$link = generate_inventory_link($url_rule,$link_params,'','',1);
							echo '<div id="'.$vehicle['vin'].'" class="inventory-vehicle saleclass-'.strtolower($vehicle['saleclass']).'">

								<div class="inventory-vehicle-top">
									'.( $vehicle['headline'] ? '<div class="inventory-list-headline">'.$vehicle['headline'].'</div>' : '' ).'
									<div class="vehicle-title">
										<a href="'.$link.'" title="'.$generic_vehicle_title.'" class="title-details">
											<span class="title-year inventory-year form-value" name="year">'.$vehicle['year'].'</span>
											<span class="title-make inventory-make form-value" name="make">'.$vehicle['make']['name'].'</span>
											<span class="title-model inventory-model form-value" name="model">'.$vehicle['model']['name'].'</span>
											<span class="title-trim inventory-trim form-value" name=trim>'.$vehicle['trim']['name'].'</span>
											<span class="inventory-saleclass form-value" name="saleclass" style="display: none;">'.$vehicle['saleclass'].'</span>
										</a>
									</div>
								</div>
								<div class="inventory-vehicle-middle">
									<div class="middle-left">
							';
									$price = get_price_display($vehicle['prices'], $this->company, $vehicle['saleclass'], $vehicle['vin'], 'inventory', $theme_settings['price_text'] );
							echo'
										<div class="inventory-price-wrap">
											' . ( !empty($price['msrp_text']) && strtolower($vehicle['saleclass']) == 'new' ? $price['msrp_text'] : '') . '
											'.$price['primary_text'].$price['ais_text'].$price['compare_text'].$price['expire_text'].$price['hidden_prices'].'
											'. ( !empty($price['ais_link']) ? $price['ais_link'] : '') .'
										</div>
							';
										if( $gform_class ){
											echo '<div class="inventory-more-info"><div class="inventory-more-info-button '.$gform_class.'" name="Confirm Availability" key="'.$vehicle['vin'].'">'.( $theme_settings['list_form_button'] ? $theme_settings['list_form_button'] : 'Get E-Price').'</div></div>';
										}
							echo'
									</div>
									<div class="middle-center">
										<div class="photo">
											<a href="'.$link.'" title="' . $generic_vehicle_title . '">
												'.($vehicle['sold'] ? '<img class="marked-sold-overlay" src="'.cdp_get_image_source().'sold_overlay.png" />' : '').'
												<img class="list-image" src="' . $vehicle['thumbnail'] . '" alt="' . $generic_vehicle_title . '" title="' . $generic_vehicle_title . '" />
											</a>
										</div>
									</div>
									<div class="middle-right">
										<div class="vehicle-information">
											<div>Stock #: <span class="vehicle-stock inventory-stock-number form-value" name="stock_number">' . $vehicle['stock_number'] . '</span></div>
											<div>VIN: <span class="vehicle-vin inventory-vin form-value" name="vin">' . $vehicle['vin'] . '</span></div>
											'.(!empty($vehicle['exterior_color'])?'<div><span class="exterior-color">Exterior: '.$vehicle['exterior_color'].'</span></div>':'')
											.( !empty($vehicle['interior_color'])?'<div><span class="interior-color">Interior: '.$vehicle['interior_color'].'</span></div>':'') . '
										</div>
										<div class="inventory-more-info"><div class="inventory-more-info-button"><a href="'.$link.'">'.( $theme_settings['list_info_button'] ? $theme_settings['list_info_button'] : 'More Info').'</a></div></div>
									</div>
								</div>
								<div class="inventory-vehicle-bottom">'.get_dealer_contact_info( $vehicle['contact_info'], $this->options['vehicle_management_system' ]['custom_contact'], $vehicle['saleclass'] );
								if( $theme_settings['display_tags'] ){
									apply_special_tags( $vehicle['tags'], $vehicle['prices']['on_sale'], $vehicle['certified'], $vehicle['video']);
									if( !empty( $vehicle['tags'] ) ){
										echo '<div class="inventory-listing-tags">';
											$tag_icons = build_tag_icons( $default_tag_names, $custom_tag_icons, $vehicle['tags'], $vehicle['vin']);
											echo $tag_icons;
										echo '</div>';
									}
								}

								if( $vehicle['autocheck'] ){
									echo display_autocheck_image( $vehicle['vin'], $vehicle['saleclass'], $type );
								}
							echo'
								</div>
								<div class="inventory-form-container"></div>
							</div>';
						}
					}
				?>
				<div class="inventory-pager"><?php echo paginate_links( $args ); ?></div>
				<?php
					if( function_exists('gravity_form') && $theme_settings['list_gform_id'] ){
						echo '<div id="hidden-form-wrapper" ><div id="list-gform-wrapper" >';
						echo gravity_form($theme_settings['list_gform_id'], TRUE, FALSE, FALSE, '', TRUE);
						echo '</div></div>';
					}
				?>
				<div id="inventory-disclaimer">
					<?php echo !empty( $inventory ) ? '<p>' . $inventory[ 0 ]->disclaimer . '</p>' : NULL; ?>
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

