<?php
/**
 * Simple Options Framework for use by developers.
 *
 * @package   Simple_Options
 * @author    Dovy Paukstys <info@simplerain.com>
 * @license   GPL-2.0+
 * @link      http://simplerain.com
 * @copyright 2013 SimpleRain, Inc.
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Simple_Options
 * @author  Dovy Paukstys <info@simplerain.com>
 */

if ( ! class_exists( 'Simple_Options' ) ) {
	class Simple_Options {			

		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 * @since   1.0.0
		 *
		 * @const   string
		 */
		const VERSION = '0.6.0';

		/**
		 * Unique identifier for your plugin.
		 *
		 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
		 * match the Text Domain file header in the main plugin file.
		 *
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $plugin_slug = 'simple-options';

		protected $plugin_url = 'https://github.com/SimpleRain/SimpleOptions';
		protected $plugin_name = 'Simple Options Framework';


		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		static $instance = null;

		/**
		 * Slug of the plugin screen.
		 *
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $plugin_screen_hook_suffix = null;

		public $dir = '';
		public $url = '';
		public $page = '';
		public $args = array();
		public $sections = array();
		public $extra_tabs = array();
		public $errors = array();
		public $warnings = array();
		public $options = array();
		public $defaults = array();
		public $bloginfo = '';


		/**
		 * Initialize the plugin by setting localization, filters, and administration functions.
		 *
		 * @since     1.0.0
		 */
		public function __construct($sections = array(), $args = array(), $extra_tabs = array()) {

			$defaults = array(
				'opt_name' => strtolower(str_replace(" ", "", wp_get_theme())),//must be defined by theme/plugin
				'google_api_key' => '',//must be defined for use with google webfonts field type
				'menu_icon' => SOF_URL.'/img/menu_icon.png',
				'menu_title' => __('Options', $this->plugin_slug),
				'page_icon' => 'icon-themes',
				'page_title' => "",
				'page_slug' => 'simple-options',
				'page_cap' => 'manage_options',
				'page_type' => 'menu',
				'page_parent' => '',
				'page_position' => 100,
				'allow_sub_menu' => true,
				'show_import_export' => true,
				'dev_mode' => false,
				'dev_queries' => false,
				'stylesheet_override' => false,
				'help_tabs' => array(),
				'help_sidebar' => '',				
				'footer_credit' => '<span id="footer-thankyou">'.__('Options Panel created using the <a href="'.$this->plugin_url.'" target="_blank">'.$this->plugin_name.'</a> Version '.self::VERSION, $this->plugin_slug).'</span>',
			);

			// Filter and set args
			$this->args = wp_parse_args($args, $defaults);
			$this->args = apply_filters('simple_options_filter_args', $this->args);	

			//filter sections
			$this->sections = apply_filters('simple-options-filter-sections-'.$this->args['opt_name'], $sections);

			//filter extra tabs
			$this->extra_tabs = apply_filters('simple-options-filter-tabs-'.$this->args['opt_name'], $extra_tabs);	
			
			/** After this point, the plugins should be registered and the configuration set */		
			
			if ( $this->sections ) { // make sure we have some sections to build

				// Load plugin text domain
				add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

				//$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'simple-options.php' );
				//add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

				// Load admin style sheet and JavaScript.
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

				//set option with defaults
				add_action('init', array($this, '_set_default_options'));	
				
				//options page
				add_action('admin_menu', array($this, '_options_page'));
				
				//register setting
				add_action('admin_init', array($this, '_register_setting'));

				//register customizer setting
				add_action( 'customize_register', array($this, '_customize_register_setting'));
							
				//hook into the wp feeds for downloading the exported settings
				add_action('do_feed_simple_options_'.$this->args['opt_name'], array($this, '_download_options'), 1, 1);
			
				// Hook to allow ajax functions, not being used
				//add_action('wp_ajax_of_ajax_post_action', array($this, '_ajax_callback'));

				// Shortcodes used within the framework
				add_shortcode('site-url', array($this, 'shortcode_site_url'));
				add_shortcode('wp-url', array($this, 'shortcode_site_url'));
				add_shortcode('theme-url', array($this, 'shortcode_theme_url'));
				add_shortcode('login-url', array($this, 'shortcode_login_url'));
				add_shortcode('logout-url', array($this, 'shortcode_logout_url'));
				add_shortcode('site-title', array($this, 'shortcode_site_title'));
				add_shortcode('site-tagline', array($this, 'shortcode_site_tagline'));
				add_shortcode('current-year', array($this, 'shortcode_current_year'));

				//get the options for use later on
				$this->options = get_option($this->args['opt_name']);	
				global $sof;
				$sof = $this->options;
				$this->bloginfo = get_bloginfo();
			}	// if			

			/** Annouce that the class is ready, and pass the object (for advanced use) */
			do_action_ref_array( 'simple_option_init', array( $this ) );			
		}

		/**
		 * Fired when the plugin is activated.
		 *
		 * @since    1.0.0
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
		 */
		public static function activate( $network_wide ) {
			// TODO: Define activation functionality here
		}

		/**
		 * Fired when the plugin is deactivated.
		 *
		 * @since    1.0.0
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
		 */
		public static function deactivate( $network_wide ) {
			// TODO: Define deactivation functionality here
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			$domain = $this->plugin_slug;
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Register and enqueue admin-specific style sheet.
		 *
		 * @since     1.0.0
		 *
		 * @return    null    Return early if no settings page is registered.
		 */
		public function enqueue_admin_styles() {

			if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
				return;
			}

			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_hook_suffix ) {
				wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), self::VERSION );
			}

		}

		public function setArgs($args) {
			$this->args = wp_parse_args($args, $this->args);
		}

		public function addSections($sections) {
			array_push($this->sections, $sections);
			//update_option( $this->args['opt_name'], $this->options );
		}

		public function setSections($sections) {
			$this->sections = $sections;
			//$this->options = $this->_default_values();
			//update_option( $this->args['opt_name'], $this->options );
		}

		public function addTabs($tabs) {
			array_push($this->extra_tabs, $tabs);
		}

		public function setTabs($tabs) {
			$this->extra_tabs = $tabs;
		}


		/**
		 * Register and enqueue admin-specific JavaScript.
		 *
		 * @since     1.0.0
		 *
		 * @return    null    Return early if no settings page is registered.
		 */
		public function enqueue_admin_scripts() {

			if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
				return;
			}

			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_hook_suffix ) {
				wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), self::VERSION );
			}

		}

		/**
		 * Render the settings page for this plugin.
		 *
		 * @since    1.0.0
		 */
		public function display_plugin_admin_page() {
			
		}

		/**
		 * Add settings action link to the plugins page.
		 *
		 * @since    1.0.0
		 */
		public function add_action_links( $links ) {

			return array_merge(
				array(
					'settings' => '<a href="' . admin_url( 'plugins.php?page=simple-options' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
				),
				$links
			);

		}


		
		/**
		 * ->get(); This is used to return an option value from the options array
		 *
		 * @since Simple_Options 1.0.0
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		*/
		function get($opt_name, $default = ""){
			return (!empty($this->options[$opt_name])) ? $this->options[$opt_name] : $default;
		}//function

		/**
		 * ->value(); This is used to return and option value from the options array after do_shortcode
		 *
		 * @since Simple_Options 1.0.0
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		*/
		function value($opt_name){
			return do_shortcode( $this->get($opt_name, true) );
		}//function
		
		/**
		 * ->set(); This is used to set an arbitrary option in the options array
		 *
		 * @since Simple_Options 1.0.1
		 * 
		 * @param string $opt_name the name of the option being added
		 * @param mixed $value the value of the option being added
		 */
		function set($opt_name = '', $value = '') {
			if($opt_name != ''){
				$this->options[$opt_name] = $value;
				update_option($this->args['opt_name'], $this->options);
			}//if
		}
		

		/**
		 * ->show(); This is used to echo and option value from the options array
		 *
		 * @since Simple_Options 1.0.1
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		*/
		function show($opt_name, $default = ''){
			$option = $this->get($opt_name);
			if(!is_array($option) && $option != ''){
				echo $option;
			}elseif($default != ''){
				echo $default;
			}
		}//function
		
		
		
		/**
		 * Get default options into an array suitable for the settings API
		 *
		 * @since Simple_Options 1.0
		 *
		*/
		function _default_values(){
			
			$defaults = array();
			
			foreach($this->sections as $k => $section){
				
				if(isset($section['fields'])){
			
					foreach($section['fields'] as $fieldk => $field){
						
						if(!isset($field['std'])){$field['std'] = '';}

						if (!isset($field['id'])) { $field['id'] = $fieldk; }
							
						$defaults[$field['id']] = $field['std'];
						
					}//foreach
				
				}//if
				
			}//foreach
			
			//fix for notice on first page load
			$defaults['last_tab'] = 0;

			$this->defaults = $defaults;

			return $defaults;
			
		}
		
		
		
		/**
		 * Set default options on admin_init if option doesnt exist (theme activation hook caused problems, so admin_init it is)
		 *
		 * @since Simple_Options 1.0
		 *
		*/
		function _set_default_options(){			
			global $sof;

			if(!get_option($this->args['opt_name'])){
				add_option($this->args['opt_name'], $this->_default_values());
			}
			
			$this->options = get_option($this->args['opt_name']);
			$sof = $this->options;

		}//function
		
		
		/**
		 * Class Theme Options Page Function, creates main options page.
		 *
		 * @since Simple_Options 1.0
		*/
		function _options_page(){

			if($this->args['page_type'] == 'submenu'){
				if(!isset($this->args['page_parent']) || empty($this->args['page_parent'])){
					$this->args['page_parent'] = 'themes.php';
				}
				$this->page = add_submenu_page(
								$this->args['page_parent'],
								$this->args['page_title'], 
								$this->args['menu_title'], 
								$this->args['page_cap'], 
								$this->args['page_slug'], 
								array($this, '_options_page_html')
							);
			}else{
				$this->page = add_menu_page(
								$this->args['page_title'], 
								$this->args['menu_title'], 
								$this->args['page_cap'], 
								$this->args['page_slug'], 
								array($this, '_options_page_html'),
								$this->args['menu_icon'],
								$this->args['page_position']
							);
							
			if(true === $this->args['allow_sub_menu']){

				if (!isset($section['type']) || $section['type'] != "divide") {
					
					//this is needed to remove the top level menu item from showing in the submenu
					add_submenu_page($this->args['page_slug'],$this->args['page_title'],'',$this->args['page_cap'],$this->args['page_slug'],create_function( '$a', "return null;" ));
											
					foreach($this->sections as $k => $section){
						if ( !isset( $section['title']) ) {
							continue;
						}

						$section = $this->_item_cleanup($section, $k);
						$this->sections[$k] = $section;

						$section = apply_filters($section['id'].'_section_menu_modifier', $section);

						$section = $this->_item_cleanup($section, $k);
						$this->sections[$k] = $section;

						add_submenu_page(
								$this->args['page_slug'],
								$section['title'],
								$section['title'], 
								$this->args['page_cap'], 
								$this->args['page_slug'].'&tab='.$section['id'], 
								create_function( '$a', "return null;" )
						);
					
					}

				}	

				if(true === $this->args['show_import_export']){
					
					add_submenu_page(
							$this->args['page_slug'],
							__('Import / Export', 'simple-options'), 
							__('Import / Export', 'simple-options'), 
							$this->args['page_cap'], 
							$this->args['page_slug'].'&tab=import_export_default', 
							create_function( '$a', "return null;" )
					);
						
				}//if
							

				foreach($this->extra_tabs as $k => $tab){
						
					// SMOF Compatibility
					if (!empty($tab['name']) && empty($tab['title'])) {
						$tab['title'] = $tab['name'];
						unset($tab['name']);
					}

					add_submenu_page(
							$this->args['page_slug'],
							$tab['title'], 
							$tab['title'], 
							$this->args['page_cap'], 
							$this->args['page_slug'].'&tab='.$k, 
							create_function( '$a', "return null;" )
					);
					
				}

				if(true === $this->args['dev_mode']){
							
					add_submenu_page(
							$this->args['page_slug'],
							__('Dev Mode Info', 'simple-options'), 
							__('Dev Mode Info', 'simple-options'), 
							$this->args['page_cap'], 
							$this->args['page_slug'].'&tab=dev_mode_default', 
							create_function( '$a', "return null;" )
					);
					
				}//if

				if(true === $this->args['dev_queries']){
							
					add_submenu_page(
							$this->args['page_slug'],
							__('Dev Mode Queries', 'simple-options'), 
							__('Dev Mode Queries', 'simple-options'), 
							$this->args['page_cap'], 
							$this->args['page_slug'].'&tab=dev_mode_queries', 
							create_function( '$a', "return null;" )
					);
					
				}//if				

			}//if			
							
				
			}//else

			add_action('admin_print_styles-'.$this->page, array($this, '_enqueue'));
			add_action('load-'.$this->page, array($this, '_load_page'));
		}//function	
		
		

		/**
		 * enqueue styles/js for theme page
		 *
		 * @since Simple_Options 1.0
		*/
		function _enqueue(){

			wp_register_style(
					'simple-options-css', 
					SOF_URL.'css/admin.css',
					array('farbtastic'),
					time(),
					'all'
				);
				
			wp_register_style(
				'simple-options-jquery-ui-css',
				apply_filters('simple-options-ui-theme', SOF_URL.'css/vendor/jquery-ui-aristo/aristo.css'),
				'',
				time(),
				'all'
			);
				
				
			if(false === $this->args['stylesheet_override']){
				wp_enqueue_style('simple-options-css');
			}
			
			
			wp_enqueue_script(
				'simple-options-js', 
				SOF_URL.'js/admin.js', 
				array('jquery'),
				time(),
				true
			);

			wp_enqueue_script(
				'showdown-js', 
				SOF_URL.'js/vendor/showdown.js', 
				array('jquery', 'simple-options-js'),
				time(),
				true
			);			

			wp_enqueue_script(
				'jquery-cookie', 
				SOF_URL.'js/vendor/cookie.js', 
				array('jquery','simple-options-js'),
				time(),
				true
			);

			wp_enqueue_script(
				'jquery-tipsy', 
				SOF_URL.'js/vendor/jquery.tipsy.js', 
				array('jquery','simple-options-js'),
				time(),
				true
			);		

			wp_enqueue_script(
				'jquery-maskedinput', 
				SOF_URL.'js/vendor/jquery.maskedinput-1.2.2.js', 
				array('jquery','simple-options-js'),
				time(),
				true
			);	

			wp_enqueue_script(
				'jquery-numeric', 
				SOF_URL.'js/vendor/jquery.numeric.js', 
				array('jquery','simple-options-js'),
				time(),
				true
			);	

			wp_localize_script('simple-options-js', 'sof_opts', array('save_pending' => __('You have changes that are not saved. Would you like to save them now?', 'simple-options'), 'reset_confirm' => __('Are you sure? Resetting will loose all custom values.', 'simple-options'), 'preset_confirm' => __('Your current options will be replaced with the values of this preset. Would you like to proceed?', 'simple-options'), 'opt_name' => $this->args['opt_name']));
			
			do_action('simple_options_enqueue');
			
			foreach($this->sections as $k => $section){
				
				if(isset($section['fields'])){
					
					foreach($section['fields'] as $fieldk => $field){

						$field = $this->_item_cleanup($field, $fieldk);

						if(isset($field['type'])){
						
							$field_class = 'Simple_Options_'.$field['type'];

							if ($field['type'] == "heading") {
								print_r($field['type']);
								continue;
							}
							
							do_action('simple_options_field_'.$field['type']);

							if(!class_exists($field_class)){
								require_once(SOF_DIR.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
							}//if
					
							if(class_exists($field_class) && method_exists($field_class, 'enqueue')){
								$enqueue = new $field_class('','',$this);
								$enqueue->enqueue();
							}//if
							
						}//if type
						
					}//foreach
				
				}//if fields
				
			}//foreach
				
			
		}//function
		
		/**
		 * Download the options file, or display it
		 *
		 * @since Simple_Options 1.0.1
		*/
		function _download_options(){
			//-'.$this->args['opt_name']
			if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)){wp_die('Invalid Secret - Smart Options Framework');exit;}
			if(!isset($_GET['feed'])){wp_die('No Feed Defined');exit;}
			$backup_options = get_option(str_replace('simple_options_','',$_GET['feed']));
			$backup_options['simple-options-backup'] = '1';
			$content = json_encode($backup_options);
			
			if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
				header('Content-Description: File Transfer');
				header('Content-type: application/txt');
				header('Content-Disposition: attachment; filename="'.str_replace('Simple_Options_','',$_GET['feed']).'_backup_'.date('d-m-Y').'.json"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				echo $content;
				exit;
			}else{
				header( 'Content-type: text/json' );
				header( 'Content-type: application/json' );
				header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); 
				header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
				header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
				header( 'Cache-Control: post-check=0, pre-check=0', false ); 
				header( 'Pragma: no-cache' ); 
				echo $content;
				exit;
			}
		}
		
		
		
		
		/**
		 * show page help
		 *
		 * @since Simple_Options 1.0
		*/
		function _load_page(){
			
			//do admin head action for this page
			add_action('admin_head', array($this, 'admin_head'));
			
			//do admin footer text hook
			add_filter('admin_footer_text', array($this, 'admin_footer_text'));
			
			$screen = get_current_screen();
			
			if(is_array($this->args['help_tabs'])){
				foreach($this->args['help_tabs'] as $tab){
					$screen->add_help_tab($tab);
				}//foreach
			}//if
			
			if($this->args['help_sidebar'] != ''){
				$screen->set_help_sidebar($this->args['help_sidebar']);
			}//if
			
			do_action('simple_options_load_page', $screen);
			
		}//function
		
		
		/**
		 * do action simple-options-admin-head for theme options page
		 *
		 * @since Simple_Options 1.0
		*/
		function admin_head(){
			
			do_action('simple_options_admin_head', $this);
			
		}//function
		
		
		function admin_footer_text($footer_text){
			if (!empty($this->args['footer_credit'])) {
				return $this->args['footer_credit'];	
			}
		}//function
		
		/**
		 * Register Option for use
		 *
		 * @since Simple_Options 1.0
		*/
		function _register_setting(){
			
			//print_r($this->options);
			//get the options for use later on
			//$this->options = get_option($this->args['opt_name']);


			register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array($this,'_validate_options'));
			$runUpdate = false;
			foreach($this->sections as $k => $section){

				if (isset($section['type']) && $section['type'] == "divide") {
					continue;
				}

				$section = $this->_item_cleanup($section, $k);
				$this->sections[$k] = $section;

				$section = apply_filters('simple-options-section-'.$section['id'].'-modifier-'.$this->args['opt_name'], $section);

				$section = $this->_item_cleanup($section, $k);
				$this->sections[$k] = $section;

				add_settings_section($section['id'].'_section', $section['title'], array($this, '_section_desc'), $section['id'].'_section_group');

				if(isset($section['fields'])){

					$section['fields'] = apply_filters('simple-options-section-'.$section['id'].'fields-modifier-'.$this->args['opt_name'], $section['fields']);
					$this->sections[$k]['fields'] = $section['fields'];

					foreach($section['fields'] as $fieldk => $field){	

						if (!isset($field['id']) || !is_numeric($fieldk)) {
							$field['id'] = $fieldk;
						}
						
						$field = $this->_item_cleanup($field, $fieldk);
						$this->sections[$k]['fields'][$fieldk] = $field;

						$field = apply_filters('simple-options-field-'.$field['id'].'modifier-'.$this->args['opt_name'], $field);
						$field = $this->_item_cleanup($field, $fieldk);
						$this->sections[$k]['fields'][$fieldk] = $field;
						
						if(isset($field['title'])){
							$th = (isset($field['subtitle']))?$field['title'].'<span class="description">'.$field['subtitle'].'</span>':$field['title'];
						}else{
							$th = '';
						}

						if (!isset($this->options[$field['id']])) {
							if (!isset($field['std'])) {
								$field['std'] = "";
							}
							$this->options[$field['id']] = $field['std'];
							$runUpdate = true;
						}

						add_settings_field($field['id'].'_field', $th, array($this,'_field_input'), $section['id'].'_section_group', $section['id'].'_section', $field); // checkbox

					}//foreach
				
				}//if(isset($section['fields'])){
				
			}//foreach
			
			do_action('simple_options_register_settings');

			if ($runUpdate) { // Always update the DB with new fields
				update_option( $this->args['opt_name'], $this->options );
			}

			if (get_transient( 'simple-options-compiler' )) {
				delete_transient( 'simple-options-compiler' );
				do_action('simple_options_compiler', $this->options);	
			}
			
		}//function
		
		function _customize_register_setting($wp_customize){
			return;
			if (!isset($this->args['customizer']) || $this->args['customizer'] != true) {
				return;
			}

			$my_theme = wp_get_theme();


			add_action( 'sanitize_option_theme_mods_'.$my_theme->{'Name'}, array($this,'_validate_options') );
			//register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array($this,'_validate_options'));
			$sectionPriority = 10;
			$fieldPriority = 10;
			foreach($this->sections as $k => $section){

				if (isset($section['type']) && $section['type'] == "divide") {
					continue;
				}

				$section = $this->_item_cleanup($section, $k);
				$this->sections[$k] = $section;

				$section = apply_filters($section['id'].'_section_modifier', $section);

				$section = $this->_item_cleanup($section, $k);
				$this->sections[$k] = $section;
				
				if (!empty($section['priority'])) {
					$theSectionPriority = $section['priority'];
				} else {
					$theSectionPriority = $sectionPriority;	
					$sectionPriority += 10;
				}
				
				$wp_customize->add_section( $section['id'].'_section', array(
				    'title'          => __( $section['title'], 'simple-options' ),
				    'priority'       => $theSectionPriority,
				    'description'		 => 'testing!!!',
				) );					
				

				if(isset($section['fields'])){
					

					$section['fields'] = apply_filters($section['id'].'_section_fields_modifier', $section['fields']);
					$this->sections[$k]['fields'] = $section['fields'];

					foreach($section['fields'] as $fieldk => $field){	
						
						$field = $this->_item_cleanup($field, $fieldk);
						$this->sections[$k]['fields'][$fieldk] = $field;

						$field = apply_filters($field['id'].'_field_modifier', $field);
						$field = $this->_item_cleanup($field, $fieldk);
						$this->sections[$k]['fields'][$fieldk] = $field;
						
						if(!isset($field['title'])){
							$field['title'] = "";
						}

						if (!empty($field['priority'])) {
							$theFieldPriority = $field['priority'];
						} else {
							$theFieldPriority = $fieldPriority;	
							$fieldPriority += 10;
						}

						$wp_customize->add_setting($field['id'].'_field', array(
							//'type'=>'option'
							));
						$wp_customize->add_control(new WP_Customize_Control($wp_customize, $field['id'].'_field', array(
							'label' => $field['title'],
							'section' => $section['id'].'_section',
							'settings' => $field['id'].'_field',
														'description' => (isset($field['subtitle']))?'<span class="description">'.$field['subtitle'].'</span>':'',
														'priority' => $theFieldPriority
						)));
						
					}//foreach
				
				}//if(isset($section['fields'])){
				
			}//foreach
			
			do_action('simple_options_customizer_register_settings');
			
		}//function		

		function add_customizer_control($option) {

		}
		
		/**
		 * Validate the Options options before insertion
		 *
		 * @since Simple_Options 1.0
		*/
		function _validate_options($plugin_options){

			set_transient('simple-options-saved', '1', 1000 );
			
			if(!empty($plugin_options['import'])){
				
				if($plugin_options['import_code'] != ''){
					$import = $plugin_options['import_code'];
				}elseif($plugin_options['import_link'] != ''){
					$import = wp_remote_retrieve_body( wp_remote_get($plugin_options['import_link']) );
				}
				
				$imported_options = json_decode(htmlspecialchars_decode($import), true);

				if(is_array($imported_options) && isset($imported_options['simple-options-backup']) && $imported_options['simple-options-backup'] == '1'){
					$plugin_options = wp_parse_args( $imported_options, $plugin_options );
					if ($_COOKIE["sof_current_tab"] == "import_export_default") {
						setcookie('sof_current_tab', '', 1, '/');
					}			
					set_transient('simple-options-compiler', '1', 1000 );
					unset($plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code']);
					return $plugin_options;
				}	
			}

			//validate fields (if needed)
			$plugin_options = $this->_validate_values($plugin_options, $this->options);

			if($this->errors){
				set_transient('simple-options-errors-'.$this->args['opt_name'], $this->errors, 1000 );		
			}//if errors
			
			if($this->warnings){
				set_transient('simple-options-warnings-'.$this->args['opt_name'], $this->warnings, 1000 );		
			}//if errors

			// If this is a new setup, init and return the defaults
			if(!empty($plugin_options['defaults'])){
				$plugin_options = $this->_default_values();
				set_transient('simple-options-compiler', '1', 1000 );
				unset($plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code']);
				return $plugin_options;
			}//if set defaults

			do_action('simple_options_validate', $plugin_options, $this->options);		
			
			if (!empty($plugin_options['compiler'])) {
				set_transient('simple-options-compiler', '1', 1000 );
			}

			unset($plugin_options['import']);
			unset($plugin_options['import_code']);
			unset($plugin_options['import_link']);
			unset($plugin_options['defaults']);
			unset($plugin_options['compiler']);

			return $plugin_options;	
		
		}//function
		
		
		
		
		/**
		 * Validate values from options form (used in settings api validate function)
		 * calls the custom validation class for the field so authors can override with custom classes
		 *
		 * @since Simple_Options 1.0
		*/
		function _validate_values($plugin_options, $options){
			foreach($this->sections as $k => $section){
				
				if(isset($section['fields'])){
				
					foreach($section['fields'] as $fieldk => $field){
						$field['section_id'] = $k;
						
						if(isset($field['type']) && $field['type'] == 'multi_text'){continue;}//we cant validate this yet
						
						if(!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == ''){
							continue;
						}
						
						//force validate of custom filed types
						
						if(isset($field['type']) && !isset($field['validate'])){
							if($field['type'] == 'color' || $field['type'] == 'color_gradient'){
								$field['validate'] = 'color';
							}elseif($field['type'] == 'date'){
								$field['validate'] = 'date';
							}
						}//if
		
						if(isset($field['validate'])){
							$validate = 'SOF_Validation_'.$field['validate'];

							do_action('simple_options_get_validation');
							
							if(!class_exists($validate)){
								require_once(SOF_DIR.'validation/'.$field['validate'].'/validation_'.$field['validate'].'.php');
							}//if
							
							if(class_exists($validate)){
								$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
								$plugin_options[$field['id']] = $validation->value;
								if(isset($validation->error)){
									$this->errors[] = $validation->error;
								}
								if(isset($validation->warning)){
									$this->warnings[] = $validation->warning;
								}
								continue;
							}//if
						}//if
						
						
						if(isset($field['validate_callback']) && function_exists($field['validate_callback'])){
							
							$callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
							$plugin_options[$field['id']] = $callbackvalues['value'];
							if(isset($callbackvalues['error'])){
								$this->errors[] = $callbackvalues['error'];
							}//if
							if(isset($callbackvalues['warning'])){
								$this->warnings[] = $callbackvalues['warning'];
							}//if
							
						}//if
						
						
					}//foreach
				
				}//if(isset($section['fields'])){
				
			}//foreach
			return $plugin_options;
		}//function
		
		
		
		
		
		
		
		
		/**
		 * HTML OUTPUT.
		 *
		 * @since Simple_Options 1.0
		*/
		function _options_page_html(){

			//add the js for the error handling before the form
			add_action('simple-options-page-before-form-'.$this->args['opt_name'], array($this, '_errors_js'), 1);
			
			//add the js for the warning handling before the form
			add_action('simple-options-page-before-form-'.$this->args['opt_name'], array($this, '_warnings_js'), 2);


			$saved = get_transient('simple-options-saved');
			delete_transient('simple-options-saved');

			echo '<div class="clear"></div><div class="wrap">
			<input type="hidden" id="security" name="security" value="'.wp_create_nonce('of_ajax_nonce').'" />
				<noscript><div class="no-js">Warning- This options panel will not work properly without javascript!</div></noscript>';
				if ( $title = get_admin_page_title() && get_admin_page_title() != "" ) {
					echo '<div id="'.$this->args['page_icon'].'" class="icon32"><br/></div>';
					echo '<h2 id="simple-options-heading">'.get_admin_page_title().'</h2>';				
				}
				echo (isset($this->args['intro_text']))?$this->args['intro_text']:'';
				
				do_action('simple_options_page_before_form');
				echo '<div id="sof-container">';
				echo '<form method="post" action="options.php" enctype="multipart/form-data" id="simple-options-form-wrapper">';
				echo '<input type="hidden" id="sof-compiler-hook" name="'.$this->args['opt_name'].'[compiler]" value="" />';
					settings_fields($this->args['opt_name'].'_group');
					
					if (empty($this->options['last_tab'])) {
						$this->options['last_tab'] = "";
					}
					$this->options['last_tab'] = (isset($_GET['tab']) && !$saved)?$_GET['tab']:$this->options['last_tab'];
					
					echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.$this->options['last_tab'].'" />';
					$my_theme = wp_get_theme();
					echo '<div id="simple-options-header">';
						echo '<div class="logo"><h2>'.$my_theme->{'Name'}.'</h2><span>'.$my_theme->{'Version'}.'</span></div>
							 		<div id="js-warning" style="display: none;">Warning- This options panel will not work properly without javascript!</div>
							  	<div class="icon-option"></div>
									<div class="clear"></div><!--clearfix-->';
					echo '</div>';
					echo '<div id="simple-options-sticky">';
						echo '<div id="info_bar">';
							echo '<a href="#" id="expand_options">Expand</a>';
							echo '<div class="sof-action_bar">';
								submit_button('', 'primary', 'sof_save', false);
								echo "&nbsp;";
								submit_button(__('Reset to Defaults', 'simple-options'), 'secondary', $this->args['opt_name'].'[defaults]', false);
							echo "</div>";
							echo '<div class="sof-ajax-loading" alt="Working...">&nbsp;</div>';
							echo '<div class="clear"></div><!--clearfix-->';
						echo '</div>';
						if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && $saved == '1'){
							if(isset($this->options['imported']) && $this->options['imported'] == 1){
								echo '<div id="simple-options-imported">'.apply_filters('simple-options-imported-text-'.$this->args['opt_name'], ''.__('<strong>Settings Imported!</strong>', 'simple-options')).'</div>';
							}else{
								echo '<div id="simple-options-save">'.apply_filters('simple-options-saved-text-'.$this->args['opt_name'], __('<strong>Settings Saved!</strong>', 'simple-options')).'</div>';
							}
						}
						echo '<div id="simple-options-save-warn">'.apply_filters('simple-options-changed-text-'.$this->args['opt_name'], __('<strong>Settings have changed, you should save them!</strong>', 'simple-options')).'</div>';
						echo '<div id="simple-options-field-errors">'.__('<strong><span></span> error(s) were found!</strong>', 'simple-options').'</div>';
						
						echo '<div id="simple-options-field-warnings">'.__('<strong><span></span> warning(s) were found!</strong>', 'simple-options').'</div>';
					echo '</div>';				
					echo '<div class="clear"></div><!--clearfix-->';
					
					echo '<div id="simple-options-sidebar">';
						echo '<ul id="simple-options-group-menu">';
							foreach($this->sections as $k => $section){
								// SMOF Compatibility
								if (!empty($section['name']) && empty($section['title'])) {
									$section['title'] = $section['name'];
									unset($section['name']);
								}								
								if (isset($section['type']) && $section['type'] == "divide") {
									echo '<li class="divide">&nbsp;</li>';
								} else {
									$icon = (!isset($section['icon']))?'<img src="'.SOF_URL.'img/glyphicons/glyphicons_019_cogwheel.png" /> ':'<img src="'.$section['icon'].'" /> ';
									echo '<li id="'.$section['id'].'_section_group_li" class="simple-options-group-tab-link-li">';
										echo '<a href="javascript:void(0);" id="'.$section['id'].'_section_group_li_a" class="simple-options-group-tab-link-a" data-rel="'.$section['id'].'">'.$icon.'<span>'.$section['title'].'</span></a>';
									echo '</li>';								
								}						}
							
							echo '<li class="divide">&nbsp;</li>';
							
							do_action('simple_options_page_after_menu', $this);
							
							if(true === $this->args['show_import_export']){
								echo '<li id="import_export_default_section_group_li" class="simple-options-group-tab-link-li">';
										echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="simple-options-group-tab-link-a" data-rel="import_export_default"><img src="'.SOF_URL.'img/glyphicons/glyphicons_082_roundabout.png" /> <span>'.__('Import / Export', 'simple-options').'</span></a>';
								echo '</li>';
								echo '<li class="divide">&nbsp;</li>';
							}//if
							
							
							
							
							
							foreach($this->extra_tabs as $k => $tab){
								$icon = (!isset($tab['icon']))?'<img src="'.SOF_URL.'img/glyphicons/glyphicons_019_cogwheel.png" /> ':'<img src="'.$tab['icon'].'" /> ';
								echo '<li id="'.$k.'_section_group_li" class="simple-options-group-tab-link-li">';
									echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="simple-options-group-tab-link-a custom-tab" data-rel="'.$k.'">'.$icon.'<span>'.$tab['title'].'</span></a>';
								echo '</li>';
							}

							
							if(true === $this->args['dev_mode']){
								echo '<li id="dev_mode_default_section_group_li" class="simple-options-group-tab-link-li">';
										echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="simple-options-group-tab-link-a custom-tab" data-rel="dev_mode_default"><img src="'.SOF_URL.'img/glyphicons/glyphicons_195_circle_info.png" /> <span>'.__('Dev Mode Info', 'simple-options').'</span></a>';
								echo '</li>';
							}//if

							if(true === $this->args['dev_queries']){
								echo '<li id="dev_mode_queries_section_group_li" class="simple-options-group-tab-link-li">';
										echo '<a href="javascript:void(0);" id="dev_mode_queries_section_group_li_a" class="simple-options-group-tab-link-a custom-tab" data-rel="dev_mode_queries"><img src="'.SOF_URL.'img/glyphicons/glyphicons_195_circle_info.png" /> <span>'.__('Dev Mode Queries', 'simple-options').'</span></a>';
								echo '</li>';
							}//if							
							
						echo '</ul>';
					echo '</div>';
					
					echo '<div id="simple-options-main">';
					
						foreach($this->sections as $k => $section){
							$section = $this->_item_cleanup($section, $k);
							echo '<div id="'.$section['id'].'_section_group'.'" class="simple-options-group-tab">';
								do_settings_sections($section['id'].'_section_group');
							echo '</div>';
						}					
						
						
						if(true === $this->args['show_import_export']){
							echo '<div id="import_export_default_section_group'.'" class="simple-options-group-tab">';
								echo '<h3>'.__('Import / Export Options', 'simple-options').'</h3>';
								
								echo '<h4>'.__('Import Options', 'simple-options').'</h4>';
								
								echo '<p><a href="javascript:void(0);" id="simple-options-import-code-button" class="button-secondary">Import from file</a> <a href="javascript:void(0);" id="simple-options-import-link-button" class="button-secondary">Import from URL</a></p>';
								
								echo '<div id="simple-options-import-code-wrapper">';
								
									echo '<div class="simple-options-section-desc">';
					
										echo '<p class="description" id="import-code-description">'.apply_filters('simple-options-import-file-description',__('Input your backup file below and hit Import to restore your sites options from a backup.', 'simple-options')).'</p>';
									
									echo '</div>';
									
									echo '<textarea id="import-code-value" name="'.$this->args['opt_name'].'[import_code]" class="large-text noUpdate" rows="8"></textarea>';
								
								echo '</div>';
								
								
								echo '<div id="simple-options-import-link-wrapper">';
								
									echo '<div class="simple-options-section-desc">';
										
										echo '<p class="description" id="import-link-description">'.apply_filters('simple-options-import-link-description',__('Input the URL to another sites options set and hit Import to load the options from that site.', 'simple-options')).'</p>';
									
									echo '</div>';

									echo '<input type="text" id="import-link-value" name="'.$this->args['opt_name'].'[import_link]" class="large-text noUpdate" value="" />';
								
								echo '</div>';
								
								
								
								echo '<p id="simple-options-import-action"><input type="submit" id="simple-options-import" name="'.$this->args['opt_name'].'[import]" class="button-primary" value="'.__('Import', 'simple-options').'"> <span>'.apply_filters('simple-options-import-warning', __('WARNING! This will overwrite any existing options, please proceed with caution!', 'simple-options')).'</span></p>';
								echo '<div class="hr"/><div class="inner"><span>&nbsp;</span></div></div>';
								
								echo '<h4>'.__('Export Options', 'simple-options').'</h4>';
								echo '<div class="simple-options-section-desc">';
									echo '<p class="description">'.apply_filters('simple-options-backup-description', __('Here you can copy/download your themes current option settings. Keep this safe as you can use it as a backup should anything go wrong. Or you can use it to restore your settings on this site (or any other site). You also have the handy option to copy the link to yours sites settings. Which you can then use to duplicate on another site', 'simple-options')).'</p>';
								echo '</div>';
								
									echo '<p><a href="javascript:void(0);" id="simple-options-export-code-copy" class="button-secondary">Copy</a> <a href="'.add_query_arg(array('feed' => 'simple_options_'.$this->args['opt_name'], 'action' => 'download_options', 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" id="simple-options-export-code-dl" class="button-primary">Download</a> <a href="javascript:void(0);" id="simple-options-export-link" class="button-secondary">Copy Link</a></p>';
									$backup_options = $this->options;
									$backup_options['simple-options-backup'] = '1';
									$encoded_options = json_encode($backup_options);
									echo '<textarea class="large-text noUpdate" id="simple-options-export-code" rows="8">';print_r($encoded_options);echo '</textarea>';
									echo '<input type="text" class="large-text noUpdate" id="simple-options-export-link-value" value="'.add_query_arg(array('feed' => 'simple_options_'.$this->args['opt_name'], 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" />';

							echo '</div>';
						}
						
						
						
						foreach($this->extra_tabs as $k => $tab){
							echo '<div id="'.$k.'_section_group'.'" class="simple-options-group-tab">';
							echo '<h3 class="test">'.$tab['title'].'</h3>';
							echo $tab['content'];
							echo '</div>';
						}

						
						
						if(true === $this->args['dev_mode']){
							echo '<div id="dev_mode_default_section_group'.'" class="simple-options-group-tab">';
								echo '<h3>'.__('Dev Mode Info', 'simple-options').'</h3>';
								echo '<div class="simple-options-section-desc">';
								echo '<textarea class="large-text" rows="38">'.print_r($this, true).'</textarea>';
								echo '</div>';
								echo '
											<script>
												function sof_object() {
													console.log( jQuery.parseJSON( decodeURIComponent( jQuery("#sof-object").val() ) ) );	
													return;
												}
											</script>';

								echo '<input type="hidden" id="sof-object" value="'.urlencode(json_encode($this)).'" /><a href="javascript:sof_object()" class="button">Show Object in Javascript Console Object</a>';
							echo '</div>';
						}

						if(true === $this->args['dev_queries']){
							echo '<div id="dev_mode_queries_section_group'.'" class="simple-options-group-tab">';
								echo '<h3>'.__('Dev Queries', 'simple-options').'</h3>';
								global $wpdb;
								echo '<textarea class="large-text" rows="38">';
					    	print_r($wpdb->queries);
					    	echo "</textarea>";
							echo '</div>';
						}						
						
						
						do_action('simple_options_page_after_section', $this);
					
						echo '<div class="clear"></div><!--clearfix-->';
					echo '</div>';
					echo '<div class="clear"></div><!--clearfix-->';
					echo '<div id="simple-options-sticky-padder" style="display: none;">&nbsp;</div>';
					echo '<div id="simple-options-footer-sticky"><div id="simple-options-footer">';
					
						if(isset($this->args['share_icons'])){
							echo '<div id="simple-options-share">';
							foreach($this->args['share_icons'] as $link){
								echo '<a href="'.$link['link'].'" title="'.$link['title'].'" target="_blank"><img src="'.$link['img'].'"/></a>';
							}
							echo '</div>';
						}
						
							echo '<div class="sof-action_bar">';
								submit_button('', 'primary', 'sof_save', false);
								echo "&nbsp;";
								submit_button(__('Reset to Defaults', 'simple-options'), 'secondary', $this->args['opt_name'].'[defaults]', false);
							echo "</div>";
							echo '<div class="sof-ajax-loading" alt="Working...">&nbsp;</div>';
							echo '<div class="clear"></div><!--clearfix-->';					


					echo '</div>';
				
				echo '</form>';
				echo '</div></div>';
				
				do_action('simple_options_page_after_form');
				
				echo '<div class="clear"></div><!--clearfix-->';	
			echo '</div><!--wrap-->';
			if (true === $this->args['dev_mode']) {
				echo '<br /><div class="sof-timer">'.get_num_queries().' queries in '.timer_stop(0).' seconds</div>';
			}

		}//function
		
		
		
		/**
		 * JS to display the errors on the page
		 *
		 * @since Simple_Options 1.0
		*/	
		function _errors_js(){
			
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('simple-options-errors-'.$this->args['opt_name'])){
				$errors = get_transient('simple-options-errors-'.$this->args['opt_name']);
				$section_errors = array();
				foreach($errors as $error){
					$section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']]))?$section_errors[$error['section_id']]:0;
					$section_errors[$error['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#simple-options-field-errors span").html("'.count($errors).'");';
						echo 'jQuery("#simple-options-field-errors").show();';
						
						foreach($section_errors as $sectionkey => $section_error){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"simple-options-menu-error\">'.$section_error.'</span>");';
						}
						
						foreach($errors as $error){
							echo 'jQuery("#'.$error['id'].'").addClass("simple-options-field-error");';
							echo 'jQuery("#'.$error['id'].'").closest("td").append("<span class=\"simple-options-th-error\">'.$error['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('simple-options-errors-'.$this->args['opt_name']);
			}
			
		}//function
		
		
		
		/**
		 * JS to display the warnings on the page
		 *
		 * @since Simple_Options 1.0.3
		*/	
		function _warnings_js(){
			
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('simple-options-warnings-'.$this->args['opt_name'])){
				$warnings = get_transient('simple-options-warnings-'.$this->args['opt_name']);
				$section_warnings = array();
				foreach($warnings as $warning){
					$section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']]))?$section_warnings[$warning['section_id']]:0;
					$section_warnings[$warning['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#simple-options-field-warnings span").html("'.count($warnings).'");';
						echo 'jQuery("#simple-options-field-warnings").show();';
						
						foreach($section_warnings as $sectionkey => $section_warning){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"simple-options-menu-warning\">'.$section_warning.'</span>");';
						}
						
						foreach($warnings as $warning){
							echo 'jQuery("#'.$warning['id'].'").addClass("simple-options-field-warning");';
							echo 'jQuery("#'.$warning['id'].'").closest("td").append("<span class=\"simple-options-th-warning\">'.$warning['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('simple-options-warnings-'.$this->args['opt_name']);
			}
			
		}//function
		
		

		
		
		/**
		 * Section HTML OUTPUT.
		 *
		 * @since Simple_Options 1.0
		*/	
		function _section_desc($section){
			
			$id = rtrim($section['id'], '_section');
			
			if(isset($this->sections[$id]['description']) && !empty($this->sections[$id]['description'])) {
				echo '<div class="simple-options-section-desc">'.$this->sections[$id]['description'].'</div>';
			}
			
		}//function
		
		
		
		
		/**
		 * Field HTML OUTPUT.
		 *
		 * Gets option from options array, then calls the speicfic field type class - allows extending by other devs
		 *
		 * @since Simple_Options 1.0
		*/
		function _field_input($field){

			// Setup the fold hidden object to be used by the JS
			if (!empty($field['fold'])) {

				if ( !is_array( $field['fold'] ) ) {
					$field['fold'] = array($field['fold']);
				}

				$data = $val = "";
				foreach( $field['fold'] as $foldk => $foldv ) {

					if ($foldv === true || $foldv === "1" || (is_int($foldv) && $foldv === 1)) {
						$foldv = array(1);
					} else if ($foldv === false || $foldv === null) {
						$foldv = array(0);
					} else if (!is_array($foldv)) {
						$foldk = $foldv;
						$foldv = array("0");
					}

					$data .= ' data-'.$foldk.'="'.implode(",", $foldv).'"';
					$val .= $foldk.",";
				}

				$val = rtrim($val, ',');
				if ($field['type'] != "info") {
					echo '<input type="hidden" '.$data.' id="foldChild-'.$field['id'].'" class="fold-data" value="'.$val.'" />';	
				} else {
					$field['fold-ids'] = $data;
					$field['fold-vals'] = $val;
				}
				
			}			

			if(isset($field['callback']) && function_exists($field['callback'])){
				$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
				do_action('simple_options_before_field_callback', $field, $value);
				call_user_func($field['callback'], $field, $value);
				do_action('simple_options_after_field_callback', $field, $value);
				return;
			}
			
			if(isset($field['type'])){
				
				$field_class = 'Simple_Options_'.$field['type'];
				
				if(class_exists($field_class)){
					require_once(SOF_DIR.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
				}//if
				
				if(class_exists($field_class)){
					$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
					do_action('simple_options_before_field', $field, $value);
					$render = '';
					$render = new $field_class($field, $value, $this);
					$render->render();
					do_action('simple_options_after_field', $field, $value);
				}//if
				
			}//if $field['type']
			
		}//function

		/**
		* SimpleOptions compatability. Changes name to title and creates ids if not present
		*
		* @since Simple_Options 0.1.2
		*
		*/
		function _item_cleanup($item, $k) {
			// Cleanup the types. SMOF compatability


			if (!empty($item['name']) && empty($item['title'])) {
				$item['title'] = $item['name'];
				unset($item['name']);
			}

			if (!empty($item['desc']) && empty($item['description'])) {
				$item['description'] = $item['desc'];
				unset($item['desc']);
			}

			if (!empty($item['std']) && empty($item['description']) && !empty($item['type']) && $item['type'] == "info") {
				$item['description'] = $item['std'];
				unset($item['std']);
			}			

			if (!empty($item['sub_desc']) && empty($item['subtitle'])) {
				$item['subtitle'] = $item['sub_desc'];
				unset($item['sub_desc']);
			}

			if (empty($item['id']) && !empty($item['title'])) {
				$item['id'] = strtolower(str_replace(' ','_', preg_replace('/[^A-Za-z0-9\-]/', '', $item['title'])));
			} else if (empty($item['id'])) {
				$item['id'] = $k;
			}	

			return $item;

		}//if

		/**
		* SimpleOptions compatability. Changes types inherit in SMOF to match SOF naming conventions
		*
		* @since Simple_Options 0.1.2
		*
		*/
		function _item_type_cleanup($item) {

			// SMOF Compatability
			if ($item['type'] == "sliderui") {
				$item['type'] = "slider";
			} else if ($item['type'] == "slider" && !empty($item['name'])) {
				$item['type'] == "slides";
			}
			if ($item['type'] == "tiles") {
				$item['type'] = "images";
				$item['pattern'] = true;
			}	
			if ($item['type'] == "multicheck") {
				$item['type'] == "multi_checkbox";
			} 
			if ($item['type'] == "upload") {
				$item['type'] == "media";
			} 
			if ($item['type'] == "select_google_font" || $item['type'] == "select_google_font_hybrid") {
				$item['type'] == "typography";
			} 
			if ($item['type'] == "images") {
				$item['type'] == "radio_images";
			} 			

			return $item;

		}//if		

	/**

	 SimpleOptions Shortcodes. For use within field values.

	 @since Simple_Options 0.0.9

	*/

		/**
			Shortcode - Site URL (URI/Link)
		**/

		function shortcode_site_url($atts,$content=NULL) {
			return $this->$bloginfo['url'];
		}

		/**
			Shortcode - Wordpress URL (URI/Link)
		**/

		function shortcode_wp_url($atts,$content=NULL) {
			return $this->$bloginfo['wpurl'];
		}		

		/**
			Shortcode - Theme URL (URI/Link)
		**/
		function shortcode_theme_url($atts,$content=NULL) {
			return $this->$bloginfo['stylesheet_directory'];
		}

		/**
			Shortcode - Login URL (URI/Link)
		**/
		function shortcode_login_url($atts,$content=NULL) {
			return wp_login_url();
		}

		/**
			Shortcode - Logout URL (URI/Link)
		**/
		function shortcode_logout_url($atts,$content=NULL) {
			return wp_logout_url();
		}

		/**
			Shortcode - Site Title
		**/
		function shortcode_site_title($atts,$content=NULL) {
			return $this->$bloginfo['name'];
		}

		/**
			Shortcode - Site Tagline
		**/
		function shortcode_site_tagline($atts,$content=NULL) {
			return $this->$bloginfo['description'];
		}

		/**
			Shortcode - Current Year
		**/
		function shortcode_current_year($atts,$content=NULL) {
			return date("Y");
		}

		/**
		  Ajax Callback Function
		
		  @since 1.0.0
		 */
		function _ajax_callback() {

			$nonce=$_POST['security'];
			
			if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
			$save_type = $_POST['type'];
			
			print_r($_POST);
			die('1');
			
			//Uploads
			if($save_type == 'upload') {
				
			}
		  	die();
		}	// function


		/**
		 * Add any number of sections to our current sections.
		 *
		 *
		 * @since 1.0.0
		 *
		 * @param array $sections Array of section arrays.
		 */		
		public function sections( $sections ) {
			if (isset($sections['title'])) {
				array_push($this->sections, $sections);
			} else {
				$this->sections += $sections;
			}
		}

		/**
		 * Add any number of extra tabs to our current extra tabs.
		 *
		 *
		 * @since 1.0.0
		 *
		 * @param array $sections Array of section arrays.
		 */		
		public function extra_tabs( $tabs ) {
			if (isset($tabs['title'])) {
				array_push($this->extra_tabs, $tabs);
			} else {
				$this->extra_tabs += $tabs;
			}
		}		

		/**
		 * Amend default configuration settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $config
		 */
		public function args( $args ) {
			// Only want to set keys we use
			$key = array( 'opt_name', 'google_api_key', 'menu_icon', 'menu_title', 'page_icon', 'page_title', 'page_slug', 'page_cap' => 'manage_options', 'page_type', 'page_parent', 'page_position', 'allow_sub_menu', 'show_import_export', 'dev_mode', 'dev_queries', 'stylesheet_override', 'help_tabs', 'help_sidebar', 'footer_credit' );
			foreach ( $key as $arg ) {
				if ( isset( $args[$arg] ) ) {
					if ( !is_array( $args[$arg] ) ) {
						$this->args[$arg] = $args[$arg];
					}
				}
			}
		}				
	} // class

	/** Create a new instance of the class */
	global $Simple_Options, $sof;

	/**
	 * Helper function to register the sections, arguments, and extra_tabs.
	 *
	 * @since 1.0.0
	 * @api
	 *
	 * @param array $sections An array of section arrays.
	 * @param array $args Optional. An array of argument/configuration values.
	 * @param array $extra_tabs Optional. An array of extra tabs.
	 */
	function Simple_Options( $sections = array(), $args = array(), $tabs = array()) {
		global $Simple_Options, $sof;	
		$Simple_Options = new Simple_Options($sections, $args, $tabs);
	} // function

	// windows-proof constants: replace backward by forward slashes - thanks to: https://github.com/peterbouwmeester
	$fslashed_dir = trailingslashit(str_replace('\\','/',dirname(__FILE__)));
	$fslashed_abs = trailingslashit(str_replace('\\','/',ABSPATH));	
	
	// Set the global directory
	if(!defined('SOF_DIR')){
		define('SOF_DIR', $fslashed_dir);
	}

	// Set the global URL
	if(!defined('SOF_URL')){
		define('SOF_URL', site_url(str_replace( $fslashed_abs, '', $fslashed_dir )));
	}		
} // if






