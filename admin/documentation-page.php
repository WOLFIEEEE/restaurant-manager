<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <div class="restaurant-manager-admin">
        <header class="restaurant-admin-header">
            <h1 class="wp-heading-inline">
                <i class="fas fa-book" aria-hidden="true"></i>
                <?php _e('Restaurant Manager Documentation', 'restaurant-manager'); ?>
            </h1>
            <p class="description">
                <?php _e('Complete documentation for Restaurant Manager plugin shortcodes and features.', 'restaurant-manager'); ?>
            </p>
        </header>

        <div class="restaurant-admin-content">
            <div class="documentation-container">
                
                <!-- Quick Navigation -->
                <nav class="doc-navigation">
                    <h3><i class="fas fa-compass"></i> Quick Navigation</h3>
                    <ul class="doc-nav-list">
                        <li><a href="#menu-shortcode"><i class="fas fa-utensils"></i> Restaurant Menu</a></li>
                        <li><a href="#findus-shortcode"><i class="fas fa-map-marker-alt"></i> Find Us</a></li>
                        <li><a href="#hero-shortcode"><i class="fas fa-images"></i> Hero Slider</a></li>
                        <li><a href="#footer-shortcode"><i class="fas fa-compress-arrows-alt"></i> Footer</a></li>
                        <li><a href="#setup-guide"><i class="fas fa-cog"></i> Setup Guide</a></li>
                    </ul>
                </nav>

                <!-- Restaurant Menu Shortcode -->
                <section id="menu-shortcode" class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-utensils"></i> Restaurant Menu</h3>
                        <span class="doc-shortcode">[restaurant_menu]</span>
                    </div>
                    
                    <div class="doc-content">
                        <p>Display your complete restaurant menu with categories, items, prices, and descriptions.</p>
                        
                        <div class="doc-subsection">
                            <h4><i class="fas fa-code"></i> Basic Usage</h4>
                            <div class="code-block">
                                <code>[restaurant_menu]</code>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-sliders-h"></i> Parameters</h4>
                            <table class="doc-table">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Default</th>
                                        <th>Options</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>show_search</code></td>
                                        <td>true</td>
                                        <td>true, false</td>
                                        <td>Enable search functionality</td>
                                    </tr>
                                    <tr>
                                        <td><code>show_filters</code></td>
                                        <td>true</td>
                                        <td>true, false</td>
                                        <td>Enable category and spice filters</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-lightbulb"></i> Examples</h4>
                            <div class="code-block">
                                <code>[restaurant_menu show_search="true" show_filters="true"]</code>
                                <span class="code-comment">// Full menu with search and filters</span>
                            </div>
                            <div class="code-block">
                                <code>[restaurant_menu show_search="false" show_filters="false"]</code>
                                <span class="code-comment">// Simple menu display only</span>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-list"></i> Features</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Responsive design for all devices</li>
                                <li><i class="fas fa-check"></i> Real-time search functionality</li>
                                <li><i class="fas fa-check"></i> Category and spice level filtering</li>
                                <li><i class="fas fa-check"></i> Restaurant contact information header</li>
                                <li><i class="fas fa-check"></i> Dynamic business hours display</li>
                                <li><i class="fas fa-check"></i> Automatic spicy indicator</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Find Us Shortcode -->
                <section id="findus-shortcode" class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-map-marker-alt"></i> Find Us</h3>
                        <span class="doc-shortcode">[Find_us]</span>
                    </div>
                    
                    <div class="doc-content">
                        <p>Display restaurant location information with contact details and directions.</p>
                        
                        <div class="doc-subsection">
                            <h4><i class="fas fa-code"></i> Basic Usage</h4>
                            <div class="code-block">
                                <code>[Find_us]</code>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-list"></i> Features</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Two-column responsive layout</li>
                                <li><i class="fas fa-check"></i> Contact information with clickable phone numbers</li>
                                <li><i class="fas fa-check"></i> Complete address details</li>
                                <li><i class="fas fa-check"></i> Business hours display</li>
                                <li><i class="fas fa-check"></i> Call and directions buttons</li>
                                <li><i class="fas fa-check"></i> Mobile-optimized design</li>
                            </ul>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-palette"></i> Styling</h4>
                            <p>The Find Us section includes professional styling with:</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Visual column separation</li>
                                <li><i class="fas fa-check"></i> High contrast buttons for accessibility</li>
                                <li><i class="fas fa-check"></i> Responsive typography</li>
                                <li><i class="fas fa-check"></i> Clean visual hierarchy</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Hero Slider Shortcode -->
                <section id="hero-shortcode" class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-images"></i> Hero Slider</h3>
                        <span class="doc-shortcode">[hero_slider]</span>
                    </div>
                    
                    <div class="doc-content">
                        <p>Display a full-width hero image slider with customizable settings and controls.</p>
                        
                        <div class="doc-subsection">
                            <h4><i class="fas fa-code"></i> Basic Usage</h4>
                            <div class="code-block">
                                <code>[hero_slider]</code>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-sliders-h"></i> Parameters</h4>
                            <table class="doc-table">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Default</th>
                                        <th>Options</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>autoplay</code></td>
                                        <td>true</td>
                                        <td>true, false</td>
                                        <td>Enable automatic slideshow</td>
                                    </tr>
                                    <tr>
                                        <td><code>autoplay_delay</code></td>
                                        <td>5000</td>
                                        <td>Any number (ms)</td>
                                        <td>Delay between slides in milliseconds</td>
                                    </tr>
                                    <tr>
                                        <td><code>show_controls</code></td>
                                        <td>true</td>
                                        <td>true, false</td>
                                        <td>Show navigation controls</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-lightbulb"></i> Examples</h4>
                            <div class="code-block">
                                <code>[hero_slider autoplay="true" autoplay_delay="3000"]</code>
                                <span class="code-comment">// 3-second autoplay with controls</span>
                            </div>
                            <div class="code-block">
                                <code>[hero_slider autoplay="false" show_controls="true"]</code>
                                <span class="code-comment">// Manual navigation only</span>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-list"></i> Features</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Full viewport height and width</li>
                                <li><i class="fas fa-check"></i> Touch/swipe gestures for mobile</li>
                                <li><i class="fas fa-check"></i> Keyboard navigation support</li>
                                <li><i class="fas fa-check"></i> Play/pause controls</li>
                                <li><i class="fas fa-check"></i> Slide indicators</li>
                                <li><i class="fas fa-check"></i> Screen reader compatibility</li>
                                <li><i class="fas fa-check"></i> Customizable overlay text and buttons</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Footer Shortcode -->
                <section id="footer-shortcode" class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-compress-arrows-alt"></i> Restaurant Footer</h3>
                        <span class="doc-shortcode">[restaurant_footer]</span>
                    </div>
                    
                    <div class="doc-content">
                        <p>Display a comprehensive footer with restaurant information, menu links, hours, and social media.</p>
                        
                        <div class="doc-subsection">
                            <h4><i class="fas fa-code"></i> Basic Usage</h4>
                            <div class="code-block">
                                <code>[restaurant_footer]</code>
                            </div>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-cogs"></i> Auto-Replace Option</h4>
                            <p>You can also set the footer to automatically replace your theme's footer:</p>
                            <ol class="setup-steps">
                                <li>Go to <strong>Restaurant Manager → Footer</strong></li>
                                <li>Check <strong>"Replace site footer automatically"</strong></li>
                                <li>Save settings</li>
                                <li>Footer will appear site-wide without shortcode</li>
                            </ol>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-list"></i> Features</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Logo and contact information</li>
                                <li><i class="fas fa-check"></i> Main menu navigation links</li>
                                <li><i class="fas fa-check"></i> Dynamic business hours</li>
                                <li><i class="fas fa-check"></i> Promotions section</li>
                                <li><i class="fas fa-check"></i> Social media icons</li>
                                <li><i class="fas fa-check"></i> Copyright and credits</li>
                                <li><i class="fas fa-check"></i> Dark theme with golden accents</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Setup Guide -->
                <section id="setup-guide" class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-cog"></i> Setup Guide</h3>
                    </div>
                    
                    <div class="doc-content">
                        <div class="doc-subsection">
                            <h4><i class="fas fa-play"></i> Getting Started</h4>
                            <ol class="setup-steps">
                                <li><strong>Configure Restaurant Info:</strong> Go to Restaurant Manager → Restaurant Info to set up your basic information</li>
                                <li><strong>Add Categories:</strong> Create menu categories in Restaurant Manager → Categories</li>
                                <li><strong>Add Menu Items:</strong> Add your dishes in Restaurant Manager → Menu Items</li>
                                <li><strong>Set Up Location:</strong> Configure your location details in Restaurant Manager → Find Us</li>
                                <li><strong>Upload Hero Images:</strong> Add slider images in Restaurant Manager → Hero Slider</li>
                                <li><strong>Customize Footer:</strong> Configure footer content in Restaurant Manager → Footer</li>
                            </ol>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-puzzle-piece"></i> Using Shortcodes</h4>
                            <p>Add shortcodes to any page, post, or widget:</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Create a new page or edit existing content</li>
                                <li><i class="fas fa-check"></i> Add the desired shortcode in the content editor</li>
                                <li><i class="fas fa-check"></i> Publish or update the page</li>
                                <li><i class="fas fa-check"></i> The plugin will automatically load required CSS and JavaScript</li>
                            </ul>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-mobile-alt"></i> Responsive Design</h4>
                            <p>All components are fully responsive and work on:</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-desktop"></i> Desktop computers</li>
                                <li><i class="fas fa-tablet-alt"></i> Tablets</li>
                                <li><i class="fas fa-mobile-alt"></i> Mobile phones</li>
                                <li><i class="fas fa-tv"></i> Large displays</li>
                            </ul>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-palette"></i> Customization</h4>
                            <p>Customize the appearance through the admin settings:</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Restaurant colors and branding</li>
                                <li><i class="fas fa-check"></i> Business hours and contact information</li>
                                <li><i class="fas fa-check"></i> Social media links</li>
                                <li><i class="fas fa-check"></i> Promotional content</li>
                                <li><i class="fas fa-check"></i> Footer display options</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Support Section -->
                <section class="doc-section">
                    <div class="doc-header">
                        <h3><i class="fas fa-life-ring"></i> Support & Tips</h3>
                    </div>
                    
                    <div class="doc-content">
                        <div class="doc-subsection">
                            <h4><i class="fas fa-lightbulb"></i> Best Practices</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Use high-quality images for the hero slider (1920x1080px recommended)</li>
                                <li><i class="fas fa-check"></i> Keep menu item names concise but descriptive</li>
                                <li><i class="fas fa-check"></i> Update business hours regularly, especially for holidays</li>
                                <li><i class="fas fa-check"></i> Test your pages on mobile devices</li>
                                <li><i class="fas fa-check"></i> Use the search and filter features to help customers find items quickly</li>
                            </ul>
                        </div>

                        <div class="doc-subsection">
                            <h4><i class="fas fa-exclamation-triangle"></i> Troubleshooting</h4>
                            <div class="troubleshoot-item">
                                <strong>Shortcode not displaying:</strong>
                                <p>Make sure the shortcode is typed exactly as shown, including brackets and underscores.</p>
                            </div>
                            <div class="troubleshoot-item">
                                <strong>Styling looks wrong:</strong>
                                <p>Check for theme conflicts. The plugin includes its own CSS that should override theme styles.</p>
                            </div>
                            <div class="troubleshoot-item">
                                <strong>Images not loading:</strong>
                                <p>Ensure images are uploaded through the WordPress media library and have proper permissions.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<style>
