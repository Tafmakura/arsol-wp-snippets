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

// Clean up the file path
$file_reference = $dup_path;

// Remove 'functions/' from plugin paths regardless of format
if (strpos($file_reference, 'wp-content/plugins/') !== false || strpos($file_reference, WP_PLUGIN_DIR) !== false) {
    $file_reference = preg_replace('#/functions/snippets/#', '/snippets/', $file_reference);
    $file_reference = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $file_reference);
    $file_reference = str_replace('/functions/', '/', $file_reference);
}

// Get plugin name from the file path
$source_name = '';
if (strpos($file_reference, WP_PLUGIN_DIR) === 0) {
    $plugin_path = $file_reference;
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
        $source_name = $plugin_data['Name'] . ' → ';
    } else {
        // Try to get plugin name from directory
        $plugin_name = basename($plugin_dir);
        $source_name = ucwords(str_replace('-', ' ', $plugin_name)) . ' → ';
    }
}
?>
<div class="arsol-addon-container arsol-error">
    <div class="arsol-first-column">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="arsol-label-container">
        <?php
        // Set up variables for the title wrapper
        $addon_id = 'duplicate-' . sanitize_title($dup_path);
        $addon_data = array(
            'name' => 'Duplicate File',
            'loading_order' => 0
        );
        $option_type = 'error';
        include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php';
        ?>
        <div class="arsol-addon-info">
            <small class="arsol-addon-error">
                <strong>Duplicate file path detected:</strong> <?php echo '<strong>' . esc_html($source_name) . '</strong>' . esc_html($file_reference); ?>
            </small>
        </div>
    </div>
</div> 