<?php
class Simple_Options_radio_img extends Simple_Options{	
	
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

			echo '<ul>';
			
			foreach($this->field['options'] as $k => $v){
				if (!isset($v['title'])) {
					$v['title'] = "";
				}
				if (!isset($v['alt'])) {
					$v['alt'] = $v['title'];
				}				

				$selected = (checked($this->value, $k, false) != '')?' sof-radio-img-selected':'';
				echo '<li class="sof-radio-img' . $class . '">';
				echo '<label class="'.$selected.' sof-radio-img-'.$this->field['id'].'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo '<img src="'.$v['img'].'" alt="'.$v['alt'].'" onclick="jQuery:sof_radio_img_select(\''.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'\', \''.$this->field['id'].'\');" />';
				if ($v['title'] != "") {
					echo '<br /><span>'.$v['title'].'</span>';	
				}
				echo '</label>';		
				echo '</li>';
			}//foreach
				
			echo '</ul>';		

		}

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.$this->field['desc'].'</span>':'';
		
		echo '</fieldset>';
		
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
			'simple-options-field-radio_img-js', 
			SOF_OPTIONS_URL.'fields/radio_img/field_radio_img.js', 
			array('jquery'),
			time(),
			true
		);
		
	}//function
	
}//class
?>