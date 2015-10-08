jQuery(document).ready(function(){
	jQuery('#cardealerpress-inventory').parent().addClass('inventory-parentClass');
// inventory Listing JS
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
			page_url = window.location.href;
			form_data['page_url'] = page_url;
			data = {'key': key, 'form': form_id, 'title': form_name, 'hooks': form_data};
		
			cdp_front_ajax_call( 'get_gform', data, '.inventory-form-container' ).done(function(result) {
				//jQuery('#'+key).siblings('.ajax-loading-message').removeClass('loading');
			}).fail(function() {
				alert('Error Loading Form.');
			});
		}
		
		//console.log('FormID: '+form_id+' | Key: '+key+' | Name: '+form_name);
		//console.dir(form_data);
	});

	// Sidebar click
	jQuery('.inventory-sidebar-content h4').click( function() {

		s_name = jQuery(this).attr('name');

		if( jQuery(this).attr('class') == 'collapsed'){
			if(typeof Storage !=="undefined"){
				sessionStorage.removeItem('inventory' + s_name);
			}
			jQuery(this).siblings('ul').css({'display':'block'});
			jQuery(this).attr('class','');
		} else {
			if(typeof Storage !=="undefined"){
				sessionStorage.setItem('inventory' + s_name, '1');
			}
			jQuery(this).siblings('ul').css({'display':'none'});
			jQuery(this).attr('class','collapsed');
		}

	});

	jQuery(function(){
		stickyTop = jQuery('#inventory-content-center').offset().top;

		jQuery(window).scroll(function(){
			windowTop = jQuery(window).scrollTop();
			centerWidth = jQuery('#inventory-content-center').width();
			leftMargin = centerWidth / 2;

			if( stickyTop < windowTop ){
				jQuery('#inventory-mobile-search-wrap').css({
					'position':'fixed',
					'top':'0',
					'width': '60%',
					'left':'6%'
				})
			} else {
				jQuery('#inventory-mobile-search-wrap').css({
					'position':'relative',
					'top':'0',
					'width': '60%',
					'left': '0',
					'margin': '0 auto 0'
				})
			}
		});

		leftHeight = jQuery('#inventory-content-left').height();
		centerHeight = jQuery('#inventory-content-center').height();

		// Check sessionStorage
		if(typeof Storage !=="undefined"){
			if (sessionStorage.mobileview){
				jQuery('#inventory-mobile-search-wrap').attr('class','active');
				jQuery('#inventory-content-left').attr('class','mobileview');
				jQuery('#inventory-content-right').attr('class','mobileview');
				jQuery('#inventory-content-left').css({
					'height': leftHeight + 'px',
					'display': 'block'
				})
				if( leftHeight > centerHeight ){
					jQuery('#inventory-content-center').height(leftHeight);
				}
				if(sessionStorage.inventorycondition){
					jQuery('.inventory-sidebar-content.content-new-used > h4').attr('class','collapsed');
					jQuery('.inventory-sidebar-content.content-new-used > h4').siblings('ul').css({'display':'none'});
				}
				if(sessionStorage.inventorystyles){
					jQuery('.inventory-sidebar-content.content-bodystyle > h4').attr('class','collapsed');
					jQuery('.inventory-sidebar-content.content-bodystyle > h4').siblings('ul').css({'display':'none'});
				}
				if(sessionStorage.inventoryvehicles){
					jQuery('.inventory-sidebar-content.content-make-model-trim > h4').attr('class','collapsed');
					jQuery('.inventory-sidebar-content.content-make-model-trim > h4').siblings('ul').css({'display':'none'});
				}
				if(sessionStorage.inventoryprice){
					jQuery('.inventory-sidebar-content.content-price-range > h4').attr('class','collapsed');
					jQuery('.inventory-sidebar-content.content-price-range > h4').siblings('ul').css({'display':'none'});
				}
				jQuery('html, body').animate({
					scrollTop: jQuery('#inventory-content-center').offset().top
				}, 600);
				jQuery('#inventory-mobile-search-wrap #inventory-mobile-search-text').text('Close Search');
			}
		}

		jQuery('#inventory-mobile-search-wrap').click( function(){

			click_name = jQuery(this).attr('class');
			if( click_name == 'inactive' ) {

				if(typeof Storage !=="undefined"){
					sessionStorage.mobileview = '0';
				}

				jQuery('html, body').animate({
					scrollTop: jQuery('#inventory-content-center').offset().top
				}, 600);
				jQuery(this).removeClass('inactive');
				jQuery(this).addClass('active');
				jQuery(this).siblings('#inventory-content-left').addClass('mobileview');
				jQuery(this).siblings('#inventory-content-right').addClass('mobileview');
				jQuery(this).siblings('#inventory-content-left').css({
					'height': leftHeight + 'px',
				})
				jQuery(this).siblings('#inventory-content-left').animate({width: 'toggle'});
				if( leftHeight > centerHeight ){
					jQuery('#inventory-content-center').height(leftHeight);
				}
				jQuery(this).children('#inventory-mobile-search-text').text('Close Search');
			} else {

				if(typeof Storage !=="undefined"){
					sessionStorage.removeItem('mobileview');
				}
				jQuery(this).removeClass('active');
				jQuery(this).addClass('inactive');
				jQuery(this).siblings('#inventory-content-left').animate({width: 'toggle'});
				jQuery(this).siblings('#inventory-content-left').removeClass('mobileview');
				jQuery(this).siblings('#inventory-content-right').removeClass('mobileview');

				if( leftHeight == jQuery('#inventory-content-center').height() ){
					jQuery('#inventory-content-center').height(centerHeight);
				}
				jQuery(this).children('#inventory-mobile-search-text').text('Search');
			}
		});
	});
}

