<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">🏪</span>
            <?php _e('Restaurant Information', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <form method="post" class="restaurant-form">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="save_restaurant_info">
            
            <h3><?php _e('Basic Information', 'restaurant-manager'); ?></h3>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="restaurant_name">
                            <span aria-hidden="true">🏪</span>
                            <?php _e('Restaurant Name', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="restaurant_name" id="restaurant_name" 
                               value="<?php echo esc_attr(get_restaurant_setting('restaurant_name', 'China Dragon Restaurant')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('The main name of your restaurant', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="restaurant_tagline">
                            <span aria-hidden="true">✨</span>
                            <?php _e('Tagline', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="restaurant_tagline" id="restaurant_tagline" 
                               value="<?php echo esc_attr(get_restaurant_setting('restaurant_tagline', 'Authentic Chinese Cuisine')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('A brief description or slogan for your restaurant', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="website_url">
                            <span aria-hidden="true">🌐</span>
                            <?php _e('Website URL', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="url" name="website_url" id="website_url" 
                               value="<?php echo esc_attr(get_restaurant_setting('website_url', 'https://www.newredbridgechinadragon.com')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('Your restaurant\'s website URL (include https://)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Address Information', 'restaurant-manager'); ?></h3>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="address_line1">
                            <span aria-hidden="true">📍</span>
                            <?php _e('Address Line 1', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="address_line1" id="address_line1" 
                               value="<?php echo esc_attr(get_restaurant_setting('address_line1', '529 E. Red Bridge Road')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="address_line2">
                            <?php _e('Address Line 2', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="address_line2" id="address_line2" 
                               value="<?php echo esc_attr(get_restaurant_setting('address_line2', '')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('Optional (Suite, Apt, etc.)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="city">
                            <span aria-hidden="true">🏙️</span>
                            <?php _e('City', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="city" id="city" 
                               value="<?php echo esc_attr(get_restaurant_setting('city', 'Kansas City')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="state">
                            <span aria-hidden="true">🗺️</span>
                            <?php _e('State', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="state" id="state" 
                               value="<?php echo esc_attr(get_restaurant_setting('state', 'MO')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="zip_code">
                            <span aria-hidden="true">📮</span>
                            <?php _e('ZIP Code', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="zip_code" id="zip_code" 
                               value="<?php echo esc_attr(get_restaurant_setting('zip_code', '64131')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Contact Information', 'restaurant-manager'); ?></h3>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="phone_primary">
                            <span aria-hidden="true">📞</span>
                            <?php _e('Primary Phone', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="tel" name="phone_primary" id="phone_primary" 
                               value="<?php echo esc_attr(get_restaurant_setting('phone_primary', '816-941-8880')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="phone_secondary">
                            <span aria-hidden="true">📱</span>
                            <?php _e('Secondary Phone', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="tel" name="phone_secondary" id="phone_secondary" 
                               value="<?php echo esc_attr(get_restaurant_setting('phone_secondary', '816-941-0808')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('Optional secondary phone number', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Services & Payment', 'restaurant-manager'); ?></h3>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="delivery_info">
                            <span aria-hidden="true">🚚</span>
                            <?php _e('Delivery Information', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <textarea name="delivery_info" id="delivery_info" rows="3" class="large-text"><?php echo esc_textarea(get_restaurant_setting('delivery_info', 'We partner with Door Dash for food delivery')); ?></textarea>
                        <p class="description"><?php _e('Information about delivery services', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="payment_methods">
                            <span aria-hidden="true">💳</span>
                            <?php _e('Payment Methods', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <textarea name="payment_methods" id="payment_methods" rows="3" class="large-text"><?php echo esc_textarea(get_restaurant_setting('payment_methods', 'VISA, MasterCard, DISCOVER, American Express')); ?></textarea>
                        <p class="description"><?php _e('List of accepted payment methods', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Restaurant Information', 'restaurant-manager'), 'primary'); ?>
        </form>
    </div>
</div>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">🕒</span>
            <?php _e('Opening Hours', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <form method="post" class="restaurant-form">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="save_opening_hours">
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="monday_hours">
                            <?php _e('Monday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="monday_hours" id="monday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('monday_hours', '11:00 am to 10:00 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:00 am to 10:00 pm">
                        <p class="description"><?php _e('Enter "Closed" if closed on this day', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tuesday_hours">
                            <?php _e('Tuesday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="tuesday_hours" id="tuesday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('tuesday_hours', 'Closed')); ?>" 
                               class="regular-text"
                               placeholder="Closed">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="wednesday_hours">
                            <?php _e('Wednesday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="wednesday_hours" id="wednesday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('wednesday_hours', '11:00 am to 10:00 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:00 am to 10:00 pm">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="thursday_hours">
                            <?php _e('Thursday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="thursday_hours" id="thursday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('thursday_hours', '11:00 am to 10:00 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:00 am to 10:00 pm">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="friday_hours">
                            <?php _e('Friday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="friday_hours" id="friday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('friday_hours', '11:00 am to 10:30 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:00 am to 10:30 pm">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="saturday_hours">
                            <?php _e('Saturday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="saturday_hours" id="saturday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('saturday_hours', '11:00 am to 10:30 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:00 am to 10:30 pm">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="sunday_hours">
                            <?php _e('Sunday', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="sunday_hours" id="sunday_hours" 
                               value="<?php echo esc_attr(get_restaurant_setting('sunday_hours', '11:30 am to 9:30 pm')); ?>" 
                               class="regular-text"
                               placeholder="11:30 am to 9:30 pm">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="special_hours_note">
                            <span aria-hidden="true">📝</span>
                            <?php _e('Special Hours Note', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <textarea name="special_hours_note" id="special_hours_note" rows="3" class="large-text"
                                  placeholder="<?php esc_attr_e('Any special notes about hours (holidays, etc.)', 'restaurant-manager'); ?>"><?php echo esc_textarea(get_restaurant_setting('special_hours_note', '')); ?></textarea>
                        <p class="description"><?php _e('Optional notes about special hours, holidays, etc.', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Opening Hours', 'restaurant-manager'), 'primary'); ?>
        </form>
    </div>
</div>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">👀</span>
            <?php _e('Preview', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <p><?php _e('This is how your restaurant information will appear on the frontend:', 'restaurant-manager'); ?></p>
        
        <div class="restaurant-info-preview">
            <div class="preview-header">
                <h3><?php echo esc_html(get_restaurant_setting('restaurant_name', 'China Dragon Restaurant')); ?></h3>
                <p><?php echo esc_html(get_restaurant_setting('restaurant_tagline', 'Authentic Chinese Cuisine')); ?> • Dine in & Take Out</p>
            </div>
            
            <div class="preview-hours">
                <h4><?php _e('Open Hours', 'restaurant-manager'); ?></h4>
                <div class="hours-grid">
                    <?php 
                    $days = array(
                        'monday' => __('Monday', 'restaurant-manager'),
                        'tuesday' => __('Tuesday', 'restaurant-manager'),
                        'wednesday' => __('Wednesday', 'restaurant-manager'),
                        'thursday' => __('Thursday', 'restaurant-manager'),
                        'friday' => __('Friday', 'restaurant-manager'),
                        'saturday' => __('Saturday', 'restaurant-manager'),
                        'sunday' => __('Sunday', 'restaurant-manager')
                    );
                    
                    foreach ($days as $day => $label):
                        $hours = get_restaurant_setting($day . '_hours', '');
                        if ($hours):
                    ?>
                        <div class="hours-item">
                            <span class="day"><?php echo $label; ?>:</span>
                            <span class="time <?php echo strtolower($hours) === 'closed' ? 'closed' : ''; ?>">
                                <?php echo esc_html($hours); ?>
                            </span>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <?php if (get_restaurant_setting('special_hours_note')): ?>
                    <div class="special-note">
                        <strong><?php echo esc_html(get_restaurant_setting('special_hours_note')); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="preview-contact">
                <div class="contact-section">
                    <h4><?php _e('Contact Information', 'restaurant-manager'); ?></h4>
                    <p><strong><?php _e('Website:', 'restaurant-manager'); ?></strong> 
                       <a href="<?php echo esc_url(get_restaurant_setting('website_url')); ?>" target="_blank">
                           <?php echo esc_html(get_restaurant_setting('website_url')); ?>
                       </a>
                    </p>
                    <p><strong><?php _e('Address:', 'restaurant-manager'); ?></strong><br>
                       <?php echo esc_html(get_restaurant_setting('address_line1')); ?><br>
                       <?php if (get_restaurant_setting('address_line2')): ?>
                           <?php echo esc_html(get_restaurant_setting('address_line2')); ?><br>
                       <?php endif; ?>
                       <?php echo esc_html(get_restaurant_setting('city')); ?>, 
                       <?php echo esc_html(get_restaurant_setting('state')); ?> 
                       <?php echo esc_html(get_restaurant_setting('zip_code')); ?>
                    </p>
                    <p><strong><?php _e('Phone:', 'restaurant-manager'); ?></strong> 
                       <?php echo esc_html(get_restaurant_setting('phone_primary')); ?>
                       <?php if (get_restaurant_setting('phone_secondary')): ?>
                           / <?php echo esc_html(get_restaurant_setting('phone_secondary')); ?>
                       <?php endif; ?>
                    </p>
                </div>
                
                <div class="services-section">
                    <h4><?php _e('Services & Payment', 'restaurant-manager'); ?></h4>
                    <?php if (get_restaurant_setting('delivery_info')): ?>
                        <p><strong><?php _e('Delivery:', 'restaurant-manager'); ?></strong> 
                           <?php echo esc_html(get_restaurant_setting('delivery_info')); ?>
                        </p>
                    <?php endif; ?>
                    <?php if (get_restaurant_setting('payment_methods')): ?>
                        <p><strong><?php _e('We Accept:', 'restaurant-manager'); ?></strong> 
                           <?php echo esc_html(get_restaurant_setting('payment_methods')); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.restaurant-info-preview {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-top: 15px;
}

.preview-header h3 {
    margin: 0 0 5px 0;
    font-size: 20px;
    color: #2c2c2c;
}

.preview-header p {
    margin: 0 0 20px 0;
    color: #666;
    font-style: italic;
}

.preview-hours h4,
.preview-contact h4 {
    margin: 20px 0 10px 0;
    color: #c41e3a;
    font-size: 16px;
}

.hours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.hours-item {
    display: flex;
    justify-content: space-between;
    background: white;
    padding: 8px 12px;
    border-radius: 4px;
    border-left: 3px solid #c41e3a;
}

.hours-item .day {
    font-weight: bold;
}

.hours-item .time.closed {
    color: #c41e3a;
    font-weight: bold;
}

.special-note {
    background: #c41e3a;
    color: white;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
}

.preview-contact {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 15px;
}

.contact-section,
.services-section {
    background: rgba(248, 248, 248, 0.5);
    padding: 15px;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .preview-contact {
        grid-template-columns: 1fr;
    }
    
    .hours-grid {
        grid-template-columns: 1fr;
    }
}
</style> 