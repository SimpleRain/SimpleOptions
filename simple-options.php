<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Simple_Options
 * @author    Dovy Paukstys <info@simplerain.com>
 * @license   GPL-2.0+
 * @link      http://simplerain.com
 * @copyright 2013 SimpleRain, Inc.
 *
 * @wordpress-plugin
 * Plugin Name: Simple Options Framework
 * Plugin URI:  https://github.com/SimpleRain/SimpleOptions
 * Description: A simple wordpress options framework for developers.
 * Version:     0.3.0
 * Author:      Dovy Paukstys
 * Author URI:  http://simplerain.com
 * Text Domain: simple-options
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-simple-options.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Simple_Options', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Simple_Options', 'deactivate' ) );

//require_once( plugin_dir_path( __FILE__ ) . 'options-init.php' );

// Debugging activation errors
add_action('activated_plugin','save_error');
function save_error() {
	file_put_contents(dirname(__FILE__). '/error_activation.html', ob_get_contents());
}

if (file_exists( dirname( __FILE__ ) . '/class-simple-updater.php') ) {
	include_once( dirname( __FILE__ ) . '/class-simple-updater.php' );
}	

if (class_exists('Simple_Updater')) {
  $Simple_Updater = new Simple_Updater( array( 
		'slug' 					=> __FILE__, 
		'force_update'	=> false,
	) );	
}