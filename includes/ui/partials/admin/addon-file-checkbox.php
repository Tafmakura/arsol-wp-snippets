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

// Clean up the file path
$file_reference = $addon_data['file'];

// Remove 'functions/' from plugin paths regardless of format
if (strpos($file_reference, 'wp-content/plugins/') !== false || strpos($file_reference, WP_PLUGIN_DIR) !== false) {
    $file_reference = preg_replace('#/functions/snippets/#', '/snippets/', $file_reference);
    $file_reference = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $file_reference);
    $file_reference = str_replace('/functions/', '/', $file_reference);
}

// Additional path cleanup
if ($option_type !== 'php') {
    $file_reference = str_replace('/../', '/', $file_reference);
}

// Check if file exists
$file_exists = true;

// Get simple source name
$source_name = '';
if (strpos($file_reference, get_stylesheet_directory_uri()) === 0) {
    $source_name = wp_get_theme()->get('Name') . ' → ';
} elseif (strpos($file_reference, get_template_directory_uri()) === 0) {
    $source_name = wp_get_theme()->get('Name') . ' → ';
} elseif (strpos($file_reference, plugins_url()) === 0 || strpos($file_reference, WP_PLUGIN_DIR) === 0) {
    // Get plugin name from the file path
    $plugin_path = str_replace(plugins_url(), WP_PLUGIN_DIR, $file_reference);
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
    $state_class = $checked ? 'enabled' : 'disabled';
    ?>
    <div class="arsol-addon-container <?php echo esc_attr($state_class); ?>">
        <div class="arsol-first-column">
            <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>" 
                   name="arsol_wp_snippets_options[<?php echo esc_attr($option_type); ?>_addon_options][<?php echo esc_attr($addon_id); ?>]" 
                   value="1" <?php checked(1, $checked); ?>/>
        </div>
        <div class="arsol-label-container">
            <div class="arsol-addon-info">
                <h4 class="arsol-addon-title">
                    <label for="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>"><?php echo esc_html($addon_data['name']); ?></label>
                </h4>
                <small class="arsol-addon-source"><?php 
                    $display_path = $file_reference;
                    if (strpos($display_path, 'wp-content/plugins/') !== false) {
                        $display_path = preg_replace('#/functions/snippets/#', '/snippets/', $display_path);
                        $display_path = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $display_path);
                        $display_path = str_replace('/functions/', '/', $display_path);
                    }
                    echo esc_html($source_name . $display_path); 
                ?></small>
            </div>
            <div class="arsol-addon-footer">
                <?php
                // Display context if available
                if (isset($addon_data['context'])) {
                    echo '<span class="arsol-addon-meta"><strong>Context:</strong> ' . esc_html($addon_data['context']) . '</span>';
                }
                
                // Display position for JS files
                if ($option_type === 'js' && isset($addon_data['position'])) {
                    echo '<span class="arsol-addon-meta"><strong>Position:</strong> ' . esc_html($addon_data['position']) . '</span>';
                }
                
                // Display dependencies if available
                if (isset($addon_data['dependencies']) && !empty($addon_data['dependencies'])) {
                    echo '<span class="arsol-addon-meta"><strong>Dependencies:</strong> ' . esc_html(implode(', ', $addon_data['dependencies'])) . '</span>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} else {
    // File doesn't exist - show error message
    ?>
    <div class="arsol-addon-container error">
        <p class="arsol-addon-error">
            <div class="arsol-first-column">
                <span class="dashicons dashicons-warning"></span>
            </div>
            <span>
                <?php echo esc_html(sprintf(__('Addon file for "%s" could not be found at: %s', 'arsol-wp-snippets'), $addon_data['name'], isset($file_path) ? $file_path : $file_reference)); ?>
            </span>
        </p>
    </div>
    <?php
}
?>