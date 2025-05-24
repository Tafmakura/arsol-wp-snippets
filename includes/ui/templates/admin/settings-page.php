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
        <?php settings_fields('arsol_wp_snippets_settings'); ?>
        
        <!-- PHP Addons Section -->
        <h2><?php esc_html_e('PHP Addons', 'arsol-wp-snippets'); ?></h2>
        <p><?php esc_html_e('Select PHP addon files to include.', 'arsol-wp-snippets'); ?></p>
        
        <?php
        $php_addon_options = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
        $available_php_addons = $admin_settings->get_php_addon_options();
        
        if (empty($available_php_addons)) {
            echo '<p>' . esc_html__('No PHP addon files available.', 'arsol-wp-snippets') . '</p>';
        } else {
            echo '<table class="form-table">';
            foreach ($available_php_addons as $addon_id => $addon_data) {
                $is_checked = isset($php_addon_options[$addon_id]) ? 'checked="checked"' : '';
                $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                
                // Check if file exists
                $file_exists = true;
                $file_status = '';
                if (isset($addon_data['file'])) {
                    $file_exists = file_exists($addon_data['file']);
                    $file_status = $file_exists ? '' : ' <strong style="color: red;">(' . esc_html__('File not found', 'arsol-wp-snippets') . ')</strong>';
                }
                
                echo '<tr>';
                echo '<td>';
                echo '<label>';
                echo '<input type="checkbox" name="arsol_wp_snippets_options[php_addon_options][' . esc_attr($addon_id) . ']" value="1" ' . $is_checked . ($file_exists ? '' : ' disabled') . ' />';
                echo ' ' . esc_html($addon_name) . $file_status;
                echo '</label>';
                
                if ($addon_description) {
                    echo '<br><small>' . esc_html($addon_description) . '</small>';
                }
                
                if (isset($addon_data['file'])) {
                    echo '<br><small>' . sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html($addon_data['file'])) . '</small>';
                }
                
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
        
        <!-- CSS Addons Section -->
        <h2><?php esc_html_e('CSS Addons', 'arsol-wp-snippets'); ?></h2>
        <p><?php esc_html_e('Select CSS addon files to include.', 'arsol-wp-snippets'); ?></p>
        
        <?php
        $css_addon_options = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        $available_css_addons = $admin_settings->get_css_addon_options();
        
        if (empty($available_css_addons)) {
            echo '<p>' . esc_html__('No CSS addon files available.', 'arsol-wp-snippets') . '</p>';
        } else {
            echo '<table class="form-table">';
            foreach ($available_css_addons as $addon_id => $addon_data) {
                $is_checked = isset($css_addon_options[$addon_id]) ? 'checked="checked"' : '';
                $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                $addon_context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                $addon_position = isset($addon_data['position']) ? $addon_data['position'] : '';
                
                // Check if file exists for URL-based files
                $file_exists = true;
                $file_status = '';
                if (isset($addon_data['file'])) {
                    // For CSS files, check if it's a local file (not URL)
                    if (!filter_var($addon_data['file'], FILTER_VALIDATE_URL)) {
                        $file_exists = file_exists($addon_data['file']);
                        $file_status = $file_exists ? '' : ' <strong style="color: red;">(' . esc_html__('File not found', 'arsol-wp-snippets') . ')</strong>';
                    }
                }
                
                echo '<tr>';
                echo '<td>';
                echo '<label>';
                echo '<input type="checkbox" name="arsol_wp_snippets_options[css_addon_options][' . esc_attr($addon_id) . ']" value="1" ' . $is_checked . ($file_exists ? '' : ' disabled') . ' />';
                echo ' ' . esc_html($addon_name) . $file_status;
                echo '</label>';
                
                if ($addon_description) {
                    echo '<br><small>' . esc_html($addon_description) . '</small>';
                }
                
                echo '<br><small>';
                echo sprintf(__('Context: %s', 'arsol-wp-snippets'), esc_html($addon_context));
                if ($addon_position) {
                    echo ' | ' . sprintf(__('Position: %s', 'arsol-wp-snippets'), esc_html($addon_position));
                }
                if (isset($addon_data['file'])) {
                    echo '<br>' . sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html(basename($addon_data['file'])));
                }
                echo '</small>';
                
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
        
        <!-- JS Addons Section -->
        <h2><?php esc_html_e('JavaScript Addons', 'arsol-wp-snippets'); ?></h2>
        <p><?php esc_html_e('Select JavaScript addon files to include.', 'arsol-wp-snippets'); ?></p>
        
        <?php
        $js_addon_options = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        $available_js_addons = $admin_settings->get_js_addon_options();
        
        if (empty($available_js_addons)) {
            echo '<p>' . esc_html__('No JS addon files available.', 'arsol-wp-snippets') . '</p>';
        } else {
            echo '<table class="form-table">';
            foreach ($available_js_addons as $addon_id => $addon_data) {
                $is_checked = isset($js_addon_options[$addon_id]) ? 'checked="checked"' : '';
                $addon_name = isset($addon_data['name']) ? $addon_data['name'] : ucwords(str_replace('-', ' ', $addon_id));
                $addon_description = isset($addon_data['description']) ? $addon_data['description'] : '';
                $addon_context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                $addon_position = isset($addon_data['position']) ? $addon_data['position'] : '';
                
                // Check if file exists for URL-based files
                $file_exists = true;
                $file_status = '';
                if (isset($addon_data['file'])) {
                    // For JS files, check if it's a local file (not URL)
                    if (!filter_var($addon_data['file'], FILTER_VALIDATE_URL)) {
                        $file_exists = file_exists($addon_data['file']);
                        $file_status = $file_exists ? '' : ' <strong style="color: red;">(' . esc_html__('File not found', 'arsol-wp-snippets') . ')</strong>';
                    }
                }
                
                echo '<tr>';
                echo '<td>';
                echo '<label>';
                echo '<input type="checkbox" name="arsol_wp_snippets_options[js_addon_options][' . esc_attr($addon_id) . ']" value="1" ' . $is_checked . ($file_exists ? '' : ' disabled') . ' />';
                echo ' ' . esc_html($addon_name) . $file_status;
                echo '</label>';
                
                if ($addon_description) {
                    echo '<br><small>' . esc_html($addon_description) . '</small>';
                }
                
                echo '<br><small>';
                echo sprintf(__('Context: %s', 'arsol-wp-snippets'), esc_html($addon_context));
                if ($addon_position) {
                    echo ' | ' . sprintf(__('Position: %s', 'arsol-wp-snippets'), esc_html($addon_position));
                }
                if (isset($addon_data['file'])) {
                    echo '<br>' . sprintf(__('File: %s', 'arsol-wp-snippets'), esc_html(basename($addon_data['file'])));
                }
                echo '</small>';
                
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
        
        <?php submit_button(); ?>
    </form>
</div>