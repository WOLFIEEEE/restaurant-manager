/**
 * Restaurant Menu Manager - WCAG 2.1 Compliant Menu System
 * Handles search, filtering, and accessibility features
 */

class RestaurantMenuManager {
    constructor(menuData) {
        this.menuData = menuData;
        this.originalMenuData = JSON.parse(JSON.stringify(menuData)); // Deep clone
        this.currentFilters = {
            search: '',
            category: '',
            spicy: ''
        };

        this.init();
    }

    init() {
        try {
            this.cacheElements();

            // Debug: Log what elements we found
            console.log('RestaurantMenuManager initialized:', {
                searchInput: !!this.searchInput,
                categoryFilter: !!this.categoryFilter,
                spicyFilter: !!this.spicyFilter,
                clearBtn: !!this.clearBtn,
                menuSections: this.menuSections.length,
                menuItems: this.menuItems.length + ' (content only - not interactive)'
            });

            this.bindEvents();
            this.setupAccessibility();
            this.updateResultsInfo();
        } catch (error) {
            console.error('Error initializing RestaurantMenuManager:', error);
        }
    }

    cacheElements() {
        // Form controls
        this.searchInput = document.getElementById('restaurant-search-input');
        this.categoryFilter = document.getElementById('restaurant-category-filter');
        this.spicyFilter = document.getElementById('restaurant-spicy-filter');
        this.clearBtn = document.getElementById('restaurant-clear-filters');

        // Results
        this.resultsInfo = document.getElementById('restaurant-results-info');
        this.menuContainer = document.getElementById('restaurant-menu-container');
        this.liveRegion = document.getElementById('restaurant-live-region');

        // Menu sections and items
        this.menuSections = document.querySelectorAll('.restaurant-menu-section');
        this.menuItems = document.querySelectorAll('.menu-list__item');
    }

