(function(f,e){var a='<a tabindex="0" class="wp-color-result" />',c='<div class="wp-picker-holder" />',b='<div class="wp-picker-container" />',g='<input type="button" class="button button-small hidden" />';var d={options:{defaultColor:false,change:false,clear:false,hide:true,palettes:true},_create:function(){if(f.browser.msie&&parseInt(f.browser.version,10)<8){return}var h=this;var i=h.element;f.extend(h.options,i.data());h.initialValue=i.val();i.addClass("wp-color-picker").hide().wrap(b);h.wrap=i.parent();h.toggler=f(a).insertBefore(i).css({backgroundColor:h.initialValue}).attr("title",wpColorPickerL10n.pick).attr("data-current",wpColorPickerL10n.current);h.pickerContainer=f(c).insertAfter(i);h.button=f(g);if(h.options.defaultColor){h.button.addClass("wp-picker-default").val(wpColorPickerL10n.defaultString)}else{h.button.addClass("wp-picker-clear").val(wpColorPickerL10n.clear)}i.wrap('<span class="wp-picker-input-wrap" />').after(h.button);i.iris({target:h.pickerContainer,hide:true,width:255,mode:"hsv",palettes:h.options.palettes,change:function(j,k){h.toggler.css({backgroundColor:k.color.toString()});if(f.isFunction(h.options.change)){h.options.change.call(this,j,k)}}});i.val(h.initialValue);h._addListeners();if(!h.options.hide){h.toggler.click()}},_addListeners:function(){var h=this;h.toggler.click(function(i){i.stopPropagation();h.element.toggle().iris("toggle");h.button.toggleClass("hidden");h.toggler.toggleClass("wp-picker-open");if(h.toggler.hasClass("wp-picker-open")){f("body").on("click",{wrap:h.wrap,toggler:h.toggler},h._bodyListener)}else{f("body").off("click",h._bodyListener)}});h.element.change(function(j){var i=f(this),k=i.val();if(k===""||k==="#"){h.toggler.css("backgroundColor","");if(f.isFunction(h.options.clear)){h.options.clear.call(this,j)}}});h.toggler.on("keyup",function(i){if(i.keyCode===13||i.keyCode===32){i.preventDefault();h.toggler.trigger("click").next().focus()}});h.button.click(function(j){var i=f(this);if(i.hasClass("wp-picker-clear")){h.element.val("");h.toggler.css("backgroundColor","");if(f.isFunction(h.options.clear)){h.options.clear.call(this,j)}}else{if(i.hasClass("wp-picker-default")){h.element.val(h.options.defaultColor).change()}}})},_bodyListener:function(h){if(!h.data.wrap.find(h.target).length){h.data.toggler.click()}},color:function(h){if(h===e){return this.element.iris("option","color")}this.element.iris("option","color",h)},defaultColor:function(h){if(h===e){return this.options.defaultColor}this.options.defaultColor=h}};f.widget("wp.wpColorPicker",d)}(jQuery));

jQuery(document).ready(function(){
	jQuery('.sof-color').wpColorPicker();

function colourNameToHex(colour) {
	colour = colour.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
    "beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
    "cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
    "darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
    "darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
    "darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
    "firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
    "gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f",
    "honeydew":"#f0fff0","hotpink":"#ff69b4",
    "indianred ":"#cd5c5c","indigo ":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
    "lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
    "lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
    "lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
    "magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
    "mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
    "navajowhite":"#ffdead","navy":"#000080",
    "oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
    "palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
    "red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
    "saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
    "tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
    "violet":"#ee82ee",
    "wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
    "yellow":"#ffff00","yellowgreen":"#9acd32"};

    if (typeof colours[colour.toLowerCase()] != 'undefined')
    	return colours[colour.toLowerCase()];

    return colour;
}

jQuery('.sof-color').live('keyup', function() {
	if (jQuery(this).val().indexOf("#") === -1) {
		jQuery(this).val(colourNameToHex(jQuery(this).val()));
	}
});

});  	

