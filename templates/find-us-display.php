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

// Get location-specific settings
$location_title = get_restaurant_setting('location_title', 'Find Us');
$address_line1 = get_restaurant_setting('address_line1', '529 E. Red Bridge Road');
$address_line2 = get_restaurant_setting('address_line2', '');
$city = get_restaurant_setting('city', 'Kansas City');
$state = get_restaurant_setting('state', 'MO');
$zip_code = get_restaurant_setting('zip_code', '64131');
$phone_primary = get_restaurant_setting('phone_primary', '816-941-8880');
$phone_secondary = get_restaurant_setting('phone_secondary', '816-941-0808');
$map_embed_url = get_restaurant_setting('map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3103.94432679123!2d-94.58792192406187!3d38.92524997171817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87c059e846dc7795%3A0xbc304065c56b18fa!2sChina%20Dragon!5e0!3m2!1sen!2sus!4v1699309137314!5m2!1sen!2sus');
$map_latitude = get_restaurant_setting('map_latitude', '38.925250');
$map_longitude = get_restaurant_setting('map_longitude', '-94.585733'); 
$directions_text = get_restaurant_setting('directions_text', 'The restaurant is easily accessible by car with parking available.');
$restaurant_name = get_restaurant_setting('restaurant_name', 'China Dragon Restaurant');
?>

