<?php
/**
 * Template for rendering CSS file option
 *
 * @package Arsol_CSS_Addons
 * 
 * @var string $css_id        The CSS file ID
 * @var array  $css_data      The CSS file data
 * @var array  $enabled_options The enabled options from settings
 * @var string $option_type   Either 'admin' or 'frontend'
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if file exists by converting URL to file path
$file_url = $css_data['file'];
$file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
$file_exists = file_exists($file_path);

if ($file_exists) {
    // File exists - show checkbox
    $checked = isset($enabled_options[$css_id]) ? $enabled_options[$css_id] : 0;
    ?>
    <p>
        <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-css-<?php echo esc_attr($css_id); ?>" 
               name="arsol_css_addons_options[<?php echo esc_attr($option_type); ?>_css_options][<?php echo esc_attr($css_id); ?>]" 
               value="1" <?php checked(1, $checked); ?>/>
        <label for="arsol-<?php echo esc_attr($option_type); ?>-css-<?php echo esc_attr($css_id); ?>"><?php echo esc_html($css_data['name']); ?></label>
    </p>
    <?php
} else {
    // File doesn't exist - show error message
    ?>
    <p class="arsol-css-error">
        <span class="dashicons dashicons-warning" style="color: #d63638; vertical-align: middle;"></span>
        <span style="color: #d63638;">
            <?php echo esc_html(sprintf(__('CSS file "%s" could not be found.', 'arsol-css-addons'), $css_data['name'])); ?>
        </span>
    </p>
    <?php
}