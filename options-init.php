<?php
/*
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 *
 */

// Try to include the framework if it is embedded in the theme.
if (strpos(dirname(__FILE__),TEMPLATEPATH) !== false &&!class_exists('Simple_Options') && file_exists( dirname( __FILE__ ) . '/options/options.php') ) {
	include_once( dirname( __FILE__ ) . '/options/options.php' );
}

// Return if the class can't be found. No errors please!
if( !class_exists('Simple_Options') ){
	return;
}

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'simple-options'),
				'desc' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'simple-options'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function
//add_filter('simple-options-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function
//add_filter('simple-options-args-twenty_eleven', 'change_framework_args');









/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
$args = array();

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = true;

//google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
$args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';

//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isn\'t required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'simple-options');

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/simplerain',
										'title' => 'Folow me on Twitter', 
										'img' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png'
										);
$args['share_icons']['linked_in'] = array(
										'link' => 'http://linkedin.com/in/dovyp',
										'title' => 'Find me on LinkedIn', 
										'img' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_337_linked_in.png'
										);

//Choose to disable the import/export feature
//$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = 'twenty_eleven';

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = wp_get_theme();

//Custom Page Title for options page - default is "", and thus hidden
//$args['page_title'] = wp_get_theme() . ' '.__('Theme Options', 'simple-options');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "simple_theme_options"
$args['page_slug'] = 'simple_theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
//$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
//$args['page_parent'] = 'themes.php';

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 27;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';

//Want to disable the sections showing as a submenu in the admin? uncomment this line
//$args['allow_sub_menu'] = false;
		
//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition		
$args['help_tabs'][] = array(
							'id' => 'simple-options-1',
							'title' => __('Theme Information 1', 'simple-options'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'simple-options')
							);
$args['help_tabs'][] = array(
							'id' => 'simple-options-2',
							'title' => __('Theme Information 2', 'simple-options'),
							'content' => __('<p>This is the tab content, HTML is allowed. Tab2</p>', 'simple-options')
							);

//Set the Help Sidebar for the options page - no sidebar by default										
$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'simple-options');



$sections = array();

