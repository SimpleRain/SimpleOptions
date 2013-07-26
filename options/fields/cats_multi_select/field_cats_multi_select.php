<?php
class Simple_Options_cats_multi_select extends Simple_Options{	
	
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

		$args = wp_parse_args($this->field['args'], array());
		$cats = get_categories($args); 

		if (!empty($cats)) {		
			echo '<select multiple id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" class="sof-select-item'.$class.'" style="min-width:30%">';
			foreach ( $cats as $cat ) {
				echo '<option value="'.$cat->term_id.'"'.selected($this->value, $cat->term_id, false).'>'.$cat->name.'</option>';
			}
			echo '</select>';
		} // If
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
			'select2', 
			SOF_OPTIONS_URL.'fields/select/select2/select2.min.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_script(
			'select2-init', 
			SOF_OPTIONS_URL.'fields/select/field_select.js', 
			array('jquery'),
			time(),
			true
		);		

		wp_enqueue_style(
			'select2', 
			SOF_OPTIONS_URL.'fields/select/select2/select2.css', 
			time(),
			true
		);		

	}//function

}//class
?>