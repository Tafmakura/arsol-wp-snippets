<?php
/**
 * Plugin Name: Arsol WP Snippets
 * Plugin URI: https://github.com/Tafmakura/arsol-wp-snippets
 * Description: A WordPress plugin to add custom code snippets and enhancements with a safe mode feature for troubleshooting. Check out our <a href="https://github.com/Tafmakura/arsol-wp-snippets">GitHub repository</a> for documentation and our <a href="https://github.com/Tafmakura/arsol-wps-packet-example">packet template</a> for examples.
 * Version: 0.0.14
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Taf Makura
 * Author URI: https://github.com/Tafmakura
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arsol-wp-snippets
 * Domain Path: /languages
 * 
 * @package Arsol_WP_Snippets
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ARSOL_WP_SNIPPETS_PLUGIN_FILE', __FILE__);
define('ARSOL_WP_SNIPPETS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ARSOL_WP_SNIPPETS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ARSOL_WP_SNIPPETS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Get the plugin version from the plugin header
 *
 * @return string The plugin version
 */
function arsol_wp_snippets_get_version() {
    static $version = null;
    
    if ($version === null) {
        $plugin_data = get_plugin_data(ARSOL_WP_SNIPPETS_PLUGIN_FILE);
        $version = $plugin_data['Version'];
    }
    
    return $version;
}

// Setup Class
require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-setup.php';

// Initialize the plugin
function arsol_wp_snippets_init() {
    // Initialize setup class which handles autoloading and other initialization
    new \Arsol_WP_Snippets\Setup();
}
add_action('plugins_loaded', 'arsol_wp_snippets_init');