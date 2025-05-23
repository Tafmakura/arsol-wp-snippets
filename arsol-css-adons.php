<?php
/**
 * Plugin Name: Arsol CSS Addons
 * Plugin URI: https://your-site.com/arsol-css-addons
 * Description: A WordPress plugin to add custom CSS functionality and enhancements
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Taf Makura
 * Author URI: https://your-site.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arsol-css-addons
 * Domain Path: /languages
 * 
 * @package Arsol_CSS_Addons
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
define('ARSOL_CSS_ADDONS_PLUGIN_FILE', __FILE__);
define('ARSOL_CSS_ADDONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ARSOL_CSS_ADDONS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ARSOL_CSS_ADDONS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('ARSOL_CSS_ADDONS_VERSION', '1.0.0');
define('ARSOL_CSS_ADDONS_ASSETS_VERSION', '1.0.0'); // Specific version for assets, helpful for cache busting

// Use correct namespace
use Arsol_CSS_Addons\Setup;

// Include the Setup class
require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-setup.php';