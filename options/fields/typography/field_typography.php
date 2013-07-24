<?php
class Simple_Options_typography extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Simple_Options 1.0.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?$this->field['class']:'typography';
		
		echo '<ul id="'.$this->field['id'].'-ul">';
		
		if(isset($this->value) && is_array($this->value)){
			foreach($this->value as $k => $value){
				if($value != ''){
				
					

					echo '<li><input type="text" id="'.$this->field['id'].'-'.$k.'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][]" value="'.esc_attr($value).'" class="'.$class.'" /> <a href="javascript:void(0);" class="simple-options-multi-text-remove">'.__('Remove', 'simple-options').'</a></li>';
					
				}//if
				
			}//foreach
		}else{
		
			echo '<li><input type="text" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][]" value="" class="'.$class.'" /> <a href="javascript:void(0);" class="simple-options-multi-text-remove">'.__('Remove', 'simple-options').'</a></li>';
		
		}//if
		
		echo '<li style="display:none;"><input type="text" id="'.$this->field['id'].'" name="" value="" class="'.$class.'" /> <a href="javascript:void(0);" class="simple-options-multi-text-remove">'.__('Remove', 'simple-options').'</a></li>';
		
		echo '</ul>';
		
		echo '<a href="javascript:void(0);" class="simple-options-multi-text-add" rel-id="'.$this->field['id'].'-ul" rel-name="'.$this->args['opt_name'].'['.$this->field['id'].'][]">'.__('Add More', 'simple-options').'</a><br/>';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 1.0.0
	*/
	function enqueue(){
/*		
		wp_enqueue_script(
			'simple-options-field-typography-js', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.js', 
			array('jquery'),
			time(),
			true
		);
*/
		wp_enqueue_style(
			'simple-options-field-typography-css', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.css', 
			time(),
			true
		);		
		
	}//function


	/**
	 * getGoogleScript Function.
	 *
	 * Used to retrieve and append the proper stylesheet to the page.
	 *
	 * @since Simple_Options 1.0.0
	*/
	function getGoogleScript($font) {
	  $link = 'http://fonts.googleapis.com/css?family='.str_replace(" ","+",$font['face']);
	  if (!empty($font['style']))
	    $link .= ':'.str_replace('-','',$font['style']);
	  if (!empty($font['script']))
	    $link .= '&subset='.$font['script'];

	  return '<link href="'.$link.'" rel="stylesheet" type="text/css" class="base_font">';
	}

	
}//class
?>