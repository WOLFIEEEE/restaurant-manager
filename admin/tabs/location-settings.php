<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_restaurant_setting')) {
    function get_restaurant_setting($key, $default = '') {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        $value = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $settings_table WHERE setting_key = %s", $key));
        return $value !== null ? $value : $default;
    }
}

if (!function_exists('save_restaurant_setting')) {
    function save_restaurant_setting($key, $value) {
        global $wpdb;
        $settings_table = $wpdb->prefix . 'restaurant_settings';
        
        // Use REPLACE INTO to handle duplicates automatically
        $result = $wpdb->query($wpdb->prepare(
            "REPLACE INTO $settings_table (setting_key, setting_value) VALUES (%s, %s)",
            $key,
            $value
        ));
        
        return $result;
    }
}

// Handle form submission
if (isset($_POST['action']) && $_POST['action'] === 'save_location_settings') {
    if (wp_verify_nonce($_POST['restaurant_manager_nonce'], 'restaurant_manager_admin_action')) {
        save_restaurant_setting('location_title', sanitize_text_field($_POST['location_title']));
        save_restaurant_setting('map_embed_url', esc_url_raw($_POST['map_embed_url']));
        save_restaurant_setting('directions_text', sanitize_textarea_field($_POST['directions_text']));
        
        echo '<div class="notice notice-success"><p>' . __('Location settings saved successfully!', 'restaurant-manager') . '</p></div>';
    }
}
?>

<div class="restaurant-admin-content">
    <div class="restaurant-header">
        <h2><i class="fas fa-map-marker-alt"></i> Find Us Settings</h2>
        <p>Configure the information displayed by the [Find_us] shortcode. Visit the Documentation page for usage details.</p>
    </div>

    <div class="postbox restaurant-postbox">
        <div class="postbox-header">
            <h2 class="hndle">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                <?php _e('Find Us Settings', 'restaurant-manager'); ?>
            </h2>
        </div>
        <div class="inside">
            <p class="description">
                <?php _e('Configure the information displayed by the [Find_us] shortcode. This includes location details, map settings, and contact information.', 'restaurant-manager'); ?>
            </p>
            
            <form method="post" class="restaurant-form">
                <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
                <input type="hidden" name="action" value="save_location_settings">
                
                <h3><i class="fas fa-cog"></i> <?php _e('Location Display Settings', 'restaurant-manager'); ?></h3>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="location_title">
                                <i class="fas fa-heading" aria-hidden="true"></i>
                                <?php _e('Page Title', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="location_title" id="location_title" 
                                   value="<?php echo esc_attr(get_restaurant_setting('location_title', 'Find Us')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php _e('The main heading displayed on the Find Us page', 'restaurant-manager'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h3><i class="fas fa-map"></i> <?php _e('Map Configuration', 'restaurant-manager'); ?></h3>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="map_embed_url">
                                <i class="fas fa-globe" aria-hidden="true"></i>
                                <?php _e('Google Maps Embed URL', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="url" name="map_embed_url" id="map_embed_url" 
                                   value="<?php echo esc_attr(get_restaurant_setting('map_embed_url')); ?>" 
                                   class="large-text">
                            <p class="description">
                                <?php _e('Get this from Google Maps → Share → Embed a map → Copy HTML src URL', 'restaurant-manager'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <h3><i class="fas fa-route"></i> <?php _e('Additional Information', 'restaurant-manager'); ?></h3>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="directions_text">
                                <i class="fas fa-directions" aria-hidden="true"></i>
                                <?php _e('Directions & Notes', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="directions_text" id="directions_text" rows="3" class="large-text"><?php echo esc_textarea(get_restaurant_setting('directions_text', 'The restaurant is easily accessible by car with parking available.')); ?></textarea>
                            <p class="description"><?php _e('Additional information about getting to your restaurant (parking, public transport, etc.)', 'restaurant-manager'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Location Settings', 'restaurant-manager'), 'primary'); ?>
            </form>
        </div>
    </div>
</div> 