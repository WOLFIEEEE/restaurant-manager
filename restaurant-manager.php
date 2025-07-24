<?php
/**
 * Plugin Name: Restaurant Manager
 * Plugin URI: https://github.com/restaurant-manager/restaurant-manager
 * Description: Complete restaurant management system with menu display, hero slider, location finder, and custom footer. Includes professional styling, accessibility features, and easy shortcode integration.
 * Version: 1.0.2
 * Author: Khushwwant parihar
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RESTAURANT_MANAGER_VERSION', '1.0.0');
define('RESTAURANT_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RESTAURANT_MANAGER_PLUGIN_PATH', plugin_dir_path(__FILE__));

class RestaurantManager {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Ensure database integrity on admin pages
        if (is_admin()) {
            $this->ensure_database_integrity();
        }
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register shortcodes
        add_shortcode('restaurant_menu', array($this, 'restaurant_menu_shortcode'));
        add_shortcode('Find_us', array($this, 'restaurant_find_us_shortcode'));
        add_shortcode('hero_slider', array($this, 'restaurant_hero_slider_shortcode'));
        add_shortcode('restaurant_footer', array($this, 'restaurant_footer_shortcode'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Handle AJAX requests
        add_action('wp_ajax_save_menu_item', array($this, 'save_menu_item'));
        add_action('wp_ajax_delete_menu_item', array($this, 'delete_menu_item'));
        add_action('wp_ajax_reset_plugin_data', array($this, 'reset_plugin_data'));
        add_action('wp_ajax_save_slider_image', array($this, 'save_slider_image'));
        add_action('wp_ajax_delete_slider_image', array($this, 'delete_slider_image'));
        
        // Add admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
        
        // Footer replacement functionality
        add_action('wp_footer', array($this, 'maybe_replace_footer'));
    }
    
    public function activate() {
        $this->create_tables();
        
        // Clean any existing duplicates before populating new data
        $this->cleanup_duplicates();
        
        $this->populate_default_data();
        
        // Set activation flag
        add_option('restaurant_manager_activated', true);
    }
    
    public function deactivate() {
        // Cleanup if needed
        delete_option('restaurant_manager_activated');
    }
    
    public function admin_notices() {
        global $wpdb;
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        
        // Check if plugin was just activated
        if (get_option('restaurant_manager_activated')) {
            delete_option('restaurant_manager_activated');
            
            // Check if tables exist and have data
            $categories_count = $wpdb->get_var("SELECT COUNT(*) FROM $categories_table");
            $items_count = $wpdb->get_var("SELECT COUNT(*) FROM $items_table");
            
            if ($categories_count == 0 || $items_count == 0) {
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p><strong>Restaurant Manager:</strong> Plugin activated but no default data found. ';
                echo '<a href="' . admin_url('admin.php?page=restaurant-manager&reset_data=1') . '">Click here to populate default menu items</a>.</p>';
                echo '</div>';
            } else {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>Restaurant Manager:</strong> Plugin activated successfully with ' . $items_count . ' menu items in ' . $categories_count . ' categories.</p>';
                echo '</div>';
            }
        }
        
        // Check if user requested data reset
        if (isset($_GET['reset_data']) && $_GET['reset_data'] == '1' && current_user_can('manage_options')) {
            $this->reset_plugin_data();
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p><strong>Restaurant Manager:</strong> Default menu data has been reset and populated successfully!</p>';
            echo '</div>';
        }
    }
    
    public function reset_plugin_data() {
        global $wpdb;
        
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        
        // Clear existing data
        $wpdb->query("TRUNCATE TABLE $items_table");
        $wpdb->query("TRUNCATE TABLE $categories_table");
        $wpdb->query("TRUNCATE TABLE $slider_table");
        
        // Re-populate
        $this->populate_default_data();
        
        if (wp_doing_ajax()) {
            wp_send_json_success('Data reset successfully');
        }
    }
    
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Categories table
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $categories_sql = "CREATE TABLE $categories_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            note text,
            sort_order int(11) DEFAULT 0,
            emoji varchar(20) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY slug (slug)
        ) $charset_collate;";
        
        // Menu items table
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        $items_sql = "CREATE TABLE $items_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            category_id int(11) NOT NULL,
            item_number varchar(20),
            name varchar(255) NOT NULL,
            description text,
            price varchar(20) NOT NULL,
            is_spicy tinyint(1) DEFAULT 0,
            sort_order int(11) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY category_id (category_id)
        ) $charset_collate;";
        
        // Hero slider table
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        $slider_sql = "CREATE TABLE $slider_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            title varchar(200) NOT NULL,
            image_url varchar(500) NOT NULL,
            alt_text varchar(200) NOT NULL,
            is_active tinyint(1) DEFAULT 1,
            sort_order int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Settings table
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        $settings_sql = "CREATE TABLE $settings_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            setting_key varchar(255) NOT NULL,
            setting_value longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY setting_key (setting_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($categories_sql);
        dbDelta($items_sql);
        dbDelta($slider_sql);
        dbDelta($settings_sql);
    }
    
    private function ensure_database_integrity() {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        // Check if settings table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$settings_table'") == $settings_table;
        
        if (!$table_exists) {
            // Recreate the settings table
            $this->create_tables();
        } else {
            // Clean up any duplicate entries (keep the one with the lowest ID)
            $cleaned = $wpdb->query("
                DELETE s1 FROM $settings_table s1
                INNER JOIN $settings_table s2 
                WHERE s1.id > s2.id 
                AND s1.setting_key = s2.setting_key
            ");
            
            // Log if duplicates were found and cleaned
            if ($cleaned > 0) {
                error_log("Restaurant Manager: Cleaned up $cleaned duplicate settings entries");
            }
        }
    }
    
    private function cleanup_duplicates() {
        global $wpdb;
        
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        // Clean duplicate categories (keep the one with lowest ID)
        $wpdb->query("
            DELETE c1 FROM $categories_table c1
            INNER JOIN $categories_table c2 
            WHERE c1.id > c2.id 
            AND c1.slug = c2.slug
        ");
        
        // Clean duplicate menu items (keep the one with lowest ID)
        $wpdb->query("
            DELETE m1 FROM $items_table m1
            INNER JOIN $items_table m2 
            WHERE m1.id > m2.id 
            AND m1.category_id = m2.category_id 
            AND m1.name = m2.name
        ");
        
        // Clean duplicate slider images (keep the one with lowest ID)
        $wpdb->query("
            DELETE s1 FROM $slider_table s1
            INNER JOIN $slider_table s2 
            WHERE s1.id > s2.id 
            AND s1.image_url = s2.image_url
        ");
        
        // Clean duplicate settings (keep the one with lowest ID)
        $wpdb->query("
            DELETE s1 FROM $settings_table s1
            INNER JOIN $settings_table s2 
            WHERE s1.id > s2.id 
            AND s1.setting_key = s2.setting_key
        ");
    }
    
    private function populate_default_data() {
        global $wpdb;
        
        $categories_table = $wpdb->prefix . 'restaurant_categories';
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        
        // Check if data already exists to prevent duplicates
        $existing_categories = $wpdb->get_var("SELECT COUNT(*) FROM $categories_table");
        $existing_items = $wpdb->get_var("SELECT COUNT(*) FROM $items_table");
        
        if ($existing_categories > 0 || $existing_items > 0) {
            // Data already exists, skip population
            return;
        }
        
        // Default categories
        $categories = array(
            array('name' => 'Lunch Specials', 'slug' => 'lunch', 'note' => 'Monday thru Sunday. Served from 11:00 am to 3:00 pm. All Lunch Special served with Fried Rice. Choice of Egg Roll, Crab Rangoon or Soup', 'emoji' => '🍱'),
            array('name' => 'Appetizers', 'slug' => 'appetizers', 'note' => '', 'emoji' => '🥟'),
            array('name' => 'Soup', 'slug' => 'soup', 'note' => '', 'emoji' => '🍜'),
            array('name' => 'Fried Rice', 'slug' => 'fried-rice', 'note' => '', 'emoji' => '🍚'),
            array('name' => 'Chow Mein', 'slug' => 'chow-mein', 'note' => 'Stir fried vegetable with Crisp Noodle', 'emoji' => '🍝'),
            array('name' => 'Mei Fun', 'slug' => 'mei-fun', 'note' => '', 'emoji' => '🍜'),
            array('name' => 'Lo Mein', 'slug' => 'lo-mein', 'note' => 'Soft Noodle', 'emoji' => '🍝'),
            array('name' => 'Egg Foo Young', 'slug' => 'egg-foo-young', 'note' => 'with White Rice', 'emoji' => '🥚'),
            array('name' => 'Chicken', 'slug' => 'chicken', 'note' => 'with White Rice', 'emoji' => '🐔'),
            array('name' => 'Beef', 'slug' => 'beef', 'note' => 'with White Rice', 'emoji' => '🥩'),
            array('name' => 'Seafood', 'slug' => 'seafood', 'note' => 'with White Rice', 'emoji' => '🦐'),
            array('name' => 'Pork', 'slug' => 'pork', 'note' => 'with White Rice', 'emoji' => '🐷'),
            array('name' => 'Vegetable', 'slug' => 'vegetable', 'note' => 'with White Rice', 'emoji' => '🥬'),
            array('name' => "Chef's Suggested Specialties", 'slug' => 'specialties', 'note' => 'with White Rice', 'emoji' => '⭐'),
            array('name' => 'Combination Plate', 'slug' => 'combination', 'note' => 'All day service. All served with Fried Rice. Choice of an Egg Roll or Crab Rangoon', 'emoji' => '🍽️'),
            array('name' => 'Soda', 'slug' => 'beverages', 'note' => '', 'emoji' => '🥤')
        );
        
        // Insert categories
        foreach ($categories as $index => $category) {
            $wpdb->insert(
                $categories_table,
                array(
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'note' => $category['note'],
                    'emoji' => $category['emoji'],
                    'sort_order' => $index + 1
                )
            );
        }
        
        // Get category IDs for menu items
        $category_ids = array();
        $categories_result = $wpdb->get_results("SELECT id, slug FROM $categories_table");
        foreach ($categories_result as $cat) {
            $category_ids[$cat->slug] = $cat->id;
        }
        
        // Default menu items
        $menu_items = $this->get_default_menu_items($category_ids);
        
        // Insert menu items
        foreach ($menu_items as $item) {
            $wpdb->insert(
                $items_table,
                array(
                    'category_id' => $item['category_id'],
                    'item_number' => $item['item_number'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'is_spicy' => $item['is_spicy'],
                    'sort_order' => $item['sort_order']
                )
            );
        }
        
        // Populate default slider images
        $this->populate_default_slider_images();
    }
    
    private function populate_default_slider_images() {
        global $wpdb;
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        
        // Check if slider images already exist
        $existing_images = $wpdb->get_var("SELECT COUNT(*) FROM $slider_table");
        if ($existing_images > 0) {
            return; // Skip if images already exist
        }
        
        // Default slider images with placeholder URLs
        $slider_images = array(
            array(
                'title' => 'Delicious Asian Noodles',
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80',
                'alt_text' => 'Close-up of delicious Asian stir-fried noodles with vegetables and chopsticks on bamboo mat',
                'is_active' => 1,
                'sort_order' => 1
            ),
            array(
                'title' => 'Fresh Sushi Platter',
                'image_url' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80',
                'alt_text' => 'Fresh sushi and sashimi platter with wasabi and soy sauce on wooden serving board',
                'is_active' => 1,
                'sort_order' => 2
            ),
            array(
                'title' => 'Authentic Chinese Dumplings',
                'image_url' => 'https://images.unsplash.com/photo-1563379091339-03246763d388?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80',
                'alt_text' => 'Steaming bamboo baskets filled with authentic Chinese dumplings in traditional restaurant setting',
                'is_active' => 1,
                'sort_order' => 3
            )
        );
        
        // Insert slider images
        foreach ($slider_images as $image) {
            $wpdb->insert($slider_table, $image);
        }
    }
    
    private function create_slider_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
        
        $slider_sql = "CREATE TABLE $slider_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            title varchar(200) NOT NULL,
            image_url varchar(500) NOT NULL,
            alt_text varchar(200) NOT NULL,
            is_active tinyint(1) DEFAULT 1,
            sort_order int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($slider_sql);
    }
    
    private function get_default_menu_items($category_ids) {
        // This contains all the menu items from your HTML
        $items = array();
        
        // Lunch Specials
        $lunch_items = array(
            array('L01', 'Chicken Chow Mein', '$8.79', false),
            array('L02', 'Moo Goo Gai Pa', '$8.79', false),
            array('L03', 'Cashew Chick', '$8.79', false),
            array('L04', 'Kung Pao Chicken', '$8.79', true),
            array('L05', 'Chicken with Broccoli', '$8.79', false),
            array('L06', 'Szechuan Chicken', '$8.79', true),
            array('L07', 'Chicken with Vegetable', '$8.79', false),
            array('L08', 'Sweet & Sour Chicken', '$8.79', false),
            array('L09', 'Sweet & Sour Pork', '$8.79', false),
            array('L10', 'Beef Chow Mein', '$8.79', false),
            array('L11', 'Szechuan Spicy Beef', '$8.79', true),
            array('L12', 'Beef with Green Pepper', '$8.79', false),
            array('L13', 'Beef with Broccoli', '$8.79', false),
            array('L14', 'Mongolian Beef', '$8.79', false),
            array('L15', 'Shrimp Chow Mein', '$8.79', false),
            array('L16', 'Szechuan Spicy Shrimp', '$8.79', true),
            array('L17', 'Shrimp with Vegetable', '$8.79', false),
            array('L18', 'Shrimp with Lobster Sc', '$8.79', false),
            array('L19', 'Sweet & Sour Shrimp', '$8.79', false),
            array('L20', "Triple D's Lite", '$8.79', false),
            array('L21', "General Tso's Chicken", '$8.79', true),
            array('L22', 'Chicken Fried Rice', '$8.79', false),
            array('L23', 'Shrimp Fried Rice', '$8.79', false),
            array('L24', 'Combination Fried Rice', '$8.79', false),
            array('L25', 'Chicken Lo Mein', '$8.79', false),
            array('L26', 'Shrimp Lo Mein', '$8.79', false),
            array('L27', 'Combination Lo Mein', '$8.79', false)
        );
        
        foreach ($lunch_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['lunch'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Appetizers
        $appetizer_items = array(
            array('01', 'Spring Rolls (2)', '$4.50', false, ''),
            array('02', 'Egg Rolls (4)', '$4.50', false, ''),
            array('03', 'Crab Rangoon (4)', '$4.95', false, ''),
            array('04', 'Fried Fantail Shrimp (6)', '$5.83', false, ''),
            array('05', 'Teriyaki Bee (4)', '$6.49', false, ''),
            array('06', 'Steamed Dumplings (6)', '$7.14', false, ''),
            array('07', 'Fried Dumplings (10)', '$7.14', false, ''),
            array('08', 'Chicken Wings (8)', '$8.80', false, ''),
            array('09', 'B-B-Q Spareribs (4)', '$6.93', false, ''),
            array('10', 'Fried Sugar Donuts (10)', '$5.17', false, ''),
            array('11', 'PuPu Tray (for 2)', '$12.50', false, 'Egg Roll Crab Rangoon, Fried Shrimp, Teriyaki Beef, Bar-B-Q Ribs, Chicken Wing. One of each for small, two of each for large.')
        );
        
        foreach ($appetizer_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['appetizers'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Soup
        $soup_items = array(
            array('12', 'Egg Drop Soup', '$2.97', false, ''),
            array('13', 'Won Ton Soup', '$3.19', false, ''),
            array('14', 'Hot & Sour Soup', '$3.19', true, ''),
            array('15', 'Triple Delight Soup', '$6.50', false, ''),
            array('16', 'Vegetable Soup', '$5.17', false, ''),
            array('17', 'House Special Soup', '$7.70', false, '')
        );
        
        foreach ($soup_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['soup'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Fried Rice
        $fried_rice_items = array(
            array('18', 'Combination Fried Rice', '$10.89', false),
            array('19', 'Shrimp Fried Rice', '$10.89', false),
            array('20', 'Chicken Fried Rice', '$10.89', false),
            array('21', 'Pork Fried Rice', '$10.89', false),
            array('22', 'Beef Fried Rice', '$10.89', false),
            array('23', 'Vegetable Fried Rice', '$10.89', false),
            array('24', 'House Special Fried Rice', '$10.89', false),
            array('25', 'Ma-La Fried Rice', '$10.89', true)
        );
        
        foreach ($fried_rice_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['fried-rice'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Chow Mein
        $chow_mein_items = array(
            array('26', 'Combination Chow Mein', '$10.34', false),
            array('27', 'Shrimp Chow Mein', '$10.34', false),
            array('28', 'Pork Chow Mein', '$10.34', false),
            array('29', 'Beef Chow Mein', '$10.34', false),
            array('30', 'Chicken Chow Mein', '$10.34', false)
        );
        
        foreach ($chow_mein_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['chow-mein'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Mei Fun
        $mei_fun_items = array(
            array('31', 'Yang Zhou Mei Fun', '$10.89', false),
            array('32', 'Shrimp Mei Fun', '$10.89', false),
            array('33', 'Chicken Mei Fun', '$10.89', false),
            array('34', 'Pork Mei Fun', '$10.89', false),
            array('35', 'Beef Mei Fun', '$10.89', false)
        );
        
        foreach ($mei_fun_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['mei-fun'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Lo Mein
        $lo_mein_items = array(
            array('36', 'Combination Lo Mein', '$10.99', false),
            array('37', 'Shrimp Lo Mein', '$10.99', false),
            array('38', 'Chicken Lo Mein', '$10.99', false),
            array('39', 'Pork Lo Mein', '$10.99', false),
            array('40', 'Beef Lo Mein', '$10.99', false)
        );
        
        foreach ($lo_mein_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['lo-mein'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Egg Foo Young
        $egg_foo_young_items = array(
            array('41', 'Combination Egg Foo Young', '$12.16', false),
            array('42', 'Shrimp Egg Foo Young', '$12.16', false),
            array('43', 'Chicken Egg Foo Young', '$12.16', false),
            array('44', 'Pork Egg Foo Young', '$12.16', false),
            array('45', 'Beef Egg Foo Young', '$12.16', false)
        );
        
        foreach ($egg_foo_young_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['egg-foo-young'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Chicken
        $chicken_items = array(
            array('46', 'Chicken with Mixed Vegetable', '$11.55', false, ''),
            array('47', 'Sweet & Sour Chicken', '$11.55', false, ''),
            array('48', 'Almond Chicken', '$11.55', false, ''),
            array('49', 'Cashew Chicken', '$11.55', false, ''),
            array('50', 'Moo-Goo Gai Pan', '$11.55', false, '')
        );
        
        foreach ($chicken_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['chicken'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Beef
        $beef_items = array(
            array('63', 'Beef with Broccoli', '$12.10', false, ''),
            array('64', 'Beef with Vegetables', '$12.10', false, ''),
            array('65', 'Beef with Green Pepper', '$12.10', false, ''),
            array('66', 'Beef with Fresh Pineapple', '$12.10', false, ''),
            array('67', 'Mongolian Beef', '$12.10', false, ''),
            array('68', 'Moo Shu Beef', '$12.10', false, 'with 4 pancakes'),
            array('69', 'Kung Pao Beef', '$12.10', true, ''),
            array('70', 'Shredded Beef Szechuan Style', '$12.10', true, ''),
            array('71', 'Beef in Garlic Sauce', '$12.10', true, ''),
            array('72', 'Curry Beef', '$12.10', true, ''),
            array('73', 'Beef with Snow Pea Pods', '$12.10', false, '')
        );
        
        foreach ($beef_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['beef'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Seafood
        $seafood_items = array(
            array('74', 'Sweet and Sour Shrimp', '$12.65', false, ''),
            array('75', 'Shrimp in Lobster Sauce', '$12.65', false, ''),
            array('76', 'Princess Shrimp', '$12.65', true, ''),
            array('77', 'Moo Shu Shrimp', '$12.65', false, 'with 4 pancakes'),
            array('78', 'Cashew Shrimp', '$12.65', false, ''),
            array('79', 'Shrimp with Vegetable', '$12.65', false, ''),
            array('80', 'Shrimp in Garlic Sauce', '$12.65', true, ''),
            array('81', 'Kung Pao Shrimp', '$12.65', true, ''),
            array('82', 'Shrimp with Snow Peas', '$12.65', false, ''),
            array('83', 'Shrimp with Broccoli', '$12.65', false, '')
        );
        
        foreach ($seafood_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['seafood'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Pork
        $pork_items = array(
            array('84', 'Sweet & Sour Pork', '$11.76', false, ''),
            array('85', 'Twice Cooked Pork', '$11.76', true, ''),
            array('86', 'Pork in Garlic Sauce', '$11.76', true, ''),
            array('87', 'Moo Shu Pork', '$11.76', false, 'with 4 pancakes'),
            array('88', 'Shredded Pork Peking', '$11.76', false, ''),
            array('89', 'Kung Pao Pork', '$11.76', true, ''),
            array('90', 'Princess Pork', '$11.76', true, '')
        );
        
        foreach ($pork_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['pork'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Vegetable
        $vegetable_items = array(
            array('91', "Buddha's Delight", '$10.87', false, ''),
            array('92', 'Hunan Home Style Bea', '$10.87', false, ''),
            array('93', 'Ma Po To Fu', '$10.87', true, ''),
            array('94', 'Broccoli in Garlic Sauce', '$10.87', true, ''),
            array('95', "General Tso's Bean Curd", '$10.87', true, '')
        );
        
        foreach ($vegetable_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['vegetable'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Chef's Specialties
        $specialties_items = array(
            array('S01', 'Happy Family', '$13.75', false, 'A combination of large gulf shrimp, scallops, beef and chicken breast blended with snow peas, bamboo shoots, mushrooms and broccoli in our own delicious brown sauce. Served on a sizzling hot plate, a delicious steaming display!'),
            array('S02', 'Seafood Combination', '$13.75', false, 'The perfect mix of large shrimp, scallops and crab meat sauteed with an assortment of fresh Chinese Vegetables.'),
            array('S03', 'Kung Pao Triple Delight', '$13.75', true, 'A spicy delight: chicken, beef and large shrimp with vegetables sauteed in our hot pepper sauce and garnished with peanuts.'),
            array('S04', 'Shrimp and Scallops in Garlic Sc', '$13.75', true, 'A tasty mixture of fresh shrimp, scallops, water chestnuts and vegetables in our garlic sauce.'),
            array('S05', 'Spicy Beef & Scallops', '$13.75', true, 'An old favorite. Sliced beef and scallops sauteed with water chestnuts in our special sauce.'),
            array('S06', 'Triple Delight', '$13.75', false, ''),
            array('S07', 'Pan Fried Noodle', '$13.75', false, ''),
            array('S08', 'Hunan Beef', '$13.75', true, 'Filet mignon sliced thin and sauteed in our sauce and garnished with broccoli.'),
            array('S09', 'Orange Beef', '$13.75', true, 'Thinly sliced beef deep-fried with batter and sauteed in orange peel and our hot sauce.'),
            array('S10', 'Sesame Beef', '$13.75', false, 'Sliced beef, deep-fried and seasoned with a mixture of seeds and black pepper in our special brown sauce.'),
            array('S11', "General Tso's Chick", '$13.75', true, 'Delicious chicken tenderloin in our tasty hot sauce.'),
            array('S12', 'Lemon Chick', '$13.75', false, 'Sliced chicken breast deep fried to a fine delicately flavored lemon sauce.'),
            array('S13', 'Sesame Chicken', '$13.75', false, 'Sliced chicken, deep fried and seasoned with a mixture of sesame seed and black pepper in our special brown sauce.'),
            array('S14', 'Orange Chicken', '$13.75', true, 'Chunk of chicken deep-fried with batter and saused in orange peel and our hot sauce.'),
            array('S15', 'Chili Chicken', '$13.75', true, ''),
            array('S16', 'Rose Shrimp', '$13.75', false, 'Batter dipped shrimp served in our special brown sweet and sour sauce.'),
            array('S17', 'Dong Tong Shrimp', '$13.75', false, 'Shrimp marinated in egg white and greatly sauteed with broccoland Chinese vegetables.'),
            array('S18', 'Shrimp Szechuan Style', '$13.75', true, 'Shrimp sauteed with ginger, onion and ketchup in our hot sauce.'),
            array('S19', 'Scallops in Garlic Sauce', '$13.75', true, 'Fresh scallops with water chestnuts and vegetable in our garlic sauce.')
        );
        
        foreach ($specialties_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['specialties'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Combination Plates
        $combination_items = array(
            array('C01', 'Chicken with Broccoli', '$10.55', false),
            array('C02', 'Cashew Chicken', '$10.55', false),
            array('C03', 'Moo Goo Gai Pan', '$10.55', false),
            array('C04', 'Hunan Chicken', '$10.55', true),
            array('C05', 'Chicken in Garlic Sauce', '$10.55', true),
            array('C06', 'Kung Pao Chicken', '$10.55', true),
            array('C07', 'Beef with Broccoli', '$10.55', false),
            array('C08', 'Beef with Green Pepper', '$10.55', false),
            array('C09', 'Mongolian Beef', '$10.55', false),
            array('C10', 'Kung Pao Beef', '$10.55', true),
            array('C11', 'Shrimp with Broccoli', '$10.55', false),
            array('C12', 'Kung Pao Shrimp', '$10.55', true),
            array('C13', 'Sweet & Sour Chicken', '$10.55', false),
            array('C14', 'Sweet & Sour Pork', '$10.55', false),
            array('C15', 'Sweet & Sour Shrimp', '$10.55', false),
            array('C16', 'Combination Lo Mein', '$10.55', false),
            array('C17', 'Shrimp Lo Mien', '$10.55', false),
            array('C18', 'Combination Egg Foo Young', '$10.55', false),
            array('C19', "General Tso's Chicken", '$10.55', true),
            array('C20', 'Sesame Chicken', '$10.55', false)
        );
        
        foreach ($combination_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['combination'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => '',
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        // Beverages
        $beverage_items = array(
            array('', 'Pepsi (Diet Pepsi, Zero Pepsi)', '$1.98', false, ''),
            array('', 'Crush Orange', '$1.98', false, ''),
            array('', 'Mug Root Beer', '$1.98', false, ''),
            array('', 'Tropicana Pink Lemonade', '$1.98', false, ''),
            array('', 'Dr Pepper', '$1.98', false, '')
        );
        
        foreach ($beverage_items as $index => $item) {
            $items[] = array(
                'category_id' => $category_ids['beverages'],
                'item_number' => $item[0],
                'name' => $item[1],
                'description' => $item[4],
                'price' => $item[2],
                'is_spicy' => $item[3] ? 1 : 0,
                'sort_order' => $index + 1
            );
        }
        
        return $items;
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Restaurant Manager',
            'Restaurant Manager',
            'manage_options',
            'restaurant-manager',
            array($this, 'admin_page'),
            'dashicons-food',
            30
        );
        
        // Add documentation as a submenu page
        add_submenu_page(
            'restaurant-manager',
            'Restaurant Manager Documentation',
            'Documentation',
            'manage_options',
            'restaurant-manager-docs',
            array($this, 'documentation_page')
        );
    }
    
    public function admin_page() {
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'admin/admin-page.php';
    }
    
    public function documentation_page() {
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'admin/documentation-page.php';
    }
    
    public function restaurant_menu_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'show_search' => 'true',
            'show_filters' => 'true'
        ), $atts);
        
        ob_start();
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'templates/menu-display.php';
        return ob_get_clean();
    }
    
    public function restaurant_find_us_shortcode($atts) {
        $atts = shortcode_atts(array(), $atts);
        
        ob_start();
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'templates/find-us-display.php';
        return ob_get_clean();
    }
    
    public function restaurant_hero_slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'autoplay' => 'true',
            'autoplay_delay' => '5000',
            'show_controls' => 'true'
        ), $atts);
        
        ob_start();
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'templates/hero-slider-display.php';
        return ob_get_clean();
    }
    
    public function restaurant_footer_shortcode($atts) {
        $atts = shortcode_atts(array(), $atts);
        
        ob_start();
        include RESTAURANT_MANAGER_PLUGIN_PATH . 'templates/footer-display.php';
        return ob_get_clean();
    }
    
    public function enqueue_frontend_assets() {
        global $post;
        
        // Check if shortcode exists in post content or if we're on a page that might use it
        if ((is_object($post) && (has_shortcode($post->post_content, 'restaurant_menu') || has_shortcode($post->post_content, 'Find_us') || has_shortcode($post->post_content, 'hero_slider') || has_shortcode($post->post_content, 'restaurant_footer'))) || is_page() || is_single()) {
            wp_enqueue_style('restaurant-manager-frontend', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/frontend.css', array(), RESTAURANT_MANAGER_VERSION);
            wp_enqueue_script('restaurant-manager-frontend', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/frontend.js', array('jquery'), RESTAURANT_MANAGER_VERSION, true);
            
            // Enqueue hero slider specific assets if hero_slider shortcode is present
            if (is_object($post) && has_shortcode($post->post_content, 'hero_slider')) {
                wp_enqueue_style('restaurant-hero-slider', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/hero-slider.css', array(), RESTAURANT_MANAGER_VERSION);
                wp_enqueue_script('restaurant-hero-slider', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/hero-slider.js', array('jquery'), RESTAURANT_MANAGER_VERSION, true);
                
                wp_localize_script('restaurant-hero-slider', 'heroSliderConfig', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('restaurant_manager_nonce'),
                    'strings' => array(
                        'play' => __('Play slideshow', 'restaurant-manager'),
                        'pause' => __('Pause slideshow', 'restaurant-manager'),
                        'previous' => __('Previous slide', 'restaurant-manager'),
                        'next' => __('Next slide', 'restaurant-manager'),
                        'slide_of' => __('Slide %1$d of %2$d', 'restaurant-manager')
                    )
                ));
            }
            
            // Enqueue footer specific assets if restaurant_footer shortcode is present or auto-replace is enabled
            global $wpdb;
            $settings_table = $wpdb->prefix . 'restaurant_settings';
            $auto_replace = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $settings_table WHERE setting_key = %s", 'footer_replace_site_footer'));
            
            if ((is_object($post) && has_shortcode($post->post_content, 'restaurant_footer')) || $auto_replace == '1') {
                wp_enqueue_style('restaurant-footer', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/footer.css', array(), RESTAURANT_MANAGER_VERSION);
            }
            
            wp_localize_script('restaurant-manager-frontend', 'restaurantManager', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('restaurant_manager_nonce')
            ));
        }
    }
    
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_restaurant-manager') {
            return;
        }
        
        // Enqueue WordPress media library for image uploads
        wp_enqueue_media();
        
        // Enqueue Font Awesome 6.5.0
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0');
        
        wp_enqueue_style('restaurant-manager-admin', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/admin.css', array('font-awesome'), RESTAURANT_MANAGER_VERSION);
        wp_enqueue_script('restaurant-manager-admin', RESTAURANT_MANAGER_PLUGIN_URL . 'assets/admin.js', array('jquery'), RESTAURANT_MANAGER_VERSION, true);
        
        wp_localize_script('restaurant-manager-admin', 'restaurantManagerAdmin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('restaurant_manager_admin_nonce')
        ));
    }
    
    public function maybe_replace_footer() {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        $auto_replace = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $settings_table WHERE setting_key = %s", 'footer_replace_site_footer'));
        
        if ($auto_replace == '1') {
            // Hide the original footer and add our custom footer
            echo '<style>
                footer:not(.restaurant-footer),
                .site-footer:not(.restaurant-footer),
                #colophon:not(.restaurant-footer),
                .footer:not(.restaurant-footer),
                .main-footer:not(.restaurant-footer) {
                    display: none !important;
                }
            </style>';
            
            // Output our custom footer
            echo $this->restaurant_footer_shortcode(array());
        }
    }
    
    public function save_menu_item() {
        check_ajax_referer('restaurant_manager_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        
        $item_id = intval($_POST['item_id']);
        $category_id = intval($_POST['category_id']);
        $item_number = sanitize_text_field($_POST['item_number']);
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $price = sanitize_text_field($_POST['price']);
        $is_spicy = isset($_POST['is_spicy']) ? 1 : 0;
        $sort_order = intval($_POST['sort_order']);
        
        $data = array(
            'category_id' => $category_id,
            'item_number' => $item_number,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'is_spicy' => $is_spicy,
            'sort_order' => $sort_order
        );
        
        if ($item_id > 0) {
            // Update existing item
            $result = $wpdb->update($items_table, $data, array('id' => $item_id));
        } else {
            // Insert new item
            $result = $wpdb->insert($items_table, $data);
        }
        
        if ($result !== false) {
            wp_send_json_success('Item saved successfully');
        } else {
            wp_send_json_error('Failed to save item');
        }
    }
    
    public function delete_menu_item() {
        check_ajax_referer('restaurant_manager_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $items_table = $wpdb->prefix . 'restaurant_menu_items';
        
        $item_id = intval($_POST['item_id']);
        
        $result = $wpdb->delete($items_table, array('id' => $item_id));
        
        if ($result !== false) {
            wp_send_json_success('Item deleted successfully');
        } else {
            wp_send_json_error('Failed to delete item');
        }
    }
    
    public function save_slider_image() {
        // Enhanced error handling with detailed logging
        try {
            // Check nonce
            if (!wp_verify_nonce($_POST['nonce'], 'restaurant_manager_admin_nonce')) {
                wp_send_json_error('Security check failed. Please refresh the page and try again.');
                return;
            }
            
            // Check user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error('You do not have permission to perform this action.');
                return;
            }
            
            // Validate required fields
            if (empty($_POST['title']) || empty($_POST['image_url']) || empty($_POST['alt_text'])) {
                wp_send_json_error('Required fields are missing. Please fill in all required fields.');
                return;
            }
            
            global $wpdb;
            $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
            
            // Check if table exists, create if it doesn't
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$slider_table'");
            if (!$table_exists) {
                // Try to create the table
                $this->create_slider_table();
                
                // Check again
                $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$slider_table'");
                if (!$table_exists) {
                    wp_send_json_error('Database table could not be created. Please check database permissions.');
                    return;
                }
            }
            
            // Sanitize input data
            $image_id = intval($_POST['image_id']);
            $title = sanitize_text_field($_POST['title']);
            $image_url = esc_url_raw($_POST['image_url']);
            $alt_text = sanitize_text_field($_POST['alt_text']);
            $is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : 1;
            $sort_order = intval($_POST['sort_order']);
            
            // Validate URL
            if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
                wp_send_json_error('Invalid image URL provided.');
                return;
            }
            
            $data = array(
                'title' => $title,
                'image_url' => $image_url,
                'alt_text' => $alt_text,
                'is_active' => $is_active,
                'sort_order' => $sort_order
            );
            
            if ($image_id > 0) {
                // Update existing image
                $result = $wpdb->update(
                    $slider_table, 
                    $data, 
                    array('id' => $image_id),
                    array('%s', '%s', '%s', '%d', '%d'),
                    array('%d')
                );
                
                if ($result === false) {
                    wp_send_json_error('Database update failed: ' . $wpdb->last_error);
                    return;
                }
                
                wp_send_json_success('Slider image updated successfully');
                
            } else {
                // Insert new image
                $result = $wpdb->insert(
                    $slider_table, 
                    $data,
                    array('%s', '%s', '%s', '%d', '%d')
                );
                
                if ($result === false) {
                    wp_send_json_error('Database insert failed: ' . $wpdb->last_error);
                    return;
                }
                
                wp_send_json_success('Slider image added successfully');
            }
            
        } catch (Exception $e) {
            wp_send_json_error('Server error: ' . $e->getMessage());
        }
    }
    
    public function delete_slider_image() {
        try {
            // Check nonce
            if (!wp_verify_nonce($_POST['nonce'], 'restaurant_manager_admin_nonce')) {
                wp_send_json_error('Security check failed. Please refresh the page and try again.');
                return;
            }
            
            // Check user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error('You do not have permission to perform this action.');
                return;
            }
            
            // Validate image ID
            if (empty($_POST['image_id']) || !is_numeric($_POST['image_id'])) {
                wp_send_json_error('Invalid image ID provided.');
                return;
            }
            
            global $wpdb;
            $slider_table = $wpdb->prefix . 'restaurant_hero_slider';
            $image_id = intval($_POST['image_id']);
            
            // Check if image exists
            $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $slider_table WHERE id = %d", $image_id));
            if (!$exists) {
                wp_send_json_error('Image not found in database.');
                return;
            }
            
            // Delete the image
            $result = $wpdb->delete(
                $slider_table, 
                array('id' => $image_id),
                array('%d')
            );
            
            if ($result === false) {
                wp_send_json_error('Database delete failed: ' . $wpdb->last_error);
                return;
            }
            
            if ($result === 0) {
                wp_send_json_error('No image was deleted. It may have already been removed.');
                return;
            }
            
            wp_send_json_success('Slider image deleted successfully');
            
        } catch (Exception $e) {
            wp_send_json_error('Server error: ' . $e->getMessage());
        }
    }
}

// Initialize the plugin
new RestaurantManager(); 