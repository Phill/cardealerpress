
	jQuery(document).ready(function() {

		jQuery('#widgets-right').on( 'click', '.vms-color-picker', function(e){
			if( jQuery(e.target).attr('alt') == 'off' ){
				jQuery(e.target).attr('alt','on')
				jQuery(e.target).iris({
					hide: false,
					width: 220,
					palettes: true
				});
			} else {
				jQuery(e.target).iris('toggle');
			}
		});

		jQuery('.widget-vms-search-button').click(function(e){

	 		url = jQuery(e.target).attr('alt') + '/inventory/';
			param = '';

			condition = jQuery(e.target).siblings().find( jQuery('.widget-vms-select-saleclass') ).val();
			make = jQuery(e.target).siblings().find( jQuery('.widget-vms-select-make') ).val();
			model = jQuery(e.target).siblings().find( jQuery('.widget-vms-select-model') ).val();
			trim = jQuery(e.target).siblings().find( jQuery('.widget-vms-select-trim') ).val();
			text = jQuery(e.target).siblings().find( jQuery('.widget-vms-text-input') ).val();

			if( condition ){
				url += cdp_widget_capatilize_word(condition) + '/';
			}

			if( make && make != 'all' ){
				url += cdp_widget_capatilize_word(make) + '/';
			}

			if( model && model != 'all' ){
				url += cdp_widget_capatilize_word(model) + '/';
			}

			if( trim && trim != 'all' ){
				param = '?trim=' + trim;
			}

			if( text ){
				if( param ) {
					param += '&search=' + text;
				} else {
					param = '?search=' + text;
				}
			}

			window.location = url + param;
		});
		
		//
		cdp_widget_preset_load();
		
	});
	
	function cdp_widget_capatilize_word( value ){
		value = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
		    return letter.toUpperCase();
		});
		return value;
	}
	

	function cdp_widget_update_dropdowns( data, widget_id ){
		jQuery.each( data, function( key, items ){
			jQuery('#'+widget_id + ' .widget-vms-select-'+key).children().remove();
			jQuery('#'+widget_id + ' .widget-vms-select-'+key).append( items['display'] );
			if( jQuery.isEmptyObject( items['disabled'] ) ){
				jQuery('#'+widget_id + ' .widget-vms-select-'+key).prop('disabled', false);
			} else {
				jQuery('#'+widget_id + ' .widget-vms-select-'+key).prop('disabled', true);
			}
		});
	}
	
	function cdp_widget_get_dropdown_data( widget_id, filter ){
		
		var data = {};
		jQuery('#'+widget_id+' .vms-select').each( function(){
			data[jQuery(this).attr('name')] = jQuery(this).attr('value');
		});
		widget_data = {'atts': data, 'filter': filter};
		
		cdp_widget_front_ajax_call( 'update_dropdowns', widget_data, widget_id ).done(function(result) {
			//ajax_dropdown = false;
		}).fail(function() {
			alert('Connection Error: Could not retrieve data.');
		});
		//console.info(data);
	}

	function cdp_widget_select_caller( selector, filter ){
		widget = jQuery(selector).parents('.vms-search-box-widget').attr('id');
		if( filter != 'trim' ){
			cdp_widget_get_dropdown_data( widget, filter );
		}
	}

	// AJAX Call
	function cdp_widget_front_ajax_call( fn, params, wrapper_id ){
		return jQuery.ajax({
			url: ajax_path,
			data: {'action': 'cdp_front_ajax_request', 'fn': fn, 'params': params},
			dataType: 'json',
			beforeSend: function(){
				jQuery('#'+wrapper_id+' .widget-vms-search-button').addClass('opacity').text( 'Loading...' );
			},
			complete: function(){

			},
			success: function(data){
				cdp_widget_update_dropdowns(data, wrapper_id);
				jQuery('#'+wrapper_id+' .widget-vms-search-button').removeClass('opacity').text('Search');
			},
			error: function(xhr, status, error) {
				alert('Ajax call failed.');
				alert(error);
			}
		});
	}
	
	function cdp_widget_preset_load(){
		jQuery('.widget-vms-preset-load').each(function(){
			var data = {};
			widget_id = jQuery(this).parents('.vms-search-box-widget').attr('id');
			jQuery('#'+widget_id+' .widget-vms-preset-load').children().each(function(){
				data[jQuery(this).attr('key')] = jQuery(this).text();
			})
			jQuery('#'+widget_id +' .widget-vms-select-saleclass').val( cdp_widget_capatilize_word(data['saleclass']) );
			widget_data = {'atts': data, 'filter': 'saleclass'};
			cdp_widget_front_ajax_call( 'update_dropdowns', widget_data, widget_id ).done(function(result) {
				
			}).fail(function() {
				alert('Connection Error: Could not retrieve data.');
			});
		});
	}
