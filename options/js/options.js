jQuery(document).ready(function($){
	
	//(un)fold options in a checkbox-group
	jQuery('.fld').click(function() {
  	var $fold='.f_'+this.id;
  	$($fold).slideToggle('normal', "swing");
	});

	// Tab the first item or the saved one
	if(jQuery('#last_tab').val() == ''){
		jQuery('.simple-options-group-tab:first').fadeIn();
		jQuery('#simple-options-group-menu li:first').addClass('active');
	}else{
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').fadeIn();
		jQuery('#'+tabid+'_section_group_li').addClass('active');
	}
	
	// Default button clicked
	jQuery('input[name="'+sof_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(sof_opts.reset_confirm)){
			return false;
		}
	});
	
	jQuery('.simple-options-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
		jQuery('.simple-options-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				//jQuery(this).delay(400).fadeIn(400);
				jQuery(this).fadeIn(300);
			}else{
				jQuery(this).hide();
			}
			
		});
		
		jQuery('.simple-options-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});


	});
	
	

	jQuery('#expand_options').click(function(e) {
		e.preventDefault();
		
		var trigger = jQuery('#expand_options');
		var width = jQuery('#simple-options-sidebar').width();
		var id = jQuery('#simple-options-group-menu .active a').data('rel')+'_section_group';
		
		if (trigger.hasClass('expanded')) {
			trigger.removeClass('expanded');
			jQuery('#simple-options-sidebar').stop().animate({'margin-left':'0px'},500);
			jQuery('#simple-options-main').stop().animate({'margin-left':width},500);

			

			jQuery('.simple-options-group-tab').each(function(){
					if(jQuery(this).attr('id') != id){
						jQuery(this).fadeOut('fast');
					}
			});
			// Show the only active one

		} else {
			trigger.addClass('expanded');
			jQuery('#simple-options-sidebar').stop().animate({'margin-left':-width-2},500);
			jQuery('#simple-options-main').stop().animate({'margin-left':'0px'},500);	
			jQuery('.simple-options-group-tab').fadeIn();

		}
		return false;
	});	
	
	jQuery('#simple-options-import').click(function(e) {
		if (jQuery('#import-code-value').val() == "" && jQuery('#import-link-value').val() == "" ) {
			e.preventDefault();
			return false;
		}
	});

	
	if(jQuery('#simple-options-save').is(':visible')){
		jQuery('#simple-options-save').slideDown();
	}
	
	if(jQuery('#simple-options-imported').is(':visible')){
		jQuery('#simple-options-imported').slideDown();
	}	
	
	jQuery('input, textarea, select').change(function() {
		// Hide errors if the user changed the field
		if (jQuery(this).hasClass('simple-options-field-error')) {
			jQuery(this).removeClass('simple-options-field-error');
			jQuery(this).parent().find('.simple-options-th-error').slideUp();
			var parentID = jQuery(this).closest('.simple-options-group-tab').attr('id');
			var hideError = true;
			jQuery('#'+parentID+' .simple-options-field-error').each(function() {
				hideError = false;
			});
			if (hideError) {
				jQuery('#'+parentID+'_li .simple-options-menu-error').hide();
			}			
		}
		jQuery('#simple-options-save-warn').slideDown();
	});
	
	
	jQuery('#simple-options-import-code-button').click(function(){
		if(jQuery('#simple-options-import-link-wrapper').is(':visible')){
			jQuery('#simple-options-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#simple-options-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#simple-options-import-link-button').click(function(){
		if(jQuery('#simple-options-import-code-wrapper').is(':visible')){
			jQuery('#simple-options-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#simple-options-import-link-wrapper').fadeIn('slow');
	});
	
	jQuery('#simple-options-export-code-copy').click(function(){
		if(jQuery('#simple-options-export-link-value').is(':visible')){jQuery('#simple-options-export-link-value').fadeOut('slow');}
		jQuery('#simple-options-export-code').toggle('fade');
	});
	
	jQuery('#simple-options-export-link').click(function(){
		if(jQuery('#simple-options-export-code').is(':visible')){jQuery('#simple-options-export-code').fadeOut('slow');}
		jQuery('#simple-options-export-link-value').toggle('fade');
	});
	
	
jQuery.fn.isOnScreen = function(){
    
    var win = jQuery(window);
    
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    
};


/**
	Show the sticky header bar and notes!
**/
  var stickyHeight = jQuery('#simple-options-footer').height();
  var stickyWidth = jQuery('#simple-options-footer').width();
  jQuery('#simple-options-sticky-padder').css({height: stickyHeight});

  function stickyInfo() {
    if( !jQuery('#info_bar').isOnScreen() && !jQuery('#simple-options-footer-sticky').isOnScreen()) {
        jQuery('#simple-options-footer').css({position: 'fixed', bottom: '0', width: stickyWidth});
        jQuery('#simple-options-footer').addClass('sticky-footer-fixed');
        jQuery('#simple-options-sticky-padder').show();
    } else {
    		jQuery('#simple-options-footer').css({background: '#eee',position: 'inherit', bottom: 'inherit', width: 'inherit' });
    		jQuery('#simple-options-sticky-padder').hide();
    		jQuery('#simple-options-footer').removeClass('sticky-footer-fixed');
    }  	
  }  
  jQuery(window).scroll(function(){
		stickyInfo();
  });
  jQuery(window).resize(function(){
		stickyInfo();
  });

	
  jQuery('#simple-options-save').delay(3000).slideUp();
  jQuery('#simple-options-field-errors').delay(4000).slideUp();

	
});