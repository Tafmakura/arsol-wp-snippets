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

// Normalize the file path
$path_info = \Arsol_WP_Snippets\Helper::normalize_path($addon_data['file']);

// Check if file exists
$file_exists = file_exists($path_info['normalized_path']);

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
                    <strong><?php echo esc_html__('Snippet file not found at →', 'arsol-wp-snippets'); ?></strong> <?php echo '<strong>' . esc_html($path_info['source_name']) . '</strong>' . esc_html($path_info['display_path']); ?>
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
                    echo '<strong>' . esc_html($path_info['source_name']) . '</strong>' . esc_html($path_info['display_path']); 
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
                
                if ($addon_type === 'js'): ?>
                <span class="arsol-addon-meta">
                    <strong>Position:</strong> <?php echo ucfirst($addon_data['position'] ?? 'footer'); ?>
                </span>
                <?php endif; ?>
                <div class="arsol-addon-meta">
                    <strong><?php echo esc_html__('Priority:', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html($priority_category); ?>
                </div>
                <div class="arsol-addon-meta">
                    <strong><?php echo esc_html__('Order:', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html($loading_order_category); ?>
                </div>
                <?php if (!empty($addon_data['dependencies'])): ?>
                <div class="arsol-addon-meta">
                    <strong><?php echo esc_html__('Dependencies:', 'arsol-wp-snippets'); ?></strong> <?php echo esc_html(implode(', ', $addon_data['dependencies'])); ?>
                </div>
                <?php endif; ?>
                
                <?php 
                // Show hook information for PHP files - displayed last
                if ($addon_type === 'php'): 
                    $hook = \Arsol_WP_Snippets\Helper::get_hook($addon_data);
                ?>
                <div class="arsol-addon-meta">
                    <strong>Hook:</strong> <?php echo esc_html($hook); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}