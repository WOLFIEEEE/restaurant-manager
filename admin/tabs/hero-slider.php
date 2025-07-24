<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$slider_table = $wpdb->prefix . 'restaurant_hero_slider';

// Debug: Check if table exists and show any errors
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$slider_table'");
$db_error = $wpdb->last_error;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurant_manager_nonce']) && wp_verify_nonce($_POST['restaurant_manager_nonce'], 'restaurant_manager_admin_action')) {
    if (isset($_POST['action']) && $_POST['action'] === 'save_slider_settings') {
        // Save general slider settings
        $autoplay = isset($_POST['autoplay']) ? 1 : 0;
        $autoplay_delay = intval($_POST['autoplay_delay']);
        $show_controls = isset($_POST['show_controls']) ? 1 : 0;
        $show_indicators = isset($_POST['show_indicators']) ? 1 : 0;
        
        update_option('restaurant_slider_autoplay', $autoplay);
        update_option('restaurant_slider_autoplay_delay', $autoplay_delay);
        update_option('restaurant_slider_show_controls', $show_controls);
        update_option('restaurant_slider_show_indicators', $show_indicators);
        
        echo '<div class="notice notice-success is-dismissible"><p>Slider settings saved successfully!</p></div>';
    }
}

// Get current settings
$autoplay = get_option('restaurant_slider_autoplay', 1);
$autoplay_delay = get_option('restaurant_slider_autoplay_delay', 5000);
$show_controls = get_option('restaurant_slider_show_controls', 1);
$show_indicators = get_option('restaurant_slider_show_indicators', 1);

// Get slider images (only if table exists)
$slider_images = array();
if ($table_exists) {
    $slider_images = $wpdb->get_results("SELECT * FROM $slider_table ORDER BY sort_order ASC, id ASC");
} else {
    // Try to create the table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
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
    dbDelta($slider_sql);
    
    // Check again
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$slider_table'");
    if ($table_exists) {
        $slider_images = $wpdb->get_results("SELECT * FROM $slider_table ORDER BY sort_order ASC, id ASC");
    }
}
?>

