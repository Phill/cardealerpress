	// Trigger Save on Enter keypress
	jQuery('#cdp-content-wrapper').on( 'keypress', '.cdp-input', function(e) {
		if(e.which == 13) {
			jQuery(e.target).blur();
		}
	});

	// Controls tabs
	jQuery('.tab-button').click(function() {
		button_id = jQuery(this).attr('id');
		tag = jQuery(this).attr('tag');
		if( tag ){
			jQuery('.view-wrapper.active').removeClass('active');
			jQuery('#'+jQuery('#'+tag).text()+'-view-wrapper').addClass('active');
		}
		jQuery(this).siblings('.active').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent().parent().find('.tab-content.active').removeClass('active');
		jQuery('.'+button_id).addClass('active');
	});
	// Controls inner tabs
	jQuery('.inner-tab-button').click(function() {
		button_id = jQuery(this).attr('id');
		jQuery(this).siblings('.active').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parents('#content-loan').find('.tab-inner-content.active').removeClass('active');
		jQuery('.'+button_id).addClass('active');
	});
	
	// Input Triggers
	jQuery('#cdp-content-wrapper').on( 'input', '.cdp-input', function(e) {
		jQuery(e.target).addClass('value-updated');
		jQuery('#cdp-content-wrapper').addClass('not-saved');
	})
	jQuery('#cdp-content-wrapper').on( 'change', '.cdp-input', function(e) {
		jQuery(e.target).addClass('value-updated');
		if( jQuery(e.target).attr('type') == 'checkbox' && !jQuery(e.target).is(':checked') ){
			jQuery(e.target).val('');
		} else if ( jQuery(e.target).attr('type') == 'checkbox' && jQuery(e.target).is(':checked') ){
			jQuery(e.target).val('on');
		}
		jQuery('#cdp-content-wrapper').addClass('not-saved');
	})
	
	// Input change
	jQuery('#cdp-content-wrapper').on( 'focusout', '.cdp-input', function(e) {
		if( jQuery(e.target).hasClass('value-updated') ){
			jQuery(e.target).removeClass('value-updated');
			tag = jQuery(e.target).attr('tag');
			extra = jQuery(e.target).attr('extra');
			data = {'path': jQuery(e.target).attr('name'), 'value': jQuery(e.target).val()};
			
			cdp_ajax_call( 'saveAdminSettings', data, '#cdp-content-wrapper' ).done(function(result) {
				if( tag ){
					jQuery('#view-'+tag).remove();
					jQuery('#ajax-'+tag).addClass('loading');
					data = {'id': 'content-'+tag};
					cdp_ajax_call( 'get'+tag, data, '#cdp-content-wrapper' ).done(function(result) {
						jQuery('#ajax-'+tag).removeClass('loading');
					}).fail(function() {
					    alert('Data Note Saved');
					});
				}
				
				if( extra ){
					location.reload();
				}
			}).fail(function() {
			    alert('Data Note Saved');
			});
		}
	})
	
	// AddTable Row
	jQuery('.add-row-button').click(function(){
		key = jQuery(this).attr('tag');
		tag = 'addTableRow';
		data = {'id': key};
		jQuery('#'+key).children().remove();
		jQuery('#'+key).siblings('.ajax-loading-message').addClass('loading');
		cdp_ajax_call( tag, data, '' ).done(function(result) {
			jQuery('#'+key).siblings('.ajax-loading-message').removeClass('loading');
		}).fail(function() {
		    alert('Data Not Added');
		});
	});
	
	// RemoveTable Row
	jQuery('.inner-table-content').on( 'click', 'div.remove', function(e){
		key = jQuery(e.target).parent().parent().parent().attr('ID');
		tag = 'removeTableRow';
		value = jQuery(e.target).attr('tag');
		data = {'id': key, 'value': value};
		jQuery('#'+key).children().remove();
		jQuery('#'+key).siblings('.ajax-loading-message').addClass('loading');
		cdp_ajax_call( tag, data, '' ).done(function(result) {
			jQuery('#'+key).siblings('.ajax-loading-message').removeClass('loading');
		}).fail(function() {
		    alert('Data Not Removed');
		});
	});
	
	// Generate Keyword Pot
	jQuery('.generate-keyword-pot').click(function(){
		key = jQuery(this).attr('tag');
		tag = 'generateKeywordPot';
		data = {'id': key};
		jQuery('#'+key).text('');
		jQuery('#'+key).siblings('.ajax-loading-message').addClass('loading');
		cdp_ajax_call( tag, data, '' ).done(function(result) {
			jQuery('#'+key).siblings('.ajax-loading-message').removeClass('loading');
		}).fail(function() {
		    alert('Data Not Added');
		});
	});
	
	// Show Inner Table Row Content
	jQuery('.inner-table-content').on( 'click', '.inner-row-label', function(e){
		if( jQuery(e.target).parent().siblings('.inner-row-content').hasClass('active') ){
			jQuery(e.target).parent().siblings('.inner-row-content').removeClass('active');
		} else {
			jQuery(e.target).parent().siblings('.inner-row-content').addClass('active');
		}
	});
	
	// Call Media Upload | tags
	jQuery('.inner-table-content').on( 'click', '.custom_media_upload', function (e)  {
    	e.preventDefault();
		media_id = e.target.id;

    	var custom_uploader = wp.media({
    	    title: 'Upload Icon',
			width: 200,
			height: 200,
    	    button: {
    	        text: 'Apply Icon'
    	    },
    	    multiple: false  // Set this to true to allow multiple files to be selected
    	})
    	.on('select', function() {
    	    var attachment = custom_uploader.state().get('selection').first().toJSON();
    	    jQuery('.custom_media_image.'+media_id).attr('src', attachment.url);
    	    jQuery('.custom_media_url.'+media_id).val(attachment.url).change().select();
    	})
    	.open();
	});
	
	// Call Media Upload | Default No Image
	jQuery('.custom_media_upload_default_image').click( function(e){
    	e.preventDefault();
		media_id = jQuery(this).attr('id');

    	var custom_uploader = wp.media({
    	    title: 'Upload Default No Image',
			width: 200,
			height: 200,
    	    button: {
    	        text: 'Default No Image'
    	    },
    	    multiple: false  // Set this to true to allow multiple files to be selected
    	})
    	.on('select', function() {
    	    var attachment = custom_uploader.state().get('selection').first().toJSON();
    	    jQuery('.custom_media_url.'+media_id).val(attachment.url).change().select();
    	})
    	.open();
	});
	
	// Display MSW
	jQuery('.msw-button').click(function(){
		key = jQuery(this).attr('name');
		tag = jQuery(this).attr('tag');
		if( jQuery(this).hasClass('inactive') ){
			switch(tag){
				case 'getModelsVRS':
					alert('At least one Make must be selected, before being able to select available Models.')
					break;
			}
			return;
		}
		filter = jQuery(this).attr('filter');
		data = {'id': key, 'filter': filter};
		if( jQuery('#'+key).hasClass('active') ){
			jQuery('#'+key).removeClass('active');
		} else {
			jQuery('#'+key).addClass('active');
			if( !jQuery('#'+key+' .msw-wrapper').length ){
				jQuery('#'+key).find('.ajax-loading-message').addClass('loading');
				cdp_ajax_call( tag, data, '' ).done(function(result) {
					jQuery('#'+key).find('.ajax-loading-message').removeClass('loading');
				}).fail(function() {
				    alert('Data Note Saved');
				});
			}
		}
	});
	
	// MSW Controls
	jQuery('.msw-view-wrapper').on( 'click', '.msw-wrapper', function (e){
		if( jQuery(e.target).attr('name') ){
			path = jQuery(this).attr('name');
			tag = jQuery(this).attr('tag');
			key = jQuery(e.target).attr('class');
			item = jQuery(e.target).attr('name');
			element = jQuery(e.target);
			var value = [];
			jQuery('.msw-wrapper.'+tag+' ul.msw-included > li').each(function(){
				value.push(jQuery(this).attr('name'));
			});

			switch(key){
				case 'msw-add':
					value.push(item);
					jQuery('.msw-wrapper.'+tag+' ul.msw-included').append(element);
					jQuery(element).removeClass('msw-add').addClass('msw-remove');
					break;
				case 'msw-remove':
					if( jQuery.inArray(item, value) !== -1 ){
						value.splice( jQuery.inArray(item, value), 1);
						jQuery('.msw-wrapper.'+tag+' ul.msw-available').append(element);
					}
					jQuery(element).removeClass('msw-remove').addClass('msw-add');
					break;
				case 'msw-add-all':
					jQuery('.msw-wrapper.'+tag+' ul.msw-available > li').each(function(){
						value.push(jQuery(this).attr('name'));
						jQuery('.msw-wrapper.'+tag+' ul.msw-included').append(jQuery(this));
						jQuery(this).removeClass('msw-add').addClass('msw-remove');
					});
					break;
				case 'msw-remove-all':
					jQuery('.msw-wrapper.'+tag+' ul.msw-included > li').each(function(){
						jQuery('.msw-wrapper.'+tag+' ul.msw-available').append(jQuery(this));
						jQuery(this).removeClass('msw-remove').addClass('msw-add');
					});
					value = [];
					break;
			}
			data = {'path': path, 'value': value };
			cdp_ajax_call( 'saveAdminSettings', data, '#cdp-content-wrapper' ).done(function(result) {

			}).fail(function() {
			    alert('Data Note Saved...');
			});
			//alert('Path: '+path+' | Tag: '+tag+' | Key: '+key+' | Item: '+item+' | Value: '+value);
			//console.log('Path: '+path+' | Tag: '+tag+' | Key: '+key+' | Item: '+item+' | Value: '+value);
			//console.dir(data);
		}
	});
	jQuery('.msw-view-wrapper').on( 'click', '.msw-window-title', function (e){
		if( jQuery(e.target).siblings('.msw-data-wrapper').hasClass('active') ){
			jQuery(e.target).siblings('.msw-data-wrapper').removeClass('active');
		} else {
			jQuery(e.target).siblings('.msw-data-wrapper').addClass('active');
		}
	});
	
	//Refresh Button
	jQuery('img.refresh-button').click( function(e){
		tag = jQuery(e.target).attr('tag');
		jQuery('.'+tag+' .msw-wrapper' ).remove();
		jQuery('.'+tag).removeClass('active');
	})
	
	// AJAX Call
	function cdp_ajax_call( fn, params, wrapper ){
		return jQuery.ajax({
			url: ajax_path,
			data: {'action': 'cdp_admin_handle_request', 'fn': fn, 'params': params},
			dataType: 'json',
			beforeSend: function(){
				jQuery(wrapper).addClass('saving');
				jQuery(wrapper).removeClass('not-saved');
			},
			success: function(data){
				if( data['id'].length > 0 ){
					jQuery('#'+data['id']).append(data['content']);
				}
			},
			complete: function(){
				jQuery(wrapper).addClass('saved');
				jQuery(wrapper).removeClass('saving');
				setTimeout( 'cdp_aftersave("'+wrapper+'")', 500);
			},
			error: function(xhr, status, error) {
				alert('Ajax call failed.');
				alert(error);
			}
		});
	}
	
	function cdp_aftersave(wrapper){
		if( jQuery(wrapper).hasClass('saved')){
			jQuery(wrapper).removeClass('saved');
		}
	}
	
	//Uninstall
	jQuery('#cdp-uninstall-button').click(function(){
		jQuery('#cdp-uninstall-dialog').slideDown();
		jQuery('#cdp-uninstall-button').css({'display':'none'});
	});
	
	jQuery('#cdp-uninstall-yes').click( function(){
		var target_url = jQuery('#cdp-uninstall-button').attr('target');
		
		cdp_ajax_call( 'runCleanUninstall', '', '' ).done(function(result) {
			window.location = target_url;
		}).fail(function() {
		    alert('Was not able to clean uninstall.');
		});
	})
	
	jQuery('#cdp-uninstall-cancel').click( function(){
		jQuery('#cdp-uninstall-dialog').slideUp();
		jQuery('#cdp-uninstall-button').css({'display':'block'});
	})