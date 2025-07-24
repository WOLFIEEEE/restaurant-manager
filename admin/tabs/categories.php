<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">➕</span>
            <?php _e('Add New Category', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <form method="post" class="restaurant-form">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="add_category">
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="category_name">
                            <span aria-hidden="true">📂</span>
                            <?php _e('Category Name', 'restaurant-manager'); ?>
                            <span class="required">*</span>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="category_name" id="category_name" required class="regular-text">
                        <p class="description"><?php _e('The name of the menu category', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="category_emoji">
                            <span aria-hidden="true">😀</span>
                            <?php _e('Emoji', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="category_emoji" id="category_emoji" class="small-text" maxlength="5">
                        <p class="description"><?php _e('Optional emoji to display with the category', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="category_note">
                            <span aria-hidden="true">📝</span>
                            <?php _e('Description', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <textarea name="category_note" id="category_note" rows="3" class="large-text"></textarea>
                        <p class="description"><?php _e('Optional description or note for this category', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="category_sort_order">
                            <span aria-hidden="true">🔢</span>
                            <?php _e('Sort Order', 'restaurant-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="number" name="category_sort_order" id="category_sort_order" value="0" min="0" class="small-text">
                        <p class="description"><?php _e('Order in which categories appear (lower numbers first)', 'restaurant-manager'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Add Category', 'restaurant-manager'), 'primary'); ?>
        </form>
    </div>
</div>

<div class="postbox restaurant-postbox">
    <div class="postbox-header">
        <h2 class="hndle">
            <span aria-hidden="true">📂</span>
            <?php _e('Menu Categories', 'restaurant-manager'); ?>
        </h2>
    </div>
    <div class="inside">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php _e('Category', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Items Count', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Sort Order', 'restaurant-manager'); ?></th>
                    <th scope="col" class="manage-column"><?php _e('Actions', 'restaurant-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr class="no-items">
                        <td colspan="4">
                            <div class="restaurant-empty-state">
                                <div class="restaurant-empty-icon">📂</div>
                                <h3><?php _e('No Categories Found', 'restaurant-manager'); ?></h3>
                                <p><?php _e('Create your first menu category using the form above.', 'restaurant-manager'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): 
                        $item_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $items_table WHERE category_id = %d", $category->id));
                    ?>
                        <tr>
                            <td class="column-primary">
                                <strong>
                                    <?php if ($category->emoji): ?>
                                        <span aria-hidden="true"><?php echo esc_html($category->emoji); ?></span>
                                    <?php endif; ?>
                                    <?php echo esc_html($category->name); ?>
                                </strong>
                                <?php if ($category->note): ?>
                                    <br><small class="description"><?php echo esc_html($category->note); ?></small>
                                <?php endif; ?>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="#" class="edit-category" 
                                           data-id="<?php echo esc_attr($category->id); ?>"
                                           data-name="<?php echo esc_attr($category->name); ?>"
                                           data-emoji="<?php echo esc_attr($category->emoji); ?>"
                                           data-note="<?php echo esc_attr($category->note); ?>"
                                           data-sort="<?php echo esc_attr($category->sort_order); ?>">
                                            <?php _e('Edit', 'restaurant-manager'); ?>
                                        </a>
                                    </span>
                                    <?php if ($item_count == 0): ?>
                                    |
                                    <span class="delete">
                                        <a href="#" class="delete-category submitdelete" 
                                           data-id="<?php echo esc_attr($category->id); ?>"
                                           data-name="<?php echo esc_attr($category->name); ?>">
                                            <?php _e('Delete', 'restaurant-manager'); ?>
                                        </a>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="count"><?php echo $item_count; ?></span>
                                <?php _e('items', 'restaurant-manager'); ?>
                            </td>
                            <td><?php echo esc_html($category->sort_order); ?></td>
                            <td>
                                <button type="button" class="button button-small edit-category" 
                                        data-id="<?php echo esc_attr($category->id); ?>"
                                        data-name="<?php echo esc_attr($category->name); ?>"
                                        data-emoji="<?php echo esc_attr($category->emoji); ?>"
                                        data-note="<?php echo esc_attr($category->note); ?>"
                                        data-sort="<?php echo esc_attr($category->sort_order); ?>">
                                    <span aria-hidden="true">✏️</span> <?php _e('Edit', 'restaurant-manager'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-category-modal" class="restaurant-modal" style="display: none;">
    <div class="restaurant-modal-backdrop"></div>
    <div class="restaurant-modal-wrap wp-clearfix">
        <form id="edit-category-form" method="post">
            <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
            <input type="hidden" name="action" value="update_category">
            <input type="hidden" name="category_id" id="edit-category-id">
            
            <div class="restaurant-modal-header">
                <h1><?php _e('Edit Category', 'restaurant-manager'); ?></h1>
                <button type="button" class="restaurant-modal-close">×</button>
            </div>
            
            <div class="restaurant-modal-content">
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="edit-category_name">
                                <span aria-hidden="true">📂</span>
                                <?php _e('Category Name', 'restaurant-manager'); ?>
                                <span class="required">*</span>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="category_name" id="edit-category_name" required class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-category_emoji">
                                <span aria-hidden="true">😀</span>
                                <?php _e('Emoji', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="category_emoji" id="edit-category_emoji" class="small-text" maxlength="5">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-category_note">
                                <span aria-hidden="true">📝</span>
                                <?php _e('Description', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="category_note" id="edit-category_note" rows="3" class="large-text"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="edit-category_sort_order">
                                <span aria-hidden="true">🔢</span>
                                <?php _e('Sort Order', 'restaurant-manager'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="number" name="category_sort_order" id="edit-category_sort_order" min="0" class="small-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="restaurant-modal-actions">
                <button type="button" class="button restaurant-modal-close"><?php _e('Cancel', 'restaurant-manager'); ?></button>
                <button type="submit" class="button button-primary">
                    <span aria-hidden="true">💾</span> <?php _e('Update Category', 'restaurant-manager'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<form id="delete-category-form" method="post" style="display: none;">
    <?php wp_nonce_field('restaurant_manager_admin_action', 'restaurant_manager_nonce'); ?>
    <input type="hidden" name="action" value="delete_category">
    <input type="hidden" name="category_id" id="delete-category-id">
</form>

<script>
jQuery(document).ready(function($) {
    $('.edit-category').on('click', function(e) {
        e.preventDefault();        
        var data = $(this).data();
        $('#edit-category-id').val(data.id);
        $('#edit-category_name').val(data.name);
        $('#edit-category_emoji').val(data.emoji);
        $('#edit-category_note').val(data.note);
        $('#edit-category_sort_order').val(data.sort);        
        $('#edit-category-modal').show();
        $('#edit-category_name').focus();
    });
    
    $('.restaurant-modal-close, .restaurant-modal-backdrop').on('click', function() {
        $('#edit-category-modal').hide();
    });
    
    $('.delete-category').on('click', function(e) {
        e.preventDefault();        
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');        
        if (confirm('<?php echo esc_js(__('Are you sure you want to delete', 'restaurant-manager')); ?> "' + categoryName + '"?')) {
            $('#delete-category-id').val(categoryId);
            $('#delete-category-form').submit();
        }
    });
});
</script> 