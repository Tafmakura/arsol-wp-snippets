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
$file_exists = false;
$file_path = '';

// Get simple source name
$source_name = '';
if (strpos($file_reference, get_stylesheet_directory_uri()) === 0) {
    $source_name = wp_get_theme()->get('Name') . ' → ';
    $file_path = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $file_reference);
    $file_exists = file_exists($file_path);
} elseif (strpos($file_reference, get_template_directory_uri()) === 0) {
    $source_name = wp_get_theme()->get('Name') . ' → ';
    $file_path = str_replace(get_template_directory_uri(), get_template_directory(), $file_reference);
    $file_exists = file_exists($file_path);
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
    
    // Check if file exists in plugin directory
    $file_path = $plugin_path;
    $file_exists = file_exists($file_path);
} else {
    // Direct file path
    $file_path = $file_reference;
    $file_exists = file_exists($file_path);
}

// Check dependencies if file exists
$missing_dependencies = array();
if ($file_exists && !empty($addon_data['dependencies'])) {
    $addon_type = isset($addon_data['type']) ? $addon_data['type'] : $option_type;
    
    if ($addon_type === 'css') {
        $wp_styles = wp_styles();
        foreach ($addon_data['dependencies'] as $dependency) {
            if (!isset($wp_styles->registered[$dependency])) {
                $missing_dependencies[] = $dependency;
            }
        }
    } elseif ($addon_type === 'js') {
        $wp_scripts = wp_scripts();
        foreach ($addon_data['dependencies'] as $dependency) {
            if (!isset($wp_scripts->registered[$dependency])) {
                $missing_dependencies[] = $dependency;
            }
        }
    }
}

if (!$file_exists) {
    // File doesn't exist - show error message
    ?>
    <div class="arsol-addon-container arsol-error">
        <div class="arsol-first-column">
            <span class="dashicons dashicons-warning"></span>
        </div>
        <div class="arsol-label-container">
            <div class="arsol-addon-info">
                <?php include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php'; ?>
                <small class="arsol-addon-error">
                    <strong><?php echo esc_html__('Snippet file not found at →', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html(isset($file_path) ? $file_path : $file_reference); ?>
                </small>
            </div>
        </div>
    </div>
    <?php
} elseif (!empty($missing_dependencies)) {
    // File exists but dependencies are missing - show dependency error
    ?>
    <div class="arsol-addon-container arsol-error">
        <div class="arsol-first-column">
            <span class="dashicons dashicons-warning"></span>
        </div>
        <div class="arsol-label-container">
            <div class="arsol-addon-info">
                <?php include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php'; ?>
                <small class="arsol-addon-error">
                    <strong><?php echo esc_html__('Missing Dependencies:', 'arsol-wp-snippets'); ?></strong>
                    <ul class="arsol-dependency-list">
                        <?php foreach ($missing_dependencies as $dependency): ?>
                        <li><?php echo esc_html($dependency); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </small>
            </div>
        </div>
    </div>
    <?php
} else {
    // File exists and all dependencies are present - show normal display
    $checked = isset($enabled_options[$addon_id]) ? $enabled_options[$addon_id] : 0;
    $state_class = $checked ? 'enabled' : 'disabled';
    ?>
    <div class="arsol-addon-container <?php echo esc_attr($state_class); ?>">
        <div class="arsol-first-column">
            <span class="arsol-wp-snippets-checkbox" >
                <input type="checkbox" id="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>" 
                       name="arsol_wp_snippets_options[<?php echo esc_attr($option_type); ?>_addon_options][<?php echo esc_attr($addon_id); ?>]" 
                       value="1" <?php checked(1, $checked); ?>/>
            </span>
           
        </div>
        <div class="arsol-label-container">
            <div class="arsol-addon-info">
                <?php include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/arsol-addon-title-wrapper.php'; ?>
            
                <small class="arsol-addon-source"><?php 
                    $display_path = $file_reference;
                    if (strpos($display_path, 'wp-content/plugins/') !== false) {
                        $display_path = preg_replace('#/functions/snippets/#', '/snippets/', $display_path);
                        $display_path = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $display_path);
                        $display_path = str_replace('/functions/', '/', $display_path);
                    }
                    echo '<strong>' . esc_html($source_name) . '</strong>' . esc_html($display_path); 
                ?></small>
            </div>
            <div class="arsol-addon-footer">
                <span class="arsol-addon-meta">
                        <strong>Context:</strong> <?php 
                            $context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                            echo ucfirst($context);
                        ?>
                </span>
                <?php 
                // Define addon type for use in the template
                $addon_type = isset($addon_data['type']) ? $addon_data['type'] : $option_type;
                if ($addon_type === 'js'): 
                ?>
                <span class="arsol-addon-meta">
                    <strong>Position:</strong> <?php echo ucfirst($addon_data['position'] ?? 'footer'); ?>
                </span>
                <?php endif; ?>
                <div class="arsol-addon-meta">
                    <strong><?php echo esc_html__('Timing:', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html($loading_order_category); ?>
                </div>
                <?php if (!empty($addon_data['dependencies'])): ?>
                <div class="arsol-addon-meta">
                    <strong><?php echo esc_html__('Dependencies:', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html(implode(', ', $addon_data['dependencies'])); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
?>