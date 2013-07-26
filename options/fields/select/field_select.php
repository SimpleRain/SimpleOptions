<?php
class Simple_Options_select extends Simple_Options{	
	
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

		if (!empty($this->field['data']) && empty($this->field['options'])) {
			if (empty($this->field['args'])) {
				$this->field['args'] = array();
				//$this->field['args'] = array('number' => '10');
			}

			$args = wp_parse_args($this->field['args'], array());	
			
			if ($this->field['data'] == "categories" || $this->field['data'] == "category") {
				$this->field['options'] = get_categories($args, true); 
			} else if ($this->field['data'] == "pages" || $this->field['data'] == "page") {
				foreach (get_pages($args) as $k=>$item) {
					$this->field['options'][$item->ID] = $item->post_title;
				}
			} else if ($this->field['data'] == "tags" || $this->field['data'] == "tag") {
				$this->field['options'] = get_tags($args);
			} else if ($this->field['data'] == "posts" || $this->field['data'] == "post") {
				$args = wp_parse_args($this->field['args'], array('numberposts' => '-1'));
				foreach (get_posts($args) as $k=>$item) {
					$this->field['options'][$item->ID] = $item->post_title;
				}
			} else if ($this->field['data'] == "post_type" || $this->field['data'] == "post_types") {
				$args = wp_parse_args($this->field['args'], array('public' => true));
				foreach (get_post_types($args, 'object') as $k=>$item) {
					$this->field['options'][$k] = $item->labels->name;
				}
			} else if ($this->field['data'] == "menus" || $this->field['data'] == "menu") {
				foreach (wp_get_nav_menus($args) as $k=>$item) {
					$this->field['options'][$item->term_id] = $item->name;
				}
			}			
		}

		$class = (isset($this->field['class']))?' '.$this->field['class']:'';
		if (!empty($this->field['options'])) {
			if (isset($this->field['multiple']) && $this->field['multiple']) {
				$multiple = " multiple";
			} else {
				$multiple = "";
			}
			
			if (!empty($this->field['width'])) {
				$width = ' style="'.$this->field['width'].'"';
			} else {
				$width = ' style="width: 40%;"';
			}	

			echo '<select'.$multiple.' id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" class="sof-select-item'.$class.'"'.$width.'>';
				if (empty($multiple)) {
					echo '<option></option>';
				} else {

				}
				//echo "~~".."~~";

				foreach($this->field['options'] as $k => $v){
					if (is_object($v)) {
						echo '<option value="'.$v->term_id.'" '.selected($this->value, $v->term_id, false).'>'.$v->name.'</option>';	
					} else {
						echo '<option value="'.$k.'" '.selected($this->value, $k, false).'>'.$v.'</option>';	
					}
				}//foreach
			echo '</select>';			
		}

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function
	

	function getData($type) {


	}

	
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