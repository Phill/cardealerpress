// inventory Detail

if( jQuery('#inventory-detail').length ){

	jQuery(document).ready(function(){
		
		// Collapse Toggle
		jQuery('.collapse-toggle').click(function(){
			toggle = jQuery(this).attr('name');
			if( jQuery('.collapse-divider.'+toggle).hasClass('collapsed') ){
				jQuery('.collapse-divider.'+toggle).removeClass('collapsed').slideDown('slow');
			} else {
				jQuery('.collapse-divider.'+toggle).addClass('collapsed').slideUp('slow');
			}
		});

		// Detail Slideshow
		jQuery(document).ready(function() {
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
		});

		// Tab Control
		jQuery('.tabs-button').click(function() {
			tab_name = jQuery(this).attr('name');
			jQuery(this).siblings('.active').removeClass('active');
			jQuery(this).parent().parent().find('.tabs-content.active').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.tabs-content-'+tab_name).addClass('active');
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

		// Detail Form buttons
		jQuery('#inventory-schedule').click(function() {

		    var name = jQuery( "#formvehicletestdrive-name" ),
		    email = jQuery( "#formvehicletestdrive-email" ),
		    phone = jQuery( "#formvehicletestdrive-phone" ),
		    comments = jQuery( "#formvehicletestdrive-comments" ),
		    allFields = jQuery( [] ).add( name ).add( email ).add( phone ).add( comments );

		    jQuery('#inventory-schedule-form').dialog({
		        autoOpen: true,
		        height: 500,
		        width: 400,
		        modal: true,
		        buttons: {
		            "Send Inquiry": function() {
		                var bValid = true;
		                allFields.removeClass( "ui-state-error" );
		                bValid = bValid && checkLength( name, "Your Name", 1, 100 );
		                bValid = bValid && checkLength( email, "Your Email", 6, 80 );
		                bValid = bValid && checkLength( phone, "Your Phone Number", 7, 20 );
		                bValid = bValid && checkLength( comments, "Your Comments", 1, 255 );
		                // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		                bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "E-mail validation failed. Please try again." );
		                if ( bValid ) {
		                    jQuery( '#formvehicletestdrive' ).submit();
		                    jQuery( this ).dialog( "close" );
		                }
		            },
		            Cancel: function() {
		                jQuery( this ).dialog( "close" );
		            }
		        },
		        close: function() {
		            allFields.val( "" ).removeClass( "ui-state-error" );
		            tips.text( "" ).removeClass( "ui-state-highlight" );
		        }
		    });

		    return false;

		});

		jQuery('#loan-calculator-button').click(function() {
			if( jQuery(this).siblings('#loan-calculator-data').hasClass('active') ){
				jQuery('#loan-calculator-data').removeClass('active');
			} else {
				jQuery('#loan-calculator-data').addClass('active');
			}
		});
		
	});
}

// inventory Listing
if( jQuery('#inventory-listing').length ) {
	
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
	
	// Quick Links
	jQuery('#inventory-list-sidebar > ul > li .list-sidebar-label').click(function() {
		if(jQuery(this).parent().hasClass('inventory-collapsed')) {
			jQuery(this).parent().removeClass('inventory-collapsed');
			jQuery(this).parent().addClass('inventory-expanded');
		} else {
			jQuery(this).parent().addClass('inventory-collapsed');
			jQuery(this).parent().removeClass('inventory-expanded');
		}
		if( jQuery(this).parent().children('ul').is(":hidden")) {
			jQuery(this).parent().children('ul').slideDown('slow', function() {});
		} else {
			jQuery(this).parent().children('ul').slideUp('slow', function() {});
		}
	});
	// Mobile Click
	jQuery('#list-sidebar-label-mobile').click(function(){
		if( jQuery(this).hasClass('mobile-click') ){
			if(jQuery(this).hasClass('mobile-active')){
				jQuery(this).removeClass('mobile-active');
				jQuery('#inventory-list-sidebar > ul').removeClass('active');
				jQuery(this).text('Refine Your Search');
			} else {
				jQuery(this).addClass('mobile-active');
				jQuery('#inventory-list-sidebar > ul').addClass('active');
			}
		} else {
			jQuery('#inventory-list-sidebar > ul').addClass('active');
			jQuery(this).addClass('mobile-click');
			jQuery(this).addClass('mobile-active');
			jQuery('#inventory-list-sidebar > ul > li .list-sidebar-label').each(function(){
				jQuery(this).click();
			});
			jQuery(this).text('Hide Refined Search');
		}
	});
}


// inventory General

jQuery(document).ready(function(){
	jQuery('#cardealerpress-inventory').parent().addClass('inventory-parentClass');
	
	// Helps Responsive Menu
	if (jQuery('#cardealerpress-inventory').length){
		jQuery('#inventory-quick-links').attr('name','hidden');
		jQuery('#inventory-quick-links > h3').click(function(){
			if (jQuery('#inventory-quick-links').attr('name').match(/hidden/i) != null){
				jQuery('#inventory-quick-links').attr('name','show');
				jQuery('#inventory-quick-links > ul').slideDown();
			} else {
				jQuery('#inventory-quick-links').attr('name','hidden');
				jQuery('#inventory-quick-links > ul').slideUp();
			}
		});
	}

	// AIS iFrame
	var frame = jQuery('<div class="aisframe"><iframe id="ais-iframe" width="785" src="about:blank" height="415" frameborder="0"></iframe></div>');

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
	
	jQuery('.inventory-vehicle .ais-link-js span').click(function(e){
		ais_url = jQuery(e.target).attr('href');
		loadIframe(ais_url);
		jQuery( '.aisframe' ).dialog( 'open' );
		return false;
	});
	
});

function loadIframe( url ) {
		var iframe = jQuery('#ais-iframe');
		if ( iframe.length ) {
				iframe.attr( 'src' , url );
				return false;
		}
		return true;
}

function list_search_field(e){
	e.preventDefault();
	key = encodeURI('search'); value = encodeURI(jQuery('#inventory-search-box').val());
	var kvp = document.location.search.substr(1).split('&');
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
    document.location.search = kvp.join('&'); 
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