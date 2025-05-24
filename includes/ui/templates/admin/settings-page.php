<?php
/**
 * Admin Settings Page Template
 * 
 * Available variables:
 * @var string $page_title - The page title
 * @var string $settings_slug - The settings slug
 * @var Admin_Settings $admin_settings - The admin settings instance
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current options
$options = get_option('arsol_wp_snippets_options', array());
?>

<div class="wrap">
    <h1><?php echo esc_html($page_title); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('arsol_wp_snippets_settings');
        ?>
        
        <!-- PHP Addons Section -->
        <div class="arsol-settings-section">
            <h2><?php esc_html_e('PHP Addons', 'arsol-wp-snippets'); ?></h2>
            <p><?php esc_html_e('Select PHP addon files to include.', 'arsol-wp-snippets'); ?></p>
            
            <?php
            $php_addon_options = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
            $available_php_addons = $admin_settings->get_php_addon_options();
            
            if (empty($available_php_addons)) {
                echo '<p>' . esc_html__('No PHP addon files available.', 'arsol-wp-snippets') . '</p>';
            } else {
                foreach ($available_php_addons as $addon_id => $addon_data) {
                    $is_checked = isset($php_addon_options[$addon_id]) ? 'checked="checked"' : '';
                    $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                    $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                    ?>
                    <div class="addon-option">
                        <label>
                            <input 
                                type="checkbox" 
                                name="arsol_wp_snippets_options[php_addon_options][<?php echo esc_attr($addon_id); ?>]" 
                                value="1" 
                                <?php echo $is_checked; ?>
                            />
                            <strong><?php echo esc_html($addon_name); ?></strong>
                            
                            <?php if ($addon_description): ?>
                                <p class="description"><?php echo esc_html($addon_description); ?></p>
                            <?php endif; ?>
                            
                            <div class="addon-meta">
                                <small>
                                    <?php if (isset($addon_data['file'])): ?>
                                        <?php echo sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html(basename($addon_data['file']))); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </label>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <!-- CSS Addons Section -->
        <div class="arsol-settings-section">
            <h2><?php esc_html_e('CSS Addons', 'arsol-wp-snippets'); ?></h2>
            <p><?php esc_html_e('Select CSS addon files to include.', 'arsol-wp-snippets'); ?></p>
            
            <?php
            $css_addon_options = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
            $available_css_addons = $admin_settings->get_css_addon_options();
            
            if (empty($available_css_addons)) {
                echo '<p>' . esc_html__('No CSS addon files available.', 'arsol-wp-snippets') . '</p>';
            } else {
                foreach ($available_css_addons as $addon_id => $addon_data) {
                    $is_checked = isset($css_addon_options[$addon_id]) ? 'checked="checked"' : '';
                    $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                    $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                    $addon_context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    $addon_position = isset($addon_data['position']) ? $addon_data['position'] : '';
                    ?>
                    <div class="addon-option">
                        <label>
                            <input 
                                type="checkbox" 
                                name="arsol_wp_snippets_options[css_addon_options][<?php echo esc_attr($addon_id); ?>]" 
                                value="1" 
                                <?php echo $is_checked; ?>
                            />
                            <strong><?php echo esc_html($addon_name); ?></strong>
                            
                            <?php if ($addon_description): ?>
                                <p class="description"><?php echo esc_html($addon_description); ?></p>
                            <?php endif; ?>
                            
                            <div class="addon-meta">
                                <small>
                                    <?php echo sprintf(__('Context: %s', 'arsol-wp-snippets'), esc_html($addon_context)); ?>
                                    <?php if ($addon_position): ?>
                                        | <?php echo sprintf(__('Position: %s', 'arsol-wp-snippets'), esc_html($addon_position)); ?>
                                    <?php endif; ?>
                                    <?php if (isset($addon_data['file'])): ?>
                                        <br><?php echo sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html(basename($addon_data['file']))); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </label>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <!-- JS Addons Section -->
        <div class="arsol-settings-section">
            <h2><?php esc_html_e('JavaScript Addons', 'arsol-wp-snippets'); ?></h2>
            <p><?php esc_html_e('Select JavaScript addon files to include.', 'arsol-wp-snippets'); ?></p>
            
            <?php
            $js_addon_options = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
            $available_js_addons = $admin_settings->get_js_addon_options();
            
            if (empty($available_js_addons)) {
                echo '<p>' . esc_html__('No JS addon files available.', 'arsol-wp-snippets') . '</p>';
            } else {
                foreach ($available_js_addons as $addon_id => $addon_data) {
                    $is_checked = isset($js_addon_options[$addon_id]) ? 'checked="checked"' : '';
                    $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                    $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                    $addon_context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    $addon_position = isset($addon_data['position']) ? $addon_data['position'] : '';
                    ?>
                    <div class="addon-option">
                        <label>
                            <input 
                                type="checkbox" 
                                name="arsol_wp_snippets_options[js_addon_options][<?php echo esc_attr($addon_id); ?>]" 
                                value="1" 
                                <?php echo $is_checked; ?>
                            />
                            <strong><?php echo esc_html($addon_name); ?></strong>
                            
                            <?php if ($addon_description): ?>
                                <p class="description"><?php echo esc_html($addon_description); ?></p>
                            <?php endif; ?>
                            
                            <div class="addon-meta">
                                <small>
                                    <?php echo sprintf(__('Context: %s', 'arsol-wp-snippets'), esc_html($addon_context)); ?>
                                    <?php if ($addon_position): ?>
                                        | <?php echo sprintf(__('Position: %s', 'arsol-wp-snippets'), esc_html($addon_position)); ?>
                                    <?php endif; ?>
                                    <?php if (isset($addon_data['file'])): ?>
                                        <br><?php echo sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html(basename($addon_data['file']))); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </label>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>

<style>
.arsol-settings-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.arsol-settings-section h2 {
    margin-top: 0;
    margin-bottom: 10px;
}

.addon-option {
    margin-bottom: 15px;
    padding: 10px;
    background: #f9f9f9;
    border-left: 4px solid #0073aa;
}

.addon-option label {
    display: block;
    cursor: pointer;
}

.addon-option input[type="checkbox"] {
    margin-right: 8px;
}

.addon-meta {
    margin-top: 5px;
    color: #666;
}

.addon-meta small {
    font-size: 12px;
}
</style>