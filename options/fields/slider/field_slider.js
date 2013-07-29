jQuery.noConflict();


jQuery(document).ready(function(){
	jQuery('.sof_slider').each(function() {
		//slider init
		jQuery(this).slider({
			value: parseInt(sliderParam.val),
			min: parseInt(sliderParam.min),
			max: parseInt(sliderParam.max),
			step: parseInt(sliderParam.step),
			range: "min",
			slide: function( event, ui ) {
				console.log('here'+"#" + sliderParam.id);

				jQuery("#" + sliderParam.id).val( ui.value );
			}
		});
	});

	// Update the slider from the input and vice versa
	jQuery(".slider-input").keyup(function () {
			var value = parseInt(jQuery(this).val());
			if (value > sliderParam.max) {
				value = sliderParam.max;
			} else if (value < sliderParam.min) {
				value = sliderParam.min;
			}
	    jQuery('#'+sliderParam.id+'-slider').slider("value", value);
	    jQuery("#" + sliderParam.id).val( value );
	});

	// Limit input for negative
	var neg = false;
	if (parseInt(sliderParam.min) < 0) {
		neg = true;
	}

	jQuery(".slider-input").numeric({ negative : neg, min: sliderParam.min, max:sliderParam.max});	

});	