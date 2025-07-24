<?php
if (!defined('ABSPATH')) {
    exit;
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
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label>
                        <i class="fas fa-redo" aria-hidden="true"></i>
                        <?php _e('Reset Plugin Data', 'restaurant-manager'); ?>
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
                        <span aria-hidden="true">📊</span>
                        <?php _e('Database Status', 'restaurant-manager'); ?>
                    </label>
                </th>
                <td>
                    <p>
                        <strong><?php _e('Categories:', 'restaurant-manager'); ?></strong> 
                        <?php echo $categories_count; ?>
                    </p>
                    <p>
                        <strong><?php _e('Menu Items:', 'restaurant-manager'); ?></strong> 
                        <?php echo $items_count; ?>
                    </p>
                    <p>
                        <strong><?php _e('Restaurant Settings:', 'restaurant-manager'); ?></strong> 
                        <?php 
                        $settings_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}restaurant_settings");
                        echo $settings_count;
                        ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</div>

 