<?php
class Simple_Options_media extends Simple_Options{	
	
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

		// No errors please
		$defaults = array(
			'id' => '',
			'url' => '',
			);
		$this->value = wp_parse_args( $this->value, $defaults );

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		
		$hide = '';

		if (!empty($this->field['mode']) && $this->field['mode'] == "min") {
			$hide ='hide ';
		}

	  if ( empty($this->value['id']) && isset($this->field['std']['id']) ) {
			$this->value['id'] = $this->field['std']['id'];
	  } else if ( empty($this->value['id']) ) {
		$this->value['id'] = "";
	  }

	  if ( empty($this->value['url']) && isset($this->field['std']['url']) ) {
			$this->value['url'] = $this->field['std']['url'];
	  } else if ( empty($this->value['url']) ) {
		$this->value['url'] = "";
	  }

		if (!empty($this->value['id']) && $this->value['id'] != "" ) {
			if (empty($this->value['url'])) {
				$this->value['url'] = wp_get_attachment_url( $this->value['id'] );
			}
		}

		echo '<input class="'.$hide.'upload'.$class.'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][url]" id="'.$this->args['opt_name'].'['.$this->field['id'].'][url]" value="'. $this->value['url'] .'" readonly="readonly" />';
		echo '<input type="hidden" class="upload-id" name="'.$this->args['opt_name'].'['.$this->field['id'].'][id]" "'.$this->args['opt_name'].'['.$this->field['id'].'][id]" value="'. $this->value['id'] .'" />';

		//Upload controls DIV
		echo '<div class="upload_button_div">';
		//If the user has WP3.5+ show upload/remove button

			echo '<span class="button media_upload_button" id="'.$this->field['id'].'">Upload</span>';
			$hide = '';
			if ( empty( $this->value['url'] ) || $this->value['url'] == "" ) {
				$hide =' hide';
			}
			echo '<span class="button remove-image'. $hide.'" id="reset_'. $this->field['id'] .'" title="' . $this->field['id'] . '">Remove</span>';

		echo '</div>' . "\n";

		//Preview
		$hide = '';
		if (empty($this->value['url'])) {
			$hide =" hide";
		}

		echo '<div class="screenshot'.$hide.'">';
		echo '<a class="of-uploaded-image" href="'. $this->value['url'] . '">';
		echo '<img class="sof-option-image" id="image_'.$this->field['id'].'" src="'.$this->value['url'].'" alt="" />';
		echo '</a>';
		echo '</div>';
		echo '<div class="clear"></div>' . "\n";
		echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
		
	}//function
	

	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 1.0.0
	*/
	function enqueue(){

		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
		else {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}

		wp_enqueue_script(
			'simple-options-media-js',
			SOF_OPTIONS_URL.'fields/media/field_media.js',
			array('jquery', 'wp-color-picker'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-media-css',
			SOF_OPTIONS_URL.'fields/media/field_media.css',
			time(),
			true
		);

	}//function

}//class
?>