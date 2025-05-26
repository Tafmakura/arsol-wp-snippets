<?php
/**
 * Template for displaying duplicate file errors
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @var string $dup_path The duplicate file path to display
 */

// Normalize the path
$path_info = \Arsol_WP_Snippets\Helper::normalize_path($dup_path);
?>
<div class="arsol-addon-container arsol-error">
    <div class="arsol-first-column">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="arsol-label-container">
        <?php
        // Set up variables for the title wrapper
        $addon_id = 'duplicate-' . sanitize_title($dup_path);
        
        // Try to get the addon data from filter options first
        $file_extension = pathinfo($dup_path, PATHINFO_EXTENSION);
        $filtered_addons = array();
        
        // Get addon data from appropriate filter based on file type
        if ($file_extension === 'php') {
            $filtered_addons = apply_filters('arsol_wp_snippets_php_addon_files', array());
        } elseif ($file_extension === 'css') {
            $filtered_addons = apply_filters('arsol_wp_snippets_css_addon_files', array());
        } elseif ($file_extension === 'js') {
            $filtered_addons = apply_filters('arsol_wp_snippets_js_addon_files', array());
        }
        
        // Find the matching addon data
        $matching_addon = null;
        foreach ($filtered_addons as $id => $data) {
            if (isset($data['file']) && $data['file'] === $dup_path) {
                $matching_addon = $data;
                break;
            }
        }
        
        $addon_data = array(
            'name' => $matching_addon ? $matching_addon['name'] : $path_info['source_name'] . basename($dup_path),
            'loading_order' => $matching_addon ? $matching_addon['loading_order'] : 0,
            'type' => $file_extension
        );
        $option_type = 'error';
        include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php';
        ?>
        <div class="arsol-addon-info">
            <small class="arsol-addon-error">
                <strong>Duplicate file path detected at → </strong> <?php echo esc_html($path_info['display_path']); ?>
            </small>
        </div>
    </div>
</div> 