jQuery(document).ready(function(){
	
	
	if(jQuery('#last_tab').val() == ''){

		jQuery('.simple-options-group-tab:first').slideDown('fast');
		jQuery('#simple-options-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown('fast');
		jQuery('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
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
	
	jQuery('input, textarea, select').change(function(){
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
	
	
function footerInView(elem) {
		return false;
		var elem = jQuery('#simple-options-footer');
    var docViewTop = jQuery(window).scrollTop();
    var docViewBottom = docViewTop + jQuery(window).height();
    var barHeight = jQuery('#simple-options-sticky').height();

    var elemTop = jQuery(elem).offset().top;
    var elemBottom = elemTop + jQuery(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}


/**
	Shoe the sticky header bar and notes!
**/
  var stickyHeader = jQuery('#simple-options-sticky').offset().top-28;
  
  function stickyInfo() {
  	jQuery('#simple-options-sticky-padder').height(jQuery('#simple-options-sticky').height());
    if( jQuery(window).scrollTop() > stickyHeader && !footerInView()) {
        jQuery('#simple-options-sticky').css({position: 'fixed', top: '28px', width: jQuery('#simple-options-form-wrapper').width(), 'z-index':'999999' });
        jQuery('#simple-options-sticky-padder').show();
        //jQuery('#simple-options-sticky-padder').css(height: jQuery('#simple-options-sticky').height());
    } else {
    		jQuery('#simple-options-sticky').css({position: 'static', top: '28px', width: jQuery('#simple-options-form-wrapper').width() });
    		jQuery('#simple-options-sticky-padder').hide();
    }  	
  }  
  jQuery(window).scroll(function(){
		stickyInfo();
  });
  jQuery(window).resize(function(){
		stickyInfo();
  });

		
	
});