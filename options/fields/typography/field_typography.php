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
		global $wp_filesystem;
		// Initialize the Wordpress filesystem, no more using file_put_contents function
		if (empty($wp_filesystem)) {
			require_once(ABSPATH .'/wp-admin/includes/file.php');
			WP_Filesystem();
		}  

		
		//$this->get_google_webfonts_array();

		// No errors please
		$defaults = array(
			'family' => '',
			'size' => '',
			'style' => '',
			'color' => '',
			'height' => '',
			);
		$this->value = wp_parse_args( $this->value, $defaults );

		if(!empty($this->field['std'])) { 
			$this->value = wp_parse_args( $this->value, $this->field['std'] );
		}

		$units = array('px', 'em', '%');
		if (!empty($this->field['units']['type']) && in_array($this->field['units']['type'], $units)) {
			$unit = $this->field['units']['type'];
		} else {
			$unit = 'px';
		}
	
	  $gfonts = json_decode($wp_filesystem->get_contents(SOF_OPTIONS_URL.'fields/typography/webfonts.json'), true);
	  
	  echo '<div id="'.$this->field['id'].'-container" class="sof-typography-container" data-id="'.$this->field['id'].'" data-units="'.$unit.'">';

	  /**
			Font Family
		**/
	  if (!empty($field['display']['family'])):	  
	    echo '<div class="select_wrapper typography-family" original-title="Font family" style="width: 220px; margin-right: 5px;">';
	    echo '<select class="sof-typography sof-typography-family sof-select-item" id="'.$this->field['id'].'-family" name="'.$this->args['opt_name'].'['.$this->field['id'].'][family]" data-id="'.$this->field['id'].'">';
		 	echo '<optgroup label="Standard Fonts">';
	    $faces = array(
	      "Arial, Helvetica, sans-serif" => "Arial, Helvetica, sans-serif",
	      "'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
	      "'Bookman Old Style', serif" => "'Bookman Old Style', serif",
	      "'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
	      "Courier, monospace" => "Courier, monospace",
	      "Garamond, serif" => "Garamond, serif",
	      "Georgia, serif" => "Georgia, serif",
	      "Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
	      "'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
	      "'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
	      "'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
	      "'MS Serif', 'New York', sans-serif" =>"'MS Serif', 'New York', sans-serif",
	      "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
	      "Tahoma, Geneva, sans-serif" =>"Tahoma, Geneva, sans-serif",
	      "'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
	      "'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
	      "Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif",
	    );

	    foreach ($faces as $i=>$face) {
	      echo '<option data-google="false" data-details="'.urlencode(json_encode(
	        array('400'=>'Normal',
	              '700'=>'Bold',
	              '400-italic'=>'Normal Italic',
	              '700-italic'=>'Bold Italic',
	            )
	        )).'" value="'. $i .'" ' . selected($this->value['family'], $i, false) . '>'. $face .'</option>';
	    }
	    echo '</optgroup>';

	    $google = "false";
	    if ( isset( $gfonts ) ) {
	    	echo '<optgroup label="Google Web Fonts">';
	      foreach ($gfonts as $i => $face) {
	        if ( $i == $this->value['family'] )
	          $google = "true";
	        
	        echo '<option data-details="'.urlencode(json_encode($face)).'" data-google="true" value="'.$i.'" ' . selected($this->value['family'], $i, false) . '>'. $i .'</option>';
	      }
	      echo '</optgroup>';
	    }

	    echo '</select></div>';
	  
	  endif;



    /** 
    Font Weight 
    **/
    if(!empty($this->value['display']['style'])):
      echo '<div class="select_wrapper typography-style" original-title="Font style">';
      echo '<select class="sof-typography sof-typography-style select" original-title="Font style" name="'.$this->field['id'].'[style]" id="'. $this->field['id'].'_style" data-id="'.$this->field['id'].'">';
		 	if (empty($this->value['style'])) {
		 		echo '<option value="">Inherit</option>';
		 	}
      $styles = array('100'=>'Ultra-Light 100',
                '200'=>'Light 200',
                '300'=>'Book 300',
                '400'=>'Normal 400',
                '500'=>'Medium 500',
                '600'=>'Semi-Bold 600',
                '700'=>'Bold 700',
                '800'=>'Extra-Bold 800',
                '900'=>'Ultra-Bold 900',
                '100-italic'=>'Ultra-Light 100 Italic',
                '200-italic'=>'Light 200 Italic',
                '300-italic'=>'Book 300 Italic',
                '400-italic'=>'Normal 400 Italic',
                '500-italic'=>'Medium 500 Italic',
                '600-italic'=>'Semi-Bold 600 Italic',
                '700-italic'=>'Bold 700 Italic',
                '800-italic'=>'Extra-Bold 800 Italic',
                '900-italic'=>'Ultra-Bold 900 Italic',
              );
      $nonGStyles = array('200'=>'Lighter','400'=>'Normal','700'=>'Bold','900'=>'Bolder');
      if (isset($gfonts[$this->value['family']])) {
        $styles = array();
        foreach ($gfonts[$this->value['family']]['variants'] as $k=>$v) {
          echo '<option value="'. $v['id'] .'" ' . selected($this->value['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      } else {
        foreach ($nonGStyles as $i=>$style){
          if (!isset($this->value['style']))
            $this->value['style'] = false;
          echo '<option value="'. $i .'" ' . selected($this->value['style'], $i, false) . '>'. $style .'</option>';
        }
      }

      echo '</select></div>';

    endif;


    /** 
    Font Script 
    **/
    if(!empty($this->value['display']['script'])):
      echo '<div class="select_wrapper typography-script tooltip" original-title="Font Script">';
      echo '<select class="sof-typography sof-typography-script" original-title="Font script"  id="'.$this->field['id'].'-script" name="'.$this->args['opt_name'].'['.$this->field['id'].'][script]">';
      if (isset($gfonts[$this->value['family']])) {
        $styles = array();
        foreach ($gfonts[$this->value['family']]['subsets'] as $k=>$v) {
          echo '<option value="'. $v['id'] .'" ' . selected($this->value['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      }
      echo '</select></div>';

    endif;


		/**
		Font Size
		**/
  	if(!empty($this->value['display']['size'])):
    	echo '<div class="input-append"><input type="text" class="span2 sof-typography-size mini" original-title="Font size" id="'.$this->field['id'].'-size" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]" value="'.$this->value['size'].'"><span class="add-on">'.$unit.'</span></div>';
  	endif;


		/**
		Line Height 
		**/
		if(!empty($this->value['display']['height'])):
		 	echo '<div class="input-append"><input type="text" class="span2 sof-typography sof-typography-height mini" original-title="Font height" id="'.$this->field['id'].'-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'.$this->value['height'].'"><span class="add-on">'.$unit.'</span></div>';
		endif;




    /** 
    Font Color 
    **/
    if(!empty($this->value['display']['color'])):
    	$default = "";
    	if (empty($this->field['std']['color']) && !empty($this->field['color'])) {
    		$default = $this->value['color'];
			} else if (!empty($this->field['std']['color'])) {
				$default = $this->field['std']['color'];
			}
      echo '<div id="' . $this->field['id'] . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.$this->value['color'].'"></div></div>';
      echo '<input data-default-color="'.$default.'" class="sof-color sof-typography-color" original-title="Font color" id="'.$this->field['id'].'-color" name="'.$this->args['opt_name'].'['.$this->field['id'].'][color]" type="text" value="'. $this->value['color'] .'" data-id="'.$this->field['id'].'" />';
    endif;


    /**
		Font Preview
    **/
		if(!empty($this->value['display']['preview'])):
	    if(isset($value['preview']['text'])){
	      $g_text = $value['preview']['text'];
	    } else {
	      $g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
	    }
	    if(isset($value['preview']['size'])) {
	      $g_size = 'style="font-size: '. $value['preview']['size'] .';"';
	    } else {
	      $g_size = '';
	    }

	    echo '<p class="'.$this->field['id'].'_previewer typography-preview" '. $g_size .'>'. $g_text .'</p>';
	    echo "</div>";

	    echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
    endif;

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
			'simple-options-field-typography-js', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-field-typography-css', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.css', 
			time(),
			true
		);	

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

/*
 * If GoogleAPI key is set, call the API and enable Google Font dropdown, otherwise use the cached font list.
 */
function get_google_webfonts_array() {
	define("GOOGLE_FONTS_API_KEY", "AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII");
	// Check if they have a valid API key and SSL on this box. Won't work without SSL.
	if(extension_loaded('openssl') && GOOGLE_FONTS_API_KEY != "") {
		$url = "https://www.googleapis.com/webfonts/v1/webfonts?key=".GOOGLE_FONTS_API_KEY;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = json_decode(curl_exec($ch));
		curl_close($ch);
		if ($result->error->code == 400) {
			// Bad API key
			add_action( 'wp_footer', 'alert_bad_key', 199 );
			return json_decode(file_get_contents(dirname(__FILE__).'/webfonts.json'));
		} else {
			$res = array();
			foreach ($result->items as $font) {
				$res[$font->family] = array(
					'variants' => getVariants($font->variants),
					'subsets' => getSubsets($font->subsets)
				);
			}
			echo json_encode($res);
			exit();
			return $res;
		}
	} else {
		// No API key specified
		return json_decode(file_get_contents(dirname(__FILE__).'/webfonts.json'));
	}
}
	
}//class
?>