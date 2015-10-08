<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	function multiple_select_window( $path, $available, $include, $tag = 'default_window', $title = '' ){
		$include = is_array($include) ? $include : array();
		$active = empty($title) ? 'active' : '';
		if( $path && $available ){
			$content = '<div class="msw-wrapper '.$tag.'" tag="'.$tag.'" name="'.$path.'">';
			
			$content .= empty($title) ? '' : '<div class="msw-window-title">'.$title.'</div>';
			$content .= '<div class="msw-data-wrapper '.$active.'">';
			
			$content .= '<div class="msw-list-wrapper">';
			$content .= '<div class="msw-title">Available</div>';
			$content .= '<div class="msw-list-items"><ul class="msw-available">';
			foreach( $available as $item){
				if( !in_array($item,$include) ){
					$content .= '<li class="msw-add" name="'.$item.'">'.$item.'</li>';
				}
			}
			$content .= '</ul></div>';
			$content .= '</div>';
			
			$content .= '<div class="control-wrapper" for="'.$tag.'">';
			$content .= '<button id="control-include" class="msw-add-all" name="include">Include All</button>';
			$content .= '<button id="control-reset" class="msw-remove-all" name="reset">Reset</button>';
			$content .= '</div>';

			$content .= '<div class="msw-list-wrapper">';
			$content .= '<div class="msw-title">Included</div>';
			$content .= '<div class="msw-list-items"><ul class="msw-included">';
			if( $include ){
				foreach( $include as $item){
					$content .= '<li class="msw-remove" name="'.$item.'">'.$item.'</li>';
				}
			}
			$content .= '</ul></div>';
			$content .= '</div>';
			
			$content .= '</div></div>';
		}
		return $content;
	}
	
	function get_admin_theme_view( $options ){
		$theme = $options['name'];
		//Title/Current Theme
		$content = '<div id="view-CurrentTheme" class="view-wrapper"><div class="tr-wrapper"><div class="td-full"><h4 class="divider"><span>'.ucfirst($theme).'</span> Theme Settings</h4></div></div>';
		//Change Theme
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Change Theme:</span></div><div class="td-two">';
		$content .= '<select name="vehicle_management_system/theme/name" class="cdp-input" tag="CurrentTheme">';
		$themes = cdp_plugin::get_themes( 'inventory' );
		foreach( $themes as $key => $value ) {
			$selected = $value == $theme ? 'selected' : NULL;
			$content .= '<option ' . $selected . ' value="' . $value . '">' . ucwords( $value ) .'</option>';
		}
		$content .= '</select>';
		$content .= '</div></div>';
		// Armadillo Specific | Format
		if( $theme == 'armadillo' ){
			$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Detail Format:</span></div><div class="td-two">';
			$content .= '<select name="vehicle_management_system/theme/detail_format" class="cdp-input">';
			$value = isset($options['detail_format']) ? $options['detail_format'] : '';
			$content .= '<option value="0"'. ( !isset($value) || empty($value) ? 'selected' : '' ) .' >Default</option>';
			$content .= '<option value="1"'. ( $value == 1 ? 'selected' : '' ).'>Style 2</option>';
			$content .= '</select>';
			$content .= '</div></div>';
		}
		// Display Standard Equipment
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Standard Equipment:</span></div><div class="td-two">';
		$value = isset($options[ 'show_standard_eq' ]) ? $options[ 'show_standard_eq' ] : '';
		$content .= '<input type="checkbox" id="display-standard-eq" class="cdp-input" name="vehicle_management_system/theme/show_standard_eq"'.( !empty($value) ? ' checked ' : '') .' />';
		$content .= '</div></div>';
		// Display Tags
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Display Tags:</span></div><div class="td-two">';
		$value = isset($options[ 'display_tags' ]) ? $options[ 'display_tags' ] : '';
		$content .= '<input type="checkbox" id="display-tags" class="cdp-input" name="vehicle_management_system/theme/display_tags"'.( !empty($value) ? ' checked ' : '') .' />';
		$content .= '</div></div>';
		// Display Geo Search
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Display Geo Search:</span></div><div class="td-two">';
		$value = isset($options[ 'display_geo' ]) ? $options[ 'display_geo' ] : '';
		$content .= '<input type="checkbox" id="display-geo" class="cdp-input" name="vehicle_management_system/theme/display_geo"'. ( !empty($value) ? ' checked ' : '') .' />';
		$content .= '</div></div>';
		// Add Zip to Geo
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Add Geo Zip to Search:</span></div><div class="td-two">';	
		$value = isset($options[ 'add_geo_zip' ]) ? $options[ 'add_geo_zip' ] : '';
		$content .= '<input type="checkbox" id="geo-zip" class="cdp-input" name="vehicle_management_system/theme/add_geo_zip"'.( !empty($value) ? ' checked ' : '' ).' />';
		$content .= '</div></div>';
		// Display Similar Vehicles
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Display Similar Vehicles:</span></div><div class="td-two">';
		$value = isset($options[ 'display_similar' ]) ? $options[ 'display_similar' ] : '';
		$content .= '<input type="checkbox" id="display-similar" class="cdp-input" name="vehicle_management_system/theme/display_similar"'. ( !empty($value) ? ' checked ' : '' ).' />';
		$content .= '</div></div>';
		// Hide Certified Saleclass
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Hide Certified Saleclass:</span></div><div class="td-two">';
		$value = isset($options[ 'hide_certified_saleclass' ]) ? $options[ 'hide_certified_saleclass' ] : '';
		$content .= '<input type="checkbox" id="hide-certified-saleclass" class="cdp-input" name="vehicle_management_system/theme/hide_certified_saleclass"'. ( !empty($value) ? ' checked ' : '' ).' />';
		$content .= '</div></div>';
		// Default Image Tab
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Default Photo/Video Tab:</span></div><div class="td-two">';
		$content .= '<select id="default-image-tab" name="vehicle_management_system/theme/default_image_tab" class="cdp-input">';
		$value = isset($options[ 'default_image_tab' ]) ? $options[ 'default_image_tab' ] : '';
		$content .= '<option value="0" '.( !isset($value) || empty($value) ? 'selected' : '' ) .'>Photo</option>';
		$content .= '<option value="1" '.( $value == 1 ? 'selected' : '' ) .'>Video</option>';
		$content .= '</select>';
		$content .= '</div></div>';
		// Default Info Tab
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Default Vehicle Info:</span></div><div class="td-two">';
		$content .= '<select id="default-info-tab" name="vehicle_management_system/theme/default_info_tab" class="cdp-input">';
		$value = isset($options[ 'default_info_tab' ]) ? $options[ 'default_info_tab' ] : '';
		$content .= '<option value="0" '.( !isset($value) || empty($value) ? 'selected' : '' ) .'>Options</option>';
		$content .= '<option value="1" '.( $value == 1 ? 'selected' : '' ) .'>Description</option>';
		$content .= '</select>';
		$content .= '</div></div>';
		//Default List Info Text
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">List Info Button:</span></div><div class="td-two">';
		$value = isset($options[ 'list_info_button' ]) ? $options[ 'list_info_button' ] : '';
		$content .= '<input type="text" id="list-btn-text" class="cdp-input" name="vehicle_management_system/theme/list_info_button" value="'.$value.'" />';
		$content .= '</div></div>';
		//List Form Button Text
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">List Form Button:</span></div><div class="td-two">';
		$value = isset($options[ 'list_form_button' ]) ? $options[ 'list_form_button' ] : '';
		$content .= '<input type="text" id="list-btn-price-text" class="cdp-input" name="vehicle_management_system/theme/list_form_button" value="'.$value.'" />';
		$content .= '</div></div>';
		//List Form ID
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">List Form ID:</span></div><div class="td-two">';
		$value = isset($options[ 'list_gform_id' ]) ? $options[ 'list_gform_id' ] : ''; 
		$content .= '<input type="text" id="list-gform-id" class="cdp-input input-short" name="vehicle_management_system/theme/list_gform_id" pattern="[0-9]" value="'.$value.'" />';
		$content .= '</div></div>';
		// Detail Form ID
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Detail Form ID:</span></div><div class="td-two">';
		$value = isset($options[ 'detail_gform_id' ]) ? $options[ 'detail_gform_id' ] : ''; 
		$content .= '<input type="text" id="detail-gform-id" class="cdp-input input-short" name="vehicle_management_system/theme/detail_gform_id" pattern="[0-9]" value="'.$value.'" />';
		$content .= '</div></div>';

		$content .= '</div>';

		//$content .= cdp_flex_settings($options['flex']);
		
		return $content;
	}
	
	function cdp_flex_settings( $theme, $settings ){
		$content = '<div id="view-Flex" class="view-wrapper"><div class="tr-wrapper"><div class="td-full"><h4 class="divider">Flex Settings</h4></div></div>';
		
		//Photos
		$content .= '<div class="tr-wrapper tr-color"><div class="td-full"><span class="td-title">Photo</span></div><div class="td-two">';
		$value = $settings[$theme][ 'photos' ]; 
		$content .= '<input type="text" id="detail-flex-width" class="cdp-input input-short" name="vehicle_management_system/theme/flex/"'.$theme.'/photo/width pattern="[0-1000]" value="'.$value.'" />';
		$content .= '</div></div>';
		
		$content .= '</div>';
		return $content;
	}
	
	function get_admin_showcase_theme_view( $options ){
		$theme = $options['name'];
		//Title/Current Theme
		$content = '<div id="view-CurrentTheme" class="view-wrapper"><div class="tr-wrapper"><div class="td-full"><h4 class="divider"><span>'.ucfirst($theme).'</span> Theme Settings</h4></div></div>';
		//Change Theme
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Change Theme:</span></div><div class="td-two">';
		$content .= '<select name="vehicle_reference_system/theme/name" class="cdp-input" tag="CurrentThemeShowcase">';
		$themes = cdp_plugin::get_themes( 'showcase' );
		foreach( $themes as $key => $value ) {
			$selected = $value == $theme ? 'selected' : NULL;
			$content .= '<option ' . $selected . ' value="' . $value . '">' . ucwords( $value ) .'</option>';
		}
		$content .= '</select>';
		$content .= '</div></div>';
		// Form ID
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Gravity Form ID:</span></div><div class="td-two">';
		$value = $options[ 'form_id' ]; 
		$content .= '<input type="text" id="showcase-form-id" class="cdp-input input-short" name="vehicle_reference_system/theme/form_id" pattern="[0-9]" value="'.$value.'" />';
		$content .= '</div></div>';
		// Display VMS ID
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Display Similar Vehicles:</span></div><div class="td-two">';
		$value = $options[ 'display_vms' ]; 
		$content .= '<input type="checkbox" id="display_vms" class="cdp-input" name="vehicle_reference_system/theme/display_vms" '. ( !empty($value) ? ' checked ' : '' ).' />';
		$content .= '</div></div>';
		// Display VMS Count
		$content .= '<div class="tr-wrapper tr-color"><div class="td-two right"><span class="td-title">Similar Count:</span></div><div class="td-two">';
		$value = $options[ 'display_vms_count' ];
		$option_array = array( 4, 8, 12, 16);
		$content .= '<select name="vehicle_reference_system/theme/vms_count" class="cdp-input" >';
		foreach( $option_array as $val ){
			$content .= '<option value="'.$val.'" '.($value == $val ? 'selected': '').' >'.$val.'</option>';
		}
		$content .= '</select>';
		$content .= '</div></div>';
		
		$content .= '</div>';
		return $content;
	}
	
	function get_form_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'form-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['title']) ? $value['title'] : 'New Form' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">ID:</span></div>';
				$content .= '<div class="inner-td value"><input type="number" name="vehicle_management_system/theme/forms/'.$key.'/id" id="form-'.$key.'-id" class="cdp-input input-short '.$id_value.'" value="'.$value['id'].'" /></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Title:</span></div>';
				$content .= '<div class="inner-td value"><input type="text" name="vehicle_management_system/theme/forms/'.$key.'/title" id="form-'.$key.'-title" class="cdp-input '.$id_value.'" value="' . $value['title'] . '" /></div>';
				$content .= '</div>';

				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Sale Class:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/theme/forms/'.$key.'/saleclass" id="form-'.$key.'-saleclass" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['saleclass'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['saleclass'] == '1' ? 'selected' : '' ).'>New</option>';
					$content .= '<option value="2" '.( $value['saleclass'] == '2' ? 'selected' : '' ).'>Used</option>';
				$content .= '</select></div>';
				$content .= '</div>';

				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">[x]</div>';

				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_email_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'email-'.$key;
				if( $value['saleclass'] == 0 ){ 
					$saleclass='All'; 
				} else if( $value['saleclass'] == 1 ){
					$saleclass='New'; 
				} else { 
					$saleclass='Used'; 
				}
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['id']) ? 'Email for ID - '.$value['id'].' '. $saleclass : 'New Email' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">ID:</span></div>';
				$content .= '<div class="inner-td value"><input type="number" name="vehicle_management_system/theme/emails/dealers/'.$key.'/id" id="email-'.$key.'-id" class="cdp-input input-short '.$id_value.'" value="'.$value['id'].'" /></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Email:</span></div>';
				$content .= '<div class="inner-td value"><input type="text" name="vehicle_management_system/theme/emails/dealers/'.$key.'/email" id="email-'.$key.'-email" class="cdp-input '.$id_value.'" value="' . $value['email'] . '" /></div>';
				$content .= '</div>';

				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Sale Class:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/theme/emails/dealers/'.$key.'/saleclass" id="email-'.$key.'-saleclass" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['saleclass'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['saleclass'] == '1' ? 'selected' : '' ).'>New</option>';
					$content .= '<option value="2" '.( $value['saleclass'] == '2' ? 'selected' : '' ).'>Used</option>';
				$content .= '</select></div>';
				$content .= '</div>';

				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">[x]</div>';

				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_tag_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'tag-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['name']) ? $value['name'] : 'New Tag' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Name:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/tags/data/'.$key.'/name" type="text" class="cdp-input" value="'.$value['name'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Order:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/tags/data/'.$key.'/order" type="number" class="cdp-input input-short" value="'.$value['order'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title"><a id="media-'.$id_value.'" href="#" class="custom_media_upload '.$id_value.'">Upload:</a></span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/tags/data/'.$key.'/url" type="text" class="cdp-input custom_media_url media-'.$id_value.'" value="'.$value['url'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Icon:</span></div>';
				$content .= '<div class="inner-td value"><img class="custom_media_image media-'.$id_value.'" src="'.$value['url'].'" /></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Link:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/tags/data/'.$key.'/link" type="text" class="cdp-input" value="'.$value['link'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">Remove [x]</div>';
								
				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_script_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'tag-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['name']) ? $value['name'] : 'New Script' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Name:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/scripts/data/'.$key.'/name" type="text" class="cdp-input" value="'.$value['name'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Sale Class:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/scripts/data/'.$key.'/saleclass" id="form-'.$key.'-saleclass" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['saleclass'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['saleclass'] == '1' ? 'selected' : '' ).'>New</option>';
					$content .= '<option value="2" '.( $value['saleclass'] == '2' ? 'selected' : '' ).'>Used</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Location:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/scripts/data/'.$key.'/location" id="form-'.$key.'-location" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['location'] == '0' ? 'selected' : '' ).'>Header</option>';
					$content .= '<option value="1" '.( $value['location'] == '1' ? 'selected' : '' ).'>Footer</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Page:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/scripts/data/'.$key.'/page" id="form-'.$key.'-page" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['page'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['page'] == '1' ? 'selected' : '' ).'>List</option>';
					$content .= '<option value="2" '.( $value['page'] == '2' ? 'selected' : '' ).'>Detail</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">URL:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/scripts/data/'.$key.'/url" type="text" class="cdp-input" value="'.$value['url'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">Remove [x]</div>';
								
				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_style_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'tag-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['name']) ? $value['name'] : 'New Style' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Name:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/styles/data/'.$key.'/name" type="text" class="cdp-input" value="'.$value['name'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Sale Class:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/styles/data/'.$key.'/saleclass" id="form-'.$key.'-saleclass" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['saleclass'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['saleclass'] == '1' ? 'selected' : '' ).'>New</option>';
					$content .= '<option value="2" '.( $value['saleclass'] == '2' ? 'selected' : '' ).'>Used</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Page:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_management_system/styles/data/'.$key.'/page" id="form-'.$key.'-page" class="cdp-input '.$id_value.'" >';
					$content .= '<option value="0" '.( $value['page'] == '0' ? 'selected' : '' ).'>All</option>';
					$content .= '<option value="1" '.( $value['page'] == '1' ? 'selected' : '' ).'>List</option>';
					$content .= '<option value="2" '.( $value['page'] == '2' ? 'selected' : '' ).'>Detail</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">URL:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_management_system/styles/data/'.$key.'/url" type="text" class="cdp-input" value="'.$value['url'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Override:</span></div>';
				$content .= '<input type="checkbox" id="display_vms" class="cdp-input" name="vehicle_management_system/styles/data/'.$key.'/override" '. ( !empty($value['override']) ? ' checked ' : '' ).' /> <small>Will remove theme style</small>';
				$content .= '</div>';
				
				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">Remove [x]</div>';
								
				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_video_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'video-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['name']) ? $value['name'] : 'New Video' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Name:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/videos/'.$key.'/name" type="text" class="cdp-input" value="'.$value['name'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Make:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/videos/'.$key.'/make" type="text" class="cdp-input" value="'.$value['make'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Model:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/videos/'.$key.'/model" type="text" class="cdp-input" value="'.$value['model'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Video URL:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/videos/'.$key.'/url" type="text" class="cdp-input" value="'.$value['url'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">Remove [x]</div>';
				
				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_message_rows( $data ){
		$content = '';
		
		if( !empty($data) ){
			foreach( $data as $key => $value){
				$id_value = 'video-'.$key;
				$content .= '<div class="inner-row-wrapper">';
				
				$content .= '<div class="inner-row-label"><h5>'.( !empty($value['name']) ? $value['name'] : 'New Message' ).'</h5></div>';
				$content .= '<div class="inner-row-content">';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Name:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/messages/'.$key.'/name" type="text" class="cdp-input" value="'.$value['name'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Count:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/messages/'.$key.'/evaluate" type="number" class="cdp-input input-short" value="'.$value['evaluate'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Operator:</span></div>';
				$content .= '<div class="inner-td value"><select name="vehicle_reference_system/messages/'.$key.'/operator" class="cdp-input short">';
				$content .= '<option value=">" ' . ($value['operator'] == ">" ? "selected": "") . ' >&gt;</option>';
				$content .= '<option value="<" ' . ($value['operator'] == "<" ? "selected": "") . ' >&lt;</option>';
				$content .= '<option value=">=" ' . ($value['operator'] == ">=" ? "selected": "") . ' >&ge;</option>';
				$content .= '<option value="<=" ' . ($value['operator'] == "<=" ? "selected": "") . ' >&le;</option>';
				$content .= '<option value="=" ' . ($value['operator'] == "=" ? "selected": "") . ' >=</option>';
				$content .= '<option value="!=" ' . ($value['operator'] == "!=" ? "selected": "") . ' >&ne;</option>';
				$content .= '</select></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label top"><span class="td-title">Message:</span></div>';
				$content .= '<div class="inner-td value"><textarea name="vehicle_reference_system/messages/'.$key.'/text" class="cdp-textarea">'.$value['text'].'</textarea></div>';
				$content .= '</div>';
				
				$content .= '<div class="inner-tr-wrapper">';
				$content .= '<div class="inner-td label"><span class="td-title">Title:</span></div>';
				$content .= '<div class="inner-td value"><input name="vehicle_reference_system/messages/'.$key.'/title" type="text" class="cdp-input" value="'.$value['title'].'"/></div>';
				$content .= '</div>';
				
				$content .= '<div tag="'.$key.'" class="remove '.$id_value.'">Remove [x]</div>';
				
				$content .= '</div></div>';
			}
		}
		
		return $content;
	}
	
	function get_sc_detail_vehicles( $vehicle, $vrs, $company, $options, $inv_options, $form ){
		$sc_content = ''; $fuel = array();
		//wrapper -s
		$sc_content .= '<div id="'.$vehicle['vin'].'" class="sc-detail-item">';

		$sc_content .= '<div class="sc-detail-top-container"">'; //top container -s
		$sc_content .= '<div class="sc-detail-top-one">'; //top one -s
		//title
		$sc_content .= '<div class="sc-detail-title-wrapper">';
		$sc_content .= '<span class="sc-detail-saleclass form-value" name="saleclass"><small>'.$vehicle['saleclass'].'</small></span>';
		$sc_content .= '<span class="sc-detail-year form-value" name="year">'.$vehicle['year'].'</span>';
		$sc_content .= '<span class="sc-detail-make form-value" name="make">'.$vehicle['make']['name'].'</span>';
		$sc_content .= '<span class="sc-detail-model form-value" name="model">'.$vehicle['model']['name'].'</span>';
		$sc_content .= '</div>';
		$sc_content .= '</div>'; //top one -e
		$sc_content .= '<div class="sc-detail-top-two">'; //top two -s
		//image
		$sc_content .= '<div class="sc-img-wrapper">';
		$sc_content .= '<img class="sc-detail-image" src="'.$vehicle['thumbnail'].'" />';
		$sc_content .= '</div>';
		$sc_content .= '</div>'; //top two -e
		$sc_content .= '<div class="sc-detail-top-three">'; //top three -s
		//price
		$price = get_price_display($vehicle['prices'], $company, $vehicle['saleclass'], $vehicle['vin'], 'sc-detail', $options['vehicle_management_system' ]['theme']['price_text'], array() );
		$sc_content .= '<div class="sc-detail-price-wrapper">';
		//$sc_content .= (!empty($price['ais_link'])) ? $price['ais_link'] : '';
		$sc_content .= $price['compare_text'].$price['ais_text'].$price['primary_text'].$price['expire_text'].$price['hidden_prices'];
		$sc_content .= '</div>';
		//stock | vin
		$sc_content .= '<div class="sc-detail-top-info">';
		$sc_content .= '<div class="top-info-value">Stock #: <span class="sc-detail-stock-number form-value" name="stock_number">'.$vehicle['stock_number'].'</span></div>';
		$sc_content .= '<div class="top-info-value">Vin: <span class="sc-detail-vin form-value" name="vin">'.$vehicle['vin'].'</span></div>';
		$sc_content .= '</div>';
		//buttons
		$sc_content .= '<div class="sc-detail-buttons">';
		$sc_content .= '<div class="sc-detail-button sc-action-button show-details" name="vehicle_info" key="'.$vehicle['vin'].'">Show Details</div>';
		if( function_exists('gravity_form') ){
			if($form){
				$sc_content .= '<div class="sc-detail-button sc-action-form" name="vehicle_form" key="'.$vehicle['vin'].'">Request Info</div>';	
			}	
		}
		$sc_content .= '</div>';
		$sc_content .= '</div>'; //top three -e
		$sc_content .= '<div class="sc-detail-top-four">'; // top four -s
		$sc_content .= get_dealer_contact_info( $vehicle['contact_info'], $inv_options, $vehicle['saleclass'] );
		$sc_content .= '</div>'; // top four -e
		$sc_content .= '</div>'; //top container -e
		
		$sc_content .= '<div class="sc-detail-bottom-container">'; //bottom container -s
		
		$sc_content .= '<div class="sc-detail-bottom-one">'; //bottom one -s
		//bottom buttons
		$sc_content .= '<div class="sc-detail-bottom-buttons">';
		$sc_content .= '<div class="bottom-button vehicle_info-button"><img class="sc-action-button inner-button" name="vehicle_info" key="'.$vehicle['vin'].'" src="'.cdp_get_image_source().'info_icon.png" /><small class="sc-detail-label label-information">Information</small></div>';
		$sc_content .= '<div class="bottom-button vehicle_details-button"><img class="sc-action-ajax" name="vehicle_details" key="'.$vehicle['vin'].'" tag="info" src="'.cdp_get_image_source().'detail_info_icon.png" /><small class="sc-detail-label label-details">Comments</small></div>';
		$sc_content .= '<div class="bottom-button vehicle_images-button"><img class="sc-action-ajax" name="vehicle_images" key="'.$vehicle['vin'].'" tag="images" src="'.cdp_get_image_source().'image_icon.png" /><small class="sc-detail-label lable-pictures">Pictures</small></div>';
		if( function_exists('gravity_form') ){
			$sc_content .= $form?'<div class="bottom-button vehicle_form-button"><img class="sc-action-form inner-button" name="vehicle_form" key="'.$vehicle['vin'].'" src="'.cdp_get_image_source().'email_icon.png" /><small class="sc-detail-label label-offer">Make Offer</small></div>': '';
		}
		$sc_content .= '</div>';
		$sc_content .= '</div>'; //bottom one -e
		
		$sc_content .= '<div class="sc-detail-bottom-two">'; //bottom two -s
		//bottom content
		$sc_content .= '<div class="sc-detail-bottom-content">'; //bottom content -s
		
		$sc_content .= '<div class="bottom-content vehicle_info-content">'; //bottom vehicle info -s
		$sc_content .= '<div class="vehicle_info-data">';
		$sc_content .= '<div class="data-row"><span class="column-title">Year:</span><span class="column-value">'.$vehicle['year'].'</span></div>';
		$sc_content .= '<div class="data-row"><span class="column-title">Make:</span><span class="column-value">'.$vehicle['make']['name'].'</span></div>';
		$sc_content .= '<div class="data-row"><span class="column-title">Model:</span><span class="column-value">'.$vehicle['model']['name'].'</span></div>';
		$sc_content .= $vehicle['trim']['name'] ? '<div class="data-row"><span class="column-title">Trim:</span><span class="column-value sc-detail-trim form-value" name="trim">'.$vehicle['trim']['name'].'</span></div>': '';
		$sc_content .= $vehicle['odometer'] ? '<div class="data-row"><span class="column-title">Odometer:</span><span class="column-value sc-detail-odometer form-value" name="odometer">'.$vehicle['odometer'].'</span></div>': '';
		$sc_content .= $vehicle['exterior_color'] ? '<div class="data-row"><span class="column-title">Exterior:</span><span class="column-value sc-detail-exterior form-value" name="exterior">'.$vehicle['exterior_color'].'</span></div>': '';
		$sc_content .= $vehicle['interior_color'] ? '<div class="data-row"><span class="column-title">Interior:</span><span class="column-value sc-detail-interior form-value" name="interior">'.$vehicle['interior_color'].'</span></div>': '';
		$sc_content .= $vehicle['body_style'] ? '<div class="data-row"><span class="column-title">Body:</span><span class="column-value sc-detail-body form-value" name="body">'.$vehicle['body_style'].'</span></div>': '';
		$sc_content .= $vehicle['engine'] ? '<div class="data-row"><span class="column-title">Engine:</span><span class="column-value sc-detail-engine form-value" name="engine">'.$vehicle['engine'].'</span></div>': '';
		$sc_content .= $vehicle['transmission'] ? '<div class="data-row"><span class="column-title">Trans:</span><span class="column-value sc-detail-transmission form-value" name="transmission">'.$vehicle['transmission'].'</span></div>': '';
		$sc_content .= $vehicle['drive_train'] ? '<div class="data-row"><span class="column-title">Drive Train:</span><span class="column-value sc-detail-drive-train form-value" name="drive_train">'.$vehicle['drive_train'].'</span></div>': '';
		$sc_content .= $vehicle['doors'] ? '<div class="data-row"><span class="column-title">Doors:</span><span class="column-value sc-detail-doors form-value" name="doors">'.$vehicle['doors'].'</span></div>': '';
		//$sc_content .= $vehicle['certified'] ? '<div class="data-row"><span class="column-title">Certified:</span><span class="column-value sc-detail-certified form-value" name="certified">Yes</span></div>': '';
		$fuel = get_fuel_economy_display( $vehicle['fuel_economy'], $company->country_code, 0, $vrs, $vehicle['acode'], TRUE );
		if( $fuel ){
			$sc_content .= '<div class="data-row"><span class="column-title">City:</span><span class="column-value form-value sc-detail-fuel-city" name="fuel_city">'.$fuel['city'].'</span></div>';
			$sc_content .= '<div class="data-row"><span class="column-title">Highway:</span><span class="column-value form-value sc-detail-fuel-highway" name="fuel_highway">'.$fuel['hwy'].'</span></div>';
		}
		$sc_content .= '</div>';
		$sc_content .= '</div>'; //bottom vehicle info -e
		
		$sc_content .= '<div class="bottom-content vehicle_details-content">';
		//populated with Ajax Call
		$sc_content .= '</div>';
		
		$sc_content .= '<div class="bottom-content vehicle_images-content">';
		//populated with Ajax Call
		$sc_content .= '</div>';
		
		$sc_content .= '<div class="bottom-content vehicle_form-content">';
		//populated with Ajax Call
		$sc_content .= '</div>';
		
		$sc_content .= '</div>'; //bottom content -e
		$sc_content .= '</div>'; //bottom two -e
		
		$sc_content .= '</div>'; //bottom container -e
		
		$sc_content .= '</div>'; //wrapper -e
		
		return $sc_content;
	}
	
	function get_sc_detail_vehicle_info_display( $vehicle, $flags ){
		$options = $option_list = $tab_content = $tab_buttons = $content = '';
		
		if( $vehicle['dealer_options'] ) {
			sort($vehicle['dealer_options']);
			foreach( $vehicle['dealer_options'] as $option ) {
				$option_list .= '<li>' . $option . '</li>';
			}
			$options .= '<div class="sc-detail-option-list"><ul>'.$option_list.'</ul></div>';	
		}
		$equipment = $flags['show_eq'] ? display_equipment( $vehicle['standard_equipment']) : '';
		
		$tabs = array( 'Comments' => $vehicle['description'], 'Dealer Options' => $options, 'Standard Equipment' => $equipment );
		$active_class = 'active'; $tab_buttons = '';
		foreach( $tabs as $tab => $data ){
			if( $data ){
				$tab_id = 'tab_'.strtolower(str_replace(' ','_',$tab));
				$tab_buttons .= '<div class="tab-button sc-action-button inner-tab-button '.$tab_id.'-button '.$active_class.'" name="'.$tab_id.'" key="'.$vehicle['vin'].'">'.$tab.'</div>';
				$tab_content .= '<div class="tab-content '.$tab_id.'-content '.$active_class.'">'.$data.'</div>';
				$active_class = '';
			}
		}
		$content .= '<div class="bottom-tab-buttons-wrapper">'.$tab_buttons.'</div>';
		$content .= '<div class="bottom-tab-content-wrapper">'.$tab_content.'</div>';
		
		return $content;
	}
	
	function get_sc_detail_images_display( $vehicle ){
		$content = '';
		if( $vehicle['video'] ){
			$content .= '<div class="sc-detail-video" onclick=\'window.open("'.$vehicle['video'].'","popup","width=640,height=500,scrollbars=no,resizable=yes,toolbar=no,directories=no,location=no,menubar=yes,status=no,left=50,top=125"); return false\'>Play Video ';
			$content .= '<img class="sc-detail-video-img" src="'.cdp_get_image_source().'has_video.png" />';
			$content .= '</div>';
		}
		$content .= '<div class="sc-detail-photo-container">';
		foreach( $vehicle['photos'] as $photo ) {
			$content .= '<a class="sc-lightbox" rel="'.$vehicle['vin'].'-slides" href="'.str_replace('&', '&amp;', $photo).'"><img src="'.str_replace('&', '&amp;', $photo).'" /></a>';
		}
		$content .= '</div>';
		return $content;
	}
	
	function get_sc_slider_view( $vehicle, $class, $company, $options, $url_rule ){
		$content = '';
		$link_params = array( 'year' => $vehicle['year'], 'make' => $vehicle['make']['name'],  'model' => $vehicle['model']['name'], 'state' => $company->state, 'city' => $company->city, 'vin' => $vehicle['vin'] );
		$link = generate_inventory_link($url_rule,$link_params,'','',1);

		$content .= '<div class="'.$class.'-wrapper"><a class="'.$class.'-item" href="'.$link.'">';
			//title
			$content .= '<div class="'.$class.'-title-wrapper">';
				$content .= '<span class="'.$class.'-year">' . $vehicle['year'] . '</span>';
				$content .= '<span class="'.$class.'-make">' . $vehicle['make']['name'] . '</span>';
				$content .= '<span class="'.$class.'-model">' . $vehicle['model']['name'] . '</span>';
			$content .= '</div>';
			//image
			$content .= '<div class="'.$class.'-img-wrapper">';
				$content .= '<img src="'.$vehicle['thumbnail'].'" />';
			$content .= '</div>';
			//price
			$price = get_price_display($vehicle['prices'], $company, $vehicle['saleclass'], $vehicle['vin'], 'short-code', $options['vehicle_management_system' ]['theme']['price_text'], array() );
			$content .= '<div class="'.$class.'-price-wrapper">';
			if ( $price['primary_price'] > 0 ) {
				$content .= '<div class="'.$class.'-price-value"><span class="sc-price-symbol">$</span>' .  number_format( $price['primary_price'] , 0 , '.' , ',' ) . '</div>';
			} else {
				$content .= '<div class="'.$class.'-price-value-text">'.$price['primary_text'].'</div>';
			}
			$content .= '</div>';
		$content .= '</a></div>';
		
		return $content;
	}
?>