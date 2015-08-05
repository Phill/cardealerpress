//	inventory Listing JS
if ( jQuery('#inventory-listing').length ) {
	
	jQuery('.inventory_get_gform').click(function(){
		form_name = jQuery(this).attr('name');
		key = jQuery(this).attr('key');
		form_id = jQuery('#inventory-gform-id').attr('form');
	
		if( jQuery('#'+key+' .list-form-wrapper').length ){
			if( jQuery(this).hasClass('active') ){
				jQuery('#'+key+' .list-form-wrapper').remove();
				jQuery(this).removeClass('active');
			} else {
				jQuery('#'+key+' .inventory_get_gform.active').removeClass('active');
				jQuery(this).addClass('active');
			}
		} else {
			jQuery('.inventory_get_gform.active').removeClass('active');
			jQuery('.list-form-wrapper.active').remove();
			jQuery(this).addClass('active');
			var form_data = {};
			jQuery('#'+key+' .form-value').each( function(){
				form_data[jQuery(this).attr('name')] = jQuery(this).text();
			});
	
			data = {'key': key, 'form': form_id, 'title': form_name, 'hooks': form_data};
	
			cdp_front_ajax_call( 'get_gform', data, '.inventory-form-container' ).done(function(result) {
				//jQuery('#'+key).siblings('.ajax-loading-message').removeClass('loading');
			}).fail(function() {
				alert('no good');
			});
		}
		//console.log('FormID: '+form_id+' | Key: '+key+' | Name: '+form_name);
		//console.dir(form_data);
	});

	// inventory Mobile Search
	jQuery('#mobile-show-search').click( function() {
		if( jQuery(this).hasClass('active') ){
			jQuery(this).removeClass('active');
			jQuery(this).text('Show Search Options');
			jQuery('#inventory-search-wrapper').removeClass('mobile-view');			
		} else {
			jQuery(this).addClass('active');
			jQuery(this).text('Hide Search Options');
			jQuery('#inventory-search-wrapper').addClass('mobile-view');
		}
	});
	
	jQuery('input.list-search-value').keypress(function(e) {
    	if(e.which == 13) {
        	//alert('You pressed enter!');
			get_list_input_values(e);
    	}
	});

	// inventory Advanced Search Show
	jQuery('#inventory-advance-show').click(function() {
	    name = jQuery(this).attr('name');
	    if ( name == 'hidden' ) {
	        jQuery('#inventory-search-advance').slideDown();
	        jQuery(this).attr('name', 'active').text('Hide Advanced');
	    } else {
	        jQuery('#inventory-search-advance').slideUp();
	        jQuery(this).attr('name', 'hidden').text('Advanced Search');
	    }
	});

	// inventory default text clear
	jQuery('#inventory-search-box').focus( function() {
		if( jQuery('#inventory-search-box.invalid').length ){
			jQuery(this).removeClass('invalid');
			jQuery(this).val('');
		}
	});
}

