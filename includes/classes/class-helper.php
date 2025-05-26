<?php
namespace Arsol_WP_Snippets;

/**
 * Helper Class
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Helper {
    /**
     * Normalize a file path and get its display information
     *
     * @param string $file_path The file path to normalize
     * @return array Array containing normalized path and source information
     */
    public static function normalize_path($file_path) {
        $result = array(
            'normalized_path' => $file_path,
            'source_name' => '',
            'display_path' => $file_path
        );

        // Clean up the file path
        $file_reference = $file_path;

        // Remove 'functions/' from plugin paths regardless of format
        if (strpos($file_reference, 'wp-content/plugins/') !== false || strpos($file_reference, WP_PLUGIN_DIR) !== false) {
            $file_reference = preg_replace('#/functions/snippets/#', '/snippets/', $file_reference);
            $file_reference = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $file_reference);
            $file_reference = str_replace('/functions/', '/', $file_reference);
        }

        // Get source name and normalize path based on file location
        if (strpos($file_reference, get_stylesheet_directory_uri()) === 0) {
            $result['source_name'] = wp_get_theme()->get('Name') . ' → ';
            $result['normalized_path'] = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $file_reference);
        } elseif (strpos($file_reference, get_template_directory_uri()) === 0) {
            $result['source_name'] = wp_get_theme()->get('Name') . ' → ';
            $result['normalized_path'] = str_replace(get_template_directory_uri(), get_template_directory(), $file_reference);
        } elseif (strpos($file_reference, plugins_url()) === 0 || strpos($file_reference, WP_PLUGIN_DIR) === 0) {
            // Get plugin name from the file path
            $plugin_path = str_replace(plugins_url(), WP_PLUGIN_DIR, $file_reference);
            $plugin_dir = dirname($plugin_path);
            
            // Keep going up until we find the plugin's main PHP file
            $found_plugin = false;
            while (!$found_plugin && strpos($plugin_dir, WP_PLUGIN_DIR) === 0) {
                $plugin_file = $plugin_dir . '/' . basename($plugin_dir) . '.php';
                if (file_exists($plugin_file)) {
                    $found_plugin = true;
                } else {
                    $plugin_dir = dirname($plugin_dir);
                }
            }
            
            if ($found_plugin) {
                $plugin_data = get_plugin_data($plugin_file);
                $result['source_name'] = $plugin_data['Name'] . ' → ';
            } else {
                // Try to get plugin name from directory
                $plugin_name = basename($plugin_dir);
                $result['source_name'] = ucwords(str_replace('-', ' ', $plugin_name)) . ' → ';
            }
            
            $result['normalized_path'] = $plugin_path;
        }

        // Set the display path
        $result['display_path'] = $result['normalized_path'];

        return $result;
    }

    /**
     * Get addon data from duplicate file information
     *
     * @param array $dup_data The duplicate file data containing file, name, and loading_order
     * @return array Array containing addon data for display
     */
    public static function get_duplicate_addon_data($dup_data) {
        return array(
            'name' => $dup_data['name'],
            'loading_order' => $dup_data['loading_order'],
            'type' => pathinfo($dup_data['file'], PATHINFO_EXTENSION)
        );
    }

    /**
     * Get duplicate file ID for HTML elements
     *
     * @param array $dup_data The duplicate file data containing file, name, and loading_order
     * @return string Sanitized ID for the duplicate file
     */
    public static function get_duplicate_file_id($dup_data) {
        return 'duplicate-' . sanitize_title($dup_data['file']);
    }

    /**
     * Process duplicate file data for storage
     *
     * @param array $addon_data The addon data containing file, name, and optional loading_order
     * @return array Processed duplicate file data
     */
    public static function process_duplicate_data($addon_data) {
        // Get the source name for the first file
        $path_info = self::normalize_path($addon_data['file']);
        return array(
            'file' => $addon_data['file'],
            'name' => $addon_data['name'],
            'loading_order' => isset($addon_data['loading_order']) ? $addon_data['loading_order'] : 10,
            'first_source' => $path_info['source_name'],
            'first_name' => $addon_data['name']
        );
    }
} 