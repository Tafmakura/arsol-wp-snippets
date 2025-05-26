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
        
        // Get the addon data from the duplicates array
        $addon_data = array(
            'name' => is_array($dup_path) ? $dup_path['name'] : $path_info['source_name'] . basename($dup_path),
            'loading_order' => is_array($dup_path) ? $dup_path['loading_order'] : 10,
            'type' => pathinfo(is_array($dup_path) ? $dup_path['file'] : $dup_path, PATHINFO_EXTENSION)
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