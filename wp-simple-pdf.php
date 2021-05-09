<?php
/**
 * Plugin Name:     Simple Wrapper for mpdf
 * Plugin URI:      https://github.com/kdmurthy/wp-simple-pdf
 * Description:     This plugin provides a simple wrapper for mpdf library.
 * Author:          Dakshinamurthy Karra
 * Author URI:      https://jaliansystems.com
 * Text Domain:     wp-simple-pdf
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         pdf
 */

// Your code starts here.

namespace WpSimplePDF;

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * Bootstrap plugin
 *
 * @return void
 */
function load() {
	include dirname( __FILE__ ) . '/class-simplepdf.php';
}
add_action( 'plugins_loaded', 'WpSimplePDF\load' );
