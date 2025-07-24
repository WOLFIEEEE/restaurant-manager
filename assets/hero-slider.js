/**
 * Restaurant Manager - Hero Slider JavaScript
 * Fully accessible, WCAG-compliant image slider with smooth transitions
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
            this.transitionDuration = 800; // Match CSS transition duration

            // Check for reduced motion preference
            this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            this.init();
        }

        init() {
            if (this.slides.length === 0) return;

            this.preloadImages();
            this.setupSlides();
            this.setupControls();
            this.setupIndicators();
            this.setupKeyboardNavigation();

            // Remove loading state after initialization
            setTimeout(() => {
                this.container.setAttribute('data-loading', 'false');
            }, 100);

            // Start autoplay if enabled
            if (this.isPlaying && !this.prefersReducedMotion && this.totalSlides > 1) {
                this.startAutoplay();
            }
        }

        preloadImages() {
            this.slides.forEach((slide, index) => {
                const img = slide.querySelector('img');
                if (img && img.src) {
                    // Create a new image to preload
                    const preloadImg = new Image();
                    preloadImg.src = img.src;

                    // Add loading state management for first image
                    if (index === 0 && !img.complete) {
                        slide.style.opacity = '0';
                        preloadImg.onload = () => {
                            slide.style.opacity = '';
                        };
                        // Fallback timeout
                        setTimeout(() => {
                            slide.style.opacity = '';
                        }, 2000);
                    }
                }
            });
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
            if (this.isTransitioning || index === this.currentSlide || index >= this.totalSlides) return;

            this.isTransitioning = true;
            const currentSlide = this.slides[this.currentSlide];
            const nextSlide = this.slides[index];

            // Ensure next slide image is loaded before transition
            const nextImage = nextSlide.querySelector('img');
            if (nextImage && !nextImage.complete) {
                nextImage.onload = () => this.performTransition(currentSlide, nextSlide, index);
                // Fallback in case image fails to load
                setTimeout(() => {
                    if (this.isTransitioning) {
                        this.performTransition(currentSlide, nextSlide, index);
                    }
                }, 200);
            } else {
                // Use requestAnimationFrame for smoother transitions
                requestAnimationFrame(() => {
                    this.performTransition(currentSlide, nextSlide, index);
                });
            }
        }

        performTransition(currentSlide, nextSlide, index) {
            // Clear any existing transition classes
            this.slides.forEach(slide => {
                slide.classList.remove('transitioning-in', 'transitioning-out');
            });

            // Prepare next slide for transition
            nextSlide.classList.add('transitioning-in');
            nextSlide.removeAttribute('aria-hidden');
            nextSlide.setAttribute('aria-current', 'true');

            // Start transition out for current slide
            currentSlide.classList.add('transitioning-out');
            currentSlide.classList.remove('active');
            currentSlide.setAttribute('aria-hidden', 'true');
            currentSlide.removeAttribute('aria-current');

            // Force reflow to ensure classes are applied
            nextSlide.offsetHeight;

            // Update current slide index
            this.currentSlide = index;

            // Update indicators immediately for better UX
            this.updateIndicators();

            // Handle transition completion
            setTimeout(() => {
                // Clean up transition classes
                currentSlide.classList.remove('transitioning-out');
                nextSlide.classList.remove('transitioning-in');
                nextSlide.classList.add('active');

                // Reset transition state
                this.isTransitioning = false;

                // Update status for screen readers
                if (this.statusElement) {
                    this.statusElement.textContent = `Slide ${this.currentSlide + 1} of ${this.totalSlides}`;
                }

                // Restart autoplay
                if (this.isPlaying) {
                    this.startAutoplay();
                }
            }, this.transitionDuration);
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

    // Expose globally for debugging
    window.HeroSlider = HeroSlider;

})();