<?php
/**
 * Main plugin file
 *
 * @package   TogetherJS
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      http://github.com/barryceelen
 * @copyright 2013 Barry Ceelen
 *
 * Plugin Name: TogetherJS
 * Plugin URI:  http://github.com/barryceelen/wp-together-js
 * Description: Adds together.js to your WordPress site.
 * Version:     0.0.1
 * Author:      Barry Ceelen
 * Author URI:  http://github.com/barryceelen
 * Text Domain: together-js
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include and instantiate main plugin class.
require_once( plugin_dir_path( __FILE__ ) . 'class-together-js.php' );
add_action( 'plugins_loaded', array( 'TogetherJS', 'get_instance' ) );