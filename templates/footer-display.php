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

// Get footer settings
$show_logo = get_restaurant_setting('footer_show_logo', '1');
$show_contact = get_restaurant_setting('footer_show_contact', '1');
$show_hours = get_restaurant_setting('footer_show_hours', '1');
$show_menu = get_restaurant_setting('footer_show_menu', '1');
$show_promotions = get_restaurant_setting('footer_show_promotions', '1');
$show_social = get_restaurant_setting('footer_show_social', '1');

// Get social media URLs
$facebook_url = get_restaurant_setting('footer_facebook_url', '');
$instagram_url = get_restaurant_setting('footer_instagram_url', '');
$twitter_url = get_restaurant_setting('footer_twitter_url', '');
$youtube_url = get_restaurant_setting('footer_youtube_url', '');
$yelp_url = get_restaurant_setting('footer_yelp_url', '');

// Get promotions
$promotion_1_title = get_restaurant_setting('footer_promotion_1_title', '');
$promotion_1_desc = get_restaurant_setting('footer_promotion_1_desc', '');
$promotion_1_link = get_restaurant_setting('footer_promotion_1_link', '');

$promotion_2_title = get_restaurant_setting('footer_promotion_2_title', '');
$promotion_2_desc = get_restaurant_setting('footer_promotion_2_desc', '');
$promotion_2_link = get_restaurant_setting('footer_promotion_2_link', '');

$promotion_3_title = get_restaurant_setting('footer_promotion_3_title', '');
$promotion_3_desc = get_restaurant_setting('footer_promotion_3_desc', '');
$promotion_3_link = get_restaurant_setting('footer_promotion_3_link', '');

// Get copyright info
$copyright_text = get_restaurant_setting('footer_copyright_text', '© 2024 China Dragon All Rights Reserved');
$designed_by_text = get_restaurant_setting('footer_designed_by_text', '');
$designed_by_link = get_restaurant_setting('footer_designed_by_link', '');

// Get custom logo or use site logo
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = '';
if ($custom_logo_id) {
    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'medium');
}
?>

