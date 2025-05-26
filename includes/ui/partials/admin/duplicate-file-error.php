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
$path_info = \Arsol_WP_Snippets\Path_Normalizer::normalize_path($dup_path);
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
                <strong>Duplicate file path detected:</strong> <?php echo '<strong>' . esc_html($path_info['source_name']) . '</strong>' . esc_html($path_info['display_path']); ?>
            </small>
        </div>
    </div>
</div> 