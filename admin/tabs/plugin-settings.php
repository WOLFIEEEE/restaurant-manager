<?php
if (!defined('ABSPATH')) {
    exit;
}

// Handle duplicate cleanup
if (isset($_GET['cleanup_duplicates']) && $_GET['cleanup_duplicates'] === '1') {
    if (wp_verify_nonce($_GET['_wpnonce'], 'cleanup_duplicates')) {
        global $wpdb;
        
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        $cleaned_categories = 0;
        $cleaned_items = 0;
        $cleaned_slider = 0;
        $cleaned_settings = 0;
        
        // Clean duplicate categories (keep the one with lowest ID)
        $cleaned_categories = $wpdb->query("
            DELETE c1 FROM $categories_table c1
            INNER JOIN $categories_table c2 
            WHERE c1.id > c2.id 
            AND c1.slug = c2.slug
        ");
        
        // Clean duplicate menu items (keep the one with lowest ID)
        $cleaned_items = $wpdb->query("
            DELETE m1 FROM $items_table m1
            INNER JOIN $items_table m2 
            WHERE m1.id > m2.id 
            AND m1.category_id = m2.category_id 
            AND m1.name = m2.name
        ");
        
        // Clean duplicate slider images (keep the one with lowest ID)
        $cleaned_slider = $wpdb->query("
            DELETE s1 FROM $slider_table s1
            INNER JOIN $slider_table s2 
            WHERE s1.id > s2.id 
            AND s1.image_url = s2.image_url
        ");
        
        // Clean duplicate settings (keep the one with lowest ID)
        $cleaned_settings = $wpdb->query("
            DELETE s1 FROM $settings_table s1
            INNER JOIN $settings_table s2 
            WHERE s1.id > s2.id 
            AND s1.setting_key = s2.setting_key
        ");
        
        $total_cleaned = $cleaned_categories + $cleaned_items + $cleaned_slider + $cleaned_settings;
        
        if ($total_cleaned > 0) {
            echo '<div class="notice notice-success"><p><strong>' . 
                 sprintf(__('Successfully cleaned up %d duplicate entries:', 'restaurant-manager'), $total_cleaned) . 
                 '</strong><br>' .
                 sprintf(__('Categories: %d, Menu Items: %d, Slider Images: %d, Settings: %d', 'restaurant-manager'), 
                        $cleaned_categories, $cleaned_items, $cleaned_slider, $cleaned_settings) . 
                 '</p></div>';
        } else {
            echo '<div class="notice notice-info"><p>' . __('No duplicate entries found to clean up.', 'restaurant-manager') . '</p></div>';
        }
    }
}

// Handle complete data reset
if (isset($_GET['reset_all_data']) && $_GET['reset_all_data'] === '1') {
    if (wp_verify_nonce($_GET['_wpnonce'], 'reset_all_data')) {
        global $wpdb;
        
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        // Truncate all tables
        $wpdb->query("TRUNCATE TABLE $categories_table");
        $wpdb->query("TRUNCATE TABLE $items_table");
        $wpdb->query("TRUNCATE TABLE $slider_table");
        $wpdb->query("TRUNCATE TABLE $settings_table");
        
        echo '<div class="notice notice-success"><p><strong>' . 
             __('All plugin data has been completely reset. You can now safely reinstall or repopulate data.', 'restaurant-manager') . 
             '</strong></p></div>';
    }
}
?>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <i class="fas fa-tools" aria-hidden="true"></i>
            <?php _e('Plugin Tools', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <div style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
            <p style="margin: 0; color: #0073aa;">
                <i class="fas fa-info-circle" style="color: #0073aa; margin-right: 5px;"></i>
                <strong><?php _e('Plugin Reinstallation Guide:', 'restaurant-manager'); ?></strong>
                <?php _e('If you\'re experiencing duplicate data after reinstalling the plugin, use the "Clean Duplicates" tool below. For a completely fresh start, use "Complete Data Reset" before reinstalling.', 'restaurant-manager'); ?>
            </p>
        </div>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label>
                        <i class="fas fa-broom" aria-hidden="true"></i>
                        <?php _e('Clean Duplicate Data', 'restaurant-manager'); ?>
                    </label>
                </th>
                <td>
                    <p class="description">
                        <?php _e('Remove duplicate entries from all plugin tables (categories, menu items, slider images, settings). This is safe and keeps the original entries.', 'restaurant-manager'); ?>
                    </p>
                    <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('cleanup_duplicates', '1', admin_url('admin.php?page=restaurant-manager&tab=settings')), 'cleanup_duplicates')); ?>" 
                       class="button button-primary"
                       onclick="return confirm('<?php esc_attr_e('This will remove duplicate entries while keeping the original data. Continue?', 'restaurant-manager'); ?>');">
                        <i class="fas fa-broom" aria-hidden="true"></i>
                        <?php _e('Clean Duplicates', 'restaurant-manager'); ?>
                    </a>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label>
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        <?php _e('Complete Data Reset', 'restaurant-manager'); ?>
                    </label>
                </th>
                <td>
                    <p class="description">
                        <?php _e('Completely remove ALL plugin data (categories, menu items, slider images, settings). Use this before reinstalling the plugin to start fresh.', 'restaurant-manager'); ?>
                    </p>
                    <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('reset_all_data', '1', admin_url('admin.php?page=restaurant-manager&tab=settings')), 'reset_all_data')); ?>" 
                       class="button button-secondary"
                       style="color: #d63638; border-color: #d63638;"
                       onclick="return confirm('<?php esc_attr_e('This will DELETE ALL plugin data permanently and cannot be undone. Are you absolutely sure?', 'restaurant-manager'); ?>');">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        <?php _e('Reset All Data', 'restaurant-manager'); ?>
                    </a>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label>
                        <i class="fas fa-redo" aria-hidden="true"></i>
                        <?php _e('Reset to Default Menu', 'restaurant-manager'); ?>
                    </label>
                </th>
                <td>
                    <p class="description">
                        <?php _e('This will delete all current menu data and restore the original 160+ default menu items with categories.', 'restaurant-manager'); ?>
                    </p>
                    <a href="<?php echo esc_url(add_query_arg('reset_data', '1', admin_url('admin.php?page=restaurant-manager'))); ?>" 
                       class="button button-secondary"
                       onclick="return confirm('<?php esc_attr_e('This will delete ALL existing menu data and cannot be undone. Continue?', 'restaurant-manager'); ?>');">
                        <i class="fas fa-redo" aria-hidden="true"></i>
                        <?php _e('Reset to Default Menu', 'restaurant-manager'); ?>
                    </a>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label>
                        <i class="fas fa-database" aria-hidden="true"></i>
                        <?php _e('Database Status', 'restaurant-manager'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    global $wpdb;
                    $categories_table = $wpdb->prefix . 'restaurant_categories';
                    $items_table = $wpdb->prefix . 'restaurant_menu_items';
                    $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
                    $settings_table = $wpdb->prefix . 'restaurant_settings';
                    
                    $categories_count = $wpdb->get_var("SELECT COUNT(*) FROM $categories_table");
                    $items_count = $wpdb->get_var("SELECT COUNT(*) FROM $items_table");
                    $slider_count = $wpdb->get_var("SELECT COUNT(*) FROM $slider_table");
                    $settings_count = $wpdb->get_var("SELECT COUNT(*) FROM $settings_table");
                    
                    // Check for potential duplicates
                    $duplicate_categories = $wpdb->get_var("
                        SELECT COUNT(*) FROM (
                            SELECT slug FROM $categories_table 
                            GROUP BY slug HAVING COUNT(*) > 1
                        ) AS duplicates
                    ");
                    $duplicate_items = $wpdb->get_var("
                        SELECT COUNT(*) FROM (
                            SELECT category_id, name FROM $items_table 
                            GROUP BY category_id, name HAVING COUNT(*) > 1
                        ) AS duplicates
                    ");
                    $duplicate_settings = $wpdb->get_var("
                        SELECT COUNT(*) FROM (
                            SELECT setting_key FROM $settings_table 
                            GROUP BY setting_key HAVING COUNT(*) > 1
                        ) AS duplicates
                    ");
                    ?>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                        <div>
                            <p><strong><?php _e('Total Records:', 'restaurant-manager'); ?></strong></p>
                            <ul style="margin-left: 20px;">
                                <li><?php printf(__('Categories: %d', 'restaurant-manager'), $categories_count); ?></li>
                                <li><?php printf(__('Menu Items: %d', 'restaurant-manager'), $items_count); ?></li>
                                <li><?php printf(__('Slider Images: %d', 'restaurant-manager'), $slider_count); ?></li>
                                <li><?php printf(__('Settings: %d', 'restaurant-manager'), $settings_count); ?></li>
                            </ul>
                        </div>
                        <div>
                            <p><strong><?php _e('Potential Duplicates:', 'restaurant-manager'); ?></strong></p>
                            <ul style="margin-left: 20px;">
                                <li style="color: <?php echo $duplicate_categories > 0 ? '#d63638' : '#00a32a'; ?>">
                                    <?php printf(__('Categories: %d', 'restaurant-manager'), $duplicate_categories); ?>
                                </li>
                                <li style="color: <?php echo $duplicate_items > 0 ? '#d63638' : '#00a32a'; ?>">
                                    <?php printf(__('Menu Items: %d', 'restaurant-manager'), $duplicate_items); ?>
                                </li>
                                <li style="color: <?php echo $duplicate_settings > 0 ? '#d63638' : '#00a32a'; ?>">
                                    <?php printf(__('Settings: %d', 'restaurant-manager'), $duplicate_settings); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if ($duplicate_categories > 0 || $duplicate_items > 0 || $duplicate_settings > 0): ?>
                        <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 10px; margin-top: 10px;">
                            <p style="margin: 0; color: #856404;">
                                <i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i>
                                <strong><?php _e('Duplicates detected!', 'restaurant-manager'); ?></strong>
                                <?php _e('Use the "Clean Duplicates" tool above to remove them safely.', 'restaurant-manager'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

 