// inventory Detail JS
if ( jQuery('#inventory-detail').length ) {
	
	jQuery('#loan-calculator-button').click(function() {
		if( jQuery(this).siblings('#loan-calculator-data').hasClass('active') ){
			jQuery('#loan-calculator-data').removeClass('active');
		} else {
			jQuery('#loan-calculator-data').addClass('active');
		}
	});
	
	// Collapse Toggle
	jQuery('.collapse-toggle').click(function(){
		toggle = jQuery(this).attr('name');
		if( jQuery('.collapse-divider.'+toggle).hasClass('collapsed') ){
			jQuery('.collapse-divider.'+toggle).removeClass('collapsed').slideDown('slow');
		} else {
			jQuery('.collapse-divider.'+toggle).addClass('collapsed').slideUp('slow');
		}
	});
	
	// Form Buttons
	jQuery('.form-button').click(function(e) {
		form_name = jQuery(e.target).attr('name');
		jQuery('#'+form_name).dialog({
			autoOpen: true,
			dialogClass: "form-wrap",
			modal: true,
			resizable: false,
			width: 360
		})
	});
// Detail Slideshow
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
	

	jQuery(document).ready(function() { // Document Ready


		// inventory adjust Nav height to match img height
		function check_img_height() {
			img_height = jQuery('#vehicle-images img').height();
			nav_height = jQuery('#vehicle-thumbnails').height();

			if (img_height != nav_height){
				jQuery('#vehicle-thumbnails').css({'height':img_height});
				jQuery('#vehicle-images').css({'height':img_height});
			}

			//setTimeout(check_img_height, 500);
		}
		//check_img_height();

		// Tab Control
		jQuery('.tabs-button').click(function() {
			tab_name = jQuery(this).attr('name');
			jQuery(this).siblings('.active').removeClass('active');
			jQuery(this).parent().parent().find('.tabs-content.active').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.tabs-content-'+tab_name).addClass('active');
		});
	
	}); // Document Ready *end

	// Show hidden form on click
	jQuery('.inventory-show-form').click(function() {
		jQuery('#vehicle-inquiry-subpre-hidden').val( jQuery(this).attr('name') );
		jQuery('.inventory-hidden-form').dialog({
			dialogClass: 'inventory-dialog-form',
			autoOpen: true,
			height: 400,
			width: 300,
			modal: true,
			resizable: false,
			draggable: false,
			title: jQuery(this).attr('name')
		});

		return false;
	});
	
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
			},
			open: function( event, ui ){
				jQuery('#wp-video-shortcode-wrapper .wp-video-shortcode').css({'height':'360px','width':'640px'});
				jQuery('#wp-video-shortcode-wrapper .mejs-overlay-play').css({'height':'329px','width':'640px'});
			}
		})
		jQuery('.mejs-play > button').click();
	});

	function get_video_width(){
		results = jQuery('#wp-video-shortcode-wrapper > div').width();
		//console.log(results);
		return results;
	}
}

});

