<?php
class Simple_Options_slider extends Simple_Options{	
	
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

		if( !isset( $this->field['min'] ) ) { 
			$this->field['min'] = 0; 
		} 
		if( !isset( $this->field['max'] ) ) { 
			$this->field['max'] = $this->field['min'] + 1; 
		} 		
		if( !isset( $this->field['step'] ) ) { 
			$this->field['step'] = 1; 
		} 
		
		if(!isset($this->field['edit']) && $this->field['edit'] == false){ 
			$this->field['edit']  = ' readonly="readonly"'; 
		} else {
			$this->field['edit']  = '';
		}
		
		if( !isset( $this->value ) ) { 
			$this->value = $this->field['min']; 
		} 
		
		
		//values
		$s_data = 'data-id="'.$this->field['id'].'" data-val="'.$this->value.'" data-min="'.$this->field['min'].'" data-max="'.$this->field['max'].'" data-step="'.$this->field['step'].'"';
		
		//html output
		echo '<input type="text" name="'.$this->field['id'].'" id="'.$this->field['id'].'" value="'. $this->value .'" class="mini" '. $this->field['edit'] .' />';
		echo '<div id="'.$this->field['id'].'-slider" class="smof_sliderui" style="margin-left: 7px;" '. $s_data .'></div>';
		
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
		
		wp_enqueue_script(
			'select2-init', 
			SOF_OPTIONS_URL.'fields/select/field_slider.js', 
			array('jquery'),
			time(),
			true
		);		

		wp_enqueue_style(
			'select2', 
			SOF_OPTIONS_URL.'fields/select/field_slider.css', 
			time(),
			true
		);		

	}//function

}//class
?>