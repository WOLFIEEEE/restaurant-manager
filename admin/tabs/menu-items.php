<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Status Info -->
<?php if ($categories_count == 0 || $items_count == 0): ?>
    <div class="notice notice-warning restaurant-notice">
        <div class="restaurant-notice-content">
            <h3><span aria-hidden="true">⚠️</span> <?php _e('Setup Required', 'restaurant-manager'); ?></h3>
            <p>
                <?php if ($categories_count == 0): ?>
                    <?php _e('No categories found. You need categories before adding menu items.', 'restaurant-manager'); ?>
                <?php endif; ?>
                <?php if ($items_count == 0): ?>
                    <?php _e('No menu items found in your database.', 'restaurant-manager'); ?>
                <?php endif; ?>
            </p>
            <p>
                <a href="<?php echo esc_url(add_query_arg('reset_data', '1', admin_url('admin.php?page=restaurant-manager'))); ?>" 
                   class="button button-primary"
                   onclick="return confirm('<?php esc_attr_e('This will delete all existing menu data and restore default items. Continue?', 'restaurant-manager'); ?>');">
                    <span aria-hidden="true">🔄</span>
                    <?php _e('Initialize Default Menu Data', 'restaurant-manager'); ?>
                </a>
            </p>
        </div>
    </div>
<?php else: ?>
    <div class="notice notice-info restaurant-notice">
        <div class="restaurant-notice-content">
            <h3><span aria-hidden="true">📊</span> <?php _e('Menu Overview', 'restaurant-manager'); ?></h3>
            <p>
                <?php printf(
                    _n('%d category with %d menu item', '%d categories with %d menu items', $categories_count, 'restaurant-manager'),
                    $categories_count,
                    $items_count
                ); ?>
            </p>
        </div>
    </div>
<?php endif; ?>

