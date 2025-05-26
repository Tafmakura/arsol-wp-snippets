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

// Get loading order and determine its category
$loading_order = \Arsol_WP_Snippets\Helper::get_loading_order($addon_data);
$loading_order_category = '';
if ($loading_order <= 5) {
    $loading_order_category = 'Early';
} elseif ($loading_order <= 10) {
    $loading_order_category = 'Default';
} elseif ($loading_order <= 20) {
    $loading_order_category = 'Late';
} else {
    $loading_order_category = 'Very Late';
}
?>
<div class="arsol-addon-title-wrapper">
    <div class="arsol-addon-title">
        <h4 class="arsol-addon-title">
            <label for="arsol-<?php echo esc_attr($option_type); ?>-addon-<?php echo esc_attr($addon_id); ?>"><?php echo esc_html($addon_data['name']); ?></label>
        </h4>
    </div>
    <div class="arsol-addon-loading-order">
        <?php 
        // Display loading order number
        $addon_type = isset($addon_data['type']) ? $addon_data['type'] : $option_type;
        if ($addon_type === 'js' || $addon_type === 'css' || $addon_type === 'php'): 
        ?>
        <span class="arsol-loading-order">
            <?php echo esc_html($loading_order); ?>
        </span>
        <?php endif; ?>
    </div>
</div> 