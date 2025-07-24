<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$categories_table = $wpdb->prefix . 'restaurant_categories';
$items_table = $wpdb->prefix . 'restaurant_menu_items';
$settings_table = $wpdb->prefix . 'restaurant_settings';

// Create settings table if it doesn't exist
$wpdb->query("CREATE TABLE IF NOT EXISTS $settings_table (
    id int(11) NOT NULL AUTO_INCREMENT,
    setting_key varchar(255) NOT NULL,
    setting_value longtext,
    PRIMARY KEY (id),
    UNIQUE KEY setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Get current tab
$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'menu-items';

// Handle form submissions
if (isset($_POST['action'])) {
    if (!wp_verify_nonce($_POST['restaurant_manager_nonce'], 'restaurant_manager_admin_action')) {
        wp_die(__('Security check failed', 'restaurant-manager'));
    }
    
    switch ($_POST['action']) {
        case 'add_item':
            $data = array(
                'category_id' => intval($_POST['category_id']),
                'item_number' => sanitize_text_field($_POST['item_number']),
                'name' => sanitize_text_field($_POST['name']),
                'description' => sanitize_textarea_field($_POST['description']),
                'price' => sanitize_text_field($_POST['price']),
                'is_spicy' => isset($_POST['is_spicy']) ? 1 : 0,
                'sort_order' => intval($_POST['sort_order'])
            );
            
            if ($wpdb->insert($items_table, $data)) {
                add_settings_error('restaurant_manager', 'item_added', __('Menu item added successfully!', 'restaurant-manager'), 'success');
            } else {
                add_settings_error('restaurant_manager', 'item_add_failed', __('Failed to add menu item.', 'restaurant-manager'), 'error');
            }
            break;
            
        case 'update_item':
            $item_id = intval($_POST['item_id']);
            $data = array(
                'category_id' => intval($_POST['category_id']),
                'item_number' => sanitize_text_field($_POST['item_number']),
                'name' => sanitize_text_field($_POST['name']),
                'description' => sanitize_textarea_field($_POST['description']),
                'price' => sanitize_text_field($_POST['price']),
                'is_spicy' => isset($_POST['is_spicy']) ? 1 : 0,
                'sort_order' => intval($_POST['sort_order'])
            );
            
            if ($wpdb->update($items_table, $data, array('id' => $item_id))) {
                add_settings_error('restaurant_manager', 'item_updated', __('Menu item updated successfully!', 'restaurant-manager'), 'success');
            } else {
                add_settings_error('restaurant_manager', 'item_update_failed', __('Failed to update menu item.', 'restaurant-manager'), 'error');
            }
            break;
            
        case 'delete_item':
            $item_id = intval($_POST['item_id']);
            if ($wpdb->delete($items_table, array('id' => $item_id))) {
                add_settings_error('restaurant_manager', 'item_deleted', __('Menu item deleted successfully!', 'restaurant-manager'), 'success');
            } else {
                add_settings_error('restaurant_manager', 'item_delete_failed', __('Failed to delete menu item.', 'restaurant-manager'), 'error');
            }
            break;
            
        case 'save_restaurant_info':
            $settings = array(
                'restaurant_name' => sanitize_text_field($_POST['restaurant_name']),
                'restaurant_tagline' => sanitize_text_field($_POST['restaurant_tagline']),
                'website_url' => esc_url_raw($_POST['website_url']),
                'address_line1' => sanitize_text_field($_POST['address_line1']),
                'address_line2' => sanitize_text_field($_POST['address_line2']),
                'city' => sanitize_text_field($_POST['city']),
                'state' => sanitize_text_field($_POST['state']),
                'zip_code' => sanitize_text_field($_POST['zip_code']),
                'phone_primary' => sanitize_text_field($_POST['phone_primary']),
                'phone_secondary' => sanitize_text_field($_POST['phone_secondary']),
                'delivery_info' => sanitize_textarea_field($_POST['delivery_info']),
                'payment_methods' => sanitize_textarea_field($_POST['payment_methods'])
            );
            
            foreach ($settings as $key => $value) {
                $wpdb->replace($settings_table, array(
                    'setting_key' => $key,
                    'setting_value' => $value
                ));
            }
            
            add_settings_error('restaurant_manager', 'info_saved', __('Restaurant information saved successfully!', 'restaurant-manager'), 'success');
            break;
            
        case 'save_opening_hours':
            $settings = array(
                'monday_hours' => sanitize_text_field($_POST['monday_hours']),
                'tuesday_hours' => sanitize_text_field($_POST['tuesday_hours']),
                'wednesday_hours' => sanitize_text_field($_POST['wednesday_hours']),
                'thursday_hours' => sanitize_text_field($_POST['thursday_hours']),
                'friday_hours' => sanitize_text_field($_POST['friday_hours']),
                'saturday_hours' => sanitize_text_field($_POST['saturday_hours']),
                'sunday_hours' => sanitize_text_field($_POST['sunday_hours']),
                'special_hours_note' => sanitize_textarea_field($_POST['special_hours_note'])
            );
            
            foreach ($settings as $key => $value) {
                $wpdb->replace($settings_table, array(
                    'setting_key' => $key,
                    'setting_value' => $value
                ));
            }
            
            add_settings_error('restaurant_manager', 'hours_saved', __('Opening hours saved successfully!', 'restaurant-manager'), 'success');
            break;
            
        case 'save_location_settings':
            $settings = array(
                'location_title' => sanitize_text_field($_POST['location_title']),
                'map_embed_url' => esc_url_raw($_POST['map_embed_url']),
                'map_latitude' => sanitize_text_field($_POST['map_latitude']),
                'map_longitude' => sanitize_text_field($_POST['map_longitude']),
                'directions_text' => sanitize_textarea_field($_POST['directions_text'])
            );
            
            foreach ($settings as $key => $value) {
                $wpdb->replace($settings_table, array(
                    'setting_key' => $key,
                    'setting_value' => $value
                ));
            }
            
            add_settings_error('restaurant_manager', 'location_saved', __('Location settings saved successfully!', 'restaurant-manager'), 'success');
            break;
            
        case 'add_category':
            $data = array(
                'name' => sanitize_text_field($_POST['category_name']),
                'slug' => sanitize_title($_POST['category_name']),
                'emoji' => sanitize_text_field($_POST['category_emoji']),
                'note' => sanitize_textarea_field($_POST['category_note']),
                'sort_order' => intval($_POST['category_sort_order'])
            );
            
            if ($wpdb->insert($categories_table, $data)) {
                add_settings_error('restaurant_manager', 'category_added', __('Category added successfully!', 'restaurant-manager'), 'success');
            } else {
                add_settings_error('restaurant_manager', 'category_add_failed', __('Failed to add category.', 'restaurant-manager'), 'error');
            }
            break;
            
        case 'update_category':
            $category_id = intval($_POST['category_id']);
            $data = array(
                'name' => sanitize_text_field($_POST['category_name']),
                'slug' => sanitize_title($_POST['category_name']),
                'emoji' => sanitize_text_field($_POST['category_emoji']),
                'note' => sanitize_textarea_field($_POST['category_note']),
                'sort_order' => intval($_POST['category_sort_order'])
            );
            
            if ($wpdb->update($categories_table, $data, array('id' => $category_id))) {
                add_settings_error('restaurant_manager', 'category_updated', __('Category updated successfully!', 'restaurant-manager'), 'success');
            } else {
                add_settings_error('restaurant_manager', 'category_update_failed', __('Failed to update category.', 'restaurant-manager'), 'error');
            }
            break;
            
        case 'delete_category':
            $category_id = intval($_POST['category_id']);
            // Check if category has items
            $item_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $items_table WHERE category_id = %d", $category_id));
            
            if ($item_count > 0) {
                add_settings_error('restaurant_manager', 'category_has_items', __('Cannot delete category with menu items. Move or delete items first.', 'restaurant-manager'), 'error');
            } else {
                if ($wpdb->delete($categories_table, array('id' => $category_id))) {
                    add_settings_error('restaurant_manager', 'category_deleted', __('Category deleted successfully!', 'restaurant-manager'), 'success');
                } else {
                    add_settings_error('restaurant_manager', 'category_delete_failed', __('Failed to delete category.', 'restaurant-manager'), 'error');
                }
            }
            break;
    }
    
    // Redirect to prevent form resubmission
    set_transient('restaurant_manager_admin_notices', get_settings_errors('restaurant_manager'), 30);
    wp_redirect(admin_url('admin.php?page=restaurant-manager&tab=' . $current_tab));
    exit;
}

// Get data for current tab
$categories = $wpdb->get_results("SELECT * FROM $categories_table ORDER BY sort_order");
$categories_count = $wpdb->get_var("SELECT COUNT(*) FROM $categories_table");
$items_count = $wpdb->get_var("SELECT COUNT(*) FROM $items_table");

// Get restaurant settings
if (!function_exists('get_restaurant_setting')) {
    function get_restaurant_setting($key, $default = '') {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        $value = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $settings_table WHERE setting_key = %s", $key));
        return $value !== null ? $value : $default;
    }
}

// Get menu items for current tab
$current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$items_per_page = 20;
$offset = ($current_page - 1) * $items_per_page;
$selected_category = isset($_GET['category_filter']) ? intval($_GET['category_filter']) : '';

$where_clause = '';
if ($selected_category) {
    $where_clause = $wpdb->prepare("WHERE i.category_id = %d", $selected_category);
}

$total_items = $wpdb->get_var("SELECT COUNT(*) FROM $items_table i $where_clause");
$menu_items = $wpdb->get_results($wpdb->prepare("
    SELECT i.*, c.name as category_name, c.emoji 
    FROM $items_table i 
    JOIN $categories_table c ON i.category_id = c.id 
    $where_clause
    ORDER BY c.sort_order, i.sort_order 
    LIMIT %d OFFSET %d
", $items_per_page, $offset));

$total_pages = ceil($total_items / $items_per_page);
?>

<div class="wrap restaurant-manager-admin">
    <header class="restaurant-admin-header">
        <h1 class="wp-heading-inline">
            <i class="fas fa-utensils" aria-hidden="true"></i>
            <?php _e('Restaurant Manager', 'restaurant-manager'); ?>
        </h1>
        <p class="description">
            <?php _e('Complete restaurant management system for menu items, settings, and more.', 'restaurant-manager'); ?>
        </p>
        
        <!-- Tab Navigation -->
        <nav class="nav-tab-wrapper restaurant-tab-wrapper" role="tablist">
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=menu-items'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'menu-items') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'menu-items') ? 'true' : 'false'; ?>">
                <i class="fas fa-utensils" aria-hidden="true"></i>
                <?php _e('Menu Items', 'restaurant-manager'); ?>
                <span class="tab-count"><?php echo $items_count; ?></span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=restaurant-settings'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'restaurant-settings') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'restaurant-settings') ? 'true' : 'false'; ?>">
                <i class="fas fa-store" aria-hidden="true"></i>
                <?php _e('Restaurant Info', 'restaurant-manager'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=categories'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'categories') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'categories') ? 'true' : 'false'; ?>">
                <i class="fas fa-folder" aria-hidden="true"></i>
                <?php _e('Categories', 'restaurant-manager'); ?>
                <span class="tab-count"><?php echo $categories_count; ?></span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=location'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'location') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'location') ? 'true' : 'false'; ?>">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                <?php _e('Find Us', 'restaurant-manager'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=hero-slider'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'hero-slider') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'hero-slider') ? 'true' : 'false'; ?>">
                <i class="fas fa-images" aria-hidden="true"></i>
                <?php _e('Hero Slider', 'restaurant-manager'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=footer-settings'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'footer-settings') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'footer-settings') ? 'true' : 'false'; ?>">
                <i class="fas fa-compress-arrows-alt" aria-hidden="true"></i>
                <?php _e('Footer', 'restaurant-manager'); ?>
            </a>

            <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=settings'); ?>" 
               class="nav-tab <?php echo ($current_tab === 'settings') ? 'nav-tab-active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo ($current_tab === 'settings') ? 'true' : 'false'; ?>">
                <i class="fas fa-cog" aria-hidden="true"></i>
                <?php _e('Settings', 'restaurant-manager'); ?>
            </a>
        </nav>
    </header>
    
    <?php 
    // Display notices from redirect
    $admin_notices = get_transient('restaurant_manager_admin_notices');
    if ($admin_notices) {
        foreach ($admin_notices as $notice) {
            $notice_class = $notice['type'] === 'success' ? 'notice-success' : 'notice-error';
            $icon = $notice['type'] === 'success' ? '✅' : '❌';
            echo '<div class="notice ' . $notice_class . ' is-dismissible restaurant-admin-notice">';
            echo '<p><span class="notice-icon" aria-hidden="true">' . $icon . '</span> ' . esc_html($notice['message']) . '</p>';
            echo '</div>';
        }
        delete_transient('restaurant_manager_admin_notices');
    }
    
    settings_errors('restaurant_manager'); 
    ?>
    
    <div class="restaurant-admin-content">
        <?php switch ($current_tab): 
            case 'menu-items': ?>
                <?php include 'tabs/menu-items.php'; ?>
                <?php break;
            case 'restaurant-settings': ?>
                <?php include 'tabs/restaurant-settings.php'; ?>
                <?php break;
            case 'categories': ?>
                <?php include 'tabs/categories.php'; ?>
                <?php break;
            case 'location': ?>
                <?php include 'tabs/location-settings.php'; ?>
                <?php break;
            case 'hero-slider': ?>
                <?php include 'tabs/hero-slider.php'; ?>
                <?php break;
            case 'footer-settings': ?>
                <?php include 'tabs/footer-settings.php'; ?>
                <?php break;

            case 'settings': ?>
                <?php include 'tabs/plugin-settings.php'; ?>
                <?php break;
            default: ?>
                <?php include 'tabs/menu-items.php'; ?>
        <?php endswitch; ?>
    </div>
</div>

<!-- Screen reader live region for announcements -->
<div aria-live="polite" aria-atomic="true" class="sr-only" id="admin-announcements"></div> 