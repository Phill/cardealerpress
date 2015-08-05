
	if( jQuery('#inventory-listing').length){
		
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

		// inventory Search
		jQuery('#inventory-search-submit').click( function() {
			//inventory_search_form( 'false' );
		});
		
		jQuery('input.list-search-value').keypress(function(e) {
	    	if(e.which == 13) {
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
	}

	if( jQuery('#inventory-detail').length){
		// inventory Slideshow
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

		// inventory Tab Control
		jQuery('.tabs-button').click(function() {
			tab_name = jQuery(this).attr('name');
			jQuery(this).siblings('.active').removeClass('active');
			jQuery(this).parent().parent().find('.tabs-content.active').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.tabs-content-'+tab_name).addClass('active');
		});

		// inventory Form
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

		// inventory Caclulator
		jQuery('#loan-calculator-button').click(function(e) {
			/*form_name = jQuery(e.target).attr('name');
			jQuery('#loan-calculator').dialog({
				autoOpen: true,
				dialogClass: "dialog-loan-wrapper",
				title: "Loan Calculator",
				modal: true,
				resizable: false,
				width: 320,
				height: 500
			})*/
			if( jQuery(e.target).siblings('#loan-calculator-data.active').length ){
				jQuery('#loan-calculator-data').removeClass('active');
			} else {
				jQuery('#loan-calculator-data').addClass('active');
			}
		});

		// Video Dialog
		var video_title = jQuery('#top-year').text() + ' ' + jQuery('#top-make').text() + ' ' + jQuery('#top-model').text();
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
			return results;
		}

	}

	jQuery(document).ready(function(){
		jQuery('#cardealerpress-inventory').parent().addClass('inventory-parentClass');
		
		// AIS iFrame
		var frame = jQuery('<div class="aisframe"><iframe width="785" src="about:blank" height="415" frameborder="0"></iframe></div>');

		frame.appendTo( 'body' );

		jQuery( '.aisframe' ).dialog({
			autoOpen: false,
			dialogClass: "dialog-ais-wrapper",
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