    bindEvents() {
        // Search input with debounce for performance
        if (this.searchInput) {
            this.searchInput.addEventListener('input', this.debounce((e) => {
                this.currentFilters.search = e.target.value.toLowerCase().trim();
                this.filterMenu();
            }, 300));

            // Clear search on Escape key
            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    e.target.value = '';
                    this.currentFilters.search = '';
                    this.filterMenu();
                    this.announceToScreenReader('Search cleared');
                }
            });
        }

        // Category filter
        if (this.categoryFilter) {
            this.categoryFilter.addEventListener('change', (e) => {
                this.currentFilters.category = e.target.value;
                this.filterMenu();
            });
        }

        // Spicy filter
        if (this.spicyFilter) {
            this.spicyFilter.addEventListener('change', (e) => {
                this.currentFilters.spicy = e.target.value;
                this.filterMenu();
            });
        }

        // Clear all filters
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => {
                this.clearAllFilters();
            });

            // Keyboard support for clear button
            this.clearBtn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.clearAllFilters();
                }
            });
        }

        // Keyboard shortcuts (Alt + S for search, Alt + C for category, Alt + R to clear)
        document.addEventListener('keydown', (e) => {
            if (e.altKey) {
                switch (e.key.toLowerCase()) {
                    case 's':
                        e.preventDefault();
                        if (this.searchInput) {
                            this.searchInput.focus();
                            this.announceToScreenReader('Search input focused');
                        }
                        break;
                    case 'c':
                        e.preventDefault();
                        if (this.categoryFilter) {
                            this.categoryFilter.focus();
                            this.announceToScreenReader('Category filter focused');
                        }
                        break;
                    case 'r':
                        e.preventDefault();
                        this.clearAllFilters();
                        break;
                }
            }
        });
    }

    setupAccessibility() {
        // Add keyboard navigation hints
        const controlsContainer = document.querySelector('.restaurant-menu-controls');
        if (controlsContainer) {
            const shortcutInfo = document.createElement('div');
            shortcutInfo.className = 'sr-only';
            shortcutInfo.id = 'keyboard-shortcuts';
            shortcutInfo.innerHTML = `
                Keyboard shortcuts: Alt+S for search, Alt+C for category filter, Alt+R to clear filters, Escape to clear search. Use Tab to navigate between controls.
            `;
            controlsContainer.appendChild(shortcutInfo);

            // Reference shortcuts in controls
            if (this.searchInput) {
                this.searchInput.setAttribute('aria-describedby', 'searchHelp keyboard-shortcuts');
            }
        }

        // Menu items are content only - no interaction needed
        // Screen readers will navigate them naturally as list items
    }



    filterMenu() {
        try {
            let visibleCount = 0;
            let visibleSections = 0;

            // Safety check
            if (!this.menuSections || this.menuSections.length === 0) {
                console.warn('No menu sections found for filtering');
                return;
            }

            this.menuSections.forEach(section => {
                const categorySlug = section.getAttribute('data-category');
                const items = section.querySelectorAll('.menu-list__item');
                let sectionHasVisibleItems = false;

                // Check if section matches category filter
                const categoryMatches = !this.currentFilters.category ||
                    categorySlug === this.currentFilters.category;

                if (categoryMatches) {
                    items.forEach(item => {
                        const isVisible = this.shouldShowItem(item);

                        if (isVisible) {
                            item.style.display = '';
                            item.removeAttribute('aria-hidden');
                            sectionHasVisibleItems = true;
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                            item.setAttribute('aria-hidden', 'true');
                        }
                    });
                } else {
                    // Hide all items in sections that don't match category filter
                    items.forEach(item => {
                        item.style.display = 'none';
                        item.setAttribute('aria-hidden', 'true');
                    });
                }

                // Show/hide entire section based on whether it has visible items
                if (sectionHasVisibleItems) {
                    section.style.display = '';
                    section.removeAttribute('aria-hidden');
                    visibleSections++;
                } else {
                    section.style.display = 'none';
                    section.setAttribute('aria-hidden', 'true');
                }
            });

            this.updateResultsInfo(visibleCount, visibleSections);
            this.announceFilterResults(visibleCount);
        } catch (error) {
            console.error('Error filtering menu:', error);
        }
    }

    shouldShowItem(item) {
        try {
            // Get item data
            const titleElement = item.querySelector('.item_title');
            const descElement = item.querySelector('.desc__content');
            const itemTitle = titleElement ? titleElement.textContent.toLowerCase() : '';
            const itemDesc = descElement ? descElement.textContent.toLowerCase() : '';
            const isSpicy = item.hasAttribute('data-spicy') && item.getAttribute('data-spicy') === 'true';

            // Check search filter
            if (this.currentFilters.search) {
                const searchTerms = this.currentFilters.search.split(' ').filter(term => term.length > 0);
                const matchesSearch = searchTerms.every(term =>
                    itemTitle.includes(term) || itemDesc.includes(term)
                );
                if (!matchesSearch) return false;
            }

            // Check spicy filter
            if (this.currentFilters.spicy) {
                if (this.currentFilters.spicy === 'spicy' && !isSpicy) return false;
                if (this.currentFilters.spicy === 'not-spicy' && isSpicy) return false;
            }

            return true;
        } catch (error) {
            console.error('Error checking item visibility:', error, item);
            return true; // Show item by default if there's an error
        }
    }

    updateResultsInfo(visibleCount = null, visibleSections = null) {
        if (!this.resultsInfo) return;

        if (visibleCount === null) {
            // Initial load - count all items
            visibleCount = this.menuItems.length;
            visibleSections = this.menuSections.length;
        }

        let message = '';
        const hasActiveFilters = this.currentFilters.search ||
            this.currentFilters.category ||
            this.currentFilters.spicy;

        if (hasActiveFilters) {
            if (visibleCount === 0) {
                message = 'No menu items match your search criteria';
            } else if (visibleCount === 1) {
                message = `Showing 1 menu item from ${visibleSections} ${visibleSections === 1 ? 'category' : 'categories'}`;
            } else {
                message = `Showing ${visibleCount} menu items from ${visibleSections} ${visibleSections === 1 ? 'category' : 'categories'}`;
            }

            // Add active filter descriptions
            const filterDescriptions = [];
            if (this.currentFilters.search) {
                filterDescriptions.push(`search: "${this.currentFilters.search}"`);
            }
            if (this.currentFilters.category) {
                const categoryOption = this.categoryFilter.querySelector(`[value="${this.currentFilters.category}"]`);
                if (categoryOption) {
                    filterDescriptions.push(`category: ${categoryOption.textContent}`);
                }
            }
            if (this.currentFilters.spicy) {
                const spicyLabels = {
                    'spicy': 'spicy items only',
                    'not-spicy': 'non-spicy items only'
                };
                filterDescriptions.push(spicyLabels[this.currentFilters.spicy]);
            }

            if (filterDescriptions.length > 0) {
                message += ` (filters: ${filterDescriptions.join(', ')})`;
            }
        } else {
            message = `Showing all ${visibleCount} menu items`;
        }

        this.resultsInfo.textContent = message;
    }

    clearAllFilters() {
        // Reset form controls
        if (this.searchInput) this.searchInput.value = '';
        if (this.categoryFilter) this.categoryFilter.value = '';
        if (this.spicyFilter) this.spicyFilter.value = '';

        // Reset filter state
        this.currentFilters = {
            search: '',
            category: '',
            spicy: ''
        };

        // Reset menu display
        this.menuSections.forEach(section => {
            section.style.display = '';
            section.removeAttribute('aria-hidden');

            section.querySelectorAll('.menu-list__item').forEach(item => {
                item.style.display = '';
                item.removeAttribute('aria-hidden');
            });
        });

        this.updateResultsInfo();
        this.announceToScreenReader('All filters cleared. Showing all menu items.');

        // Focus search input for better UX
        if (this.searchInput) {
            this.searchInput.focus();
        }
    }

    announceFilterResults(visibleCount) {
        let announcement = '';

        if (visibleCount === 0) {
            announcement = 'No menu items found matching your criteria';
        } else if (visibleCount === 1) {
            announcement = '1 menu item found';
        } else {
            announcement = `${visibleCount} menu items found`;
        }

        this.announceToScreenReader(announcement);
    }

    announceToScreenReader(message) {
        if (this.liveRegion) {
            // Clear and set message to ensure it's announced
            this.liveRegion.textContent = '';
            setTimeout(() => {
                this.liveRegion.textContent = message;
            }, 100);
        }
    }

    // Utility function for debouncing search input
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait for menu data to be available
    if (typeof window.restaurantMenuData !== 'undefined' && window.restaurantMenuData) {
        new RestaurantMenuManager(window.restaurantMenuData);
    } else {
        console.warn('Restaurant menu data not found. Menu filtering will not work.');
    }
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RestaurantMenuManager;
}