<!-- Add New Item Form -->
<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">➕</span>
            <?php _e('Add New Menu Item', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <?php if (empty($categories)): ?>
            <div class="restaurant-empty-state">
                <div class="restaurant-empty-icon">📂</div>
                <h3><?php _e('No Categories Available', 'restaurant-manager'); ?></h3>
                <p><?php _e('Create categories first in the Categories tab before adding menu items.', 'restaurant-manager'); ?></p>
                <a href="<?php echo admin_url('admin.php?page=restaurant-manager&tab=categories'); ?>" class="button button-primary">
                    <?php _e('Manage Categories', 'restaurant-manager'); ?>
                </a>
            </div>
        <?php else: ?>
        <form method="post" class="restaurant-form" novalidate>
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="add_item">
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="category_id">
                            <span aria-hidden="true">📁</span>
                            <?php _e('Category', 'restaurant-manager'); ?>
                            <span class="required">*</span>
                        </label>
                    </th>
                    <td>
                        <select name="category_id" id="category_id" required class="regular-text">
                            <option value=""><?php _e('Select Category', 'restaurant-manager'); ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo esc_attr($category->id); ?>">
                                    <?php echo esc_html($category->emoji . ' ' . $category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Choose the menu category for this item', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="item_number">
                            <span aria-hidden="true">#️⃣</span>
                            <?php _e('Item Number', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="item_number" id="item_number" class="regular-text" 
                               placeholder="L01, C15, S05">
                        <p class="description"><?php _e('Optional item number for organization (e.g., L01, C15)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="name">
                            <span aria-hidden="true">🍽️</span>
                            <?php _e('Item Name', 'restaurant-manager'); ?>
                            <span class="required">*</span>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" required class="regular-text" 
                               placeholder="<?php esc_attr_e('Enter dish name', 'restaurant-manager'); ?>">
                        <p class="description"><?php _e('The display name of your menu item', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="price">
                            <span aria-hidden="true">💰</span>
                            <?php _e('Price', 'restaurant-manager'); ?>
                            <span class="required">*</span>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="price" id="price" required class="regular-text" 
                               placeholder="$12.99" pattern="^\$\d+\.\d{2}$">
                        <p class="description"><?php _e('Include currency symbol (e.g., $12.99)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="description">
                            <span aria-hidden="true">📝</span>
                            <?php _e('Description', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <textarea name="description" id="description" rows="3" class="large-text"
                                  placeholder="<?php esc_attr_e('Optional description of the dish...', 'restaurant-manager'); ?>"></textarea>
                        <p class="description"><?php _e('Optional detailed description of the menu item', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="sort_order">
                            <span aria-hidden="true">🔢</span>
                            <?php _e('Sort Order', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="number" name="sort_order" id="sort_order" value="0" min="0" class="small-text">
                        <p class="description"><?php _e('Order within category (lower numbers appear first)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <?php _e('Options', 'restaurant-manager'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="is_spicy">
                                <input type="checkbox" name="is_spicy" id="is_spicy" value="1">
                                <span aria-hidden="true">🌶️</span>
                                <?php _e('This item is spicy', 'restaurant-manager'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Add Menu Item', 'restaurant-manager'), 'primary', 'submit', false, array('style' => 'margin-top: 20px;')); ?>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Menu Items List -->
<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">📋</span>
            <?php _e('Menu Items', 'restaurant-manager'); ?>
        </h2>
        <div class="handle-actions">
            <button type="button" class="handlediv" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel', 'restaurant-manager'); ?></span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div class="inside">
        
        <!-- Filter Section -->
        <?php if (!empty($categories)): ?>
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get" style="display: inline-block;">
                    <input type="hidden" name="page" value="restaurant-manager">
                    <input type="hidden" name="tab" value="menu-items">
                    <label for="category_filter" class="screen-reader-text"><?php _e('Filter by category', 'restaurant-manager'); ?></label>
                    <select name="category_filter" id="category_filter">
                        <option value=""><?php _e('All Categories', 'restaurant-manager'); ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo esc_attr($category->id); ?>" <?php selected($selected_category, $category->id); ?>>
                                <?php echo esc_html($category->emoji . ' ' . $category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php submit_button(__('Filter', 'restaurant-manager'), 'secondary', 'filter_action', false, array('style' => 'margin-left: 5px;')); ?>
                </form>
            </div>
            <div class="alignright">
                <span class="displaying-num">
                    <?php printf(_n('%s item', '%s items', $total_items, 'restaurant-manager'), number_format_i18n($total_items)); ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Items Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php _e('Category', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Item #', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column column-primary"><?php _e('Name', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Price', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Spicy', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Sort', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Actions', 'restaurant-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menu_items)): ?>
                    <tr class="no-items">
                        <td colspan="7">
                            <div class="restaurant-empty-state">
                                <div class="restaurant-empty-icon">🍽️</div>
                                <h3><?php _e('No Menu Items Found', 'restaurant-manager'); ?></h3>
                                <p><?php _e('Start by adding your first menu item using the form above.', 'restaurant-manager'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td>
                                <span aria-hidden="true"><?php echo esc_html($item->emoji); ?></span>
                                <?php echo esc_html($item->category_name); ?>
                            </td>
                            <td>
                                <?php if ($item->item_number): ?>
                                    <code><?php echo esc_html($item->item_number); ?></code>
                                <?php else: ?>
                                    <span style="opacity: 0.5;">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="column-primary">
                                <strong><?php echo esc_html($item->name); ?></strong>
                                <?php if ($item->description): ?>
                                    <br><small class="description"><?php echo esc_html(wp_trim_words($item->description, 10)); ?></small>
                                <?php endif; ?>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="#" class="edit-item" data-id="<?php echo esc_attr($item->id); ?>"
                                           data-category="<?php echo esc_attr($item->category_id); ?>"
                                           data-number="<?php echo esc_attr($item->item_number); ?>"
                                           data-name="<?php echo esc_attr($item->name); ?>"
                                           data-description="<?php echo esc_attr($item->description); ?>"
                                           data-price="<?php echo esc_attr($item->price); ?>"
                                           data-spicy="<?php echo esc_attr($item->is_spicy); ?>"
                                           data-sort="<?php echo esc_attr($item->sort_order); ?>">
                                            <?php _e('Edit', 'restaurant-manager'); ?>
                                        </a>
                                    </span>
                                    |
                                    <span class="delete">
                                        <a href="#" class="delete-item submitdelete" data-id="<?php echo esc_attr($item->id); ?>"
                                           data-name="<?php echo esc_attr($item->name); ?>">
                                            <?php _e('Delete', 'restaurant-manager'); ?>
                                        </a>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="restaurant-price"><?php echo esc_html($item->price); ?></span>
                            </td>
                            <td>
                                <?php if ($item->is_spicy): ?>
                                    <span class="restaurant-spicy-badge">🌶️ <?php _e('Spicy', 'restaurant-manager'); ?></span>
                                <?php else: ?>
                                    <span style="opacity: 0.5;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($item->sort_order); ?></td>
                            <td>
                                <div class="restaurant-actions">
                                    <button type="button" class="button button-small edit-item" 
                                            data-id="<?php echo esc_attr($item->id); ?>"
                                            data-category="<?php echo esc_attr($item->category_id); ?>"
                                            data-number="<?php echo esc_attr($item->item_number); ?>"
                                            data-name="<?php echo esc_attr($item->name); ?>"
                                            data-description="<?php echo esc_attr($item->description); ?>"
                                            data-price="<?php echo esc_attr($item->price); ?>"
                                            data-spicy="<?php echo esc_attr($item->is_spicy); ?>"
                                            data-sort="<?php echo esc_attr($item->sort_order); ?>">
                                        <span aria-hidden="true">✏️</span> <?php _e('Edit', 'restaurant-manager'); ?>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php 
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo; Previous'),
                    'next_text' => __('Next &raquo;'),
                    'total' => $total_pages,
                    'current' => $current_page,
                    'before_page_number' => '<span class="screen-reader-text">' . __('Page') . '</span>'
                ));
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="edit-item-modal" class="restaurant-modal" style="display: none;">
    <div class="restaurant-modal-backdrop"></div>
    <div class="restaurant-modal-wrap wp-clearfix">
        <form id="edit-item-form" method="post" class="restaurant-modal-form">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="update_item">
            <input type="hidden" name="item_id" id="edit-item-id">
            
            <div class="restaurant-modal-header">
                <h1><?php _e('Edit Menu Item', 'restaurant-manager'); ?></h1>
                <button type="button" class="restaurant-modal-close">
                    <span class="screen-reader-text"><?php _e('Close dialog', 'restaurant-manager'); ?></span>
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            
            <div class="restaurant-modal-content">
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="edit-category_id">
                                <span aria-hidden="true">📁</span>
                                <?php _e('Category', 'restaurant-manager'); ?>
                                <span class="required">*</span>
                            </label>
                        </th>
                        <td>
                            <select name="category_id" id="edit-category_id" required class="regular-text">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo esc_attr($category->id); ?>">
                                        <?php echo esc_html($category->emoji . ' ' . $category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-item_number">
                                <span aria-hidden="true">#️⃣</span>
                                <?php _e('Item Number', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="item_number" id="edit-item_number" class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-name">
                                <span aria-hidden="true">🍽️</span>
                                <?php _e('Item Name', 'restaurant-manager'); ?>
                                <span class="required">*</span>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="name" id="edit-name" required class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-price">
                                <span aria-hidden="true">💰</span>
                                <?php _e('Price', 'restaurant-manager'); ?>
                                <span class="required">*</span>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="price" id="edit-price" required class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-description">
                                <span aria-hidden="true">📝</span>
                                <?php _e('Description', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="description" id="edit-description" rows="3" class="large-text"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-sort_order">
                                <span aria-hidden="true">🔢</span>
                                <?php _e('Sort Order', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="number" name="sort_order" id="edit-sort_order" min="0" class="small-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Options', 'restaurant-manager'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="edit-is_spicy">
                                    <input type="checkbox" name="is_spicy" id="edit-is_spicy" value="1">
                                    <span aria-hidden="true">🌶️</span>
                                    <?php _e('This item is spicy', 'restaurant-manager'); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="restaurant-modal-actions">
                <button type="button" class="button restaurant-modal-close">
                    <?php _e('Cancel', 'restaurant-manager'); ?>
                </button>
                <button type="submit" class="button button-primary">
                    <span aria-hidden="true">💾</span>
                    <?php _e('Update Item', 'restaurant-manager'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete confirmation form -->
<form id="delete-item-form" method="post" style="display: none;">
    <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
    <input type="hidden" name="action" value="delete_item">
    <input type="hidden" name="item_id" id="delete-item-id">
</form>

<script>
jQuery(document).ready(function($) {
    // Edit item functionality
    $('.edit-item').on('click', function(e) {
        e.preventDefault();
        
        var data = $(this).data();
        $('#edit-item-id').val(data.id);
        $('#edit-category_id').val(data.category);
        $('#edit-item_number').val(data.number);
        $('#edit-name').val(data.name);
        $('#edit-description').val(data.description);
        $('#edit-price').val(data.price);
        $('#edit-sort_order').val(data.sort);
        $('#edit-is_spicy').prop('checked', data.spicy == '1');
        
        $('#edit-item-modal').show();
        $('#edit-name').focus();
    });
    
    // Close modal
    $('.restaurant-modal-close, .restaurant-modal-backdrop').on('click', function() {
        $('#edit-item-modal').hide();
    });
    
    // Delete item functionality
    $('.delete-item').on('click', function(e) {
        e.preventDefault();
        
        var itemId = $(this).data('id');
        var itemName = $(this).data('name');
        
        if (confirm('<?php echo esc_js(__('Are you sure you want to delete', 'restaurant-manager')); ?> "' + itemName + '"?')) {
            $('#delete-item-id').val(itemId);
            $('#delete-item-form').submit();
        }
    });
    
    // Escape key closes modal
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $('#edit-item-modal').hide();
        }
    });
});
</script> 