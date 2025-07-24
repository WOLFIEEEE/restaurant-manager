/**
 * Restaurant Manager - Hero Slider JavaScript
 * Fully accessible, WCAG-compliant image slider
 */

(function() {
    'use strict';
    
    // Initialize slider when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeSliders);
    } else {
        initializeSliders();
    }
    
    function initializeSliders() {
        const sliders = document.querySelectorAll('.hero-slider-container');
        sliders.forEach(slider => {
            const sliderInstance = new HeroSlider(slider);
            slider.sliderInstance = sliderInstance;
        });
    }
    
    class HeroSlider {
        constructor(container) {
            this.container = container;
            this.slides = container.querySelectorAll('.hero-slide');
            this.controls = container.querySelector('.hero-slider-controls');
            this.indicators = container.querySelectorAll('.hero-slider-indicator');
            this.statusElement = container.querySelector('#slider-status');
            
            // Buttons
            this.playPauseBtn = container.querySelector('.hero-slider-playpause');
            this.prevBtn = container.querySelector('.hero-slider-prev');
            this.nextBtn = container.querySelector('.hero-slider-next');
            
            // Configuration
            this.autoplay = parseInt(container.dataset.autoplay) === 1;
            this.delay = parseInt(container.dataset.delay) || 5000;
            this.totalSlides = parseInt(container.dataset.total) || this.slides.length;
            
            // State
            this.currentSlide = 0;
            this.isPlaying = this.autoplay;
            this.autoplayTimer = null;
            this.isTransitioning = false;
            
            // Check for reduced motion preference
            this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            this.init();
        }
        
        init() {
            this.setupSlides();
            this.setupControls();
            this.setupIndicators();
            this.setupKeyboardNavigation();
            
            // Start autoplay if enabled
            if (this.isPlaying && !this.prefersReducedMotion) {
                this.startAutoplay();
            }
        }
        
        setupSlides() {
            this.slides.forEach((slide, index) => {
                if (index === 0) {
                    slide.classList.add('active');
                    slide.setAttribute('aria-current', 'true');
                    slide.removeAttribute('aria-hidden');
                } else {
                    slide.classList.remove('active');
                    slide.setAttribute('aria-hidden', 'true');
                    slide.removeAttribute('aria-current');
                }
            });
        }
        
        setupControls() {
            if (!this.controls) return;
            
            if (this.playPauseBtn) {
                this.playPauseBtn.addEventListener('click', () => this.togglePlayPause());
                this.updatePlayPauseButton();
            }
            
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.prevSlide());
            }
            
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.nextSlide());
            }
        }
        
        setupIndicators() {
            this.indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => this.goToSlide(index));
            });
        }
        
        setupKeyboardNavigation() {
            this.container.addEventListener('keydown', (e) => {
                if (this.isTransitioning) return;
                
                switch (e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.prevSlide();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        this.nextSlide();
                        break;
                    case ' ':
                        if (!e.target.matches('button, a')) {
                            e.preventDefault();
                            this.togglePlayPause();
                        }
                        break;
                }
            });
        }
        
        prevSlide() {
            if (this.isTransitioning) return;
            const prevIndex = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
            this.goToSlide(prevIndex);
        }
        
        nextSlide() {
            if (this.isTransitioning) return;
            const nextIndex = this.currentSlide === this.totalSlides - 1 ? 0 : this.currentSlide + 1;
            this.goToSlide(nextIndex);
        }
        
        goToSlide(index) {
            if (this.isTransitioning || index === this.currentSlide) return;
            
            this.isTransitioning = true;
            
            // Hide current slide
            this.slides[this.currentSlide].classList.remove('active');
            this.slides[this.currentSlide].setAttribute('aria-hidden', 'true');
            this.slides[this.currentSlide].removeAttribute('aria-current');
            
            // Show new slide
            this.slides[index].classList.add('active');
            this.slides[index].removeAttribute('aria-hidden');
            this.slides[index].setAttribute('aria-current', 'true');
            
            // Update current slide index
            this.currentSlide = index;
            
            // Update indicators
            this.updateIndicators();
            
            // Reset transition state
            setTimeout(() => {
                this.isTransitioning = false;
            }, 1000);
            
            // Restart autoplay
            if (this.isPlaying) {
                this.startAutoplay();
            }
        }
        
        updateIndicators() {
            this.indicators.forEach((indicator, index) => {
                if (index === this.currentSlide) {
                    indicator.classList.add('active');
                    indicator.setAttribute('aria-current', 'true');
                } else {
                    indicator.classList.remove('active');
                    indicator.removeAttribute('aria-current');
                }
            });
        }
        
        togglePlayPause() {
            if (this.isPlaying) {
                this.pauseAutoplay();
            } else {
                this.resumeAutoplay();
            }
        }
        
        startAutoplay() {
            if (this.prefersReducedMotion || this.totalSlides < 2) return;
            
            this.clearAutoplayTimer();
            this.autoplayTimer = setTimeout(() => {
                this.nextSlide();
            }, this.delay);
        }
        
        pauseAutoplay() {
            this.clearAutoplayTimer();
            this.isPlaying = false;
            this.updatePlayPauseButton();
        }
        
        resumeAutoplay() {
            this.isPlaying = true;
            this.startAutoplay();
            this.updatePlayPauseButton();
        }
        
        clearAutoplayTimer() {
            if (this.autoplayTimer) {
                clearTimeout(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
        
        updatePlayPauseButton() {
            if (!this.playPauseBtn) return;
            
            this.playPauseBtn.setAttribute('data-playing', this.isPlaying);
            
            const label = this.isPlaying ? 'Pause slideshow' : 'Play slideshow';
            this.playPauseBtn.setAttribute('aria-label', label);
        }
    }
    
    // Expose globally
    window.HeroSlider = HeroSlider;
    
})();
