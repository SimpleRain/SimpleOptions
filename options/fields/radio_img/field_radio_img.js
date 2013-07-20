/*
 *
 * NHP_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function sof_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.sof-radio-img-'+labelclass).removeClass('sof-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('sof-radio-img-selected');
}//function