<footer class="restaurant-footer" role="contentinfo" aria-label="<?php _e('Restaurant Footer', 'restaurant-manager'); ?>">
    <div class="restaurant-footer-main">
        <div class="footer-container">
            
            <!-- Logo and Contact Section -->
            <?php if ($show_logo || $show_contact): ?>
            <div class="footer-logo-section">
                <?php if ($show_logo && $logo_url): ?>
                    <div class="footer-logo">
                        <img src="<?php echo esc_url($logo_url); ?>" 
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?>" 
                             class="footer-logo-image">
                    </div>
                <?php endif; ?>
                
                <?php if ($show_contact): ?>
                    <div class="footer-contact-info">
                        <!-- Phone Numbers -->
                        <div class="footer-contact-item">
                            <span class="contact-label"><?php _e('Tel', 'restaurant-manager'); ?>:</span>
                            <div class="phone-numbers">
                                <?php 
                                $phone_primary = get_restaurant_setting('phone_primary', '816-941-8880');
                                $phone_secondary = get_restaurant_setting('phone_secondary', '816-941-0808');
                                ?>
                                <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_primary)); ?>" 
                                   class="phone-link">
                                    <?php echo esc_html($phone_primary); ?>
                                </a>
                                <?php if ($phone_secondary): ?>
                                    <span class="phone-separator"> | </span>
                                    <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_secondary)); ?>" 
                                       class="phone-link">
                                        <?php echo esc_html($phone_secondary); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <div class="footer-contact-item">
                            <span class="contact-label"><?php _e('Address', 'restaurant-manager'); ?>:</span>
                            <address class="footer-address">
                                <?php echo esc_html(get_restaurant_setting('address_line1', '529. E. Red Bridge Road')); ?>,<br>
                                <?php echo esc_html(get_restaurant_setting('city', 'Kansas City')); ?>, 
                                <?php echo esc_html(get_restaurant_setting('state', 'MO')); ?> 
                                <?php echo esc_html(get_restaurant_setting('zip_code', '64131')); ?>
                            </address>
                        </div>
                        
                        <!-- Social Media Icons -->
                        <?php if ($show_social && ($facebook_url || $instagram_url || $twitter_url || $youtube_url || $yelp_url)): ?>
                        <div class="footer-social-icons">
                            <?php if ($facebook_url): ?>
                                <a href="<?php echo esc_url($facebook_url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="social-icon facebook-icon"
                                   aria-label="<?php _e('Visit our Facebook page', 'restaurant-manager'); ?>">
                                    <span class="social-icon-bg">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($instagram_url): ?>
                                <a href="<?php echo esc_url($instagram_url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="social-icon instagram-icon"
                                   aria-label="<?php _e('Visit our Instagram page', 'restaurant-manager'); ?>">
                                    <span class="social-icon-bg">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($twitter_url): ?>
                                <a href="<?php echo esc_url($twitter_url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="social-icon twitter-icon"
                                   aria-label="<?php _e('Visit our Twitter page', 'restaurant-manager'); ?>">
                                    <span class="social-icon-bg">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($youtube_url): ?>
                                <a href="<?php echo esc_url($youtube_url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="social-icon youtube-icon"
                                   aria-label="<?php _e('Visit our YouTube channel', 'restaurant-manager'); ?>">
                                    <span class="social-icon-bg">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($yelp_url): ?>
                                <a href="<?php echo esc_url($yelp_url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="social-icon yelp-icon"
                                   aria-label="<?php _e('Visit our Yelp page', 'restaurant-manager'); ?>">
                                    <span class="social-icon-bg">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.111 18.226c-.141.969-2.119 1.424-3.029.699l-7.988-6.373c-.395-.315-.395-.835 0-1.15l7.988-6.373c.91-.725 2.888-.27 3.029.699.07.485-.012 2.758-.012 4.424v4.65c0 1.666.082 3.939.012 4.424zm-18.24-10.574c-.69-.917-.27-2.858.639-3.057.455-.099 2.658-.062 4.303-.062h4.631c1.645 0 3.848-.037 4.303.062.909.199 1.329 2.14.639 3.057-.345.458-2.332 1.979-3.789 3.328l-4.937 4.572c-1.457 1.349-3.444 2.87-3.789 3.328z"/>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Footer Columns -->
            <div class="footer-columns">
                
                <!-- Main Menu Column -->
                <?php if ($show_menu): ?>
                <div class="footer-column">
                    <h3 class="footer-column-title"><?php _e('MAIN MENU', 'restaurant-manager'); ?></h3>
                    <ul class="footer-menu-list">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'restaurant-manager'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/find-us')); ?>"><?php _e('Find Us', 'restaurant-manager'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/menu')); ?>"><?php _e('Menu', 'restaurant-manager'); ?></a></li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Business Hours Column -->
                <?php if ($show_hours): ?>
                <div class="footer-column">
                    <h3 class="footer-column-title"><?php _e('BUSINESS HOURS', 'restaurant-manager'); ?></h3>
                    <div class="footer-hours-list">
                        <?php
                        // Build dynamic hours display
                        $days = array(
                            'monday' => 'Mon.',
                            'tuesday' => 'Tue.',
                            'wednesday' => 'Wed.',
                            'thursday' => 'Thu.',
                            'friday' => 'Fri.',
                            'saturday' => 'Sat.',
                            'sunday' => 'Sun.'
                        );
                        
                        foreach ($days as $key => $day_abbrev) {
                            $hours = get_restaurant_setting($key . '_hours', '');
                            if ($hours && strtolower($hours) !== 'closed') {
                                // Format hours display
                                if ($key === 'wednesday' || $key === 'thursday') {
                                    $day_display = $day_abbrev . ' - Thu.';
                                    if ($key === 'thursday') continue; // Skip Thursday since it's combined with Wednesday
                                } elseif ($key === 'friday' || $key === 'saturday') {
                                    $day_display = 'Fri. & Sat.';
                                    if ($key === 'saturday') continue; // Skip Saturday since it's combined with Friday
                                } else {
                                    $day_display = $day_abbrev;
                                }
                                ?>
                                <div class="footer-hours-item">
                                    <span class="hours-day"><?php echo esc_html($day_display); ?></span>
                                    <span class="hours-time"><?php echo esc_html($hours); ?></span>
                                </div>
                                <?php
                            } elseif (strtolower($hours) === 'closed' || empty($hours)) {
                                if ($key === 'tuesday') { // Specifically handle Tuesday CLOSED
                                    ?>
                                    <div class="footer-hours-item closed">
                                        <span class="hours-day"><?php echo esc_html($day_abbrev); ?></span>
                                        <span class="hours-time"><?php _e('CLOSED', 'restaurant-manager'); ?></span>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Promotions Column -->
                <?php if ($show_promotions): ?>
                <div class="footer-column">
                    <h3 class="footer-column-title"><?php _e('PROMOTIONS', 'restaurant-manager'); ?></h3>
                    <div class="footer-promotions-list">
                        <?php if ($promotion_1_title || $promotion_2_title || $promotion_3_title): ?>
                            <?php if ($promotion_1_title): ?>
                                <div class="footer-promotion-item">
                                    <?php if ($promotion_1_link): ?>
                                        <a href="<?php echo esc_url($promotion_1_link); ?>" class="promotion-link">
                                            <h4 class="promotion-title"><?php echo esc_html($promotion_1_title); ?></h4>
                                            <?php if ($promotion_1_desc): ?>
                                                <p class="promotion-desc"><?php echo esc_html($promotion_1_desc); ?></p>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <h4 class="promotion-title"><?php echo esc_html($promotion_1_title); ?></h4>
                                        <?php if ($promotion_1_desc): ?>
                                            <p class="promotion-desc"><?php echo esc_html($promotion_1_desc); ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($promotion_2_title): ?>
                                <div class="footer-promotion-item">
                                    <?php if ($promotion_2_link): ?>
                                        <a href="<?php echo esc_url($promotion_2_link); ?>" class="promotion-link">
                                            <h4 class="promotion-title"><?php echo esc_html($promotion_2_title); ?></h4>
                                            <?php if ($promotion_2_desc): ?>
                                                <p class="promotion-desc"><?php echo esc_html($promotion_2_desc); ?></p>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <h4 class="promotion-title"><?php echo esc_html($promotion_2_title); ?></h4>
                                        <?php if ($promotion_2_desc): ?>
                                            <p class="promotion-desc"><?php echo esc_html($promotion_2_desc); ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($promotion_3_title): ?>
                                <div class="footer-promotion-item">
                                    <?php if ($promotion_3_link): ?>
                                        <a href="<?php echo esc_url($promotion_3_link); ?>" class="promotion-link">
                                            <h4 class="promotion-title"><?php echo esc_html($promotion_3_title); ?></h4>
                                            <?php if ($promotion_3_desc): ?>
                                                <p class="promotion-desc"><?php echo esc_html($promotion_3_desc); ?></p>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <h4 class="promotion-title"><?php echo esc_html($promotion_3_title); ?></h4>
                                        <?php if ($promotion_3_desc): ?>
                                            <p class="promotion-desc"><?php echo esc_html($promotion_3_desc); ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="footer-promotion-item no-promotions">
                                <p class="promotion-desc"><?php _e('No promotions available at this time.', 'restaurant-manager'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom/Copyright -->
    <div class="restaurant-footer-bottom">
        <div class="footer-container">
            <div class="footer-copyright">
                <span class="copyright-text"><?php echo esc_html($copyright_text); ?></span>
                <?php if ($designed_by_text && trim($designed_by_text) !== ''): ?>
                    <span class="designed-by">
                        <?php _e('Designed by', 'restaurant-manager'); ?>
                        <?php if ($designed_by_link && trim($designed_by_link) !== '' && $designed_by_link !== '#'): ?>
                            <a href="<?php echo esc_url($designed_by_link); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html($designed_by_text); ?>
                            </a>
                        <?php else: ?>
                            <?php echo esc_html($designed_by_text); ?>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer> 