<div class="rm-findus-container" itemtype="https://schema.org/Restaurant" itemscope>
    
    <!-- Page Header -->
    <header class="rm-findus-header">
        <h1 class="rm-findus-title" itemprop="name"><?php echo esc_html($location_title); ?></h1>
    </header>

    <!-- Main Content Layout: 30% Left, 70% Right -->
    <div class="rm-findus-layout">
        
        <!-- Left Column: Contact Information (30%) -->
        <div class="rm-findus-contact-section">
            <div class="rm-findus-contact-content" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                
                <!-- Address Block -->
                <section class="rm-findus-info-block" aria-labelledby="rm-findus-address-heading">
                    <h2 id="rm-findus-address-heading" class="rm-findus-section-heading">
                        <i class="fas fa-map-marker-alt"></i> <?php _e('Visit Us', 'restaurant-manager'); ?>
                    </h2>
                    
                    <div class="rm-findus-address-info">
                        <p class="rm-findus-address-line" itemprop="streetAddress">
                            <?php echo esc_html($address_line1); ?>
                            <?php if ($address_line2): ?>
                                <br><?php echo esc_html($address_line2); ?>
                            <?php endif; ?>
                        </p>
                        
                        <p class="rm-findus-address-city">
                            <span itemprop="addressLocality"><?php echo esc_html($city); ?></span>, 
                            <span itemprop="addressRegion"><?php echo esc_html($state); ?></span> 
                            <span itemprop="postalCode"><?php echo esc_html($zip_code); ?></span>
                        </p>
                    </div>
                </section>

                <!-- Phone Block -->
                <section class="rm-findus-info-block" aria-labelledby="rm-findus-phone-heading">
                    <h2 id="rm-findus-phone-heading" class="rm-findus-section-heading">
                        <i class="fas fa-phone"></i> <?php _e('Call Us', 'restaurant-manager'); ?>
                    </h2>
                    
                    <div class="rm-findus-phone-info">
                        <?php if ($phone_primary): ?>
                        <div class="rm-findus-phone-entry">
                            <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_primary)); ?>" 
                               class="rm-findus-phone-number"
                               itemprop="telephone"
                               aria-label="<?php printf(esc_attr__('Call primary phone number %s', 'restaurant-manager'), $phone_primary); ?>">
                                <span class="rm-findus-phone-type"><?php _e('Main:', 'restaurant-manager'); ?></span>
                                <span class="rm-findus-phone-digits"><?php echo esc_html($phone_primary); ?></span>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($phone_secondary): ?>
                        <div class="rm-findus-phone-entry">
                            <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_secondary)); ?>" 
                               class="rm-findus-phone-number"
                               itemprop="telephone"
                               aria-label="<?php printf(esc_attr__('Call secondary phone number %s', 'restaurant-manager'), $phone_secondary); ?>">
                                <span class="rm-findus-phone-type"><?php _e('Alt:', 'restaurant-manager'); ?></span>
                                <span class="rm-findus-phone-digits"><?php echo esc_html($phone_secondary); ?></span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Directions Block -->
                <?php if ($directions_text): ?>
                <section class="rm-findus-info-block">
                    <h2 class="rm-findus-section-heading">
                        <i class="fas fa-route"></i> <?php _e('Getting Here', 'restaurant-manager'); ?>
                    </h2>
                    <p class="rm-findus-directions-description"><?php echo esc_html($directions_text); ?></p>
                </section>
                <?php endif; ?>

            </div>
        </div>

        <!-- Right Column: Map & Text Description (70%) -->
        <div class="rm-findus-map-section">
            
            <!-- Map Area -->
            <section class="rm-findus-map-area" aria-labelledby="rm-findus-map-heading">
                <h2 id="rm-findus-map-heading" class="rm-findus-section-heading">
                    <i class="fas fa-map"></i> <?php _e('Location Map', 'restaurant-manager'); ?>
                </h2>
                
                <div class="rm-findus-map-frame">
                    <a href="#rm-findus-map-text" class="rm-findus-skip-link">
                        <?php _e('Skip to map description', 'restaurant-manager'); ?>
                    </a>
                    
                    <!-- Interactive Map -->
                    <?php if ($map_embed_url): ?>
                    <div class="rm-findus-iframe-container">
                        <iframe 
                            src="<?php echo esc_url($map_embed_url); ?>" 
                            class="rm-findus-map-embed" 
                            title="<?php printf(esc_attr__('Interactive Google Map showing %s location', 'restaurant-manager'), esc_attr($restaurant_name)); ?>"
                            aria-label="<?php printf(esc_attr__('Map showing %s restaurant location in %s', 'restaurant-manager'), esc_attr($restaurant_name), esc_attr($city)); ?>"
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Text Description & Directions -->
            <section id="rm-findus-map-text" class="rm-findus-description-area">
                <h3 class="rm-findus-description-title">
                    <?php printf(__('About Our %s Location', 'restaurant-manager'), esc_html($city)); ?>
                </h3>
                
                <div class="rm-findus-location-details">
                    <div class="rm-findus-address-details">
                        <h4 class="rm-findus-detail-label"><?php _e('Complete Address:', 'restaurant-manager'); ?></h4>
                        <p class="rm-findus-detail-text">
                            <?php echo esc_html($restaurant_name); ?><br>
                            <?php echo esc_html($address_line1); ?><?php if ($address_line2): ?>, <?php echo esc_html($address_line2); ?><?php endif; ?><br>
                            <?php echo esc_html($city); ?>, <?php echo esc_html($state); ?> <?php echo esc_html($zip_code); ?>
                        </p>
                    </div>
                    
                    <?php if ($map_latitude && $map_longitude): ?>
                    <div class="rm-findus-coordinates-details">
                        <h4 class="rm-findus-detail-label"><?php _e('GPS Coordinates:', 'restaurant-manager'); ?></h4>
                        <p class="rm-findus-detail-text">
                            <?php echo esc_html($map_latitude); ?>, <?php echo esc_html($map_longitude); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php 
                $maps_url = 'https://maps.google.com/maps?q=' . urlencode($restaurant_name . ', ' . $address_line1 . ', ' . $city . ', ' . $state . ' ' . $zip_code);
                if ($map_latitude && $map_longitude) {
                    $maps_url = 'https://maps.google.com/maps?q=' . urlencode($restaurant_name) . ',' . $map_latitude . ',' . $map_longitude;
                }
                ?>
                <div class="rm-findus-directions-action">
                    <a href="<?php echo esc_url($maps_url); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="rm-findus-directions-button" 
                       aria-label="<?php printf(esc_attr__('Open %s location in Google Maps (opens in new tab)', 'restaurant-manager'), esc_attr($restaurant_name)); ?>">
                        <i class="fas fa-directions"></i> <span><?php _e('Get Directions', 'restaurant-manager'); ?></span>
                    </a>
                </div>
            </section>

        </div>

    </div>
</div> 