$sections[] = array(
				'icon' => '',
				'title' => __('Home Settings', 'simple-options'),
				'header' => __('Welcome to the Simple Options Framework Demo', 'simple-options'),
				'desc' => __('Simple Options Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="http://github.com/SimpleRain/SimpleOptions/">http://github.com/SimpleRain/SimpleOptions/</a>', 'simple-options'),
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_020_home.png',
				'fields' => array(
					
					array(
						'id' => 'media', //must be unique
						'type' => 'media', 
						'title' => __('Media', 'simple-options'),
						'sub_desc' => __('Upload any media using the Wordpress native uploader', 'simple-options'),
						),

					array(
						'id' => 'media-min', //must be unique
						'type' => 'media', 
						'mode'=> 'min',
						'title' => __('Media Minimalistic (min)', 'simple-options'),
						'desc'=> __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'simple-options'),
						'sub_desc' => __('Upload any media using the Wordpress native uploader', 'simple-options'),
						),				
				
					array(
						'id' => 'gallery', //must be unique
						'type' => 'gallery', 
						'title' => __('Gallery', 'simple-options'),
						'desc'=> __('Add a gallery using the integrated media gallery of wordpress. No preview, but fully supports order, etc.', 'simple-options'),
						),		
						
					array(
						'id' => 'slider1', //must be unique
						'type' => 'slider', 
						'title' => __('JQuery UI Slider Example 1', 'simple-options'),
						'desc'=> __('JQuery UI slider description.<br /> Min: 1, max: 500, step: 3, default value: 45', 'simple-options'),
						"std" 		=> "45",
						"min" 		=> "1",
						"step"		=> "3",
						"max" 		=> "500",
						),	

					array(
						'id' => 'slider2', //must be unique
						'type' => 'slider', 
						'title' => __('JQuery UI Slider Example 2 w/ Steps (5)', 'simple-options'),
						'desc'=> __('JQuery UI slider description.<br /> Min: 0, max: 300, step: 5, default value: 75', 'simple-options'),
						"std" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						),	

					array(
						'id' => 'switch-on', //must be unique
						'type' => 'switch', 
						'title' => __('Switch On', 'simple-options'),
						'sub_desc'=> __('Look, it\'s on!', 'simple-options'),
						"std" 		=> 1,
						),	

					array(
						'id' => 'switch-off', //must be unique
						'type' => 'switch', 
						'title' => __('Switch Off', 'simple-options'),
						'sub_desc'=> __('Look, it\'s on!', 'simple-options'),
						"std" 		=> 0,
						),	

					array(
						'id' => 'switch-custom', //must be unique
						'type' => 'switch', 
						'title' => __('Switch - Custom Titles', 'simple-options'),
						'sub_desc'=> __('Look, it\'s on!', 'simple-options'),
						"std" 		=> 0,
						'on' => 'Enabled',
						'off' => 'Disabled',
						),	

					array(
						'id' => 'switch-fold', //must be unique
						'type' => 'switch', 
						'title' => __('Switch - With Hidden Items', 'simple-options'),
						'sub_desc'=> __('Also called a "fold" parent.', 'simple-options'),
						'desc' => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'simple-options'),
						'std' 		=> 0,
						),	
					array(
						'id' => 'layout', //must be unique
						'type' => 'layout', 
						'fold' => array('switch-fold'=>array(1,2)),
						'title' => __('Homepage Layout Manager', 'simple-options'),
						'sub_desc'=> __('Organize how you want the layout to appear on the homepage.', 'simple-options'),
						),

					array(
						'id' => 'slides', //must be unique
						'type' => 'slides', 
						'title' => __('Slides Options', 'simple-options'),
						'sub_desc'=> __('Unlimited slider with drag and drop sortings.', 'simple-options'),
						),

					array(
						'id' => 'patterns', //must be unique
						'type' => 'patterns', 
						'title' => __('Background Patterns Images', 'simple-options'),
						'sub_desc'=> __('Select a background pattern.', 'simple-options'),
						'std' 		=> 0,
						),		

					array(
						'id' => 'typography', //must be unique
						'type' => 'typography', 
						'title' => __('Typography', 'simple-options'),
						'sub_desc'=> __('Typography option with each property can be called individually.', 'simple-options'),
						),	


					),
				);






