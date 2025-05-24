<?php
/**
 * Template for rendering addon file option
 *
 * @package Arsol_WP_Snippets
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

// Check if file exists
$file_exists = true;
$file_reference = $addon_data['file'];

// Determine how to check file existence based on file type
if (filter_var($file_reference, FILTER_VALIDATE_URL)) {
    // It's a URL - convert to file path for theme files
    $theme_child_uri = get_stylesheet_directory_uri();
    $theme_parent_uri = get_template_directory_uri();
    
    if (strpos($file_reference, $theme_child_uri) === 0) {
        // Child theme file
        $file_path = str_replace($theme_child_uri, get_stylesheet_directory(), $file_reference);
        $file_exists = file_exists($file_path);
    } elseif (strpos($file_reference, $theme_parent_uri) === 0) {
        // Parent theme file
        $file_path = str_replace($theme_parent_uri, get_template_directory(), $file_reference);
        $file_exists = file_exists($file_path);
    } else {
        // External URL or plugin file - assume it exists
        $file_exists = true;
    }
} else {
    // It's a file path - check directly
    $file_exists = file_exists($file_reference);
}

if ($file_exists) {
    // File exists - show checkbox
    $checked = isset($enabled_options[$addon_id]) ? $enabled_options[$addon_id] : 0;
    ?>
    <p>
        <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>" 
               name="arsol_wp_snippets_options[<?php echo esc_attr($option_type); ?>_addon_options][<?php echo esc_attr($addon_id); ?>]" 
               value="1" <?php checked(1, $checked); ?>/>
        <label for="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>"><?php echo esc_html($addon_data['name']); ?></label>
    </p>
    <?php
} else {
    // File doesn't exist - show error message
    ?>
    <p class="arsol-addon-error">
        <span class="dashicons dashicons-warning" style="color: #d63638; vertical-align: middle;"></span>
        <span style="color: #d63638;">
            <?php echo esc_html(sprintf(__('Addon file for "%s" could not be found at: %s', 'arsol-wp-snippets'), $addon_data['name'], isset($file_path) ? $file_path : $file_reference)); ?>
        </span>
    </p>
    <?php
}
?>