// inventory Detail JS
if ( jQuery('#inventory-detail').length ) {
	
	// Video Dialog
	var video_title = jQuery('#title-year').text() + ' ' + jQuery('#title-make').text() + ' ' + jQuery('#title-model').text();
	jQuery('#video-overlay-wrapper-dm').click(function(e) {
		jQuery('#dm-video-wrapper').dialog({
			autoOpen: true,
			dialogClass: "dialog-video-wrapper",
			modal: true,
			resizable: false,
			width: 640,
			height: 520,
			title: video_title
		})
	});

	jQuery('#video-overlay-wrapper').click(function(e) {
		var video_width;
		if( !video_width ){
			video_width = get_video_width();
			video_width = video_width + 35;//Added for dialog padding
		}
		jQuery('#wp-video-shortcode-wrapper').dialog({
			autoOpen: true,
			dialogClass: "dialog-video-wrapper",
			modal: true,
			resizable: false,
			width: video_width,
			title: video_title,
			beforeClose: function( event, ui ){
				jQuery('.mejs-pause > button').click();
			}
		})

		jQuery('.mejs-play > button').click();
	});

	function get_video_width(){
		results = jQuery('#wp-video-shortcode-wrapper > div').width();
		//console.log(results);
		return results;
	}
	
	
	// Loan Calc
	jQuery('#loan-calculator-button').click(function() {
		if( jQuery(this).siblings('#loan-calculator-data').hasClass('active') ){
			jQuery('#loan-calculator-data').removeClass('active');
		} else {
			jQuery('#loan-calculator-data').addClass('active');
		}
	});

	// inventory Show Images
	jQuery('#inventory-nav-button.active').click(function() {
		if( jQuery(this).hasClass('visible') ){
			jQuery('#vehicle-thumbnails').removeClass('show-all');
			jQuery(this).removeClass('visible');
			jQuery(this).text('Show All Images');
		} else {
			jQuery('#vehicle-thumbnails').addClass('show-all');
			jQuery(this).addClass('visible');
			jQuery(this).text('Hide Images');
		}
	});
	
	//Move Photo Button
	jQuery('.tabs-content.tabs-content-img-photo').append( jQuery('#inventory-nav-button') );
	
	// Tab Control
	jQuery('.tabs-button').click(function() {
		tab_name = jQuery(this).attr('name');
		jQuery(this).siblings('.active').removeClass('active');
		jQuery(this).parent().parent().find('.tabs-content.active').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.tabs-content-'+tab_name).addClass('active');
	});

	jQuery(document).ready(function() { // Document Ready
		// inventory Slideshow/Image Cycle
			jQuery('#vehicle-images')
			.cycle({
				slides: '> a',
				fx: 'fade',
				pager: '#vehicle-thumbnails',
				pagerTemplate: '<a href="#"><img src="{{href}}" style="width:70px;height:50px;" /></a>'
			});

			jQuery('#vehicle-images > a')
			.lightbox({
				imageClickClose: false,
				loopImages: true,
				fitToScreen: true,
				scaleImages: true,
				xScale: 1.0,
				yScale: 1.0,
				displayDownloadLink: true
			});


	}); // Document Ready *end

	// Form Buttons
	jQuery('.form-button').click(function(e) {
		form_name = jQuery(e.target).attr('name');
		jQuery('#'+form_name).dialog({
			autoOpen: true,
			dialogClass: "form-wrap",
			modal: true,
			resizable: false,
			width: 320,
			height: 450
		})
	});

}

// inventory General
jQuery(document).ready(function() {

	jQuery('#cardealerpress-inventory').parent().addClass('inventory-parentClass');

	// AIS iFrame
	var frame = jQuery('<div class="aisframe"><iframe width="785" src="about:blank" height="415" frameborder="0"></iframe></div>');

	frame.appendTo( 'body' );

	jQuery( '.aisframe' ).dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		width: 820,
		height: 485,
		open: function( event , ui ) { jQuery( '.ui-widget-overlay').click( function() { jQuery( '.aisframe' ).dialog( 'close' ); } ); },
		title: 'Incentives and Rebates'
	});

	jQuery( '.view-available-rebates > a' ).click(
		function() {
			jQuery( '.aisframe' ).dialog( 'open' );
			return false;
		}
	);
});

function loadIframe( url ) {
		var iframe = jQuery( 'iframe' );
		if ( iframe.length ) {
				iframe.attr( 'src' , url );
				return false;
		}
		return true;
}

function get_list_input_values(e){
	e.preventDefault();
	query = document.location.search;
	jQuery('.list-search-value').each(function(){
		key = jQuery(this).attr('name');
		value = jQuery(this).val();
		if( value && !jQuery(this).hasClass('invalid') ){
			//console.log('Name: '+key+' | Value: '+value );
			query = '?'+add_query_field(query, key, value);
			//console.log(query);
		} else {
			query = '?'+remove_query_field(query, key);
		}
		
	})
	if( query.length == 1 ){
		document.location.search = '';
	} else{
		document.location.search = query;		
	}

}

function add_query_field(query, key, value){
	//e.preventDefault();
	key = encodeURI(key); value = encodeURI(value);
	var kvp = query.substr(1).split('&');
    var i=kvp.length; var x; while(i--) 
    {
		x = kvp[i].split('=');
        if (x[0]==key)
        {
			x[1] = value;
	        kvp[i] = x.join('=');
	        break;
	    }
	}
	if(i<0) {kvp[kvp.length] = [key,value].join('=');}
    return kvp.join('&');
}