// inventory General
jQuery(document).ready(function() {

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

	// Clear Default Form Values
	jQuery( '.inventory-form-full input' ).focus(
		function() {
			if ( jQuery(this).attr('alt') == 'empty' ) {
				jQuery(this).attr('alt','');
				jQuery(this).attr('value','');
				jQuery(this).css({'color':'#000'});
			}
		}
	)
	jQuery( '.inventory-form-full textarea' ).focus(
		function() {
			if ( jQuery(this).attr('alt') == 'empty' ) {
				jQuery(this).attr('alt','');
				jQuery(this).attr('value','');
				jQuery(this).css({'color':'#000'});
			}
		}
	)
});

function loadIframe( url ) {
		var iframe = jQuery( 'iframe' );
		if ( iframe.length ) {
				iframe.attr( 'src' , url );
				return false;
		}
		return true;
}

function inventory_process_forms( url, form_id ) {

	form_error = '<span style="border-bottom: 1px solid #000; margin-bottom: 1%;"> - ERROR - </span>';
	form_errors = '';
	form = '';

	if ( url ) {
		switch ( form_id ) {
			case '0': //Request Information
				form = jQuery('#vehicle-inquiry');
				required_values = get_inventory_form_required_values( form_id );
				form_errors = inventory_form_error_check( required_values );
				checkbox_string = inventory_build_checkbox_string();
				if ( checkbox_string != '' ) {
					jQuery('#vehicle-inquiry-comments').val(checkbox_string + "\n" + jQuery('#vehicle-inquiry-form-comments').val());
				} else {
					jQuery('#vehicle-inquiry-comments').val(jQuery('#vehicle-inquiry-form-comments').val());
				}
				jQuery('#vehicle-inquiry-name').val(required_values['First Name'] + ' ' + required_values['Last Name']);
				break;
			case '1': //Test Drive
				form = jQuery('#vehicle-testdrive');
				required_values = get_dolphin_detail_form_required_values( form_id );
				form_errors = dolphin_detail_form_error_check( required_values );
				jQuery('#vehicle-testdrive-name').val(required_values['First Name'] + ' ' + required_values['Last Name']);
				jQuery('#vehicle-testdrive-timetocall').val( jQuery('#vehicle-testdrive-date').val() + ' - ' + jQuery('#vehicle-testdrive-time').val() );
				break;
			case '2': //Tell Friend
				form = jQuery('#form-friend');
				required_values = get_dolphin_detail_form_required_values( form_id );
				form_errors = dolphin_detail_form_error_check( required_values );
				jQuery('#form-friend-from-name').val(required_values['First Name'] + ' ' + jQuery('#friend-from-l-name').val() );
				jQuery('#form-friend-name').val(required_values['Friend First Name'] + ' ' + jQuery('#friend-to-l-name').val() );
				break;
			case '3': //Request Hidden Form Information
				form = jQuery('#vehicle-inquiry-hidden');
				required_values = get_inventory_form_required_values( form_id );
				form_errors = inventory_form_error_check( required_values );
				jQuery('#vehicle-inquiry-subject-hidden').val( jQuery('#vehicle-inquiry-subpre-hidden').val() + ' - ' + jQuery('#vehicle-inquiry-subpost-hidden').val() );
				jQuery('#vehicle-inquiry-name-hidden').val(required_values['First Name'] + ' ' + required_values['Last Name']);
				break;
		}
	}

	if ( form ) {
		if ( form_errors ) {
			form_error += form_errors;
			form.find('.form-error').css({'display':'block'}).html( form_error );
			return false;
		} else {
			form.find('.form-error').css({'display':'none'}).html('');
			if( form_id == 3 ){
				jQuery('.inventory-hidden-form').dialog( "close" );
			}

			if ( url.length > 1) {
				jQuery(form).attr('action', url);
				return true;
			} else {
				return false;
			}

		}
	}
}

