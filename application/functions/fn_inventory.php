<?php

	function itemize_vehicle( $vehicle ){

		$data = array();

		$prices = $vehicle->prices;
		$data['prices']['was_now'] = $prices->{ 'use_was_now?' };
		$data['prices']['strike_through'] = $prices->{ 'use_price_strike_through?' };
		$data['prices']['on_sale'] = $prices->{ 'on_sale?' };
		$data['prices']['sale_price'] = isset( $prices->sale_price ) ? $prices->sale_price : NULL;
		$data['prices']['sale_expire'] = isset( $prices->sale_expire ) ? $prices->sale_expire : NULL;
		$data['prices']['retail_price'] = $prices->retail_price;
		$data['prices']['default_text'] = $prices->default_price_text;
		$data['prices']['asking_price'] = $prices->asking_price;
		$data['prices']['ais']['text'] = isset( $vehicle->ais_incentive->to_s ) ? $vehicle->ais_incentive->to_s : NULL;
		preg_match( '/\$\d*(\s)?/' , $data['prices']['ais']['text'] , $incentive );
		$data['prices']['ais']['value'] = isset( $incentive[ 0 ] ) ? str_replace( '$' , NULL, $incentive[ 0 ] ) : 0;

		$data['id'] = $vehicle->id;
		$data['year'] = isset($vehicle->year) ? $vehicle->year: '';
		$data['make']['name'] = isset($vehicle->make) ? urldecode( $vehicle->make ) : '';
		$data['make']['clean'] = str_replace( '/' , '%2F' ,  $data['make']['name'] );
		$data['model']['name'] = isset($vehicle->model_name) ? urldecode( $vehicle->model_name ) : '';
		$data['model']['clean'] = str_replace( '/' , '%2F' ,  $data['model']['name'] );
		$data['trim']['name'] = isset($vehicle->trim) ? urldecode( $vehicle->trim ) : '';
		$data['trim']['clean'] = str_replace( '/' , '%2F' ,  $data['trim']['name'] );
		$data['stock_number'] = isset($vehicle->stock_number) ? $vehicle->stock_number: '';
		$data['vin'] = isset($vehicle->vin) ? $vehicle->vin : '';
		$data['engine'] = isset($vehicle->engine) ? $vehicle->engine: '';
		$data['transmission'] = isset($vehicle->transmission) ? $vehicle->transmission: '';
		$data['exterior_color'] = isset($vehicle->exterior_color) ? $vehicle->exterior_color: '';
		$data['interior_color'] = isset($vehicle->interior_color) ? $vehicle->interior_color: '';
		$data['odometer'] = isset($vehicle->odometer) ? $vehicle->odometer: '';
		$data['icons'] = isset($vehicle->icons) ? $vehicle->icons: array();
		$data['tags'] = isset($vehicle->tags) ? $vehicle->tags : '';
		$data['thumbnail'] = urldecode( $vehicle->photos[ 0 ]->small );
		$data['photos_raw'] = isset($vehicle->photos) ? $vehicle->photos : array();
		$data['body_style'] = isset($vehicle->body_style) ? $vehicle->body_style: '';
		$data['vehicle_class'] = isset($vehicle->vehicleclass) ? $vehicle->vehicleclass: '';
		$data['drive_train'] = isset($vehicle->drive_train) ? $vehicle->drive_train : '';
		$data['doors'] = isset($vehicle->doors) ? $vehicle->doors: '';
		$data['headline'] = isset($vehicle->headline) ? $vehicle->headline: '';
		$data['saleclass'] = isset($vehicle->saleclass) ? $vehicle->saleclass: '';
		$data['certified'] = (!empty($vehicle->certified) ) ? $vehicle->certified : 'false';
		$data['autocheck'] = isset( $vehicle->auto_check_url ) ? TRUE : FALSE;
		$data['disclaimer'] = isset($vehicle->disclaimer) ? $vehicle->disclaimer: '';
		$data['carfax'] = isset($vehicle->carfax) ? $vehicle->carfax->url : NULL;
		$data['video'] = isset($vehicle->video_url) ? $vehicle->video_url : NULL;
		$data['dealer_options'] = isset($vehicle->dealer_options) ? $vehicle->dealer_options: array();
		$data['standard_equipment'] = isset($vehicle->standard_equipment) ? $vehicle->standard_equipment: array();
		$data['description'] = isset($vehicle->description) ? $vehicle->description: '';
		$data['fuel_economy'] = isset($vehicle->fuel_economy) ? $vehicle->fuel_economy: '';
		$data['acode'] = isset($vehicle->ads_acode) && !empty($vehicle->ads_acode) ? $vehicle->ads_acode : NULL;
		$data['sold'] = isset($vehicle->sold_on) ? TRUE : FALSE;
		$data['model_code'] = isset($vehicle->model_code) ? $vehicle->model_code: '';
		$data['fuel_type'] = isset($vehicle->fuel_type) ? $vehicle->fuel_type: '';

		$contact = $vehicle->contact_info;
		$data['contact_info']['dealer_id'] = isset($contact->company_id) ? $contact->company_id: '0';
		$data['contact_info']['dealer'] = isset($contact->dealer_name) ? $contact->dealer_name: '';
		$data['contact_info']['greeting'] = isset($contact->greeting) ? $contact->greeting : '';
		$data['contact_info']['manager'] = isset($contact->internet_manager) ? $contact->internet_manager: '';
		$data['contact_info']['phone'] = isset($contact->phone) ? $contact->phone: '';
		$data['contact_info']['location'] = isset($vehicle->vehicle_location) ? $vehicle->vehicle_location: '';
		
		//Photo Check
		if ( is_Empty_check( $data['thumbnail'] ) ){
			$data['thumbnail'] = cdp_get_image_source().'list_no_photo.png';
		}
		if ( is_Empty_check( $data['photos_raw'] ) ){
			$data['photos'][0] = cdp_get_image_source().'detail_no_photo.png';
		} else {
			foreach( $data['photos_raw'] as $photo ){
				$temp[] = $photo->large;
			}
			$data['photos'] = $temp;
		}
		
		$options = get_option('cardealerpress_settings');
		$default_no_photo = $options['vehicle_management_system']['data']['default_no_image'];
		if( !empty($default_no_photo) ){
			$data['thumbnail'] = strstr($data['thumbnail'],'_no_photo') ? $default_no_photo : $data['thumbnail'];
			$data['photos'][0] = strstr($data['photos'][0],'_no_photo') ? $default_no_photo : $data['photos'][0];
		}
		
		return $data;
	}
	
	function display_breadcrumb( $parameters, $company, $override, $sc = '' ){
		
		if( !empty($sc) ){
			unset( $parameters[ 'saleclass' ] );
			$array_jump = array( 'saleclass' => $sc );
			$parameters = $array_jump + $parameters;
		} else {
			$array_jump = array( 'saleclass' => $parameters[ 'saleclass' ] );
			unset( $parameters[ 'saleclass' ] );
			$parameters = $array_jump + $parameters;
		}

		$company_name = ( !empty( $override['contact_name'][strtolower($parameters[ 'saleclass' ])] ) ) ? $override['contact_name'][strtolower($parameters[ 'saleclass' ])] : $company->name;
		$company_name = $override['breadcrumb'] ? $override['breadcrumb']: $company_name;

		$breadcrumb = '<a href="' . site_url() . '/" title="' . $company_name . ': Home Page">' . ucwords( strtolower( urldecode( $company_name ) ) ) . '</a>';
		$put_in_trail = array('saleclass','make','model','trim','vin');

		unset($parameters['taxonomy']);

		$rules = get_option( 'rewrite_rules' );
		$crumb_trail = isset($rules['^(inventory)']) ? '/inventory/' : '?taxonomy=inventory';

		foreach( $parameters as $key => $value ) {
			if( in_array( $key , $put_in_trail ) ) {
				if( isset($rules['^(inventory)']) ) {
					if( $key == 'trim' and $value != 'all' ){
						$crumb_trail = add_query_arg( array($key => $value), $crumb_trail );
						$breadcrumb .= ' <span>&gt;</span> <a href=' .( $key != 'vin' ? $crumb_trail : ''). '>' . ucfirst( urldecode( $value ) ) . '</a>';	
					} else if ( $key != 'trim' && $value != 'all'){
						$crumb_trail .= rawurlencode( urldecode( $value ) ) . '/';
						$breadcrumb .= ' <span>&gt;</span> <a href=' .( $key != 'vin' ? $crumb_trail : ''). '>' . ucfirst( urldecode( $value ) ) . '</a>';	
					}
				} else {
					$crumb_trail = add_query_arg( array($key => $value), $crumb_trail );
					$breadcrumb .= ' <span>&gt;</span> <a href=' .( $key != 'vin' ? $crumb_trail : ''). '>' . ucfirst( urldecode( $value ) ) . '</a>';
				}
			}
		}
		return $breadcrumb;
	}

	function get_dealer_contact_info( $contact, $override, $sc ){
		$phone_num = get_dealer_contact_number( $contact, $override, $sc );
		$company = get_dealer_contact_name( $contact, $override, $sc );
		
		$results = '<div class="dealer-contact-wrapper"><span class="dealer-name form-value" name="dealer_name">'.$company.'</span>' .( !empty($phone_num) ? ' <span class="dealer-number"><a tel="'.$phone_num.'" >'.$phone_num.'</a></span>' : '' ).'<span class="dealer-id form-value" name="dealer_id" style="display: none;">'.$contact['dealer_id'].'</span>'.get_automall_email($contact['dealer_id'], $sc).'</div>';
		return $results;
	}
	
	function get_automall_email( $dealer_id, $vehicle_saleclass = 'new', $val_only = FALSE ){
		$content = '';
		$cdp_options = get_option( 'cardealerpress_settings' );
		$dealer_emails = !empty( $cdp_options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ]['dealers'] ) ? $cdp_options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ]['dealers'] : array();
		$default_email = $cdp_options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ]['defaults'][ strtolower($vehicle_saleclass) ];
		foreach($dealer_emails as $value){
			if( $value['id'] == $dealer_id ){
				$email_saleclass = $value['saleclass'] == 1 ? 'new' : 'used' ;
				if( $value['saleclass'] == 0 || ($email_saleclass == strtolower($vehicle_saleclass) ) ){
					if( $val_only ){
						$content = $value['email'];
					} else {
						$content = '<span class="automall-email-value form-value" name="automall_email" style="display: none;">'.$value['email'].'</span>';	
					}
				}	
			}
		}
		if( empty($content) && $default_email ){
			if($val_only){
				$content = $default_email;
			} else {
				$content = '<span class="automall-email-value form-value" name="automall_email" style="display: none;">'.$default_email.'</span>';	
			}
		}
		return $content;
	}
	
	
	function get_dealer_contact_name( $contact, $override, $sc ){
		$results = ( !empty( $override['contact_name'][strtolower($sc)] ) ) ? $override['contact_name'][strtolower($sc)] : $contact['dealer'];
		return $results;
	}
	
	function get_dealer_contact_number( $contact, $override, $sc ){
		$results = ( !empty( $override['phone'][strtolower($sc)] ) ) ? $override['phone'][strtolower($sc)] : $contact['phone'];
		return $results;
	}

	function get_price_display( $price, $company, $saleclass, $vin, $theme = 'custom', $custom_price = array(), $cro = array() ){
		
		
		$text_standard = ( !empty($custom_price[strtolower($saleclass)]['standard_price']) ) ? $custom_price[strtolower($saleclass)]['standard_price'].' ' : 'Price: ';
		$text_compare = ( !empty($custom_price[strtolower($saleclass)]['compare_price']) ) ? $custom_price[strtolower($saleclass)]['compare_price'].' ' : 'Compare At: ' ;
		$text_sale = ( !empty($custom_price[strtolower($saleclass)]['sale_price']) ) ? $custom_price[strtolower($saleclass)]['sale_price'].' ' : 'Sale Price: ';
		$text_default = ( !empty($custom_price[strtolower($saleclass)]['default_price']) ) ? $custom_price[strtolower($saleclass)]['default_price'].' ' : $price['default_text'];
		$strike = ($price['strike_through']) ? $theme.'-strike-through' : '';
		
		$data = array();
		$data['primary_price'] = 0;

		/*if( $cro['link'] ){
			//$data['rebate_link'] = '<div class="'.$theme.'-rebate-link view-available-rebates"><a href="'.$cro['link'].'" target="_blank" title="VIEW AVAILABLE INCENTIVES" onclick="return loadIframe( this.href );">VIEW INCENTIVES</a></div>';
			$onclick_string = "'".$cro['link']."','popup', 'width=960,height=800,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'";
			$data['rebate_link'] = '<div class="'.$theme.'-rebate-link"><a onclick="window.open('.$onclick_string.'); return false" title="VIEW AVAILABLE INCENTIVES">VIEW INCENTIVES</a></div>';
		}*/
		$data['ais_link'] = '';
		$data['compare_text'] = '';
		$data['ais_text'] = ( !empty($price['ais']['text']) ) ? '<div class="'.$theme.'-ais-incentive-l-text">' . $price['ais']['text'] . '</div>' : '';
		$data['expire_text'] = ( !empty($price['sale_expire']) ) ? '<div class="'.$theme.'-sale-expires">Sale Expires: ' . $price['sale_expire'] . '</div>' : '';
		$data['msrp_text'] = ( !empty($price['retail_price']) ) ? '<div class="'.$theme.'-msrp">MSRP: $'.number_format($price['retail_price'] , 0 , '.' , ',' ).'</div>' : '<div class="'.$theme.'-msrp"></div>';

		if( $price['on_sale'] && $price['sale_price'] > 0 && $price['sale_price'] <= $price['asking_price'] ) {
			$now_text = $text_standard;
			if( $price['was_now'] ) {
				$price_class = ( $price['strike_through'] ) ? $theme.'-strike-through '.$theme.'-asking-price' : $theme.'-asking-price';
				if( $price['ais']['value'] > 0 && empty($data['rebate_link']) ) {
					$data['compare_text'] = '<div class="'.$price_class.' '.$theme.'-ais"><span>'.$text_compare.'</span> $'.number_format($price['sale_price'] , 0 , '.' , ',' ).'</div>';
				} else {
					$data['compare_text'] = '<div class="'.$price_class.'"><span>'.$text_compare.'</span> $'.number_format($price['asking_price'] , 0 , '.' , ',' ).'</div>';
				}
				$now_text = $text_sale;
			}
			if( $price['ais']['value'] > 0 && empty($data['rebate_link']) ) {
				$data['primary_text'] = '<div class="'.$theme.'-sale-price '.$theme.'-ais '.$theme.'-main-price">'.$now_text.'<span>$'.number_format($price['sale_price'] - $price['ais']['value'] , 0 , '.' , ',' ) .'</span></div>';
				$data['primary_price'] = $price['sale_price'] - $price['ais']['value'];
			} else {
				$data['primary_text'] = '<div class="'.$theme.'-sale-price '.$theme.'-main-price">'.$now_text.'<span>$' . number_format( $price['sale_price'] , 0 , '.' , ',' ) .'</span></div>';
				$data['primary_price'] = $price['sale_price'];
			}
		} else {
			if( $price['asking_price'] > 0 ) {
				if( $price['ais']['value'] > 0 && empty($data['rebate_link']) ) {
					$data['primary_text'] = '<div class="'.$theme.'-your-price '.$theme.'-ais '.$theme.'-main-price">'.$text_sale.'<span>$' . number_format( $price['asking_price'] - $price['ais']['value'] , 0 , '.' , ',' ) . '</span></div>';
					$data['compare_text'] = '<div class="'.$strike.' '.$theme.'-asking-price '.$theme.'-ais"><span>'.$text_compare.'</span> $'.number_format( $price['asking_price'] , 0 , '.' , ',' ) .'</div>';
					$data['primary_price'] = $price['asking_price'] - $price['ais']['value'];
				} else {
					$data['primary_text'] = '<div class="'.$theme.'-asking-price '.$theme.'-main-price">'.$text_standard.'<span> $'.number_format( $price['asking_price'] , 0 , '.' , ',' ) .'</span></div>';
					$data['primary_price'] = $price['asking_price'];
				}
			} else {
				$data['primary_text'] = '<div class="'.$theme.'-no-price '.$theme.'-main-price">'.$text_default.'</div>';
			}
		}

		if( !empty($price['ais']['text']) && isset( $company->api_keys ) && !empty($vin) && empty($data['rebate_link']) ) {
			$data['ais_link'] = '<div class="'.$theme.'-ais-link view-available-rebates"><a href="http://onecar.aisrebates.com/dlr2/inline/IncentiveOutput.php?vID='. $vin . '&wID=' . $company->api_keys->ais . '&zID=' . $company->zip . '" target="_blank" title="VIEW AVAILABLE INCENTIVES" onclick="return loadIframe( this.href );">VIEW INCENTIVES</a></div>';
			$data['ais_link_js'] = '<div class="'.$theme.'-ais-link view-available-rebates ais-link-js"><span href="http://onecar.aisrebates.com/dlr2/inline/IncentiveOutput.php?vID='. $vin . '&wID=' . $company->api_keys->ais . '&zID=' . $company->zip . '" target="_blank" title="VIEW AVAILABLE INCENTIVES" onclick="return loadIframe( this.href );">VIEW INCENTIVES</span></div>';
		}

		//Clean Price Values
		$data['hidden_prices'] = '<div class="hidden-vehicle-prices" style="display: none;"><div class="hidden-msrp form-value" name="msrp">'.$price['retail_price'].'</div>'. (( $price['ais']['value'] > 0 && empty($data['rebate_link']) ) ? '<div class="hidden-rebate form-value" name="rebate">'.$price['ais']['value'].'</div>' : '') . '<div class="hidden-sale form-value" name="sale">'.$price['sale_price'].'</div><div class="hidden-asking form-value" name="asking">'.$price['asking_price'].'</div><div class="hidden-main form-value" name="price">'.$data['primary_price'].'</div>'. (( $price['sale_price'] > 0 && ($price['asking_price'] - $price['sale_price']) != 0 ) ? '<div class="hidden-discount" name="discount">'. ($price['asking_price'] - $price['sale_price']) .'</div>' : '') . '</div>';

		return $data;
	}
	
	function cdp_get_image_source(){
		$value = plugins_url( '/cardealerpress/application/assets/images/' );
		return $value;
	}
	
	function cdp_get_css_source(){
		$value = plugins_url( '/cardealerpress/application/assets/css/' );
		return $value;
	}
	
	function cdp_get_js_source(){
		$value = plugins_url( '/cardealerpress/application/assets/js/' );
		return $value;
	}

	function get_photo_detail_display( $photos, $video, $active = 1, $flex = '' ){
		
		$flex_item = $flex ? 'style="flex: 1 1 auto; margin: 0; text-align: center;" ' : '';

		$class = ( $active == 1 ) ? array( 'active', '' ) : array( '', 'active');
		$content_video = '';

		if( $video ){
			$buttons = '<div '.$flex_item.' class="tabs-button tabs-button-img-photo '.$class[1].'" name="img-photo">Photos</div><div '.$flex_item.' class="tabs-button tabs-button-img-video '.$class[0].'" name="img-video">Video</div>';
			$content_video = '<div class="tabs-content tabs-content-img-video '.$class[0].'">';
				$mime = wp_check_filetype( $video, wp_get_mime_types() );
				if( empty($mime['ext']) || strpos($mime['type'],'video') === false ){
					if (strpos($video,'dmotorworks') !== false || strpos($video,'idostream') !== false || strpos($video,'liveVideo') !== false || strpos($video,'vehicledata') !== false ){
						$content_video .= '<div id="video-overlay-wrapper-dm" onclick=\'window.open("'.$video.'","popup","width=640,height=500,scrollbars=no,resizable=yes,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=50,top=125"); return false\'>';
						$content_video .= '<img id="video-overlay-play-button" src="'.cdp_get_image_source().'video_play_button.png" />';
						$content_video .= '<img id="video-overlay-image" src="'.$photos[0].'" />';
						$content_video .= '</div>';
					} else {
						$content_video .= '<div id="inventory-video-wrapper">';
						//currently only works for youtube videos
						preg_match('/(?<=v(\=|\/))([-a-zA-Z0-9_]+)|(?<=youtu\.be\/)([-a-zA-Z0-9_]+)/', $video, $matches);
						$content_video .= '<iframe id="inventory-video-iframe" src="http://www.youtube.com/embed/'.$matches[0].'?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>';
						$content_video .= '</div>';
					}
				} else {
					$content_video .= '<div id="video-overlay-wrapper">';
					$content_video .= '<img id="video-overlay-play-button" src="'.cdp_get_image_source().'video_play_button.png" />';
					$content_video .= '<img id="video-overlay-image" src="'.$photos[0].'" />';
					$content_video .= '</div>';
					$content_video .= '<div id="wp-video-shortcode-wrapper">';
					$content_video .= wp_video_shortcode( array( 'src' => $video, 'height' => 360, 'width' => 640 ) );
					$content_video .= '</div>';
				}
			$content_video .= '</div>';
		} else {
			$buttons = '<div '.$flex_item.' class="tabs-button tabs-button-img-photo active" name="img-photo">Photos</div>';
			$class[1] = 'active';
		}

		$content_photos = '<div class="tabs-content tabs-content-img-photo '.$class[1].'"><div id="vehicle-images">';
			foreach( $photos as $photo ) {
				$content_photos .= '<a class="lightbox" rel="slides" href="'.str_replace('&', '&amp;', $photo).'"><img src="'.str_replace('&', '&amp;', $photo).'" /></a>';
			}
		$content_photos .= '</div><div id="vehicle-thumbnails"></div></div>';

		$display = '<div id="photo-tabs-wrapper">';

		$display .= '<div id="photo-tabs-buttons" '.$flex.'>';
		$display .= $buttons;
		$display .= '</div>';

		$display .= '<div id="photo-tabs-content">';
		$display .= $content_photos;
		$display .= $content_video;
		$display .= '</div>';

		$display .= '</div>';

		return $display;
	}

	function get_fuel_economy_display( $fuel, $country, $img_id = 0, $vrs = NULL, $acode = NULL, $value_only = FALSE ){

		$city = !empty( $fuel ) && !empty( $fuel->city ) ? $fuel->city : 0;
		$highway = !empty( $fuel ) && !empty( $fuel->highway ) ? $fuel->highway : 0;
		
		if( $country == 'CA' || ( empty($city) || empty($highway) ) ) {
			if( !empty($acode) ){
				$fuel_call = NULL;
				$request = 'http://vrs.dealertrend.com/fuel_economies.json?acode='.$acode.'&api=2';
				$response = wp_remote_get( $request );
				if ( !is_wp_error($response)) {
					if( isset( $response['body'] ) ){
						$fuel_call = json_decode( $response['body'], true );
						$fuel_call = $fuel_call[ 0 ];
					}
				} 

				if( !empty($fuel_call) && $country == 'CA' ){
					$city = !empty($fuel_call['city_lp_100km']) ? $fuel_call['city_lp_100km'] : 0;
					$highway = !empty($fuel_call['highway_lp_100km']) ? $fuel_call['highway_lp_100km']: 0;
				} else {
					$city = !empty($fuel_call['city']) ? $fuel_call['city'] : 0;
					$highway = !empty($fuel_call['highway']) ? $fuel_call['highway'] : 0;
				}
			}
		}

		switch($img_id){
			case 0:
				$img_name = 'mpg_39x52.png';
				break;
			case 1:
				$img_name = 'mpg_s_39x52.png';
				break;
			case 2:
				$img_name = 'mpg_green_44x65.png';
				break;
		}

		$img_link = '<img src="'.cdp_get_image_source().$img_name.'" name="fuel-economy" />';
		$fuel_text = $value_only ? array() : '';
		
		if($value_only){
			$fuel_text = array( 'city' => $city, 'hwy' => $highway );
		}

		if( !empty($city) && !empty($highway) && empty($fuel_text) ){
			$fuel_text = '<div id="fuel-economy-wrapper"><div id="fuel-city"><span id="city-text">City</span><span id="city-value">' . $city . '</span></div><div id="fuel-image">'.$img_link.'</div><div id="fuel-highway"><span id="highway-text">Hwy</span><span id="highway-value">' . $highway . '</span></div><p id="fuel-disclaimer">Actual mileage will vary with options, driving conditions, driving habits and vehicle&#39;s condition.</p></div>';
		}

		return $fuel_text;

	}

	function get_form_button_display( $forms, $saleclass = "All", $override = false ){
		$ids = array();
		switch( strtolower($saleclass) ){
			case 'new':
				$sc = 1;
				break;
			case 'used':
				$sc = 2;
				break;
			default:
				$sc = 0;
		}
		echo '<div id="form-button-wrapper">';

		foreach( $forms as $key => $form ){
			if( (!empty($form['button']) || $override) && ($form['saleclass'] == 0 || $form['saleclass'] == $sc) ){
				$ids[] = $form['id'];
				echo '<div id="form-button-'.$form['id'].'" class="form-button form-'.$form['id'].'" name="form-id-'.$form['id'].'">';
				echo $form['title'];
				echo '</div>';
			}
		}

		if( !empty($ids) ){
			echo '<div id="form-data">';
			foreach( $ids as $id ){
				echo '<div id="form-id-'.$id.'" class="form-wrap">';
				echo gravity_form($id, true);
				echo '</div>';
			}
			echo '</div>';
		}

		echo '</div>';

	}
	
	function get_gform_button_display( $forms, $saleclass = "All", $override = false ){
		$ids = array();
		switch( strtolower($saleclass) ){
			case 'new':
				$sc = 1;
				break;
			case 'used':
				$sc = 2;
				break;
			default:
				$sc = 0;
		}
		$results = '<div id="form-button-wrapper">';
		foreach( $forms as $key => $form ){
			if( $form['saleclass'] == 0 || $form['saleclass'] == $sc ){
				$ids[] = $form['id'];
				$results .= '<div id="form-button-'.$form['id'].'" class="form-button form-'.$form['id'].'" name="form-id-'.$form['id'].'">'.$form['title'].'</div>';
			}
		}
		if( !empty($ids) ){
			$results .= '<div id="form-data">';
			foreach( $ids as $id ){
				$results .= '<div id="form-id-'.$id.'" class="form-wrap">'.do_shortcode('[gravityform id='.$id.' title=true description=false]').'</div>';
			}
			$results .= '</div>';
		}

		$results .= '</div>';
		return $results;
	}

	function get_form_display( $forms, $saleclass = "All" ){

		switch( strtolower($saleclass) ){
			case 'new':
				$sc = 1;
				break;
			case 'used':
				$sc = 2;
				break;
			default:
				$sc = 0;
		}

		foreach( $forms as $key => $form ){
			if( empty($form['button']) && ($form['saleclass'] == 0 || $form['saleclass'] == $sc) ){
				echo '<div class="form-wrapper">';
					echo '<div id="form-id-'.$form['id'].'" class="form-display-wrap form-'.$form['id'].'" name="form-id-'.$form['id'].'">';
					echo gravity_form($form['id'], true);
					echo '</div>';
				echo '</div>';
			}
		}

	}

	function get_loan_calculator( $loan_data, $price, $button = True, $params = array() ){
		$display = '';
		
		//Get Loan Values
		if( isset($params['saleclass']) ){
			$loan = $loan_data[strtolower($params['saleclass'])]['default'];
			get_custom_loan($loan_data['custom'], $loan, $params);
		}
		
		$calculate = isset($loan['display_calc']) ? $loan['display_calc'] : '';

		if( !empty($calculate) ){
			if( is_numeric($price) && $price != 0 ){
				$display = '<div id="loan-calculator-wrapper">';

				$display .= ($button) ? '<div id="loan-calculator-button">Loan Calculator</div>': '';

				$display .= '<div id="loan-calculator-data">';
				$display .= '<div id="loan-calculator">';
				$display .= '<div class="loan-value"><label>Vehicle Price</label><input type="text" id="loan-calculator-price" value="$'.( is_numeric($price) ? number_format( $price , 0 , '.' , ',' ) : '0' ).'" /></div>';
				$display .= '<div class="loan-value"><label>Interest Rate</label><input type="text" id="loan-calculator-interest-rate" value="'.$loan['interest'].'%" /></div>';
				$display .= '<div class="loan-value"><label>Trade-in Value</label><input type="text" id="loan-calculator-trade-in-value" value="$'.number_format( $loan['trade'] , 0 , '.' , ',' ).'" /></div>';
				$display .= '<div class="loan-value"><label>Term (months)</label><input type="text" id="loan-calculator-term" value="'.$loan['term'].'" /></div>';
				$display .= '<div class="loan-value"><label>Down Payment</label><input type="text" id="loan-calculator-down-payment" value="$'.number_format( $loan['down'] , 0 , '.' , ',' ).'" /></div>';
				$display .= '<div class="loan-value"><label>Sales Tax</label><input type="text" id="loan-calculator-sales-tax" value="'.$loan['tax'].'%" /></div>';

				$display .= '<hr>';

				$display .= ($loan['display_bi_monthly']) ? '<div class="loan-payment"><label>Bi Monthly Cost</label><div id="loan-calculator-bi-monthly-cost"></div></div>' :'';
				$display .= ($loan['display_monthly']) ? '<div class="loan-payment"><label>Monthly Cost</label><div id="loan-calculator-monthly-cost"></div></div>' :'';

				$display .= ($loan['display_total_cost']) ? '<div class="loan-total"><label>Total Cost<br><small>(including taxes)</small></label><div id="loan-calculator-total-cost"></div></div>' : '';

				$display .= '<div id="loan-calculator-submit"><button>Calculate</button></div>';

				$display .= '</div></div>';

				$display .= '</div>';
			} else {
				$display = '<div id="loan-calculator-error">No price is currently available to calculate. Please contact dealer for price information.</div>';
			}	
		}

		return $display;
	}
	
	function get_loan_value( $loan_data, $price, $params = array() ){
		$display = '';
		//Get Loan Values
		if( isset($params['saleclass']) ){
			$loan = $loan_data[strtolower($params['saleclass'])]['default'];
			get_custom_loan($loan_data['custom'], $loan, $params);
		}
		$calculate = isset($loan['display_payment']) ? $loan['display_payment'] : '';
		if( !empty($calculate) ){
			if( is_numeric($price) && $price != 0 ){
				$number_pattern = '/[^0-9\.]/';
				$interest = preg_replace( $number_pattern, '',$loan['interest']);
				$term = preg_replace( $number_pattern, '',$loan['term']);
				$trade = preg_replace( $number_pattern, '',$loan['trade']);
				$down = preg_replace( $number_pattern, '',$loan['down']);
				$tax = preg_replace( $number_pattern, '',$loan['tax']);
				$total_price = ( ($price - $trade) - $down );
				$total_price += $total_price * ($tax > 0 ? $tax / 100 : 1);
				
				if( $interest > 0 ){
				    $interest /= 1200;
				    $monthly_payment = $interest * $total_price / ( 1 - pow( 1 + $interest , -$term ) );		
				} else {
					$monthly_payment = $total_price / $term;
				}
					
				$display .= '<div class="loan-payment-wrapper" '.(!empty($loan['disclaimer']) ? 'title="'.$loan['disclaimer'].'"' : '' ).'>';
				$display .= str_replace( '[payment]', '<strong>'.round($monthly_payment).'</strong>', $loan['display_text']);
				$display .= '</div>';
			}
		}
		return $display;
	}
	
	function get_custom_loan($custom, &$default, $params){
		if ( !empty($custom) ){
			$param_saleclass = strtolower($params['saleclass']) == 'new'? 1 : (strtolower($params['saleclass']) == 'used' ? 2: 0) ;
			foreach($custom as $key => $loan){
				$check_saleclass = (empty($loan['saleclass']) || $loan['saleclass'] == $param_saleclass) ? TRUE: FALSE;
				$check_model = (!empty($loan['model']) && strtolower($loan['model']) == strtolower($params['model']) ) ? TRUE: FALSE;
				$check_trim = !empty($loan['trim']) ? (strtolower($loan['trim']) == strtolower($params['trim']) ? TRUE : FALSE) : TRUE;
				$check_year = !empty($loan['year']) ? ( $loan['year'] == $params['year'] ? TRUE : FALSE) : TRUE;
				if( $check_saleclass && $check_model && $check_trim && $check_year ){
					foreach($loan as $id => $value){
						if( !empty($value) and isset($default[$id]) ){
							$default[$id] = $value;
						} 
					}	
				}
			}
		}
	}

	function build_tab_display( $buttons, $data, $active = 0, $flex = ''){

		$flex_item = $flex ? 'style="flex: 1 1 auto; margin: 0; text-align: center;" ' : '';
		
		$active_tab = '';
		if( isset($data[ isset($buttons[$active][0]) ][0]) ){
			$active_tab = $buttons[ $active ][0];			
		} else {
			foreach( $buttons as $key => $button){
				if( empty($active_tab) ){
					$active_tab = ( $data[ $button[0] ][0] ) ? $button[0] : '';
				}
			}
		}

		switch( strtolower($data['values']['saleclass']) ){
			case 'new': $sc = 1; break;
			case 'used': $sc = 2; break;
			default: $sc = 0;
		}

		echo '<div id="vehicle-details-wrapper" >';

		echo '<div id="vehicle-details-tabs-buttons" '.$flex.'>';
		foreach( $buttons as $key => $button){
			if( $data[ $button[0] ][0] ){
				$class = ( $active_tab == $button[0] ) ? 'active' : '' ;
				switch ( $button[0] ){ //Button Output
					case 'form':
						$i = 0;
						foreach( $data[ $button[0] ][1] as $key => $form ){
							if( !empty($form['button']) && ($form['saleclass'] == 0 || $form['saleclass'] == $sc) ){
								$form_ids[$i] = $form['id'];
								echo '<div '.$flex_item.' class="tabs-button tabs-button-details-form-'.$form['id'].' '.$class.' " name="details-form-'.$i.'">'.$form['title'].'</div>';
								$i++;
							}
						}
						break;
					default:
						echo '<div '.$flex_item.' class="tabs-button tabs-button-details-'.$button[0].' '.$class.' " name="details-'.$button[0].'">'.$button[1].'</div>';
				}
			}
		}
		echo '</div>';

		echo '<div id="vehicle-details-tabs-content">';
		foreach( $buttons as $key => $button){
			if( $data[ $button[0] ][0] && $button[0] != 'form' ){
				$class = ( $active_tab == $button[0] ) ? 'active' : '' ;

				echo '<div class="tabs-content tabs-content-details-'.$button[0].' '.$class.' "><div id="vehicle-detail-'.$button[0].'">';

				switch ( $button[0] ){ //Content Output
					case 'options':
						echo '<ul>';
							foreach( $data[ $button[0] ][1] as $option ){
								echo '<li>' . $option . '</li>';
							}
						echo '</ul>';
						break;
					case 'equipment':
						echo display_equipment($data[ $button[0] ][1]);
						break;
					case 'loan':
						echo get_loan_calculator($data[ $button[0] ][1], $data['values']['price'], False, $data[ $button[0] ][2]);
						break;

					default:
						echo $data[ $button[0] ][1];
				}
				echo '</div></div>';

			}
		}
		//Form Loop
		if( !empty($form_ids) ){
			foreach( $form_ids as $key => $id ){
				echo '<div class="tabs-content tabs-content-details-form-'.$key.'"><div id="vehicle-detail-form-'.$key.'" class="vehicle-detail-form" >';
				echo '<div id="form-id-'.$id.'" class="form-wrapper">';
				echo gravity_form($id, true, false, false, '', true);
				echo '</div></div></div>';
			}
		}
		echo '</div>';

		echo '</div>';

	}

	function get_vehicle_detail_display( $options, $description, $show_equipment = FALSE, $equipment = array(), $active = 0 ){

		switch ($active){
			case 0:
				$class = count($options) > 0 ? array( 'active', '', '') : '';
				break;
			case 1:
				$class = strlen($description) > 0 ? array( '', 'active', '') : '';
				break;
			case 2:
				$class = $show_equipment && !is_Empty_check($equipment) ? array( '', '', 'active') : '';
				break;
		}

		if( empty($class) ){
			if( count($options) > 0 ){
				$class = array( 'active', '', '');
			} else if( strlen($description) > 0 ){
				$class = array( '', 'active', '');
			} else if( $show_equipment && !is_Empty_check($equipment) ){
				$class = array( '', '', 'active');
			}
		}

		$buttons = '';
		$content = '';

		if( count($options) > 0 ){
			$buttons .= '<div class="tabs-button tabs-button-details-options '.$class[0].'" name="details-options">Vehicle Options</div>';
			$content .= '<div class="tabs-content tabs-content-details-options '.$class[0].'"><div id="vehicle-detail-options"><ul>';
				foreach( $options as $option ){
					$content .= '<li>' . $option . '</li>';
				}
			$content .= '</ul></div></div>';
		}

		if( strlen($description) > 0 ){
			$buttons .= '<div class="tabs-button tabs-button-details-desc '.$class[1].'" name="details-desc">Description</div>';
			$content .= '<div class="tabs-content tabs-content-details-desc '.$class[1].'"><div id="vehicle-detail-desc">';
			$content .= $description;
			$content .= '</div></div>';
		}

		if( $show_equipment && !is_Empty_check($equipment) ){
			$buttons .= '<div class="tabs-button tabs-button-details-equipment '.$class[2].'" name="details-equipment">Standard Equipment</div>';
			$content .= '<div class="tabs-content tabs-content-details-equipment '.$class[2].'"><div id="vehicle-detail-equipment">';
			$content .= display_equipment($equipment);
			$content .= '</div></div>';
		}

		$display = '<div id="vehicle-details-wrapper" >';

		$display .= '<div id="vehicle-details-tabs-buttons">';
		$display .= $buttons;
		$display .= '</div>';

		$display .= '<div id="vehicle-details-tabs-content">';
		$display .= $content;
		$display .= '</div>';

		$display .= '</div>';

		return $display;

	}

	function get_default_tag_names(){
		$icons = array(
			'on-sale',
			'certified',
			'special',
			'cherry-deal',
			'custom-wheels',
			'gas-saver',
			'good-buy',
			'hybrid',
			'local-trade-in',
			'low-miles',
			'moon-roof',
			'navagation',
			'one-owner',
			'priced-to-go',
			'rare',
			'sale-pending',
			'under-blue-book',
			'wont-last',
			'video'
		);

		return $icons;
	}

	function apply_special_tags( &$tags, $on_sale = false, $certified = 'false', $video = NULL ){

		if( !empty($on_sale) ){
			$tags[] = 'on-sale';
		}

		if( $certified != 'false' ){
			$tags[] = 'certified';
		}

		if( !empty($video) ){
			$tags[] = 'video';
		}

	}

	function build_tag_icons( $default, $custom, $tags, $vin = '' ){

		$icons = '';
		$temp = array();

		if( !empty($custom) ){
			usort( $custom, 'sort_custom_tags' );

			//Get Custom Icons
			foreach( $custom as $value ){
				if( in_array($value['name'], $tags) ){
					$icons .= !empty($value['link']) ? '<a href="'.str_replace( '{vin}', $vin, $value['link'] ).'">' : '';
					$icons .= '<img title="'.$value['name'].'" class="icon-custom icon-emb-'.$value['name'].'" src="'.$value['url'].'" />';
					$icons .= !empty($value['link']) ? '</a>' : '';
					$temp[] = $value['name'];
				}
			}
		}
		$img_src = cdp_get_image_source();
		//Get Defaults
		foreach( $default as $value ){
			if( !in_array($value, $temp) ){
				if( in_array($value, $tags) ){
					$icons .= '<img title="'.$value.'" class="icon-default icon-emb-'.$value.'" src="'.$img_src.'tags/'.$value.'.png" />';
				}
			}
		}

		return $icons;

	}
	
	function decode_geo_query( $geo, &$params, &$geo_params ){
		if( !empty($geo) && (isset($params['geo_state']) || isset($params['geo_city']) || isset($params['geo_zip']) ) ){
			if( isset($params['geo_state']) && strtolower($params['geo_state']) != 'all' ){
				$geo_params['state'] = $params['geo_state'];
				unset($params['geo_state']);
			}
			
			if( isset($params['geo_city']) && strtolower($params['geo_city']) != 'all' ){
				$geo_params['city'] = $params['geo_city'];
				unset($params['geo_city']);
			}
			
			if( isset($params['geo_zip']) && strtolower($params['geo_zip']) != 'all' ){
				$geo_params['zip'] = $params['geo_zip'];
				unset($params['geo_zip']);
			}
			
			if( isset($geo_params['zip']) ){
				$geo_params['key'] = 'zip';
				$geo_params['city'] = recursive_array_parent_key_search($geo_params['zip'], $geo);
				$geo_params['state'] = recursive_array_parent_key_search($geo_params['city'], $geo);
			} else if( isset($geo_params['city']) ){
				$geo_params['key'] = 'city';
				$geo_params['state'] = recursive_array_parent_key_search($geo_params['city'], $geo);
			} else if( isset($geo_params['state']) ){
				$geo_params['key'] = 'state';
			} else {
				$geo_params['key'] = 'default';
			}
			$dealer_ids = get_geo_dealer_ids($geo, $geo_params);
			if( !empty($dealer_ids) ){
				$params['geo_search'] = 1;
				$params['dealer_id'] = implode(',', $dealer_ids);
			}
		}
	}
	
	function get_geo_dealer_ids($geo, $params){
		$dealers = array();
		if( isset($params['zip'] ) ){
			foreach( $geo[$params['state']][$params['city']][$params['zip']] as $dealer){
				$dealers[] = $dealer;
			}
		} else if( isset($params['city'] ) ){
			foreach( $geo[$params['state']][$params['city']] as $zips){
				foreach($zips as $dealer){
					$dealers[] = $dealer;
				}
			}
		} else if( isset($params['state']) && count($geo) > 1 ){
			foreach( $geo[$params['state']] as $cities){
				foreach($cities as $zips){
					foreach($zips as $dealer){
						$dealers[] = $dealer;
					}
				}
			}
		}

		return $dealers;
	}
	
	function recursive_array_parent_key_search($needle,$haystack, $parent_key = '') {
	    foreach($haystack as $key=>$value) {
			if( strval($needle)===strval($key) ){
				return $parent_key;
			} else if( is_array($value) ){
				$pass = $key;
				$found = recursive_array_parent_key_search($needle,$value,$pass);
				if( $found !== false ){
					return $found;
				}
			}
	    }
	    return false;
	}
	
	function build_geo_dropdown($geo, $params, $show_zip = FALSE, $remove_text = ''){
		$result = $search_tag = $back_tag = '';
		if( !empty($geo) ){
			if( !isset($params['error']) ){
				$result = '<select id="inventory-geo-select" onchange="document.location = this.value;">';
				$case_key = isset($params['key']) ? $params['key'] : 'default';
				$case_key = empty($show_zip) && in_array($case_key, array('city', 'zip') ) ? 'state' : $case_key;
				//$case_key = isset($geo[ (isset($params[$case_key]) ? $params[$case_key]: '') ]) ? $case_key : 'default';
				switch($case_key){
					case 'zip':
					case 'city':
						$search_tag = '<div id="geo_text">'.$params['city'].', '.$params['state'].' '.(isset($params['zip'])?$params['zip']:'').'</div>';
						$back_link = remove_query_arg( array('geo_zip','geo_city', 'geo_state') );
						$back_tag = '<a id="geo-back-link" href="'.$back_link.'">'.( !empty($remove_text) ? $remove_text : 'Return').'</a>';
						$result .= '<option value="'.add_query_arg( array('geo_zip' => 'all') ).'">All Zip Codes</option>';
						foreach( $geo[$params['state']][$params['city']] as $zip => $dealer){
							$selected = ( isset($params['zip']) && $params['zip'] == $zip ) ? 'selected' : '' ;
							$result .= '<option value="'.add_query_arg( array('geo_zip' => $zip) ).'" '.$selected.'>'.$zip.'</option>';
						}
						break;
					case 'state':
						$search_tag = '<div id="geo_text">'.(isset($params['city']) ?$params['city'].', ':'').$params['state'].'</div>';
						$back_link = remove_query_arg( array('geo_state', 'geo_city', 'geo_zip') );
						$back_tag = '<a id="geo-back-link" href="'.$back_link.'">'.( !empty($remove_text) ? $remove_text : 'Return').'</a>';
						$result .= '<option value="'.add_query_arg( array('geo_city' => 'all') ).'">All Cities</option>';
						foreach( $geo[$params['state']] as $city => $zips){
							$selected = ( isset($params['city']) && $params['city'] == $city ) ? 'selected' : '' ;
							$result .= '<option value="'.add_query_arg( array('geo_city' => rawurlencode($city)) ).'" '.$selected.'>'.$city.'</option>';
						}
						break;
					default:
						if( count($geo) > 1 ){
							$result .= '<option value="'.add_query_arg( array('geo_state' => 'all') ).'">All States</option>';
							foreach( $geo as $state => $cities){
								$result .= '<option value="'.add_query_arg( array('geo_state' => rawurlencode($state)) ).'">'.$state.'</option>';
							}
						} else {
							$search_tag = isset($params['state'])  ? '<div id="geo_text">'.$params['state'].'</div>' : '';
							$result .= '<option value="'.add_query_arg( array('geo_city' => 'all') ).'">All Cities</option>' ;
							foreach( $geo as $state => $cities){
								foreach( $cities as $city => $zips){
									$result .= '<option value="'.add_query_arg( array('geo_city' => rawurlencode($city)) ).'">'.$city.'</option>';
								}
							}
						}
						break;
				}
				$result .= '</select>';
			} else {
				$result = '<div id="geo-error">Error</div>';
			}
			$results = array( 'dropdown' => $result, 'back_link' => $back_tag, 'search' => $search_tag );
			return $results;
		}
	}

	function sort_custom_tags( $a , $b ){
		return ( $a->order > $b->order ) ? +1 : -1;
	}

	function display_autocheck_image( $vin, $sc = 'new', $page = 'list' ){
		$autocheck = '';
		if( strtolower($sc) == 'used' ){
			$autocheck = '<div class="autocheck-'.$page.'" >';
			$onclick_string = "'".site_url() . "/inventory/autocheck/".$vin."/','popup', 'width=960,height=800,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'";
			$autocheck .= '<a onclick="window.open('.$onclick_string.'); return false"  ><img src="'.cdp_get_image_source().'autocheck_'.$page.'.png" /></a>';
			$autocheck .= '</div>';
		}
		return $autocheck;
	}

	function get_proxy_site_page( $url ){
		$options = array(
		    CURLOPT_RETURNTRANSFER => true,     // return web page
		    CURLOPT_HEADER         => true,     // return headers
		    CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		    CURLOPT_ENCODING       => "",       // handle all encodings
		    CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		    CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		    CURLOPT_TIMEOUT        => 120,      // timeout on response
		    CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		);

		$ch = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$remoteSite = curl_exec( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['content'] = $remoteSite;
		return $header;
	}

	function sort_equipment( $a , $b ) {
		return ( $a->group > $b->group ) ? +1 : -1;
	}

	// Sort Options by length
	function sort_length($a,$b){
		return strlen($a)-strlen($b);
	}

	function display_equipment( $equipment ){
		$eq = '';

		if( !is_Empty_check( $equipment ) ){
			
			$equipment_groups = array();
			$equipment_data = array();

			usort( $equipment , 'sort_equipment' );

			foreach( $equipment as $item ) {
				$equipment_data[ $item->group ][] = $item;
				if( ! in_array( $item->group , $equipment_groups ) ) {
					$equipment_groups[] = $item->group;
				}
			}

			foreach( $equipment_groups as $group ) {
				$eq .= '<div class="equipment_group">';
				$replace = array( ' ',',','/','-');
				$name = strtolower(str_replace($replace,'_',$group));
				$eq .= '<h4 class="collapse-toggle" name="'.$name.'">' . $group . '</h4>';
				$eq .= '<ul class="equipment_list collapse-divider '.$name.'">';
				foreach( $equipment_data[ $group ] as $data ) {
					$eq .= '<li>' . $data->name;
					$eq .= ! empty( $data->data ) ? ': ' . $data->data : NULL;
					$eq .= '</li>';
				}
				$eq .= '</ul>';
				$eq .= '</div>';
			}
		} 

		return $eq;
	}

	function get_similar_vehicles( $vms, $detail_vin, $sale_class, $vehicle_class, $price, $make, $make_filter, $location, $flex = '' ){
		
		if ( !empty( $vehicle_class ) ) {
			$price_from = $price - 3000;
			$price_to = $price + 1000;
			if ( $price_from < 0 ) {
				$price_from = 0;
			}

			if ( $price > 0) {
				$sim_array = array( 'search_sim' => 1 , 'photo_view' => 1 , 'saleclass' => $sale_class , 'vehicleclass' => $vehicle_class , 'per_page' => 4 , 'price_from' => $price_from, 'price_to' => $price_to);
			} else {
				$sim_array = array( 'search_sim' => 1 , 'photo_view' => 1 , 'saleclass' => $sale_class , 'vehicleclass' => $vehicle_class , 'per_page' => 4 , 'make' => $make );
			}

			if ( strcasecmp( $sale_class, 'new') == 0 && !empty( $make_filter ) ) {
				$sim_array = array_merge( $sim_array, array( 'make_filters' =>  $make_filter ) );
			}

			$vms->tracer = 'Obtaining Similar Vehicles - 1';
			$inventory_sims = $vms->get_inventory()->please( $sim_array );

			$sim_value = '';

			if ( !empty( $inventory_sims ) && count($inventory_sims) > 1 ) {
				$sim_value = similar_vehicle_view( $inventory_sims, $location, $detail_vin, $flex );
			} else if ( isset( $sim_array['price_from'] ) ) {
				unset( $sim_array['price_from'] );
				unset( $sim_array['price_to'] );
				$sim_array = $sim_array + array( 'make' => $make );
				$vms->tracer = 'Obtaining Similar Vehicles - 2';
				$inventory_sims = $vms->get_inventory()->please( $sim_array );
				if ( !empty( $inventory_sims ) && count($inventory_sims) > 1 ) {
					$sim_value = similar_vehicle_view( $inventory_sims, $location, $detail_vin, $flex );
				}
			}
			return $sim_value;
		}
	}
	
	function similar_vehicle_view( $inventory_sims, $location, $detail_vin, $flex = '' ){
		if ( !empty( $inventory_sims ) ) {
			$rules = get_option( 'rewrite_rules' );
			$url_rule = ( isset($rules['^(inventory)']) ) ? TRUE : FALSE;
			
			$flex_item = $flex ? 'style="flex: 0 1 auto; max-width: 200px; margin: 2% 1%; background-color: #1e1e1e; width: 100%;" ' : '';
		    
			$sim_value = '<div id="similar-vehicles-wrapper">';
			$sim_value .= '<div id="similar-title">Similar Vehicles</div>';
			$sim_value .= '<div id="similar-items" '.$flex. '>';
			$sim_counter = 0;
			foreach( $inventory_sims as $inventory_sim):
				$vehicle = itemize_vehicle($inventory_sim);
				$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $location['state'], 'city' => $location['city'], 'vin' => $vehicle['vin'] );
				$link = generate_inventory_link($url_rule,$link_params,'','',1);
				$sim_incentive_price = 0;
				$sim_generic_vehicle_title = $vehicle['year'] . ' ' . $vehicle['make']['name'] . ' ' . $vehicle['model']['name'];
				if( $vehicle['prices']['ais']['text'] != NULL ) {
					preg_match( '/\$\d*(\s)?/' , $vehicle['prices']['ais']['text'] , $sim_incentive );
					$sim_incentive_price = isset( $sim_incentive[ 0 ] ) ? str_replace( '$' , NULL, $sim_incentive[ 0 ] ) : 0;
				}
				if( $vehicle['prices']['on_sale'] && $vehicle['prices']['sale_price'] > 0 ) {
					if( $sim_incentive_price > 0 ) {
						$sim_main_price = '<div class="similar-price">Price $' . number_format( $vehicle['prices']['sale_price'] - $sim_incentive_price , 0 , '.' , ',' ) . '</div>';
					} else {
						$sim_main_price = '<div class="similar-price">Price $' . number_format( $vehicle['prices']['sale_price'] , 0 , '.' , ',' ) . '</div>';
					}
				} else {
					if( $vehicle['prices']['asking_price'] > 0 ) {
						if( $sim_incentive_price > 0 ) {
							$sim_main_price = '<div class="similar-price">Price $' . number_format( $vehicle['prices']['asking_price'] - $sim_incentive_price , 0 , '.' , ',' ) . '</div>';
						} else {
							$sim_main_price = '<div class="similar-price">Price $' . number_format( $vehicle['prices']['asking_price'] , 0 , '.' , ',' ) . '</div>';
						}
					} else {
						$sim_main_price = '<div class="similar-price">' . $vehicle['prices']['default_text'] . '</div>';
					}
				}

				if ( $detail_vin != $vehicle['vin'] && $sim_counter < 3 ) {
					$sim_counter = $sim_counter + 1;
					// Similar Start
					$sim_value .= '<div class="similar-item" '.$flex_item.'>';
					// Similar Headline
					$sim_value .= '<div class="similar-headline">';
					$sim_value .= '<span class="similar-saleclass">' . $vehicle['saleclass'] . '</span>';
					$sim_value .= '<a href="' . $link . '" title="' . $sim_generic_vehicle_title . '" >';
					$sim_value .= '<span class="similar-make">' . $vehicle['make']['name'] . '</span>';
					$sim_value .= '<span class="similar-make">' . $vehicle['model']['name'] . '</span>';
					$sim_value .= '</a></div>';
					// Similar Content Wrapper -start
					$sim_value .= '<div class="similar-content-wrap">';
					// Similar Photo
					$sim_value .= '<div class="similar-column-left">';
					$sim_value .= '<div class="similar-photo">';
					$sim_value .= '<a href="' . $link . '" title="' . $sim_generic_vehicle_title . '">';
					$sim_value .= '<img src="' . $vehicle['thumbnail'] . '" alt="' . $sim_generic_vehicle_title . '" title="' . $sim_generic_vehicle_title . '" />';
					$sim_value .= '</a></div></div>';
					// Similar Info
					$sim_value .= '<div class="similar-column-right">';
					$sim_value .= '<div class="similar-info">';
					$sim_value .= '<div class="similar-details">';
					$sim_value .= '<span class="similar-stock-number">Stock #: ' . $vehicle['stock_number'] . '</span>';
					$sim_value .= '<span class="similar-year">Year: ' . $vehicle['year'] . '</span>';
					$sim_value .= '<span class="similar-trim">Trim: ' . $vehicle['trim']['name'] . '</span>';
					$sim_value .= '</div>';
					$sim_value .= '<div class="similar-price">';
					$sim_value .= $sim_main_price;
					$sim_value .= '</div>';
					$sim_value .= '</div></div>';
					// Similar Content Wrapper -end
					$sim_value .= '</div>';
					// Similar Button
					$sim_value .= '<div class="similar-button">';
					$sim_value .= '<a href="' . $link . '" title="More Information: ' . $sim_generic_vehicle_title . '">More Information</a>';
					$sim_value .= '</div>';
					// Similar End
					$sim_value .= '</div>';
				}
			endforeach;
			$sim_value .= '</div></div>';
			return $sim_value;
		}
	}

	function generate_inventory_link($rule, $params, $include = array(), $remove = array(), $type = 0 ){
		$exclude = array('page', 'dealer_id', 'taxonomy', 'per_page', 'make', 'model', 'saleclass', 'geo_search' );
		if( !empty($include) ){
			foreach($include as $key => $value){
				$params[$key] = $value;
			}
		}
		if( !empty($remove) ){
			foreach($remove as $value){
				unset($params[$value]);
			}
		}
		if($rule){
			if( $type ){
				$link = '/inventory/'.$params['year'].'/'.rawurlencode(rawurldecode($params['make'])).'/'.rawurlencode(rawurldecode($params['model'])).'/'.rawurlencode(rawurldecode($params['state'])).'/'.rawurlencode(rawurldecode(strip_tags($params['city']))).'/'.$params['vin'].'/';
			} else {
				$link = '/inventory/'.$params['saleclass'].'/';
				$link .= isset($params['make']) && strtolower($params['make']) != 'all' ? rawurlencode(rawurldecode($params['make'])) . '/' : '';
				$link .= isset($params['model']) && strtolower($params['model']) != 'all' ? rawurlencode(rawurldecode($params['model'])) . '/' : '';
				foreach($params as $key => $param){
					if( !in_array($key, $exclude) && strtolower($param) != 'all'){
						$link = add_query_arg( array($key => $param), $link );
					}
				}
			}
		} else {
			$link = '?taxonomy=inventory';
			foreach($params as $key => $param){
				$link = add_query_arg( array($key => $param), $link );
			}
		}
		apply_geo_query_links($link);
		return $link;
	}
	
	function apply_geo_query_links( &$link ){
		if( isset($_GET['geo_state']) ){
			$link = add_query_arg( array('geo_state' => rawurlencode($_GET['geo_state'])), $link);
		}
		if( isset($_GET['geo_city']) ){
			$link = add_query_arg( array('geo_city' => rawurlencode($_GET['geo_city'])), $link);
		}
		if( isset($_GET['geo_zip']) ){
			$link = add_query_arg( array('geo_zip' => rawurlencode($_GET['geo_zip'])), $link);
		}
	}

	function build_slider_script( $params ){

		$pre = ($params['type'] == 'price') ? '$' : '';
		$set_values = (isset($params['search'][0]) && isset($params['search'][1])) ? array($params['search'][0], $params['search'][1]) : array($params['default'][0], $params['default'][1]);

		$script = '<script type="text/javascript" >jQuery(document).ready(function(){';
		$script .= 'jQuery("#'.$params['type'].'-range").slider({max: '.$params['default'][1].',min: '.$params['default'][0].',range: true,step: '.$params['step'].',values: [ '.$set_values[0].', '.$set_values[1].' ], slide: function( event, ui ) { jQuery( "#'.$params['type'].'-range-values" ).val( "'.$pre.'" + ui.values[ 0 ] + " - '.$pre.'" + ui.values[ 1 ] ); jQuery( "#'.$params['type'].'-range-flag" ).val( "true" ); } }); jQuery( "#'.$params['type'].'-range-values" ).val( "'.$pre.'" + jQuery( "#'.$params['type'].'-range" ).slider( "values", 0 ) + " - '.$pre.'" + jQuery( "#'.$params['type'].'-range" ).slider( "values", 1 ) );';
		$script .= '});</script>';

		return $script;
	}

	function apply_gravity_form_hooks( $data ){

		$hooks = get_gravity_hooks( $data );

		foreach( $hooks as $key => $result ){
			add_filter("gform_field_value_".$key, 
				function($value) use ($result) {
					$value=$result;
					return $value;
				}
			);
		}

	}

	function get_gravity_hooks( $data ){

		$hooks = array('dt_stock_number' => $data['stock_number'], 'dt_vin' => $data['vin'], 'dt_year' => $data['year'], 'dt_make' => $data['make']['name'], 'dt_model' => $data['model']['name'], 'dt_trim' => $data['trim']['name'], 'dt_saleclass' => $data['saleclass'], 'dt_exterior' => $data['exterior_color'], 'dt_interior' => $data['interior_color'], 'dt_mileage' => $data['odometer'], 'dt_price' => $data['primary_price'], 'dt_dealer' => $data['contact_info']['dealer'], 'dt_dealer_id' => $data['contact_info']['dealer_id'], 'dt_location' => $data['contact_info']['location'], 'dt_phone' => $data['contact_info']['phone'] );
		
		$hooks['dt_automall_email'] = get_automall_email( $data['contact_info']['dealer_id'], $data['saleclass'] , TRUE );

		return $hooks;
	}
	
	function cdp_apply_setting_scripts( $scripts, $page, $saleclass ){
		$page_type = $page == 'list' ? '1' : '2';
		$sc_type = strtolower($saleclass) == 'new' ? '1' : '2';
		$replace = array( ' ',',','/','-');
		foreach( $scripts as $script ){
			if( $script['saleclass'] == '0' || $script['saleclass'] == $sc_type){
				if( $script['page'] == '0' || $script['page'] == $page_type ){
					$in_footer = $script['location'] == 0 ? FALSE : TRUE;
					$script_name = strtolower(str_replace($replace,'_',$script['name']));
					wp_enqueue_script( $script_name, $script['url'], 'jQuery', False, $in_footer);
				}
			}
		}
	}
	
	function cdp_apply_setting_styles( $styles, $page, $saleclass ){
		$page_type = $page == 'list' ? '1' : '2';
		$sc_type = strtolower($saleclass) == 'new' ? '1' : '2';
		$replace = array( ' ',',','/','-');
		foreach( $styles as $style ){
			if( $style['saleclass'] == '0' || $style['saleclass'] == $sc_type){
				if( $style['page'] == '0' || $style['page'] == $page_type ){
					$style_name = strtolower(str_replace($replace,'_',$style['name']));
					wp_enqueue_style( $style_name, $style['url'] );
					if( !empty($style['override']) ){
						wp_deregister_style( 'cdp_inventory' );
					}
				}
			}
		}
	}
	
	function cdp_create_saleclass_dd_sc( $flag = 'all', $default = 'all' ){
		$content = '<div class="sc-search-item saleclass">';
		$content .= '<label class="sc-search-label">Sale Class</label>';
		$content .= '<select id="sc-search-saleclass" class="inventory-select" onchange="cdp_vehicle_caller(this.value, \'saleclass\');">';
			
		switch( strtolower($flag) ){
			case 'all':
				$content .= '<option value="" '.(strtolower($default) == 'all' ?'selected':'' ).' >All Vehicles</option>';
				$content .= '<option value="new" '.(strtolower($default) == 'new' ?'selected':'' ).'>New Vehicles</option>';
				$content .= '<option value="used" '.(strtolower($default) == 'used' ?'selected':'' ).'>Pre-Owned Vehicles</option>';
				//$content .= '<option value="certified">Certified Pre-Owned</option>';
				break;
			case 'new':
				$content .= '<option value="new" selected>New Vehicles</option>';
				break;
			case 'used':
				$content .= '<option value="used" selected>Pre-Owned Vehicles</option>';
				//$content .= '<option value="certified">Certified Pre-Owned</option>';
				break;
			case 'certified':
				$content .= '<option value="used" selected>Pre-Owned Vehicles</option>';
				//$content .= '<option value="certified" selected>Certified Pre-Owned</option>';
				break;
		}
		
		$content .= '</select>';
		$content .= '</div>';
		return $content;
	}
	
	function cdp_generate_make_options( $vms, $saleclass, $makes = array(), $val_only = FALSE, $alt_id = 0 ){
		
		if( empty($makes) || strtolower($saleclass) == 'used' ) {
			$vms->tracer = 'Getting Makes';
			$makes = $vms->get_makes( $alt_id )->please( array( 'saleclass' => $saleclass ) );
		}
		$options = '';
		if( $val_only ){
			return $makes; //Returns data as Array
		} else {
			if( count($makes) == 1 ){
				$content['val'] = strtolower(rawurlencode($makes[0]));
			} else {
				$content['val'] = '';
				$options = '<option value="">All Makes</option>';
			}
			foreach($makes as $make){
				$options .= '<option value="'.strtolower(rawurlencode($make)).'">'.$make.'</option>';
			}
			$content['display'] = $options; 
			return $content;
		}
		
	}
	
	function cdp_generate_model_options( $vms, $saleclass, $make = '', $val_only = FALSE, $alt_id = 0 ){

		if( empty($make) ){
			$content['val'] = '';
			$content['display'] = '<option value="">Models</option>';
			$content['disabled'] = 'TRUE';
			return $content;
		}

		$vms->tracer = 'Getting Models';
		$models = $vms->get_models( $alt_id )->please( array( 'saleclass' => $saleclass, 'make' => $make ) );

		if( $val_only ){
			return $models;
		} else {
			if( count($models) == 1 ){
				$content['val'] = strtolower(rawurlencode($models[0]));
			} else {
				$content['val'] = '';
				$options = '<option value="">All Models</option>';
			}
			foreach($models as $model){
				$options .= '<option value="'.strtolower(rawurlencode($model)).'">'.$model.'</option>';
			}
			$content['display'] = $options; 
			return $content;
		}

	}
	
	function cdp_generate_trim_options( $vms, $saleclass, $make = '', $model = '', $val_only = FALSE, $alt_id = 0 ){

		if( empty($make) || empty($model) ){
			$content['val'] = '';
			$content['display'] = '<option value="">Trims</option>';
			$content['disabled'] = 'TRUE';
			return $content;
		}

		$vms->tracer = 'Getting Trims';
		$trims = $vms->get_trims( $alt_id )->please( array( 'saleclass' => $saleclass , 'make' => $make , 'model' => $model ) );

		if( $val_only ){
			return $trims;
		} else {
			if( count($trims) == 1 ){
				$content['val'] = strtolower(rawurlencode($trims[0]));
			} else {
				$content['val'] = '';
				$options = '<option value="">All Trims</option>';
			}
			foreach($trims as $trim){
				$options .= '<option value="'.strtolower(rawurlencode($trim)).'">'.$trim.'</option>';
			}
			$content['display'] = $options; 
			return $content;
		}

	}

	function is_Empty_check($obj){
		if( empty($obj) ){
			return true;
		} else if( is_numeric( $obj ) ){
			return false;
		}else if( is_string($obj) ){
			return !strlen(trim($obj));
		}else if( is_object($obj) ){
			return is_Empty_check((array)$obj);
		}
		// It's an array!
		foreach($obj as $element)
			if (is_Empty_check($element)) continue; // so far so good.
			else return false;

		// all good.
		return true;
	}
?>
