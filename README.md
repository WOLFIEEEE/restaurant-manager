# Restaurant Manager - Complete WordPress Restaurant Solution

A comprehensive WordPress plugin for restaurant management with menu display, hero slider, location finder, and custom footer functionality.

## 🚀 Features

### Core Functionality
- **Menu Management** - Add, edit, organize restaurant menu items with categories
- **Hero Image Slider** - Full-width responsive image carousel with accessibility features
- **Location Finder** - "Find Us" section with contact information and map integration
- **Custom Footer** - Professional restaurant footer with hours, social links, and promotions
- **Shortcode System** - Easy integration with any page, post, or widget

### Professional Design
- ✅ Fully responsive across all devices
- ✅ Professional restaurant styling and typography
- ✅ Clean, modern interface with Font Awesome icons
- ✅ Optimized performance and loading times
- ✅ Cross-browser compatibility

### Developer Features  
- ✅ Clean, well-documented code
- ✅ WordPress coding standards compliant
- ✅ Secure input handling and sanitization
- ✅ Database optimization with proper indexing
- ✅ Translation ready with proper text domains

## 📋 Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.4 or higher  
- **MySQL:** 5.6 or higher

## 🎯 Available Shortcodes

### Restaurant Menu
```
[restaurant_menu]
[restaurant_menu show_search="true" show_filters="true"]
```

### Find Us Section
```
[Find_us]
```

### Hero Image Slider
```
[hero_slider]
[hero_slider autoplay="false" autoplay_delay="3000"]
```

### Restaurant Footer
```
[restaurant_footer]
```

## 🛠️ Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/restaurant-manager/`
3. Activate the plugin through the WordPress admin
4. Navigate to **Restaurant Manager** in your admin menu
5. Configure your settings and add content
6. Use shortcodes on any page or post

## 📖 Quick Start Guide

1. **Set Up Restaurant Info** - Add basic restaurant details (name, address, phone)
2. **Create Categories** - Organize your menu with appetizers, mains, desserts, etc.
3. **Add Menu Items** - Upload food images and descriptions with pricing
4. **Configure Location** - Set up your "Find Us" page with map and directions
5. **Upload Hero Images** - Add stunning slider images for your homepage
6. **Customize Footer** - Configure social links, hours, and promotional content

## 🎨 Admin Interface

The plugin features a professional admin interface with:

- **Dashboard Overview** - Quick access to all components
- **Font Awesome Icons** - Professional iconography throughout
- **Tabbed Navigation** - Easy switching between different sections
- **Success Notifications** - Clear feedback for all actions
- **Documentation Tab** - Built-in help and usage guides

### Admin Sections

- <i class="fas fa-utensils"></i> **Menu Items** - Manage your food and drink items
- <i class="fas fa-store"></i> **Restaurant Info** - Basic business information
- <i class="fas fa-folder"></i> **Categories** - Organize menu sections
- <i class="fas fa-map-marker-alt"></i> **Find Us** - Location and contact settings
- <i class="fas fa-images"></i> **Hero Slider** - Homepage image carousel
- <i class="fas fa-compress-arrows-alt"></i> **Footer** - Custom footer configuration
- <i class="fas fa-book"></i> **Documentation** - Complete usage guide

## 🔧 Technical Details

### Database Tables
- `wp_restaurant_categories` - Menu categories
- `wp_restaurant_menu_items` - Menu items and details  
- `wp_restaurant_settings` - Plugin configuration
- `wp_restaurant_hero_slider` - Hero slider images

### File Structure
```
restaurant-manager/
├── admin/                    # Admin interface files
│   ├── admin-page.php       # Main admin page
│   └── tabs/                # Individual admin tabs
├── assets/                   # CSS, JS, and media files
│   ├── admin.css            # Admin styling
│   ├── frontend.css         # Frontend styling
│   ├── hero-slider.css      # Hero slider styles
│   └── footer.css           # Footer styles
├── templates/                # Frontend display templates
│   ├── menu-display.php     # Menu shortcode template
│   ├── find-us-display.php  # Find Us shortcode template
│   ├── hero-slider-display.php # Hero slider template
│   └── footer-display.php   # Footer template
└── restaurant-manager.php   # Main plugin file
```

## 🎛️ Customization Options

### Menu Display
- Search functionality toggle
- Category and spice level filters
- Responsive grid layouts
- Custom restaurant information header

### Hero Slider
- Autoplay controls with customizable timing
- Navigation controls and indicators
- Touch/swipe gesture support
- Keyboard accessibility

### Find Us Section
- Google Maps integration
- Clickable phone numbers
- Custom directions text
- Responsive two-column layout

### Footer
- Social media icon integration
- Business hours display
- Promotional content sections
- Site-wide footer replacement option

## 📚 Built-in Documentation

The plugin includes comprehensive documentation accessible through the admin interface:

- **Shortcode Usage Examples** - Copy-paste ready code snippets
- **Parameter References** - Complete attribute listings
- **Setup Guides** - Step-by-step configuration instructions
- **Troubleshooting** - Common issues and solutions
- **Best Practices** - Optimization tips and recommendations

## 🔐 Security Features

- **Nonce Verification** - All form submissions are secured
- **Input Sanitization** - User data is properly cleaned
- **SQL Injection Protection** - Prepared statements throughout
- **XSS Prevention** - Output escaping on all dynamic content
- **Access Control** - Proper capability checks

## 🌐 Accessibility Compliance

- **WCAG 2.1 AA Standards** - Meets accessibility guidelines
- **Keyboard Navigation** - Full keyboard support
- **Screen Reader Compatible** - Proper ARIA attributes
- **Color Contrast** - 4.5:1 contrast ratios maintained
- **Focus Management** - Logical tab order
- **Alternative Text** - Image descriptions supported

## 🚀 Performance Optimization

- **Conditional Loading** - Assets load only when needed
- **Minified Resources** - Optimized CSS and JavaScript
- **Database Indexing** - Proper database optimization
- **Caching Ready** - Compatible with caching plugins
- **Minimal HTTP Requests** - Efficient resource loading

## 🤝 Support

For support questions, feature requests, or bug reports:

1. **Check Documentation** - Visit the Documentation tab in the admin
2. **Review Settings** - Ensure proper plugin configuration
3. **Test Compatibility** - Try with default WordPress themes
4. **Contact Support** - Reach out for additional assistance

## 📄 License

This plugin is licensed under the GPL v2 or later.

---

**Version:** 1.0.0  
**Tested up to:** WordPress 6.4  
**Minimum PHP:** 7.4  
**Author:** Restaurant Manager Team 