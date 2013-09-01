<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Simple_Options
 * @author    Dovy Paukstys <info@simplerain.com>
 * @license   GPL-2.0+
 * @link      https://github.com/SimpleRain/SimpleOptions/
 * @copyright 2013 SimpleRain, Inc.
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here