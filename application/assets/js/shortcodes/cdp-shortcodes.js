//Button Click - regular
jQuery('#sc-detail-container').on( 'click', '.sc-action-button', function(e){
	key = jQuery(e.target).attr('key');
	display = jQuery(e.target).attr('name');
	if( jQuery(e.target).hasClass('inner-button') ){
		jQuery('#'+key+' .bottom-button.active').removeClass('active');
		jQuery('#'+key+' .bottom-content.active').removeClass('active');
		jQuery('#'+key+' .'+display+'-button').addClass('active');
		jQuery('#'+key+' .'+display+'-content').addClass('active');
	} else if( jQuery(e.target).hasClass('inner-tab-button') ){
		jQuery('#'+key+' .tab-button.active').removeClass('active');
		jQuery('#'+key+' .tab-content.active').removeClass('active');
		jQuery('#'+key+' .'+display+'-button').addClass('active');
		jQuery('#'+key+' .'+display+'-content').addClass('active');
	} else {
		if( jQuery('#'+key).hasClass('active') ){
			jQuery('#'+key).removeClass('active');
			jQuery('#'+key+' .sc-detail-bottom-container').removeClass('active');
			jQuery(e.target).text('Show Details');
		} else {
			jQuery('#'+key).addClass('active').addClass('viewed');
			jQuery('#'+key+' .sc-detail-bottom-container').addClass('active');
			jQuery('#'+key+' .'+display+'-button').addClass('active');
			jQuery('#'+key+' .'+display+'-content').addClass('active');
			jQuery(e.target).text('Hide Details');
			jQuery('html,body').animate({ scrollTop: jQuery('#'+key).offset().top - 100 }, 1000);
			//alert( jQuery('#'+key).offset().top );
		}
	}
});

//Button Click - ajax
jQuery('#sc-detail-container').on( 'click', '.sc-action-ajax', function(e){
	key = jQuery(e.target).attr('key');
	display = jQuery(e.target).attr('name');
	tag = jQuery(e.target).attr('tag');
	container = '#'+key+' .'+display+'-content';
	
	data = {'key': key, 'tag': tag};
	
	jQuery('#'+key+' .bottom-button.active').removeClass('active');
	jQuery('#'+key+' .bottom-content.active').removeClass('active');
	jQuery('#'+key+' .'+display+'-button').addClass('active');
	jQuery('#'+key+' .'+display+'-content').addClass('active');

	if( jQuery('#'+key+' .'+display+'-content').hasClass('loaded') ){
		//Do Nothing already loaded
	} else {
		cdp_front_ajax_call( 'get_vehicle_details', data, container ).done(function(result) {
			jQuery('#'+key+' .'+display+'-content').addClass('loaded');
			cdp_call_lightbox();
		}).fail(function() {
			alert('Connection Error: Could not retrieve data.');
		});	
	}
});

//Form Call
jQuery('#sc-detail-container').on( 'click', '.sc-action-form', function(e){
	key = jQuery(e.target).attr('key');
	display = jQuery(e.target).attr('name');
	container = '#'+key+' .'+display+'-content';
	form_id = jQuery('#sc-detail-form-id').attr('form');
	
	var form_data = {};
	jQuery('#'+key+' .form-value').each( function(){
		form_data[jQuery(this).attr('name')] = jQuery(this).text();
	});
	
	data = {'key': key, 'form': form_id, 'hooks': form_data};
	
	if( jQuery(e.target).hasClass('sc-detail-button') ){
		jQuery('#'+key).addClass('active')
		jQuery('#'+key+' .sc-detail-bottom-container').addClass('active');
		jQuery(e.target).siblings('.show-details').text('Hide Details');
	}
	
	jQuery('#'+key+' .bottom-button.active').removeClass('active');
	jQuery('#'+key+' .bottom-content.active').removeClass('active');
	jQuery('#'+key+' .'+display+'-button').addClass('active');
	jQuery('#'+key+' .'+display+'-content').addClass('active');
	jQuery('#'+key+' .'+display+'-content .list-form-wrapper').remove();
	
	cdp_front_ajax_call( 'get_gform', data, container ).done(function(result) {

	}).fail(function() {
		alert('Connection Error: Could not retrieve data.');
	});	
	
});

//Mobile Action
jQuery('#mobile-action-button').click( function() {
	if( jQuery('#sc-detail-mobile-wrapper').hasClass('active') ){
		jQuery('#sc-detail-mobile-wrapper').removeClass('active');
		jQuery('#sc-detail-search-container .sc-search-item').removeClass('mobile');
	} else {
		jQuery('#sc-detail-mobile-wrapper').addClass('active');
		jQuery('#sc-detail-search-container .sc-search-item').addClass('mobile');
	}
})

