jQuery(document).ready(function(){
	
	jQuery('.simple-options-multi-text-remove').live('click', function(){
		sof_change();
		jQuery(this).prev('input[type="text"]').val('');
		jQuery(this).parent().fadeOut('slow', function(){jQuery(this).remove();});
	});
	
	jQuery('.simple-options-multi-text-add').click(function(){
		var new_input = jQuery('#'+jQuery(this).attr('rel-id')+' li:last-child').clone();
		jQuery('#'+jQuery(this).attr('rel-id')).append(new_input);
		jQuery('#'+jQuery(this).attr('rel-id')+' li:last-child').removeAttr('style');
		jQuery('#'+jQuery(this).attr('rel-id')+' li:last-child input[type="text"]').val('');
		jQuery('#'+jQuery(this).attr('rel-id')+' li:last-child input[type="text"]').attr('name' , jQuery(this).attr('rel-name'));
	});
	
});