/**
 * Restaurant Manager Admin JavaScript
 */

jQuery(document).ready(function($) {

    // Handle reset data functionality if needed via AJAX in the future
    $('.reset-data-ajax').on('click', function(e) {
        e.preventDefault();

        if (!confirm('This will delete all existing menu data and restore default items. Continue?')) {
            return false;
        }

        $.ajax({
            url: restaurantManagerAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'reset_plugin_data',
                nonce: restaurantManagerAdmin.nonce
            },
            beforeSend: function() {
                $('.reset-data-ajax').prop('disabled', true).text('Resetting...');
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                $('.reset-data-ajax').prop('disabled', false).text('Reset Data');
            }
        });
    });

    // Auto-save form data to localStorage as backup
    $('#add-item-form input, #add-item-form textarea, #add-item-form select').on('change', function() {
        var formData = $('#add-item-form').serialize();
        localStorage.setItem('restaurant_manager_form_backup', formData);
    });

    // Restore form data if page was refreshed
    var backupData = localStorage.getItem('restaurant_manager_form_backup');
    if (backupData && $('#add-item-form #name').val() === '') {
        // Parse and restore form data (simple implementation)
        // You could implement a more sophisticated restore mechanism here
    }

    // Clear backup when form is successfully submitted
    $('#add-item-form').on('submit', function() {
        localStorage.removeItem('restaurant_manager_form_backup');
    });

    // Add loading state to form submissions
    $('#add-item-form, #edit-item-form').on('submit', function() {
        $(this).find('input[type="submit"]').prop('disabled', true).val('Saving...');
    });

    // Enhanced edit modal functionality
    window.openEditModal = function(data) {
        document.getElementById('edit-item-id').value = data.id;
        document.getElementById('edit-category_id').value = data.category;
        document.getElementById('edit-item_number').value = data.number;
        document.getElementById('edit-name').value = data.name;
        document.getElementById('edit-description').value = data.description;
        document.getElementById('edit-price').value = data.price;
        document.getElementById('edit-sort_order').value = data.sort;
        document.getElementById('edit-is_spicy').checked = data.spicy == '1';

        document.getElementById('edit-item-modal').style.display = 'flex';
        document.getElementById('edit-name').focus();
    };

    window.closeEditModal = function() {
        document.getElementById('edit-item-modal').style.display = 'none';
    };

    // Add keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Escape key closes modal
        if (e.keyCode === 27) {
            closeEditModal();
        }
    });
});