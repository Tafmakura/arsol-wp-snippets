<?php
/**
 * Template for rendering addon title wrapper
 *
 * @package Arsol_WP_Snippets
 * 
 * @var string $addon_id       The addon file ID
 * @var array  $addon_data     The addon file data
 * @var string $option_type    The addon type: 'php', 'css', or 'js'
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="arsol-addon-title-wrapper">
    <div class="arsol-addon-title">
        <h4 class="arsol-addon-title">
            <label for="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>"><?php echo esc_html($addon_data['name']); ?></label>
        </h4>
    </div>
    <div class="arsol-addon-priority">
        <?php 
        // Display priority number
        $addon_type = isset($addon_data['type']) ? $addon_data['type'] : $option_type;
        if ($addon_type === 'js' || $addon_type === 'css' || $addon_type === 'php'): 
        ?>
        <span class="arsol-loading-order" title="<?php echo esc_attr__('Loading Order', 'arsol-wp-snippets'); ?>">
            <?php 
            $priority = isset($addon_data['priority']) ? intval($addon_data['priority']) : 10;
            echo esc_html($priority); 
            ?>
        </span>
        <?php endif; ?>
    </div>
</div> 