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
 * @var array $dup_data The duplicate file data containing file, name, and loading_order
 */

// Normalize the path
$path_info = \Arsol_WP_Snippets\Helper::normalize_path($dup_data['file']);
?>
<div class="arsol-addon-container arsol-error">
    <div class="arsol-first-column">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="arsol-label-container">
        <div class="arsol-addon-info">
            <?php
            // Set up variables for the title wrapper
            $addon_id = \Arsol_WP_Snippets\Helper::get_duplicate_file_id($dup_data);
            $addon_data = \Arsol_WP_Snippets\Helper::get_duplicate_addon_data($dup_data);
            $option_type = 'error';
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php';
            ?>
            <small class="arsol-addon-error">
                <strong>Duplicate file path detected for → </strong> <?php echo '<strong>' . esc_html($path_info['source_name']) . '</strong>' . esc_html($path_info['display_path']); ?>
                <strong>→ first used by → </strong> <?php echo '<strong>' . esc_html($dup_data['first_source']) . '</strong>'; ?>
                <strong>→ Actual Name → </strong><?php echo esc_html($dup_data['first_name']); ?>
            </small>
        </div>
    </div>
</div> 