<?php
class SOF_Validation_color extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field, $value, $current){
		
		parent::__construct();
		$this->field = $field;
		$this->field['msg'] = (isset($this->field['msg']))?$this->field['msg']:__('This field must be a valid color value.', 'simple-options');
		$this->value = $value;
		$this->current = $current;
		$this->validate();
		
	}//function
	
	

	/**
	 * Validate Color
	 *
	 * Takes the user's input color value and returns it only if it's a valid color.
	 *
	 * @since Simple_Options 1.0.0
	*/	
	function validate_color($color) {
		
		$named = array('transparent', 'none', 'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

		if (in_array(strtolower($color), $named)) {
		  /* A color name was entered instead of a Hex Value, so just exit function */
		  return $color;
		}

		$color = str_replace('#','', $color);
		if (strlen($color) == 3) {
			$color = $color.$color;
		}
	  if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
	    return '#' . $color;
	  }
	  return false;

	}//function

	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Simple_Options 1.0.0
	*/
	function validate(){
		
		if(is_array($this->value)) { // If array
			foreach($this->value as $k => $value){
				if ($color = $this->validate_color($value)) {
					$this->value[$k] = $color;
				} else {
					$this->value[$k] = (isset($this->current[$k]))?$this->current[$k]:'';
					$this->error = $this->field;
					return;		
				}
			}//foreach
		} else { // not array
			if ($color = $this->validate_color($this->value)) {
				$this->value = $color;
			} else {
				$this->value = (isset($this->current))?$this->current:'';
				$this->error = $this->field;
				return;		
			}
		} // END array check
		
	}//function
	
}//class
?>