function get_inventory_form_required_values( id ) {
	obj = {};

	switch ( id ) {
		case '0':
			if ( jQuery('#vehicle-inquiry-f-name').attr('alt') == 'empty' ){
				obj['First Name'] = '';
			} else {
				obj['First Name'] = jQuery('#vehicle-inquiry-f-name').val();
			}
			if ( jQuery('#vehicle-inquiry-l-name').attr('alt') == 'empty' ){
				obj['Last Name'] = '';
			} else {
				obj['Last Name'] = jQuery('#vehicle-inquiry-l-name').val();
			}
			if ( jQuery('#vehicle-inquiry-email').attr('alt') == 'empty' ){
				obj['Email'] = '';
			} else {
				obj['Email'] = jQuery('#vehicle-inquiry-email').val();
			}
			obj['Privacy'] = jQuery('#vehicle-inquiry-privacy:checked').val();
			break;
		case '1':
			obj['First Name'] = jQuery('#vehicle-testdrive-f-name').val();
			obj['Last Name'] = jQuery('#vehicle-testdrive-l-name').val();
			obj['Email'] = jQuery('#vehicle-testdrive-email').val();
			obj['Privacy'] = jQuery('#vehicle-testdrive-privacy:checked').val();
			break;
		case '2':
			obj['First Name'] = jQuery('#friend-from-f-name').val();
			obj['Friend First Name'] = jQuery('#friend-to-f-name').val();
			obj['Email'] = jQuery('#friend-from-email').val();
			obj['Email2'] = jQuery('#friend-to-email').val();
			obj['Privacy'] = jQuery('#friend-privacy:checked').val();
			break;
		case '3':
			if ( jQuery('#vehicle-inquiry-f-name-hidden').attr('alt') == 'empty' ){
				obj['First Name'] = '';
			} else {
				obj['First Name'] = jQuery('#vehicle-inquiry-f-name-hidden').val();
			}
			if ( jQuery('#vehicle-inquiry-l-name-hidden').attr('alt') == 'empty' ){
				obj['Last Name'] = '';
			} else {
				obj['Last Name'] = jQuery('#vehicle-inquiry-l-name-hidden').val();
			}
			if ( jQuery('#vehicle-inquiry-email-hidden').attr('alt') == 'empty' ){
				obj['Email'] = '';
			} else {
				obj['Email'] = jQuery('#vehicle-inquiry-email-hidden').val();
			}
			obj['Privacy'] = jQuery('#vehicle-inquiry-privacy-hidden:checked').val();
			break;
	}

	return obj;
}

// AJAX Call
function cdp_front_ajax_call( fn, params, wrapper ){
	return jQuery.ajax({
		url: ajax_path,
		data: {'action': 'cdp_front_ajax_request', 'fn': fn, 'params': params},
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(data){
			if( data['id'].length > 0 ){
				jQuery('#'+data['id']+' .inventory-form-container').append(data['content']);
			}
		},
		complete: function(){
		},
		error: function(xhr, status, error) {
			//alert('Ajax call failed.');
			//alert(error);
		}
	});
}

function inventory_form_error_check( required ) {

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

function inventory_build_checkbox_string() {

	checkbox_text = '';
	jQuery('.inventory-form-top-checkboxes .inventory-checkbox:checked').each(
		function() {
			checkbox_temp = jQuery(this).attr('name');
			checkbox_temp = checkbox_temp.replace('inventory-checkbox-','').replace('-', ' ');

			checkbox_text += checkbox_temp + "\n";
		}
	);
	if ( checkbox_text != '' ){
		checkbox_text = 'Selected Checkboxes: ' + "\n" + "\n" + checkbox_text;
	}

	return checkbox_text;
}

function video_popup(url , title) {
	if (! window.focus) return true;
	var href;
	if (typeof(url) == 'string') {
		href=url;
	} else {
		href=url.href;
		window.open(href, title, 'width=640,height=480,scrollbars=no');
		return false;
	}
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	return pattern.test(emailAddress);
}
