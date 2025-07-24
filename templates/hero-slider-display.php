<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$slider_table = $wpdb->prefix . 'restaurant_hero_slider';

// Get slider settings
$autoplay = get_option('restaurant_slider_autoplay', 1);
$autoplay_delay = get_option('restaurant_slider_autoplay_delay', 5000);
$show_controls = get_option('restaurant_slider_show_controls', 1);
$show_indicators = get_option('restaurant_slider_show_indicators', 1);

// Override with shortcode attributes if provided
if (isset($atts['autoplay'])) {
    $autoplay = $atts['autoplay'] === 'true' ? 1 : 0;
}
if (isset($atts['autoplay_delay'])) {
    $autoplay_delay = intval($atts['autoplay_delay']);
}
if (isset($atts['show_controls'])) {
    $show_controls = $atts['show_controls'] === 'true' ? 1 : 0;
}

// Get active slider images
$slider_images = $wpdb->get_results("SELECT * FROM $slider_table WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");

// Return early if no images
if (empty($slider_images)) {
    if (current_user_can('manage_options')) {
        echo '<div class="hero-slider-notice">';
        echo '<p>' . __('Hero Slider: No active images found.', 'restaurant-manager') . ' ';
        echo '<a href="' . admin_url('admin.php?page=restaurant-manager&tab=hero-slider') . '">' . __('Add some images', 'restaurant-manager') . '</a></p>';
        echo '</div>';
    }
    return;
}

$slider_id = 'hero-slider-' . uniqid();
$total_slides = count($slider_images);
?>

<section class="hero-slider-container" 
         role="region" 
         aria-label="<?php _e('Restaurant Hero Image Carousel', 'restaurant-manager'); ?>"
         data-autoplay="<?php echo $autoplay; ?>"
         data-delay="<?php echo $autoplay_delay; ?>"
         data-total="<?php echo $total_slides; ?>"
         data-loading="true">
    
    
    <div class="hero-slider-wrapper" id="<?php echo $slider_id; ?>">
        
        <!-- Main Slider -->
        <div class="hero-slider-track" 
             role="group" 
             aria-label="<?php _e('Image carousel', 'restaurant-manager'); ?>"
             aria-live="<?php echo $autoplay ? 'off' : 'polite'; ?>"
             aria-atomic="true">
            
            <?php foreach ($slider_images as $index => $image): ?>
                <div class="hero-slide" 
                     data-slide="<?php echo $index; ?>"
                     <?php echo $index === 0 ? 'aria-current="true"' : 'aria-hidden="true"'; ?>>
                    
                    <div class="hero-slide-image">
                        <img src="<?php echo esc_url($image->image_url); ?>" 
                             alt="<?php echo esc_attr($image->alt_text); ?>"
                             loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                             draggable="false">
                    </div>
                    
                    <div class="hero-slide-overlay">
                        <div class="hero-slide-content">
                            <h2 class="hero-slide-title" aria-live="polite">
                                <?php _e('CLOSED ON TUESDAY', 'restaurant-manager'); ?>
                            </h2>
                            <div class="hero-slide-action">
                                <a href="<?php echo get_permalink(get_option('restaurant_menu_page_id')); ?>" 
                                   class="hero-slide-button"
                                   role="button"
                                   aria-label="<?php _e('View our restaurant menu', 'restaurant-manager'); ?>">
                                    <span><?php _e('Check Our Menu', 'restaurant-manager'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($show_controls): ?>
            <!-- Play/Pause Button - Positioned Independently -->
            <button type="button" 
                    class="hero-slider-playpause" 
                    aria-label="<?php echo $autoplay ? __('Pause automatic slideshow. Currently playing.', 'restaurant-manager') : __('Start automatic slideshow. Currently paused.', 'restaurant-manager'); ?>"
                    aria-describedby="slideshow-status"
                    data-playing="<?php echo $autoplay; ?>">
                <span class="play-icon" aria-hidden="true">▶</span>
                <span class="pause-icon" aria-hidden="true">⏸</span>
                <span class="sr-only play-text"><?php _e('Start automatic slideshow of restaurant images', 'restaurant-manager'); ?></span>
                <span class="sr-only pause-text"><?php _e('Pause automatic slideshow of restaurant images', 'restaurant-manager'); ?></span>
            </button>
            
            <!-- Hidden status for screen readers -->
            <div id="slideshow-status" class="sr-only" aria-live="polite">
                <?php echo $autoplay ? __('Slideshow is currently playing automatically', 'restaurant-manager') : __('Slideshow is currently paused', 'restaurant-manager'); ?>
            </div>
        <?php endif; ?>
            
        <?php if ($show_controls && $total_slides > 1): ?>
            <!-- Navigation Controls -->
            <div class="hero-slider-controls" role="group" aria-label="<?php _e('Carousel navigation controls', 'restaurant-manager'); ?>">
                
                <!-- Previous Button -->
                <button type="button" 
                        class="hero-slider-prev" 
                        aria-label="<?php _e('Previous slide', 'restaurant-manager'); ?>">
                    <span aria-hidden="true">‹</span>
                </button>
                
                <!-- Next Button -->
                <button type="button" 
                        class="hero-slider-next" 
                        aria-label="<?php _e('Next slide', 'restaurant-manager'); ?>">
                    <span aria-hidden="true">›</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if ($show_indicators && $total_slides > 1): ?>
            <!-- Slide Indicators -->
            <div class="hero-slider-indicators" 
                 role="group" 
                 aria-label="<?php _e('Slide indicators', 'restaurant-manager'); ?>">
                <?php for ($i = 0; $i < $total_slides; $i++): ?>
                    <button type="button" 
                            class="hero-slider-indicator <?php echo $i === 0 ? 'active' : ''; ?>" 
                            data-slide="<?php echo $i; ?>"
                            aria-label="<?php printf(__('Go to slide %d', 'restaurant-manager'), $i + 1); ?>"
                            <?php echo $i === 0 ? 'aria-current="true"' : ''; ?>>
                        <span class="sr-only">
                            <?php printf(__('Slide %d of %d', 'restaurant-manager'), $i + 1, $total_slides); ?>
                        </span>
                    </button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        
        <!-- Screen Reader Live Region -->
        <div class="sr-only" aria-live="polite" aria-atomic="true" id="slider-status">
            <?php printf(__('Slide 1 of %d', 'restaurant-manager'), $total_slides); ?>
        </div>
    </div>
    

</section>

<?php if ($total_slides > 1): ?>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "ItemList",
        "name": "<?php _e('Restaurant Hero Images', 'restaurant-manager'); ?>",
        "numberOfItems": <?php echo $total_slides; ?>,
        "itemListElement": [
            <?php foreach ($slider_images as $index => $image): ?>
            {
                "@type": "ImageObject",
                "position": <?php echo $index + 1; ?>,
                "url": "<?php echo esc_url($image->image_url); ?>",
                "name": "<?php echo esc_js($image->title); ?>",
                "description": "<?php echo esc_js($image->alt_text); ?>"
            }<?php echo $index < $total_slides - 1 ? ',' : ''; ?>
            <?php endforeach; ?>
        ]
    }
    </script>
<?php endif; ?> 