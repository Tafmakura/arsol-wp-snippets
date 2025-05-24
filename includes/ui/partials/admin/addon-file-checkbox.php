<?php
/**
 * Template for rendering addon file option
 *
 * @package Arsol_CSS_Addons
 * 
 * @var string $addon_id       The addon file ID
 * @var array  $addon_data     The addon file data
 * @var array  $enabled_options The enabled options from settings
 * @var string $option_type    The addon type: 'php', 'css', or 'js'
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if file exists by converting URL to file path
$file_url = $addon_data['file'];
$file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
$file_exists = file_exists($file_path);

if ($file_exists) {
    // File exists - show checkbox
    $checked = isset($enabled_options[$addon_id]) ? $enabled_options[$addon_id] : 0;
    ?>
    <p>
        <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>" 
               name="arsol_css_addons_options[<?php echo esc_attr($option_type); ?>_addon_options][<?php echo esc_attr($addon_id); ?>]" 
               value="1" <?php checked(1, $checked); ?>/>
        <label for="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>"><?php echo esc_html($addon_data['name']); ?></label>
    </p>
    <?php
} else {
    // File doesn't exist - show error message with file path
    ?>
    <p class="arsol-addon-error">
        <span class="dashicons dashicons-warning" style="color: #d63638; vertical-align: middle;"></span>
        <span style="color: #d63638;">
            <?php echo esc_html(sprintf(__('Addon file for "%s" could not be found at: %s', 'arsol-css-addons'), $addon_data['name'], $file_path)); ?>
        </span>
    </p>
    <?php
}