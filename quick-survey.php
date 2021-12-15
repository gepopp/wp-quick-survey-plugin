<?php
/**
 * Plugin Name:       Quick Survey
 * Plugin URI:        https://poppgerhard.at/quick-survey
 * Description:       Add a simple questionaire to posts.
 * Version:           0.0.13
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Gerhard Popp
 * Author URI:        https://poppgerhard.at/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       quick-survey
 * Domain Path:       /languages
 */
namespace QuickSurvey;

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

$plugindata = get_plugin_data( __FILE__ );

define( 'QSY_VERSION', $plugindata['Version'] );
define( 'QSY_DIR', __DIR__ );
define( 'QSY_FILE', __FILE__ );
define( 'QSY_URL', plugin_dir_url( __FILE__ ) );

$loader = require_once( QSY_DIR . '/vendor/autoload.php' );
$loader->addPsr4( 'QuickSurvey\\', __DIR__ . '/classes' );

$instance = Boot::getInstance();
$instance->boot();