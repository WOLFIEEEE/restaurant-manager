<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$categories_table = $wpdb->prefix . 'restaurant_categories';
$items_table = $wpdb->prefix . 'restaurant_menu_items';
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

// Get all categories and their items
$categories = $wpdb->get_results("
    SELECT c.*, COUNT(i.id) as item_count
    FROM $categories_table c
    LEFT JOIN $items_table i ON c.id = i.category_id AND i.is_active = 1
    GROUP BY c.id
    HAVING item_count > 0
    ORDER BY c.sort_order
");

$menu_data = array();
foreach ($categories as $category) {
    $items = $wpdb->get_results($wpdb->prepare("
        SELECT * FROM $items_table 
        WHERE category_id = %d AND is_active = 1 
        ORDER BY sort_order, name
    ", $category->id));
    
    if (!empty($items)) {
        $menu_data[$category->slug] = array(
            'title' => $category->name,
            'note' => $category->note,
            'emoji' => $category->emoji,
            'items' => $items
        );
    }
}
?>

<div class="restaurant-menu-container">
    <div class="restaurant-menu-header">
        <h1><?php echo esc_html(get_restaurant_setting('restaurant_name', 'China Dragon Restaurant')); ?></h1>
        <p><?php echo esc_html(get_restaurant_setting('restaurant_tagline', 'Authentic Chinese Cuisine')); ?> • Dine in &amp; Take Out</p>
    </div>

    <!-- Restaurant Information Section -->
    <div class="restaurant-info-section">
        <!-- Hours Section - Full Width Below Header -->
        <section class="hours-info-main" aria-labelledby="hours-heading">
            <h2 id="hours-heading">Open Hours</h2>
            <div class="hours-list-main">
                <?php
                // Build dynamic hours display
                $days = array(
                    'monday' => 'Monday',
                    'tuesday' => 'Tuesday', 
                    'wednesday' => 'Wednesday',
                    'thursday' => 'Thursday',
                    'friday' => 'Friday',
                    'saturday' => 'Saturday',
                    'sunday' => 'Sunday'
                );
                
                $hours_data = array();
                $closed_days = array();
                
                foreach ($days as $key => $day) {
                    $hours = get_restaurant_setting($key . '_hours', '');
                    if ($hours && strtolower($hours) !== 'closed') {
                        $hours_data[$key] = array('day' => $day, 'hours' => $hours);
                    } else {
                        $closed_days[] = $day;
                    }
                }
                
                // Group consecutive days with same hours
                $grouped_hours = array();
                $current_group = array();
                $current_hours = '';
                
                foreach ($hours_data as $key => $data) {
                    if ($data['hours'] === $current_hours && !empty($current_group)) {
                        $current_group[] = $data['day'];
                    } else {
                        if (!empty($current_group)) {
                            $grouped_hours[] = array(
                                'days' => $current_group,
                                'hours' => $current_hours
                            );
                        }
                        $current_group = array($data['day']);
                        $current_hours = $data['hours'];
                    }
                }
                
                // Add the last group
                if (!empty($current_group)) {
                    $grouped_hours[] = array(
                        'days' => $current_group,
                        'hours' => $current_hours
                    );
                }
                
                // Display grouped hours
                foreach ($grouped_hours as $group) {
                    $day_display = '';
                    if (count($group['days']) == 1) {
                        $day_display = $group['days'][0];
                    } elseif (count($group['days']) == 2) {
                        $day_display = implode(' &amp; ', $group['days']);
                    } else {
                        $day_display = $group['days'][0] . ' - ' . end($group['days']);
                    }
                    ?>
                    <div class="hours-item">
                        <span class="day-range"><?php echo esc_html($day_display); ?>:</span>
                        <span class="time-range"><?php echo esc_html($group['hours']); ?></span>
                    </div>
                    <?php
                }
                
                // Display closed days notice
                if (!empty($closed_days)) {
                    ?>
                    <div class="closed-notice">
                        <strong>Closed on <?php echo esc_html(implode(', ', $closed_days)); ?></strong>
                    </div>
                    <?php
                }
                
                // Display special hours note if exists
                $special_note = get_restaurant_setting('special_hours_note', '');
                if ($special_note) {
                    ?>
                    <div class="special-hours-note">
                        <strong><?php echo esc_html($special_note); ?></strong>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        
        <!-- Contact and Service Info - Two Column Layout -->
        <div class="restaurant-details">
            <section class="contact-info" aria-labelledby="contact-heading">
                <h3 id="contact-heading">Contact Information</h3>
                
                <?php $website_url = get_restaurant_setting('website_url', ''); ?>
                <?php if ($website_url): ?>
                <div class="info-item">
                    <span class="info-label">Website:</span>
                    <a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener" aria-label="Visit our website (opens in new tab)">
                        <?php echo esc_html(str_replace(array('https://', 'http://'), '', $website_url)); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <address class="restaurant-address">
                        <?php echo esc_html(get_restaurant_setting('address_line1', '529 E. Red Bridge Road')); ?><br>
                        <?php $address_line2 = get_restaurant_setting('address_line2', ''); ?>
                        <?php if ($address_line2): ?>
                            <?php echo esc_html($address_line2); ?><br>
                        <?php endif; ?>
                        <?php echo esc_html(get_restaurant_setting('city', 'Kansas City')); ?>, 
                        <?php echo esc_html(get_restaurant_setting('state', 'MO')); ?> 
                        <?php echo esc_html(get_restaurant_setting('zip_code', '64131')); ?>
                    </address>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <div class="phone-numbers">
                        <?php 
                        $phone_primary = get_restaurant_setting('phone_primary', '816-941-8880');
                        $phone_secondary = get_restaurant_setting('phone_secondary', '816-941-0808');
                        ?>
                        <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_primary)); ?>" aria-label="Call first phone number">
                            <?php echo esc_html($phone_primary); ?>
                        </a>
                        <?php if ($phone_secondary): ?>
                            <span class="phone-separator">/</span>
                            <a href="tel:+1<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone_secondary)); ?>" aria-label="Call second phone number">
                                <?php echo esc_html($phone_secondary); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <section class="service-info" aria-labelledby="service-heading">
                <h3 id="service-heading">Services &amp; Payment</h3>
                
                <?php $delivery_info = get_restaurant_setting('delivery_info', 'We partner with Door Dash for food delivery'); ?>
                <?php if ($delivery_info): ?>
                <div class="info-item">
                    <span class="info-label">Delivery:</span>
                    <span><?php echo esc_html($delivery_info); ?></span>
                </div>
                <?php endif; ?>
                
                <?php $payment_methods = get_restaurant_setting('payment_methods', 'VISA, MasterCard, DISCOVER, American Express'); ?>
                <?php if ($payment_methods): ?>
                <div class="info-item">
                    <span class="info-label">Payment Methods:</span>
                    <div class="payment-methods">
                        <span class="payment-text">We Accept:</span>
                        <span class="payment-cards"><?php echo esc_html($payment_methods); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </section>
        </div>
    </div>

    <?php if ($atts['show_search'] === 'true' || $atts['show_filters'] === 'true'): ?>
    <div class="restaurant-menu-controls" role="search" aria-label="Menu search and filters">
        <?php if ($atts['show_search'] === 'true'): ?>
        <div class="search-group">
            <label for="restaurant-search-input">Search Menu Items:</label>
            <input 
                type="text" 
                id="restaurant-search-input" 
                class="search-input" 
                placeholder="Search for dishes..."
                aria-describedby="searchHelp"
            >
            <div id="searchHelp" class="sr-only">
                Type to search through all menu items by name or description
            </div>
        </div>
        <?php endif; ?>

        <?php if ($atts['show_filters'] === 'true'): ?>
        <div class="filter-group">
            <label for="restaurant-category-filter">Filter by Category:</label>
            <select id="restaurant-category-filter" class="filter-select" aria-describedby="categoryHelp">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo esc_attr($category->slug); ?>">
                        <?php echo esc_html($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="categoryHelp" class="sr-only">
                Select a category to filter menu items
            </div>
        </div>

        <div class="filter-group">
            <label for="restaurant-spicy-filter">Spice Level:</label>
            <select id="restaurant-spicy-filter" class="filter-select" aria-describedby="spicyHelp">
                <option value="">All Items</option>
                <option value="spicy">Spicy Only</option>
                <option value="not-spicy">Non-Spicy Only</option>
            </select>
            <div id="spicyHelp" class="sr-only">
                Filter items by spice level
            </div>
        </div>

        <button type="button" class="clear-btn" id="restaurant-clear-filters" aria-describedby="clearHelp">
            Clear All Filters
        </button>
        <div id="clearHelp" class="sr-only">
            Reset all search and filter options
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="restaurant-results-info" role="status" aria-live="polite" id="restaurant-results-info">
        Showing all menu items
    </div>

    <div id="restaurant-menu-container" role="region" aria-label="Menu items">
        <?php foreach ($menu_data as $category_slug => $category_data): ?>
            <section class="restaurant-menu-section" data-category="<?php echo esc_attr($category_slug); ?>">
                <div class="section-header" data-category="<?php echo esc_attr($category_slug); ?>">
                    <h2><?php echo esc_html($category_data['title']); ?></h2>
                    <?php if ($category_data['note']): ?>
                        <p class="section-note"><?php echo esc_html($category_data['note']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="menu-columns">
                    <ul class="menu-list" role="list" aria-label="<?php echo esc_attr($category_data['title']); ?> menu items">
                        <?php foreach ($category_data['items'] as $item): ?>
                            <li class="menu-list__item" 
                                role="listitem" 
                                aria-label="<?php echo esc_attr($item->name . ', ' . $item->price . ($item->is_spicy ? ', Spicy' : '') . ($item->description ? ', ' . $item->description : '')); ?>"
                                data-spicy="<?php echo $item->is_spicy ? 'true' : 'false'; ?>">
                                
                                <div class="menu-item-number">
                                    <?php echo esc_html($item->item_number); ?>
                                </div>
                                
                                <div class="menu-item-content">
                                    <span class="dots" aria-hidden="true"></span>
                                    <div class="menu-list__item-title">
                                        <span class="item_title"><?php echo esc_html($item->name); ?></span>
                                        <?php if ($item->is_spicy): ?>
                                            <span class="spicy-indicator" aria-label="Spicy dish">Spicy</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($item->description): ?>
                                        <div class="menu-list__item-desc">
                                            <div class="desc__content"><?php echo esc_html($item->description); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="menu-list__item-price">
                                    <p><?php echo esc_html($item->price); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</div>

<!-- Live region for screen reader announcements -->
<div id="restaurant-live-region" class="live-region" role="status" aria-live="polite" aria-atomic="true"></div>

<script type="text/javascript">
// Make menu data globally available for the external JavaScript file
window.restaurantMenuData = <?php echo json_encode($menu_data); ?>;
</script> 