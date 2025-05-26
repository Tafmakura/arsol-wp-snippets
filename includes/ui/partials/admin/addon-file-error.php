<?php
/**
 * Template for displaying addon file errors
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @var array $error The error data containing type, message, and data
 * @var string $option_type The type of addon (php, css, js)
 */

// Get the addon data
$addon_data = $error['data'];
$path_info = \Arsol_WP_Snippets\Helper::normalize_path($addon_data['file']);
?>
<div class="arsol-addon-container arsol-error">
    <div class="arsol-first-column">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="arsol-label-container">
        <div class="arsol-addon-info">
            <div class="arsol-addon-title-wrapper">
                <div class="arsol-addon-title">
                    <h4 class="arsol-addon-title">
                        <label for="arsol-error-addon-<?php echo esc_attr(sanitize_title($addon_data['file'])); ?>">
                            <?php echo esc_html($addon_data['name']); ?>
                        </label>
                    </h4>
                </div>
                <?php if (isset($addon_data['loading_order'])): ?>
                <div class="arsol-addon-loading-order">
                    <span class="arsol-loading-order">
                        <?php echo esc_html($addon_data['loading_order']); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
            <small class="arsol-addon-error">
                <?php if ($error['type'] === 'duplicate'): ?>
                    <strong>Duplicate file path detected for → </strong>
                    <strong><?php echo esc_html($path_info['source_name']); ?></strong>
                    <?php echo esc_html($path_info['display_path']); ?>
                    <strong>→ first used by → </strong>
                    <strong><?php echo esc_html($addon_data['first_source']); ?></strong>
                    <strong><?php echo esc_html($addon_data['first_name']); ?></strong>
                    <br>
                    <small>
                        Loading Order: <?php echo esc_html($addon_data['loading_order']); ?>
                        (First file: <?php echo esc_html($addon_data['first_loading_order']); ?>)
                        | Total Duplicates: <?php echo esc_html($addon_data['total_duplicates']); ?>
                        <?php if (!empty($addon_data['duplicate_names'])): ?>
                        <br>
                        Other duplicates: <?php echo esc_html(implode(', ', $addon_data['duplicate_names'])); ?>
                        <?php endif; ?>
                    </small>
                <?php else: ?>
                    <strong><?php echo esc_html($error['message']); ?></strong>
                    <br>
                    <small>
                        File: <?php echo esc_html($addon_data['file']); ?>
                    </small>
                <?php endif; ?>
            </small>
        </div>
    </div>
</div> 