$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_280_settings.png',
				'title' => __('General Settings', 'simple-options'),
				'fields' => array(
					array(
						'id' => 'layout',
						'type' => 'radio_img',
						'title' => __('Main Layout', 'simple-options'), 
						'sub_desc' => __('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'simple-options'),
						'options' => array(
										'1' => array('alt' => '1 Column', 'img' => SOF_OPTIONS_URL.'img/1col.png'),
										'2' => array('alt' => '2 Column Left', 'img' => SOF_OPTIONS_URL.'img/2cl.png'),
										'3' => array('alt' => '2 Column Right', 'img' => SOF_OPTIONS_URL.'img/2cr.png'),
										'4' => array('alt' => '3 Column Middle', 'img' => SOF_OPTIONS_URL.'img/3cm.png'),
										'5' => array('alt' => '3 Column Left', 'img' => SOF_OPTIONS_URL.'img/3cl.png'),
										'6' => array('alt' => '3 Column Right', 'img' => SOF_OPTIONS_URL.'img/3cr.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						),

					array(
						'id' => 'tracking-code',
						'type' => 'textarea',
						'title' => __('Tracking Code', 'simple-options'), 
						'sub_desc' => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'simple-options'),
						'validate' => 'js',
						'desc' => 'Validate that it\'s javascript!',
						),

					array(
						'id' => 'footer-text',
						'type' => 'editor',
						'title' => __('Footer Text', 'simple-options'), 
						'sub_desc' => __('You can use the following shortcodes in your footer text: [wp-url] [site-url] [theme-url] [login-url] [logout-url] [site-title] [site-tagline] [current-year]', 'simple-options'),
						'std' => 'Powered by [wp-url]. Built on the [theme-url].',
						),

				)
			);




$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_234_brush.png',
				'title' => __('Styling Options', 'simple-options'),
				'fields' => array(

					array(
						'id' => 'stylesheet',
						'type' => 'select',
						'title' => __('Theme Stylesheet', 'simple-options'), 
						'sub_desc' => __('Select your themes alternative color scheme.', 'simple-options'),
						'options' => array('default.css'=>'default.css', 'color1.css'=>'color1.css'),
						'std' => 'default.css',
						),
					array(
						'id' => 'color-background',
						'type' => 'color',
						'title' => __('Body Background Color', 'simple-options'), 
						'sub_desc' => __('Pick a background color for the theme (default: #fff).', 'simple-options'),
						'std' => '#FFFFFF',
						'validate' => 'color',
						),
					array(
						'id' => 'color-footer',
						'type' => 'color',
						'title' => __('Footer Background Color', 'simple-options'), 
						'sub_desc' => __('Pick a background color for the footer (default: #dd9933).', 'simple-options'),
						'std' => '#dd9933',
						'validate' => 'color',
						),
					array(
						'id' => 'color-header',
						'type' => 'color_gradient',
						'title' => __('Header Gradient Color Option', 'simple-options'),
						'sub_desc' => __('Only color validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'std' => array('from' => '#1e73be', 'to' => '#00897e')
						),
					array(
						'id' => 'body-font',
						'type' => 'typography',
						'title' => __('Body Font', 'simple-options'),
						'sub_desc' => __('Specify the body font properties.', 'simple-options'),
						'std' => array(
							'color'=>'#dd9933',
							'font-size'=>'12',
							'font-family'=>'Arial',
							'font-weight'=>'Normal',
							),
						),					
					array(
						'id' => 'custom-css',
						'type' => 'textarea',
						'title' => __('Custom CSS', 'simple-options'), 
						'sub_desc' => __('Quickly add some CSS to your theme by adding it to this block.', 'simple-options'),
						'desc' => __('This field is even CSS validated!', 'simple-options'),
						'validate' => 'css',
						),
				)
			);
				
$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_107_text_resize.png',
				'title' => __('Field Validation', 'simple-options'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'simple-options'),
				'fields' => array(
					array(
						'id' => '2',
						'type' => 'text',
						'title' => __('Text Option - Email Validated', 'simple-options'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'email',
						'msg' => 'custom error message',
						'std' => 'test@test.com'
						),
					array(
						'id' => 'multi_text',
						'type' => 'multi_text',
						'title' => __('Multi Text Option', 'simple-options'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						),
					array(
						'id' => '3',
						'type' => 'text',
						'title' => __('Text Option - URL Validated', 'simple-options'),
						'sub_desc' => __('This must be a URL.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'url',
						'std' => 'http://no-half-pixels.com'
						),
					array(
						'id' => '4',
						'type' => 'text',
						'title' => __('Text Option - Numeric Validated', 'simple-options'),
						'sub_desc' => __('This must be numeric.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'comma_numeric',
						'type' => 'text',
						'title' => __('Text Option - Comma Numeric Validated', 'simple-options'),
						'sub_desc' => __('This must be a comma seperated string of numerical values.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'comma_numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'no_special_chars',
						'type' => 'text',
						'title' => __('Text Option - No Special Chars Validated', 'simple-options'),
						'sub_desc' => __('This must be a alpha numeric only.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'no_special_chars',
						'std' => '0'
						),
					array(
						'id' => 'str_replace',
						'type' => 'text',
						'title' => __('Text Option - Str Replace Validated', 'simple-options'),
						'sub_desc' => __('You decide.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'str_replace',
						'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
						'std' => '0'
						),
					array(
						'id' => 'preg_replace',
						'type' => 'text',
						'title' => __('Text Option - Preg Replace Validated', 'simple-options'),
						'sub_desc' => __('You decide.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'preg_replace',
						'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
						'std' => '0'
						),
					array(
						'id' => 'custom_validate',
						'type' => 'text',
						'title' => __('Text Option - Custom Callback Validated', 'simple-options'),
						'sub_desc' => __('You decide.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate_callback' => 'validate_callback_function',
						'std' => '0'
						),
					array(
						'id' => '5',
						'type' => 'textarea',
						'title' => __('Textarea Option - No HTML Validated', 'simple-options'), 
						'sub_desc' => __('All HTML will be stripped', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'no_html',
						'std' => 'No HTML is allowed in here.'
						),
					array(
						'id' => '6',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated', 'simple-options'), 
						'sub_desc' => __('HTML Allowed (wp_kses)', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
						'std' => 'HTML is allowed in here.'
						),
					array(
						'id' => '7',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated Custom', 'simple-options'), 
						'sub_desc' => __('Custom HTML Allowed (wp_kses)', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'html_custom',
						'std' => 'Some HTML is allowed in here.',
						'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
						),
					array(
						'id' => '8',
						'type' => 'textarea',
						'title' => __('Textarea Option - JS Validated', 'simple-options'), 
						'sub_desc' => __('JS will be escaped', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'validate' => 'js'
						),

					)
				);
$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_150_check.png',
				'title' => __('Radio/Checkbox Fields', 'simple-options'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'simple-options'),
				'fields' => array(
					array(
						'id' => '10',
						'type' => 'checkbox',
						'title' => __('Checkbox Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'std' => '1'// 1 = on | 0 = off
						),
					array(
						'id' => '11',
						'type' => 'multi_checkbox',
						'title' => __('Multi Checkbox Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
						'std' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also dont need to specify opts that are 0.
						),
					array(
						'id' => '12',
						'type' => 'radio',
						'title' => __('Radio Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
					array(
						'id' => '13',
						'type' => 'radio_img',
						'title' => __('Radio Image Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array(
										'1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
										'2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
										'3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
										'4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						),
					array(
						'id' => 'radio_img',
						'type' => 'radio_img',
						'title' => __('Radio Image Option For Layout', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This uses some of the built in images, you can use them for layout options.', 'simple-options'),
						'options' => array(
										'1' => array('alt' => '1 Column', 'img' => SOF_OPTIONS_URL.'img/1col.png'),
										'2' => array('alt' => '2 Column Left', 'img' => SOF_OPTIONS_URL.'img/2cl.png'),
										'3' => array('alt' => '2 Column Right', 'img' => SOF_OPTIONS_URL.'img/2cr.png'),
										'4' => array('alt' => '3 Column Middle', 'img' => SOF_OPTIONS_URL.'img/3cm.png'),
										'5' => array('alt' => '3 Column Left', 'img' => SOF_OPTIONS_URL.'img/3cl.png'),
										'6' => array('alt' => '3 Column Right', 'img' => SOF_OPTIONS_URL.'img/3cr.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						)																		
					)
				);
$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_157_show_lines.png',
				'title' => __('Select Fields', 'simple-options'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'simple-options'),
				'fields' => array(
					array(
						'id' => 'select',
						'type' => 'select',
						'title' => __('Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for select options
						'std' => '2'
						),
					array(
						'id' => '15',
						'type' => 'select',
						'multiple' => true,
						'title' => __('Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => array('2','3')
						),

					array(
						'id' => 'multi-info',
						'type' => 'info',
						'desc' => __('You can easily add a variety of data from wordpress.', 'simple-options'),
						),

					array(
						'id' => 'select-categories',
						'type' => 'select',
						'data' => 'categories',
						'title' => __('Categories Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'select-categories-multi',
						'type' => 'select',
						'data' => 'categories',
						'multiple' => true,
						'title' => __('Categories Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'select-pages',
						'type' => 'select',
						'data' => 'pages',
						'title' => __('Pages Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'pages-multi_select',
						'type' => 'select',
						'data' => 'pages',
						'multiple' => true,
						'title' => __('Pages Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),	
					array(
						'id' => 'select-tags',
						'type' => 'select',
						'data' => 'tags',
						'title' => __('Tags Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'tags-multi_select',
						'type' => 'select',
						'data' => 'tags',
						'multiple' => true,
						'title' => __('Tags Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),	
					array(
						'id' => 'select-menus',
						'type' => 'select',
						'data' => 'menus',
						'title' => __('Menus Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'menus-multi_select',
						'type' => 'select',
						'data' => 'menu',
						'multiple' => true,
						'title' => __('Menus Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),	
						array(
						'id' => 'select-post-type',
						'type' => 'select',
						'data' => 'post_type',
						'title' => __('Post Type Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'post-type-multi_select',
						'type' => 'select',
						'data' => 'post_type',
						'multiple' => true,
						'title' => __('Post Type Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),	
										array(
						'id' => 'select-posts',
						'type' => 'select',
						'data' => 'post',
						'title' => __('Posts Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					array(
						'id' => 'select-posts-multi',
						'type' => 'select',
						'data' => 'post',
						'multiple' => true,
						'title' => __('Posts Multi Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),
					)
				);
$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
				'title' => __('Custom Fields', 'simple-options'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'simple-options'),
				'fields' => array(
					array(
						'id' => '18info',
						'type' => 'button_set',
						'title' => __('Button Set Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),	
					array(
						'id' => '19',
						'type' => 'upload',
						'title' => __('Upload Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						),
					array(
						'id' => 'select_hide_below',
						'type' => 'select_hide_below',
						'title' => __('Select Hide Below Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This field requires certain options to be checked before the below field will be shown.', 'simple-options'),
						'options' => array(
									'1' => array('name' => 'Opt 1 field below allowed', 'allow' => 'true'),
									'2' => array('name' => 'Opt 2 field below hidden', 'allow' => 'false'),
									'3' => array('name' => 'Opt 3 field below allowed', 'allow' => 'true')
									),//Must provide key => value(array) pairs for select options
						'std' => '2'
						),
					array(
						'id' => 'menu_location_select',
						'type' => 'menu_location_select',
						'title' => __('Menu Location Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This field creates a drop down menu of all the themes menu locations.', 'simple-options')
						),
					array(
						'id' => 'checkbox_hide_below',
						'type' => 'checkbox_hide_below',
						'title' => __('Checkbox to hide below', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This field creates a checkbox which will allow the user to use the next setting.', 'simple-options'),
						),
						array(
						'id' => 'post_type_select',
						'type' => 'post_type_select',
						'title' => __('Post Type Select Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This field creates a drop down menu of all registered post types.', 'simple-options'),
						//'args' => array()//uses get_post_types
						),
					array(
						'id' => 'custom_callback',
						//'type' => 'nothing',//doesnt need to be called for callback fields
						'title' => __('Custom Field Callback', 'simple-options'), 
						'sub_desc' => __('This is a completely unique field type', 'simple-options'),
						'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'simple-options'),
						'callback' => 'my_custom_field'
						),
					array(
						'id' => 'google_webfonts',
						'type' => 'google_webfonts',//doesnt need to be called for callback fields
						'title' => __('Google Webfonts', 'simple-options'), 
						'sub_desc' => __('This is a completely unique field type', 'simple-options'),
						'desc' => __('This is a simple implementation of the developer API for Google webfonts. Preview selection will be coming in future releases.', 'simple-options')
						)							
					)
				);

$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_093_crop.png',
				'title' => __('Non Value Fields', 'simple-options'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'simple-options'),
				'fields' => array(
					array(
						'id' => '20',
						'type' => 'text',
						'title' => __('Text Field', 'simple-options'), 
						'sub_desc' => __('Additional Info', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						),
					array(
						'id' => '21',
						'type' => 'divide'
						),
					array(
						'id' => '22',
						'type' => 'text',
						'title' => __('Text Field', 'simple-options'), 
						'sub_desc' => __('Additional Info', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						),
					array(
						'id' => '23',
						'type' => 'info',
						'desc' => __('<p class="description">This is the info field, if you want to break sections up.</p>', 'simple-options')
						),
					array(
						'id' => '24',
						'type' => 'text',
						'title' => __('Text Field', 'simple-options'), 
						'sub_desc' => __('Additional Info', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						)				
					)
				);
		
$sections[] = array( 'type' => 'divide' );

$sections[] = array(
				'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_049_star.png',
				'title' => __('All Field Types', 'simple-options'),
				'desc' => __('<p class="description">These are fields that are done and ready to ship.</p>', 'simple-options'),


					
				'fields' => array(

					array(
						'id' => 'theswitch', //must be unique
						'type' => 'switch',
						'title' => __('Switch', 'simple-options'),
						'sub_desc' => __('On off!', 'simple-options'),
						'desc' => __('That\'s right. It works!', 'simple-options'),
						'std' => 1, //This is a default value, used to set the options on theme activation, and if the user hits the Reset to defaults Button
						),


				array(
					'id' => 'slider222', //must be unique
					'type' => 'slider', 
					'title' => __('Slider', 'simple-options'),
					'sub_desc' => __('A slider', 'simple-options'),
					'desc' => __('That\'s right. It works! Try putting in a letter or number beyond the range. Gotta love that javascript!', 'simple-options'),
					'step' => 2,
					'min' => 1,
					'max' => 50,
					'edit' => true,
					'std' => array(
						'family'=>'Arial',
						'weight'=>'400',
						'color'=>'#000',
						'size'=>'22px',
						'script'=>'',
						'line-height'=> '14px',
						), //This is a default value, used to set the options on theme activation, and if the user hits the Reset to defaults Button
					//'class' => '' //Set custom classes for elements if you want to do something a little different - default is "typography"
					),

					array(
						'id' => 'text',
						'type' => 'text',
						'title' => __('Text Option', 'simple-options'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						),		

					array(
						'id' => 'editor',
						'type' => 'editor',
						'title' => __('Editor Option', 'simple-options'), 
						'sub_desc' => __('Can also use the validation methods if you like.', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'std' => 'OOOOOOhhhh, rich editing.'
						),
					array(
						'id' => '16',
						'type' => 'color',
						'title' => __('Color Option', 'simple-options'), 
						'sub_desc' => __('Only color validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'std' => '#FFFFFF',
						'validate' => 'color',
						),
					array(
						'id' => 'color_gradient',
						'type' => 'color_gradient',
						'title' => __('Color Gradient Option', 'simple-options'), 
						'sub_desc' => __('Only color validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'std' => array('from' => '#000000', 'to' => '#FFFFFF')
						),
					array(
						'id' => '17',
						'type' => 'date',
						'title' => __('Date Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options')
						),
					array(
						'id' => '18',
						'type' => 'button_set',
						'title' => __('Button Set Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),

					array(
						'id' => '18divide',
						'type' => 'divide',
						'title' => __('Button Set Option', 'simple-options'), 
						'sub_desc' => __('No validation can be done on this field type', 'simple-options'),
						'desc' => __('This is the description field, again good for additional info.', 'simple-options'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
									

					)
					
				);


$sections[] = array( 'type' => 'divide' );

				
	$tabs = array();
			
	if (function_exists('wp_get_theme')){
		$theme_data = wp_get_theme();
		$theme_uri = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	}else{
		$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
		$theme_uri = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}	

	$theme_info = '<div class="simple-options-section-desc">';
	$theme_info .= '<p class="simple-options-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'simple-options').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'simple-options').$author.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'simple-options').$version.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-description">'.$description.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'simple-options').implode(', ', $tags).'</p>';
	$theme_info .= '</div>';



	$tabs['theme_info'] = array(
					'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_195_circle_info.png',
					'title' => __('Theme Information', 'simple-options'),
					'content' => $theme_info
					);
	
	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
		$tabs['theme_docs'] = array(
						'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'simple-options'),
						'content' => nl2br(file_get_contents(trailingslashit(get_stylesheet_directory()).'README.html'))
						);
	}//if

	global $Simple_Options;
	$Simple_Options = new Simple_Options($sections, $args, $tabs);
	//echo $Simple_Options->value('footer-text');
}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function