.documentation-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.doc-navigation {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    border-left: 4px solid #c41e3a;
}

.doc-navigation h3 {
    margin: 0 0 15px 0;
    color: #c41e3a;
    font-size: 18px;
}

.doc-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.doc-nav-list a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.doc-nav-list a:hover {
    background: #c41e3a;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(196, 30, 58, 0.2);
}

.doc-section {
    background: white;
    border-radius: 8px;
    margin-bottom: 30px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
}

.doc-header {
    background: linear-gradient(135deg, #c41e3a 0%, #8b1322 100%);
    color: white;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.doc-header h3 {
    margin: 0;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.doc-shortcode {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 12px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.doc-content {
    padding: 25px;
}

.doc-subsection {
    margin-bottom: 25px;
}

.doc-subsection h4 {
    color: #333;
    margin: 0 0 15px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.code-block {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    padding: 12px 15px;
    margin: 10px 0;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    position: relative;
}

.code-block code {
    color: #c41e3a;
    font-weight: 600;
}

.code-comment {
    color: #6c757d;
    font-style: italic;
    display: block;
    margin-top: 5px;
}

.doc-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.doc-table th,
.doc-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.doc-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.doc-table code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    color: #c41e3a;
    font-size: 13px;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.feature-list i {
    color: #28a745;
    font-size: 14px;
}

.setup-steps {
    padding-left: 20px;
}

.setup-steps li {
    margin-bottom: 10px;
    line-height: 1.6;
}

.troubleshoot-item {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 5px;
    padding: 15px;
    margin: 15px 0;
}

.troubleshoot-item strong {
    color: #856404;
    display: block;
    margin-bottom: 5px;
}

.troubleshoot-item p {
    margin: 0;
    color: #856404;
}

@media (max-width: 768px) {
    .doc-nav-list {
        grid-template-columns: 1fr;
    }
    
    .doc-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .documentation-container {
        padding: 0 10px;
    }
    
    .doc-content {
        padding: 20px 15px;
    }
}
</style> 