function cdp_ajax_loader_display(){
	div_el = document.createElement('div');
	jQuery(div_el).addClass('ajax-loader').text('Getting requested data.');
	img_el = document.createElement('img');
	jQuery(img_el).attr('src',cdp_object.img_src+'spinner.gif');
	
	element = jQuery(div_el).append(img_el);
	return element;
}

// AJAX Call
function cdp_front_ajax_call( fn, params, wrapper ){
	return jQuery.ajax({
		//url: ajax_path,
		url: cdp_object.ajax_script,
		data: {'action': 'cdp_front_ajax_request', 'fn': fn, 'params': params},
		dataType: 'json',
		beforeSend: function(){
			jQuery(wrapper).append( cdp_ajax_loader_display() );
		},
		complete: function(){

		},
		success: function(data){
			if( !ajax_dropdown ){
				jQuery(wrapper).append(data['content']);
			} else {
				cdp_update_dropdowns(data);
			}
			jQuery('.ajax-loader').remove();

		},
		error: function(xhr, status, error) {
			alert('Ajax call failed.');
			alert(error);
		}
	});
}

function cdp_call_lightbox(){
	jQuery('#sc-detail-container .sc-lightbox').lightbox({
		imageClickClose: false,
		loopImages: true,
		fitToScreen: true,
		scaleImages: true,
		xScale: 1.0,
		yScale: 1.0,
		displayDownloadLink: true
	});
}

function cdp_get_ajax_loader_data(){
	var data = {};
	jQuery('#cdp-ajax-loader').children().each( function(){
		data[jQuery(this).attr('key')] = jQuery(this).text();
	});
	return data;
}

function cdp_get_vehicles(){
	container = document.getElementById('sc-detail-container');
	var ajax_data = cdp_get_ajax_loader_data();

	ajax_data['page'] = ajax_page;

	data = {'atts': ajax_data};
	cdp_front_ajax_call( 'get_more_vehicles', data, container ).done(function(result) {
		ajax_loading = false;
		ajax_page++;
	}).fail(function() {
		alert('Connection Error: Could not retrieve data.');
	});
}

function cdp_update_ajax_params( value, key ){
	var add_param = true;
	jQuery('#cdp-ajax-loader').children().each( function(){
		if( jQuery(this).attr('key') == key ){ add_param = false; jQuery(this).text( value ); }
	})
	if( add_param ){ jQuery('#cdp-ajax-loader').append('<div key="'+key+'">'+value+'</div>'); }
}

function cdp_update_dropdowns( data ){
	jQuery.each( data, function( key, items ){
		cdp_update_ajax_params( items['att'], key);
		jQuery('#sc-search-'+key).children().remove();
		jQuery('#sc-search-'+key).append( items['display'] );
		if( jQuery.isEmptyObject( items['disabled'] ) ){
			jQuery('#sc-search-'+key).prop('disabled', false);
		} else {
			jQuery('#sc-search-'+key).prop('disabled', true);
		}
	});
}

function cdp_get_dropdown_data( filter ){
	container = document.getElementById('sc-detail-container');
	ajax_dropdown = true;
	data = {'atts': cdp_get_ajax_loader_data(), 'filter': filter};
	cdp_front_ajax_call( 'update_dropdowns', data, container ).done(function(result) {
		ajax_dropdown = false;
		cdp_get_vehicles();
	}).fail(function() {
		alert('Connection Error: Could not retrieve data.');
	});
}

function cdp_vehicle_caller( val, filter ){
	
	cdp_update_ajax_params( val, filter );
	
	ajax_loading = true;
	ajax_page = 1;
	jQuery('#sc-detail-container .sc-detail-item').remove();
	jQuery('#cdp-ajax-end').remove();
	if( filter != 'trim' ){
		cdp_get_dropdown_data( filter );
	} else {
		cdp_get_vehicles();
	}
}

function cdp_yHandler(){

	var container = document.getElementById('sc-detail-container');
	var contentTop = jQuery(container).offset().top + 25;
	var contentHeight = container.offsetHeight + 350;
	var yOffset = window.pageYOffset; 
	var windowY = yOffset + window.innerHeight;
	
	if( jQuery('#sc-detail-search-container').length ){
		if( contentTop <= yOffset ){
			jQuery('#sc-detail-search-container').addClass('scroll');
		} else {
			jQuery('#sc-detail-search-container').removeClass('scroll');
		}
	}
	if(windowY >= contentHeight && !ajax_loading && !jQuery('#cdp-ajax-end').length ){
		// Ajax call to get more dynamic data goes here
		ajax_loading = true;
		cdp_get_vehicles( container );
	}
	
	var status = document.getElementById('cdp-ajax-status');
	status.innerHTML = contentTop+" | "+yOffset;
}

var ajax_loading = false;
var ajax_page = 2;
var ajax_dropdown = false;
window.onscroll = cdp_yHandler;