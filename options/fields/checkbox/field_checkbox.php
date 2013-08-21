<?php
class Simple_Options_checkbox extends Simple_Options{	
	
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
		
		$class = (isset($this->field['class']))?$this->field['class']:'';
		echo '<fieldset>';


		if (!empty($this->field['options']) && ( is_array($this->field['options']) || is_array($this->field['std']) ) ) :
			echo '<ul>';
			
			foreach($this->field['options'] as $k => $v){
				
				$this->value[$k] = (isset($this->value[$k]))?$this->value[$k]:'';
				echo '<li class="' . $class . '">';
				echo '<label for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="checkbox" class="checkbox" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']['.$k.']" value="1" '.checked($this->value[$k], '1', false).'/>';
				echo ' '.$v.'</label>';
				echo '</li>';
				
			}//foreach

			echo '</ul>';	

			echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';

		else: 

			echo ($this->field['desc'] != '')?' <label for="'.$this->field['id'].'">':'';
		
			echo '<input type="checkbox" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="1" class="checkbox'.$class.'" '.checked($this->value, '1', false).'/>';
		
			echo (isset($this->field['desc']) && !empty($this->field['desc']))?' '.$this->field['desc'].'</label>':'';

		endif;

	
		echo '</fieldset>';		
		
	}//function
	
}//class
?>