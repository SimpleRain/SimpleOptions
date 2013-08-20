/*
 *
 * NHP_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */

jQuery.noConflict();

jQuery(document).ready(function(){

	jQuery('.sof-radio_images label').click(function() {
		var id = jQuery(this).attr('for');
		
		jQuery(this).parent().parent().find('.sof-radio_images-selected').removeClass('sof-radio_images-selected');	

		jQuery(this).find('input[type="radio"]').prop('checked');
		jQuery('label[for="'+id+'"]').addClass('sof-radio_images-selected');
		var split = id.split('-');
		var labelclass = split[0];
		
	});
});