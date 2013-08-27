<?php
class Simple_Options_info extends Simple_Options{	
	
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
		
		$class = (isset($this->field['class']))?' '.$this->field['class']:'';

		

		if (empty($this->field['description']) && !empty($this->field['std'])) {
			$this->field['description'] = $this->field['std'];
		}
		
		echo '</td></tr></table><div class="simple-options-info-field'.$class.'">'.$this->field['description'].'</div><table class="form-table no-border"><tbody><tr><th></th><td>';
		
	}//function
	
}//class
?>