function remove_query_field(query, key){
	key = encodeURI(key);
	var kvp = query.substr(1).split('&');
    var i=kvp.length; var x; while(i--) 
    {
		x = kvp[i].split('=');
        if (x[0]==key)
        {
			kvp.splice(i,1)
	        break;
	    }
	}
    return kvp.join('&');
}



function inventory_filter_select( type ) {

	switch( type ) {
		case 'make':
			jQuery('#inventory-models select').val('');
			jQuery('#inventory-trims select').val('');
			break;

		case 'model':
			jQuery('#inventory-trims select').val('');
			break;
	}

	filter_url = build_url();
	window.location = filter_url;

}

function inventory_search_form() {

	form = jQuery('#inventory-search');
	form_url = build_url();

	form.submit(function(e) {
		if ( form_url.length > 1) {
			jQuery(form).attr('action', form_url);
			return true;
		} else {
			return false;
		}
	});

}

function inventory_detail_forms( url, form_id ) {

	form_error = '<span style="border-bottom: 1px solid #000; margin-bottom: 1%;">ERROR - </span>';
	form_errors = '';
	form = '';

	if ( url ) {
		switch ( form_id ) {
			case '0': //Request Information
				form = jQuery('#vehicle-inquiry');
				required_values = get_inventory_detail_form_required_values( form_id );
				form_errors = inventory_detail_form_error_check( required_values );
				jQuery('#vehicle-inquiry-name').val(required_values['First Name'] + ' ' + required_values['Last Name']);
				break;
			case '1': //Test Drive
				form = jQuery('#vehicle-testdrive');
				required_values = get_inventory_detail_form_required_values( form_id );
				form_errors = inventory_detail_form_error_check( required_values );
				jQuery('#vehicle-testdrive-name').val(required_values['First Name'] + ' ' + required_values['Last Name']);
				jQuery('#vehicle-testdrive-timetocall').val( jQuery('#vehicle-testdrive-date').val() + ' - ' + jQuery('#vehicle-testdrive-time').val() );
				break;
		}
	}

	if ( form ) {
		if ( form_errors ) {
			form_error += form_errors;
			form.find('.form-error').css({'display':'block'}).html( form_error );
		} else {
			form.find('.form-error').css({'display':'none'}).html('');
			form.submit(function(e) {
				if ( url.length > 1) {
					jQuery(form).attr('action', url);
					return true;
				} else {
					return false;
				}
			});
		}
	}
}

function get_inventory_detail_form_required_values( id ) {
	obj = {};

	switch ( id ) {
		case '0':
			obj['First Name'] = jQuery('#vehicle-inquiry-f-name').val();
			obj['Last Name'] = jQuery('#vehicle-inquiry-l-name').val();
			obj['Email'] = jQuery('#vehicle-inquiry-email').val();
			obj['Privacy'] = jQuery('#vehicle-inquiry-privacy:checked').val();
			break;
		case '1':
			obj['First Name'] = jQuery('#vehicle-testdrive-f-name').val();
			obj['Last Name'] = jQuery('#vehicle-testdrive-l-name').val();
			obj['Email'] = jQuery('#vehicle-testdrive-email').val();
			obj['Privacy'] = jQuery('#vehicle-testdrive-privacy:checked').val();
			break;
	}

	return obj;
}

function inventory_detail_form_error_check( required ) {

	error_text = '';

	jQuery.each(required, function(key, value) {
		if ( !value ) {
			error_text += '<span>Missing ' + key + ' </span>';
		} else if ( key == 'Email' || key == 'Email2') {
			if ( !isValidEmailAddress( value ) ) {
				error_text += '<span>Invalid Email Address</span>';
			}
		}
	});

	return error_text;

}

