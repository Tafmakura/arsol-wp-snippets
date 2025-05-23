<?php
/**
 * Template for rendering addon file option
 *
 * @package Arsol_CSS_Addons
 * 
 * @var string $css_id         The addon file ID
 * @var array  $css_data       The addon file data
 * @var array  $enabled_options The enabled options from settings
 * @var string $option_type    The option type (e.g., 'admin', 'frontend_js', etc.)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if file exists by converting URL to file path
$file_url = $css_data['file'];
$file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
$file_exists = file_exists($file_path);

// Determine file type based on extension
$file_extension = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));
$file_type_display = '';

switch ($file_extension) {
    case 'css':
        $file_type_display = 'CSS';
        break;
    case 'js':
        $file_type_display = 'JavaScript';
        break;
    case 'php':
        $file_type_display = 'PHP';
        break;
    default:
        $file_type_display = strtoupper($file_extension);
}

if ($file_exists) {
    // File exists - show checkbox
    $checked = isset($enabled_options[$css_id]) ? $enabled_options[$css_id] : 0;
    ?>
    <p>
        <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-<?php echo esc_attr($css_id); ?>" 
               name="arsol_css_addons_options[<?php echo esc_attr($option_type); ?>][<?php echo esc_attr($css_id); ?>]" 
               value="1" <?php checked(1, $checked); ?>/>
        <label for="arsol-<?php echo esc_attr($option_type); ?>-<?php echo esc_attr($css_id); ?>">
            <?php echo esc_html($css_data['name']); ?>
        </label>
    </p>
    <?php
} else {
    // File doesn't exist - show error message with file path
    ?>
    <p class="arsol-css-error">
        <span class="dashicons dashicons-warning" style="color: #d63638; vertical-align: middle;"></span>
        <span style="color: #d63638;">
            <?php echo esc_html(sprintf(__('%s file for "%s" could not be found at: %s', 'arsol-css-addons'), 
                $file_type_display, $css_data['name'], $file_path)); ?>
        </span>
    </p>
    <?php
}