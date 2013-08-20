<?php
class Simple_Options_radio_images extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field = array(), $value = '', $parent = ''){
		
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
		
		$class = (isset($this->field['class']))?' '.$this->field['class']:'';
		
		echo '<fieldset>';
			
		if (!empty($this->field['options'])) {

			echo '<ul class="sof-radio-images">';
			
			foreach($this->field['options'] as $k => $v){
				if (!isset($v['title'])) {
					$v['title'] = "";
				}
				if (!isset($v['alt'])) {
					$v['alt'] = $v['title'];
				}				

				$selected = (checked($this->value, $k, false) != '')?' sof-radio_images-selected':'';
				echo '<li class="sof-radio_images' . $class . '">';
				echo '<label class="'.$selected.' sof-radio_images-'.$this->field['id'].'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo '<img src="'.$v['img'].'" alt="'.$v['alt'].'" />';
				if ($v['title'] != "") {
					echo '<br /><span>'.$v['title'].'</span>';	
				}
				echo '</label>';		
				echo '</li>';
			}//foreach
				
			echo '</ul>';		

		}

		echo '</fieldset>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 1.0.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'simple-options-field-images-js', 
			SOF_OPTIONS_URL.'fields/radio_images/field_radio_images.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-field-images-css', 
			SOF_OPTIONS_URL.'fields/radio_images/field_radio_images.css',
			time(),
			true
		);		
		
	}//function
	
}//class
?>