function get_search_params() {
	obj = {};

	if ( jQuery('#inventory-saleclass').val() == 'Certified' ) {
		obj['saleclass'] = 'Used';
		obj['certified'] = 'yes';
	} else {
		obj['saleclass'] = jQuery('#inventory-saleclass').val();
		obj['certified'] = '';
	}

	if( jQuery('#inventory-search-box.invalid').length ){
	obj['search'] = '';
	} else {
		obj['search'] = jQuery('#inventory-search-box').val();
	}

	obj['price_from'] = jQuery('#inventory-price-from').val();
	obj['price_to'] = jQuery('#inventory-price-to').val();
	obj['year_from'] = jQuery('#inventory-year-from').val();
	obj['year_to'] = jQuery('#inventory-year-to').val();
	obj['mileage_from'] = jQuery('#inventory-mileage-from').val();
	obj['mileage_to'] = jQuery('#inventory-mileage-to').val();
	obj['vehicleclass'] = jQuery('#inventory-vehicleclass').val();

	return obj;
}

function get_filter_params() {
	obj = {};

	obj['make'] = jQuery('#inventory-makes select').val();
	obj['model'] = jQuery('#inventory-models select').val();
	obj['trim'] = jQuery('#inventory-trims select').val();

	return obj;
}

function get_hidden_params() {
	obj = {};

	obj['rewrite'] = jQuery('#hidden-rewrite').val();
	obj['saleclass'] = jQuery('#hidden-saleclass').val();

	return obj;
}

function build_url() {

	s_params = get_search_params();
	f_params = get_filter_params();
	h_params = get_hidden_params();

	url_text = '';
	url_query = '';
	url_return = '';

	jQuery.each(s_params, function(key, value) { // Search Params
		if ( key != 'saleclass' ) {
			if ( value ) {
				if ( url_query.length > 1 ) {
					url_query += '&'+ key + '=' + value;
				} else {
					url_query = key + '=' + value;
				}
			}
		}
	});

	if ( h_params['rewrite'] ) {
		url_text = '/inventory/' + s_params['saleclass'] + '/';

		if ( f_params['make'] ) { // Filter Params (clean)
			url_text += f_params['make'] + '/';
			if ( f_params['model'] ) {
				url_text += f_params['model'] + '/';
				if  ( f_params['trim'] ) {
					if( url_query.length > 1 ){
						url_query += '&trim=' + f_params['trim'];
					} else {
						url_query = 'trim=' + f_params['trim'];
					}
				}
			}
		}

		if ( url_query.length <= 2 ) {
			url_query = '';
		}

	} else {
		url_text = '?taxonomy=inventory' + '&saleclass=' + s_params['salaclass'];
		if ( f_params['make'] ) { // Filter Params (not clean)
			url_text += '&make=' + f_params['make'];
			if ( f_params['model'] ) {
				url_text += '&model=' + f_params['model'];
				if  ( f_params['trim'] ) {
					url_text += '&trim=' + f_params['trim'];
				}
			}
		}

		if ( url_query.length <= 2 ) {
			url_query = '';
		}
	}

	if( url_query.length > 1 ) {
		url_return = url_text + '?' + url_query;
	} else {
		url_return = url_text;
	}

	return url_return;

}

function move_specs ( reset ) {
    if ( reset == 0 ) {
        jQuery('#inventory-detail-specs').css({'clear':'none','float':'left','margin':'0 0 1% 4.5%','width':'60%'});
        jQuery('.inventory-detail-tab').css({'width':'32%'});
    } else {
        jQuery('#inventory-detail-specs').css({'clear':'both','float':'none','margin':'0 auto 1%','width':'94%'});
        jQuery('.inventory-detail-tab').css({'width':'20%'});
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}

// AJAX Call
function cdp_front_ajax_call( fn, params, wrapper ){
	return jQuery.ajax({
		url: ajax_path,
		data: {'action': 'cdp_front_ajax_request', 'fn': fn, 'params': params},
		dataType: 'json',
		beforeSend: function(){
			//jQuery(wrapper).addClass('saving');
			//jQuery(wrapper).removeClass('not-saved');
		},
		success: function(data){
			if( data['id'].length > 0 ){
				jQuery('#'+data['id']+' .inventory-form-container').append(data['content']);
			}
		},
		complete: function(){
			//jQuery(wrapper).addClass('saved');
			//jQuery(wrapper).removeClass('saving');
			//setTimeout( 'cdp_aftersave("'+wrapper+'")', 500);
		},
		error: function(xhr, status, error) {
			alert('Ajax call failed.');
			alert(error);
		}
	});
}