<?php if (!$table_exists): ?>
<div class="notice notice-error">
    <p><strong>Database Error:</strong> Hero slider table could not be created. Please try deactivating and reactivating the plugin.</p>
    <?php if ($db_error): ?>
        <p><strong>MySQL Error:</strong> <?php echo esc_html($db_error); ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">🎬</span>
            <?php _e('Hero Slider Management', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <p class="description">
            <?php _e('Manage your homepage hero slider images. Add beautiful, high-quality images with compelling text overlays. Use the [hero_slider] shortcode to display the slider on any page.', 'restaurant-manager'); ?>
        </p>
        
        <div class="hero-slider-actions">
            <button type="button" class="button button-primary" id="add-slider-image">
                <span aria-hidden="true">➕</span>
                <?php _e('Add New Image', 'restaurant-manager'); ?>
            </button>
        </div>
    </div>
</div>

<!-- Slider Settings -->
<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">⚙️</span>
            <?php _e('Slider Settings', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <form method="post" class="restaurant-form">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="save_slider_settings">
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="autoplay">
                            <span aria-hidden="true">▶️</span>
                            <?php _e('Auto-play Slider', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="autoplay" id="autoplay" value="1" <?php checked($autoplay, 1); ?>>
                        <p class="description"><?php _e('Automatically advance slides', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="autoplay_delay">
                            <span aria-hidden="true">⏱️</span>
                            <?php _e('Slide Duration (ms)', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="number" name="autoplay_delay" id="autoplay_delay" 
                               value="<?php echo esc_attr($autoplay_delay); ?>" 
                               min="1000" max="10000" step="500" class="small-text">
                        <p class="description"><?php _e('How long each slide displays (in milliseconds)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="show_controls">
                            <span aria-hidden="true">🎮</span>
                            <?php _e('Show Navigation Controls', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_controls" id="show_controls" value="1" <?php checked($show_controls, 1); ?>>
                        <p class="description"><?php _e('Display previous/next arrows and play/pause button', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="show_indicators">
                            <span aria-hidden="true">🔘</span>
                            <?php _e('Show Slide Indicators', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_indicators" id="show_indicators" value="1" <?php checked($show_indicators, 1); ?>>
                        <p class="description"><?php _e('Display dot indicators at the bottom of the slider', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Slider Settings', 'restaurant-manager')); ?>
        </form>
    </div>
</div>

<!-- Slider Images List -->
<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">🖼️</span>
            <?php _e('Slider Images', 'restaurant-manager'); ?>
            <span class="count">(<?php echo count($slider_images); ?>)</span>
        </h2>
    </div>
    <div class="inside">
        <?php if (empty($slider_images)): ?>
            <div class="hero-slider-empty">
                <div class="dashicons dashicons-images-alt2"></div>
                <h3><?php _e('No slider images yet', 'restaurant-manager'); ?></h3>
                <p><?php _e('Add your first slider image to get started with your hero slider.', 'restaurant-manager'); ?></p>
                <button type="button" class="button button-primary" onclick="document.getElementById('add-slider-image').click();">
                    <?php _e('Add First Image', 'restaurant-manager'); ?>
                </button>
            </div>
        <?php else: ?>
            <div class="hero-slider-grid" id="slider-images-container">
                <?php foreach ($slider_images as $image): ?>
                    <div class="slider-image-card" data-id="<?php echo $image->id; ?>">
                        <div class="slider-image-preview">
                            <img src="<?php echo esc_url($image->image_url); ?>" 
                                 alt="<?php echo esc_attr($image->alt_text); ?>"
                                 loading="lazy">
                            <div class="slider-image-overlay">
                                <div class="overlay-content">
                                    <h2 class="overlay-title">CLOSED ON TUESDAY</h2>
                                    <button class="overlay-button" type="button">Check Our Menu</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slider-image-info">
                            <h4 class="slider-image-title"><?php echo esc_html($image->title); ?></h4>
                            <p class="slider-image-alt"><?php echo esc_html($image->alt_text); ?></p>
                            
                            <div class="slider-image-meta">
                                <span class="sort-order">Order: <?php echo $image->sort_order; ?></span>
                                <span class="status <?php echo $image->is_active ? 'active' : 'inactive'; ?>">
                                    <?php echo $image->is_active ? __('Active', 'restaurant-manager') : __('Inactive', 'restaurant-manager'); ?>
                                </span>
                            </div>
                            
                            <div class="slider-image-actions">
                                <button type="button" class="button button-small edit-slider-image" 
                                        data-id="<?php echo $image->id; ?>"
                                        data-title="<?php echo esc_attr($image->title); ?>"
                                        data-url="<?php echo esc_attr($image->image_url); ?>"
                                        data-alt="<?php echo esc_attr($image->alt_text); ?>"
                                        data-active="<?php echo $image->is_active; ?>"
                                        data-order="<?php echo $image->sort_order; ?>">
                                    <?php _e('Edit', 'restaurant-manager'); ?>
                                </button>
                                <button type="button" class="button button-small button-link-delete delete-slider-image" 
                                        data-id="<?php echo $image->id; ?>"
                                        data-title="<?php echo esc_attr($image->title); ?>">
                                    <?php _e('Delete', 'restaurant-manager'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>



<!-- Image Modal -->
<div id="slider-image-modal" class="hero-slider-modal" style="display: none;">
    <div class="hero-slider-modal-content">
        <div class="hero-slider-modal-header">
            <h2 id="modal-title"><?php _e('Add Slider Image', 'restaurant-manager'); ?></h2>
            <button type="button" class="hero-slider-modal-close" aria-label="<?php _e('Close', 'restaurant-manager'); ?>">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <form id="slider-image-form" class="hero-slider-form">
            <input type="hidden" id="image-id" name="image_id" value="">
            
            <div class="form-field">
                <label for="image-title"><?php _e('Image Title', 'restaurant-manager'); ?> <span class="required">*</span></label>
                <input type="text" id="image-title" name="title" required maxlength="200"
                       placeholder="<?php _e('Enter a descriptive title for this image', 'restaurant-manager'); ?>">
                <p class="description"><?php _e('Internal title for identification purposes', 'restaurant-manager'); ?></p>
            </div>
            
            <div class="form-field">
                <label for="image-url"><?php _e('Image URL', 'restaurant-manager'); ?> <span class="required">*</span></label>
                <div class="image-url-field">
                    <input type="url" id="image-url" name="image_url" required
                           placeholder="<?php _e('https://example.com/image.jpg', 'restaurant-manager'); ?>">
                    <button type="button" class="button" id="upload-image-button">
                        <?php _e('Upload Image', 'restaurant-manager'); ?>
                    </button>
                </div>
                <p class="description"><?php _e('Recommended size: 1920x1080px or larger for best quality', 'restaurant-manager'); ?></p>
                <div id="image-preview" style="display: none;">
                    <img src="" alt="Preview" style="max-width: 200px; height: auto; margin-top: 10px;">
                </div>
            </div>
            
            <div class="form-field">
                <label for="image-alt"><?php _e('Alt Text', 'restaurant-manager'); ?> <span class="required">*</span></label>
                <input type="text" id="image-alt" name="alt_text" required maxlength="200"
                       placeholder="<?php _e('Describe what is shown in the image', 'restaurant-manager'); ?>">
                <p class="description"><?php _e('Important for accessibility - describe the image content', 'restaurant-manager'); ?></p>
            </div>
            
            <div class="form-field">
                <label for="image-order"><?php _e('Display Order', 'restaurant-manager'); ?></label>
                <input type="number" id="image-order" name="sort_order" min="0" max="999" value="0">
                <p class="description"><?php _e('Lower numbers appear first (0 = first)', 'restaurant-manager'); ?></p>
            </div>
            
            <div class="form-field">
                <label>
                    <input type="checkbox" id="image-active" name="is_active" value="1" checked>
                    <?php _e('Active', 'restaurant-manager'); ?>
                </label>
                <p class="description"><?php _e('Only active images will be displayed in the slider', 'restaurant-manager'); ?></p>
            </div>
            
            <div class="hero-slider-modal-footer">
                <button type="button" class="button" id="cancel-image">
                    <?php _e('Cancel', 'restaurant-manager'); ?>
                </button>
                <button type="submit" class="button button-primary" id="save-image">
                    <?php _e('Save Image', 'restaurant-manager'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.hero-slider-empty {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.hero-slider-empty .dashicons {
    font-size: 64px;
    width: 64px;
    height: 64px;
    margin-bottom: 20px;
    color: #ccc;
}

.hero-slider-empty h3 {
    margin: 20px 0 10px;
    color: #333;
}

.hero-slider-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.slider-image-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.slider-image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.slider-image-preview {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.slider-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slider-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.slider-image-card:hover .slider-image-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
}

.overlay-title {
    font-size: 18px;
    font-weight: bold;
    margin: 0 0 15px 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.overlay-button {
    background: #c41e3a;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
}

.slider-image-info {
    padding: 15px;
}

.slider-image-title {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.slider-image-alt {
    margin: 0 0 10px 0;
    font-size: 13px;
    color: #666;
    font-style: italic;
}

.slider-image-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0;
    font-size: 12px;
}

.sort-order {
    color: #666;
}

.status {
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 11px;
}

.status.active {
    background: #d4edda;
    color: #155724;
}

.status.inactive {
    background: #f8d7da;
    color: #721c24;
}

.slider-image-actions {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}

.hero-slider-actions {
    margin: 20px 0;
}

.hero-slider-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-slider-modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.hero-slider-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #ddd;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.hero-slider-modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.hero-slider-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-slider-form {
    padding: 25px;
}

.form-field {
    margin-bottom: 20px;
}

.form-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-field input[type="text"],
.form-field input[type="url"],
.form-field input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.image-url-field {
    display: flex;
    gap: 10px;
}

.image-url-field input {
    flex: 1;
}

.required {
    color: #d63638;
}

.hero-slider-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
    margin-top: 20px;
}


</style>

<script>
jQuery(document).ready(function($) {
    let currentImageId = 0;
    
    // Debug: Check if elements exist
    if ($('#upload-image-button').length === 0) {
        console.error('Upload image button not found!');
    }
    if (typeof wp === 'undefined') {
        console.error('WordPress wp object not available!');
    } else if (typeof wp.media === 'undefined') {
        console.error('WordPress media library not available!');
    }
    
    // Add new image button
    $('#add-slider-image').click(function() {
        currentImageId = 0;
        $('#modal-title').text('<?php _e('Add Slider Image', 'restaurant-manager'); ?>');
        $('#slider-image-form')[0].reset();
        $('#image-active').prop('checked', true);
        $('#image-preview').hide();
        $('#slider-image-modal').show();
        $('#image-title').focus();
    });
    
    // Edit image button
    $(document).on('click', '.edit-slider-image', function() {
        currentImageId = $(this).data('id');
        $('#modal-title').text('<?php _e('Edit Slider Image', 'restaurant-manager'); ?>');
        $('#image-id').val(currentImageId);
        $('#image-title').val($(this).data('title'));
        $('#image-url').val($(this).data('url'));
        $('#image-alt').val($(this).data('alt'));
        $('#image-order').val($(this).data('order'));
        $('#image-active').prop('checked', $(this).data('active') == 1);
        
        // Show preview
        $('#image-preview img').attr('src', $(this).data('url'));
        $('#image-preview').show();
        
        $('#slider-image-modal').show();
        $('#image-title').focus();
    });
    
    // Close modal
    $('.hero-slider-modal-close, #cancel-image').click(function() {
        $('#slider-image-modal').hide();
    });
    
    // Close modal on outside click
    $('#slider-image-modal').click(function(e) {
        if (e.target === this) {
            $(this).hide();
        }
    });
    
    // Upload image button - declare file_frame outside to persist
    let file_frame;
    
    $('#upload-image-button').click(function(e) {
        e.preventDefault();
        
        // Check if wp.media is available
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            alert('<?php _e('WordPress media library is not available. Please refresh the page and try again.', 'restaurant-manager'); ?>');
            return;
        }
        
        // If the media frame already exists, reopen it
        if (file_frame) {
            file_frame.open();
            return;
        }
        
        // Create the media frame
        file_frame = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('Select Slider Image', 'restaurant-manager'); ?>',
            button: {
                text: '<?php _e('Use This Image', 'restaurant-manager'); ?>'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        // When an image is selected, run a callback
        file_frame.on('select', function() {
            try {
                let attachment = file_frame.state().get('selection').first().toJSON();
                
                if (attachment && attachment.url) {
                    $('#image-url').val(attachment.url);
                    $('#image-alt').val(attachment.alt || attachment.title || attachment.filename || '');
                    $('#image-preview img').attr('src', attachment.url);
                    $('#image-preview').show();
                    
                    // Auto-fill title if empty
                    if (!$('#image-title').val()) {
                        $('#image-title').val(attachment.title || attachment.filename || 'Slider Image');
                    }
                } else {
                    alert('<?php _e('Error: Could not get image information. Please try again.', 'restaurant-manager'); ?>');
                }
            } catch (error) {
                console.error('Error processing selected image:', error);
                alert('<?php _e('Error processing selected image. Please try again.', 'restaurant-manager'); ?>');
            }
        });
        
        // Finally, open the modal
        file_frame.open();
    });
    
    // Preview image when URL changes
    $('#image-url').on('blur input', function() {
        let url = $(this).val().trim();
        if (url) {
            // Basic URL validation
            if (url.match(/\.(jpeg|jpg|gif|png|webp)$/i) || url.includes('unsplash.com') || url.includes('images.')) {
                $('#image-preview img').attr('src', url);
                $('#image-preview').show();
            } else {
                $('#image-preview').hide();
            }
        } else {
            $('#image-preview').hide();
        }
    });
    
    // Handle image load errors
    $('#image-preview img').on('error', function() {
        $(this).parent().hide();
        console.warn('Failed to load image preview');
    });
    
    // Save image form
    $('#slider-image-form').submit(function(e) {
        e.preventDefault();
        
        // Basic validation
        let title = $('#image-title').val().trim();
        let imageUrl = $('#image-url').val().trim();
        let altText = $('#image-alt').val().trim();
        
        if (!title) {
            alert('<?php _e('Please enter a title for the image.', 'restaurant-manager'); ?>');
            $('#image-title').focus();
            return;
        }
        
        if (!imageUrl) {
            alert('<?php _e('Please select or enter an image URL.', 'restaurant-manager'); ?>');
            $('#image-url').focus();
            return;
        }
        
        if (!altText) {
            alert('<?php _e('Please enter alt text for accessibility.', 'restaurant-manager'); ?>');
            $('#image-alt').focus();
            return;
        }
        
        // Disable submit button to prevent double submission
        let submitBtn = $('#save-image');
        let originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('<?php _e('Saving...', 'restaurant-manager'); ?>');
        
        let formData = {
            action: 'save_slider_image',
            nonce: '<?php echo wp_create_nonce('restaurant_manager_admin_nonce'); ?>',
            image_id: currentImageId,
            title: title,
            image_url: imageUrl,
            alt_text: altText,
            sort_order: $('#image-order').val() || 0,
            is_active: $('#image-active').is(':checked') ? 1 : 0
        };
        
        $.post(ajaxurl, formData, function(response) {
            if (response && response.success) {
                $('#slider-image-modal').hide();
                location.reload(); // Reload to show updated list
            } else {
                let errorMsg = 'Failed to save slider image';
                if (response && response.data) {
                    errorMsg = response.data;
                }
                alert('Error: ' + errorMsg);
                console.error('Save error:', response);
            }
        }).fail(function(xhr, status, error) {
            alert('Network error: ' + error + '. Please check your connection and try again.');
            console.error('AJAX error:', xhr, status, error);
        }).always(function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false).text(originalText);
        });
    });
    
    // Delete image
    $(document).on('click', '.delete-slider-image', function() {
        let imageId = $(this).data('id');
        let imageTitle = $(this).data('title');
        
        if (confirm('<?php _e('Are you sure you want to delete', 'restaurant-manager'); ?> "' + imageTitle + '"?')) {
            $.post(ajaxurl, {
                action: 'delete_slider_image',
                nonce: '<?php echo wp_create_nonce('restaurant_manager_admin_nonce'); ?>',
                image_id: imageId
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.data || 'Failed to delete slider image'));
                }
            });
        }
    });
});
</script> 