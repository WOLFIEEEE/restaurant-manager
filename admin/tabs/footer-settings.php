<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$settings_table = $wpdb->prefix . 'restaurant_settings';

// Get restaurant settings function
if (!function_exists('get_restaurant_setting')) {
    function get_restaurant_setting($key, $default = '') {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        $value = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $settings_table WHERE setting_key = %s", $key));
        return $value !== null ? $value : $default;
    }
}

// Save settings function
if (!function_exists('save_restaurant_setting')) {
    function save_restaurant_setting($key, $value) {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        // Debug: Log what we're trying to save
        error_log("Saving footer setting: $key = " . var_export($value, true));
        
        // Use REPLACE INTO to handle duplicates automatically
        $result = $wpdb->query($wpdb->prepare(
            "REPLACE INTO $settings_table (setting_key, setting_value) VALUES (%s, %s)",
            $key,
            $value
        ));
        
        // Debug: Log the result
        if ($result === false) {
            error_log("Failed to save footer setting: $key. DB Error: " . $wpdb->last_error);
        }
        
        return $result;
    }
}

// Debug: Check if table exists and clean up duplicates
global $wpdb;
$settings_table = $wpdb->prefix . 'restaurant_settings';
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$settings_table'") == $settings_table;
if (!$table_exists) {
    echo '<div class="notice notice-error"><p>' . __('Database table restaurant_settings does not exist. Please deactivate and reactivate the plugin.', 'restaurant-manager') . '</p></div>';
} else {
    // Clean up any duplicate entries
    $wpdb->query("
        DELETE s1 FROM $settings_table s1
        INNER JOIN $settings_table s2 
        WHERE s1.id > s2.id 
        AND s1.setting_key = s2.setting_key
    ");
}

// Handle form submission
if (isset($_POST['save_footer_settings']) && wp_verify_nonce($_POST['footer_settings_nonce'], 'save_footer_settings')) {
    // Footer display options
    save_restaurant_setting('footer_replace_site_footer', isset($_POST['footer_replace_site_footer']) ? '1' : '0');
    save_restaurant_setting('footer_show_logo', isset($_POST['footer_show_logo']) ? '1' : '0');
    save_restaurant_setting('footer_show_contact', isset($_POST['footer_show_contact']) ? '1' : '0');
    save_restaurant_setting('footer_show_hours', isset($_POST['footer_show_hours']) ? '1' : '0');
    save_restaurant_setting('footer_show_menu', isset($_POST['footer_show_menu']) ? '1' : '0');
    save_restaurant_setting('footer_show_promotions', isset($_POST['footer_show_promotions']) ? '1' : '0');
    save_restaurant_setting('footer_show_social', isset($_POST['footer_show_social']) ? '1' : '0');
    
    // Social media
    save_restaurant_setting('footer_facebook_url', esc_url_raw(isset($_POST['footer_facebook_url']) ? $_POST['footer_facebook_url'] : ''));
    save_restaurant_setting('footer_instagram_url', esc_url_raw(isset($_POST['footer_instagram_url']) ? $_POST['footer_instagram_url'] : ''));
    save_restaurant_setting('footer_twitter_url', esc_url_raw(isset($_POST['footer_twitter_url']) ? $_POST['footer_twitter_url'] : ''));
    save_restaurant_setting('footer_youtube_url', esc_url_raw(isset($_POST['footer_youtube_url']) ? $_POST['footer_youtube_url'] : ''));
    save_restaurant_setting('footer_yelp_url', esc_url_raw(isset($_POST['footer_yelp_url']) ? $_POST['footer_yelp_url'] : ''));
    
    // Promotions
    save_restaurant_setting('footer_promotion_1_title', sanitize_text_field(isset($_POST['footer_promotion_1_title']) ? $_POST['footer_promotion_1_title'] : ''));
    save_restaurant_setting('footer_promotion_1_desc', sanitize_textarea_field(isset($_POST['footer_promotion_1_desc']) ? $_POST['footer_promotion_1_desc'] : ''));
    save_restaurant_setting('footer_promotion_1_link', esc_url_raw(isset($_POST['footer_promotion_1_link']) ? $_POST['footer_promotion_1_link'] : ''));
    
    save_restaurant_setting('footer_promotion_2_title', sanitize_text_field(isset($_POST['footer_promotion_2_title']) ? $_POST['footer_promotion_2_title'] : ''));
    save_restaurant_setting('footer_promotion_2_desc', sanitize_textarea_field(isset($_POST['footer_promotion_2_desc']) ? $_POST['footer_promotion_2_desc'] : ''));
    save_restaurant_setting('footer_promotion_2_link', esc_url_raw(isset($_POST['footer_promotion_2_link']) ? $_POST['footer_promotion_2_link'] : ''));
    
    save_restaurant_setting('footer_promotion_3_title', sanitize_text_field(isset($_POST['footer_promotion_3_title']) ? $_POST['footer_promotion_3_title'] : ''));
    save_restaurant_setting('footer_promotion_3_desc', sanitize_textarea_field(isset($_POST['footer_promotion_3_desc']) ? $_POST['footer_promotion_3_desc'] : ''));
    save_restaurant_setting('footer_promotion_3_link', esc_url_raw(isset($_POST['footer_promotion_3_link']) ? $_POST['footer_promotion_3_link'] : ''));
    
    // Copyright
    save_restaurant_setting('footer_copyright_text', sanitize_text_field(isset($_POST['footer_copyright_text']) ? $_POST['footer_copyright_text'] : ''));
    
    // Designer section - if text is empty, also clear the link
    $designer_text = sanitize_text_field(isset($_POST['footer_designed_by_text']) ? $_POST['footer_designed_by_text'] : '');
    $designer_link = trim($designer_text) !== '' ? esc_url_raw(isset($_POST['footer_designed_by_link']) ? $_POST['footer_designed_by_link'] : '') : '';
    
    save_restaurant_setting('footer_designed_by_text', $designer_text);
    save_restaurant_setting('footer_designed_by_link', $designer_link);
    
    // Debug: Log if there were any database errors
    global $wpdb;
    if ($wpdb->last_error) {
        error_log('Restaurant Manager Footer Settings Save Error: ' . $wpdb->last_error);
        echo '<div class="notice notice-error"><p>' . __('Error saving settings: ', 'restaurant-manager') . esc_html($wpdb->last_error) . '</p></div>';
    } else {
        echo '<div class="notice notice-success"><p>' . __('Footer settings saved successfully!', 'restaurant-manager') . '</p></div>';
    }
}

// Get current settings
$footer_replace_site_footer = get_restaurant_setting('footer_replace_site_footer', '0');
$footer_show_logo = get_restaurant_setting('footer_show_logo', '1');
$footer_show_contact = get_restaurant_setting('footer_show_contact', '1');
$footer_show_hours = get_restaurant_setting('footer_show_hours', '1');
$footer_show_menu = get_restaurant_setting('footer_show_menu', '1');
$footer_show_promotions = get_restaurant_setting('footer_show_promotions', '1');
$footer_show_social = get_restaurant_setting('footer_show_social', '1');

$footer_facebook_url = get_restaurant_setting('footer_facebook_url', '');
$footer_instagram_url = get_restaurant_setting('footer_instagram_url', '');
$footer_twitter_url = get_restaurant_setting('footer_twitter_url', '');
$footer_youtube_url = get_restaurant_setting('footer_youtube_url', '');
$footer_yelp_url = get_restaurant_setting('footer_yelp_url', '');

$footer_promotion_1_title = get_restaurant_setting('footer_promotion_1_title', '');
$footer_promotion_1_desc = get_restaurant_setting('footer_promotion_1_desc', '');
$footer_promotion_1_link = get_restaurant_setting('footer_promotion_1_link', '');

$footer_promotion_2_title = get_restaurant_setting('footer_promotion_2_title', '');
$footer_promotion_2_desc = get_restaurant_setting('footer_promotion_2_desc', '');
$footer_promotion_2_link = get_restaurant_setting('footer_promotion_2_link', '');

$footer_promotion_3_title = get_restaurant_setting('footer_promotion_3_title', '');
$footer_promotion_3_desc = get_restaurant_setting('footer_promotion_3_desc', '');
$footer_promotion_3_link = get_restaurant_setting('footer_promotion_3_link', '');

$footer_copyright_text = get_restaurant_setting('footer_copyright_text', '© 2024 China Dragon All Rights Reserved');
$footer_designed_by_text = get_restaurant_setting('footer_designed_by_text', '');
$footer_designed_by_link = get_restaurant_setting('footer_designed_by_link', '');
?>

<div class="restaurant-admin-content">
    <div class="restaurant-header">
        <h2><?php _e('Footer Settings', 'restaurant-manager'); ?></h2>
        <p><?php _e('Manage your restaurant footer content, social media links, and promotions.', 'restaurant-manager'); ?></p>
    </div>

    <form method="post" class="restaurant-form">
        <?php wp_nonce_field('save_footer_settings', 'footer_settings_nonce'); ?>

        <!-- Footer Display Options -->
        <div class="form-section">
            <h3><?php _e('Display Options', 'restaurant-manager'); ?></h3>
            
            <div class="form-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="footer_replace_site_footer" value="1" <?php checked($footer_replace_site_footer, '1'); ?>>
                    <?php _e('Replace site footer automatically (no shortcode needed)', 'restaurant-manager'); ?>
                </label>
                <p class="description"><?php _e('When enabled, this footer will automatically replace your theme\'s footer.', 'restaurant-manager'); ?></p>
            </div>

            <div class="form-columns">
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_logo" value="1" <?php checked($footer_show_logo, '1'); ?>>
                        <?php _e('Show Logo & Contact Info', 'restaurant-manager'); ?>
                    </label>
                </div>
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_contact" value="1" <?php checked($footer_show_contact, '1'); ?>>
                        <?php _e('Show Contact Information', 'restaurant-manager'); ?>
                    </label>
                </div>
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_hours" value="1" <?php checked($footer_show_hours, '1'); ?>>
                        <?php _e('Show Business Hours', 'restaurant-manager'); ?>
                    </label>
                </div>
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_menu" value="1" <?php checked($footer_show_menu, '1'); ?>>
                        <?php _e('Show Main Menu Links', 'restaurant-manager'); ?>
                    </label>
                </div>
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_promotions" value="1" <?php checked($footer_show_promotions, '1'); ?>>
                        <?php _e('Show Promotions', 'restaurant-manager'); ?>
                    </label>
                </div>
                <div class="form-column">
                    <label class="checkbox-label">
                        <input type="checkbox" name="footer_show_social" value="1" <?php checked($footer_show_social, '1'); ?>>
                        <?php _e('Show Social Media Icons', 'restaurant-manager'); ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="form-section">
            <h3><?php _e('Social Media Links', 'restaurant-manager'); ?></h3>
            <p class="description"><?php _e('Add your social media profile URLs. Leave blank to hide specific icons.', 'restaurant-manager'); ?></p>
            
            <div class="form-columns">
                <div class="form-column">
                    <label for="footer_facebook_url"><?php _e('Facebook URL', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_facebook_url" name="footer_facebook_url" value="<?php echo esc_attr($footer_facebook_url); ?>" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="form-column">
                    <label for="footer_instagram_url"><?php _e('Instagram URL', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_instagram_url" name="footer_instagram_url" value="<?php echo esc_attr($footer_instagram_url); ?>" placeholder="https://instagram.com/yourpage">
                </div>
                <div class="form-column">
                    <label for="footer_twitter_url"><?php _e('Twitter URL', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_twitter_url" name="footer_twitter_url" value="<?php echo esc_attr($footer_twitter_url); ?>" placeholder="https://twitter.com/yourpage">
                </div>
                <div class="form-column">
                    <label for="footer_youtube_url"><?php _e('YouTube URL', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_youtube_url" name="footer_youtube_url" value="<?php echo esc_attr($footer_youtube_url); ?>" placeholder="https://youtube.com/yourchannel">
                </div>
                <div class="form-column">
                    <label for="footer_yelp_url"><?php _e('Yelp URL', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_yelp_url" name="footer_yelp_url" value="<?php echo esc_attr($footer_yelp_url); ?>" placeholder="https://yelp.com/biz/yourrestaurant">
                </div>
            </div>
        </div>

        <!-- Promotions Section -->
        <div class="form-section">
            <h3><?php _e('Promotions', 'restaurant-manager'); ?></h3>
            <p class="description"><?php _e('Add up to 3 promotions to display in your footer. Leave fields blank to hide specific promotions.', 'restaurant-manager'); ?></p>
            
            <!-- Promotion 1 -->
            <div class="promotion-group">
                <h4><?php _e('Promotion 1', 'restaurant-manager'); ?></h4>
                <div class="form-columns">
                    <div class="form-column">
                        <label for="footer_promotion_1_title"><?php _e('Title', 'restaurant-manager'); ?></label>
                        <input type="text" id="footer_promotion_1_title" name="footer_promotion_1_title" value="<?php echo esc_attr($footer_promotion_1_title); ?>" placeholder="Special Offer">
                    </div>
                    <div class="form-column">
                        <label for="footer_promotion_1_link"><?php _e('Link URL (optional)', 'restaurant-manager'); ?></label>
                        <input type="url" id="footer_promotion_1_link" name="footer_promotion_1_link" value="<?php echo esc_attr($footer_promotion_1_link); ?>" placeholder="https://...">
                    </div>
                </div>
                <div class="form-row">
                    <label for="footer_promotion_1_desc"><?php _e('Description', 'restaurant-manager'); ?></label>
                    <textarea id="footer_promotion_1_desc" name="footer_promotion_1_desc" rows="2" placeholder="Brief description of your promotion..."><?php echo esc_textarea($footer_promotion_1_desc); ?></textarea>
                </div>
            </div>

            <!-- Promotion 2 -->
            <div class="promotion-group">
                <h4><?php _e('Promotion 2', 'restaurant-manager'); ?></h4>
                <div class="form-columns">
                    <div class="form-column">
                        <label for="footer_promotion_2_title"><?php _e('Title', 'restaurant-manager'); ?></label>
                        <input type="text" id="footer_promotion_2_title" name="footer_promotion_2_title" value="<?php echo esc_attr($footer_promotion_2_title); ?>" placeholder="Special Offer">
                    </div>
                    <div class="form-column">
                        <label for="footer_promotion_2_link"><?php _e('Link URL (optional)', 'restaurant-manager'); ?></label>
                        <input type="url" id="footer_promotion_2_link" name="footer_promotion_2_link" value="<?php echo esc_attr($footer_promotion_2_link); ?>" placeholder="https://...">
                    </div>
                </div>
                <div class="form-row">
                    <label for="footer_promotion_2_desc"><?php _e('Description', 'restaurant-manager'); ?></label>
                    <textarea id="footer_promotion_2_desc" name="footer_promotion_2_desc" rows="2" placeholder="Brief description of your promotion..."><?php echo esc_textarea($footer_promotion_2_desc); ?></textarea>
                </div>
            </div>

            <!-- Promotion 3 -->
            <div class="promotion-group">
                <h4><?php _e('Promotion 3', 'restaurant-manager'); ?></h4>
                <div class="form-columns">
                    <div class="form-column">
                        <label for="footer_promotion_3_title"><?php _e('Title', 'restaurant-manager'); ?></label>
                        <input type="text" id="footer_promotion_3_title" name="footer_promotion_3_title" value="<?php echo esc_attr($footer_promotion_3_title); ?>" placeholder="Special Offer">
                    </div>
                    <div class="form-column">
                        <label for="footer_promotion_3_link"><?php _e('Link URL (optional)', 'restaurant-manager'); ?></label>
                        <input type="url" id="footer_promotion_3_link" name="footer_promotion_3_link" value="<?php echo esc_attr($footer_promotion_3_link); ?>" placeholder="https://...">
                    </div>
                </div>
                <div class="form-row">
                    <label for="footer_promotion_3_desc"><?php _e('Description', 'restaurant-manager'); ?></label>
                    <textarea id="footer_promotion_3_desc" name="footer_promotion_3_desc" rows="2" placeholder="Brief description of your promotion..."><?php echo esc_textarea($footer_promotion_3_desc); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Copyright Section -->
        <div class="form-section">
            <h3><?php _e('Copyright & Credits', 'restaurant-manager'); ?></h3>
            <p class="description"><?php _e('The copyright text is required. Designer section is optional - leave designer text empty to hide it entirely.', 'restaurant-manager'); ?></p>
            
            <div class="form-columns">
                <div class="form-column">
                    <label for="footer_copyright_text"><?php _e('Copyright Text', 'restaurant-manager'); ?></label>
                    <input type="text" id="footer_copyright_text" name="footer_copyright_text" value="<?php echo esc_attr($footer_copyright_text); ?>" placeholder="© 2024 Your Restaurant All Rights Reserved">
                </div>
                <div class="form-column">
                    <label for="footer_designed_by_text"><?php _e('Designed By Text (optional)', 'restaurant-manager'); ?></label>
                    <input type="text" id="footer_designed_by_text" name="footer_designed_by_text" value="<?php echo esc_attr($footer_designed_by_text); ?>" placeholder="Leave empty to hide designer section">
                </div>
                <div class="form-column">
                    <label for="footer_designed_by_link"><?php _e('Designer Link (optional)', 'restaurant-manager'); ?></label>
                    <input type="url" id="footer_designed_by_link" name="footer_designed_by_link" value="<?php echo esc_attr($footer_designed_by_link); ?>" placeholder="https://welikemenu.com">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="save_footer_settings" class="button-primary">
                <?php _e('Save Footer Settings', 'restaurant-manager'); ?>
            </button>
            <p class="help-text">
                <?php _e('Shortcode:', 'restaurant-manager'); ?> <code>[restaurant_footer]</code>
                <br>
                <?php _e('Use this shortcode to display the footer anywhere, or enable "Replace site footer automatically" above.', 'restaurant-manager'); ?>
            </p>
        </div>
    </form>
</div>

<style>
.promotion-group {
    background: #f9f9f9;
    padding: 20px;
    margin: 15px 0;
    border-radius: 5px;
    border-left: 4px solid #c41e3a;
}

.promotion-group h4 {
    margin: 0 0 15px 0;
    color: #c41e3a;
    font-size: 16px;
}

.form-columns {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-column label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.help-text {
    background: #e8f4f8;
    padding: 15px;
    border-radius: 5px;
    margin-top: 15px;
    border-left: 4px solid #2196F3;
}

.help-text code {
    background: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
    color: #c41e3a;
    font-weight: 600;
}
</style> 