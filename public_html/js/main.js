/* ============================================
   LuxeGlow Salon – Main Interactions (COMPLETE FIX)
   js/main.js  |  v3.0.0 (UPDATED)
   ============================================ */
'use strict';

(function () {
    const { $$, showToast, initCountdown } = window.LuxeGlow;

    // ── Hero Slider ──────────────────────────
    let heroSliderTimer = null;
    function initHeroSlider() {
        const slides = $$('.hero-slide');
        const dots   = $$('.hero-dot');
        if (!slides.length) return;

        if (heroSliderTimer) { clearInterval(heroSliderTimer); heroSliderTimer = null; }

        let current = 0;
        function goTo(idx) {
            slides[current].classList.remove('active');
            dots[current]?.classList.remove('active');
            current = (idx + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current]?.classList.add('active');
        }
        function next() { goTo(current + 1); }
        slides[0].classList.add('active');
        dots[0]?.classList.add('active');
        heroSliderTimer = setInterval(next, 5500);

        dots.forEach((d, i) => {
            if (d.dataset.sliderBound) return;
            d.dataset.sliderBound = '1';
            d.addEventListener('click', () => {
                clearInterval(heroSliderTimer);
                goTo(i);
                heroSliderTimer = setInterval(next, 5500);
            });
        });

        const heroEl = document.querySelector('.hero');
        if (heroEl && !heroEl.dataset.swipeBound) {
            heroEl.dataset.swipeBound = '1';
            let startX = 0;
            heroEl.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
            heroEl.addEventListener('touchend', e => {
                const diff = startX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) {
                    clearInterval(heroSliderTimer);
                    goTo(current + (diff > 0 ? 1 : -1));
                    heroSliderTimer = setInterval(next, 5500);
                }
            }, { passive: true });
        }
    }

    // ── Testimonials Slider ──────────────────
    let testimonialsTimer = null;
    function initTestimonialsSlider() {
        const track = document.querySelector('.testimonials-track');
        const items = $$('.testimonial-card', track?.parentElement);
        if (!track || !items.length) return;

        if (testimonialsTimer) { clearInterval(testimonialsTimer); testimonialsTimer = null; }

        let cur = 0;
        const dots = $$('.slider-dot-sm');
        const GAP = 24;
        const perView = () => window.innerWidth < 600 ? 1 : window.innerWidth < 900 ? 2 : 3;

        function goTo(idx) {
            const pv = perView();
            const max = Math.max(0, items.length - pv);
            cur = Math.max(0, Math.min(idx, max));
            const cardWidth = (track.parentElement.offsetWidth - GAP * (pv - 1)) / pv;
            const offset = cur * (cardWidth + GAP);
            track.style.transform = `translateX(-${offset}px)`;
            dots.forEach((d, i) => d.classList.toggle('active', i === cur));
        }

        const restart = () => { clearInterval(testimonialsTimer); testimonialsTimer = setInterval(() => goTo(cur + 1), 4000); };

        const prevBtn = document.querySelector('.slider-btn.prev');
        const nextBtn = document.querySelector('.slider-btn.next');
        if (prevBtn && !prevBtn.dataset.sliderBound) {
            prevBtn.dataset.sliderBound = '1';
            prevBtn.addEventListener('click', () => { goTo(cur - 1); restart(); });
        }
        if (nextBtn && !nextBtn.dataset.sliderBound) {
            nextBtn.dataset.sliderBound = '1';
            nextBtn.addEventListener('click', () => { goTo(cur + 1); restart(); });
        }
        dots.forEach((d, i) => {
            if (d.dataset.sliderBound) return;
            d.dataset.sliderBound = '1';
            d.addEventListener('click', () => { goTo(i); restart(); });
        });

        testimonialsTimer = setInterval(() => goTo(cur + 1), 4000);
        window.addEventListener('resize', window.LuxeGlow.debounce(() => goTo(cur), 200));
    }

    // ── Services See More/Show Less (Services.php) ──
    function initServicesSeeMore() {
        const seeMoreContainers = document.querySelectorAll('.services-see-more-container');
        
        seeMoreContainers.forEach(container => {
            const btn = container.querySelector('.services-see-more-btn');
            if (!btn) return;
            if (btn.dataset.initialized) return;
            btn.dataset.initialized = 'true';
            
            const category = btn.dataset.categorySeeMore;
            const panel = document.querySelector(`[data-category-panel="${category}"]`);
            if (!panel) return;
            
            const hiddenServices = panel.querySelectorAll('.service-card.service-hidden');
            let isExpanded = false;
            
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                this.classList.add('loading');
                this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
                this.disabled = true;
                
                setTimeout(() => {
                    if (!isExpanded) {
                        hiddenServices.forEach((item, index) => {
                            item.style.display = 'flex';
                            setTimeout(() => {
                                item.classList.add('service-show-animation');
                            }, index * 50);
                        });
                        
                        this.innerHTML = 'Show Less Services <i class="fas fa-arrow-up"></i>';
                        this.style.border = '1px solid var(--clr-gold)';
                        this.style.color = 'var(--clr-gold)';
                        isExpanded = true;
                    } else {
                        hiddenServices.forEach((item, index) => {
                            item.classList.remove('service-show-animation');
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 150);
                        });
                        
                        this.innerHTML = 'See More Services <i class="fas fa-arrow-down"></i>';
                        this.style.border = '1px solid #333';
                        this.style.color = 'var(--clr-white)';
                        isExpanded = false;
                    }
                    
                    this.classList.remove('loading');
                    this.disabled = false;
                }, 200);
            });
        });
    }

    // ── Guarantee See More/Show Less (Services.php) ──
    function initGuaranteeSeeMore() {
        const guaranteeBtn = document.getElementById('see-more-guarantee-btn');
        if (!guaranteeBtn) return;
        if (guaranteeBtn.dataset.initialized) return;
        guaranteeBtn.dataset.initialized = 'true';
        
        const hiddenGuarantees = document.querySelectorAll('.guarantee-card.guarantee-hidden');
        let isExpanded = false;
        
        guaranteeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            this.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    hiddenGuarantees.forEach((item, index) => {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('guarantee-show-animation');
                        }, index * 50);
                    });
                    
                    this.innerHTML = 'Show Less Benefits <i class="fas fa-arrow-up"></i>';
                    this.style.border = '1px solid var(--clr-gold)';
                    this.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    hiddenGuarantees.forEach((item, index) => {
                        item.classList.remove('guarantee-show-animation');
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    this.innerHTML = 'See More Benefits <i class="fas fa-arrow-down"></i>';
                    this.style.border = '1px solid #333';
                    this.style.color = 'var(--clr-white)';
                    isExpanded = false;
                }
                
                this.classList.remove('loading');
                this.disabled = false;
            }, 200);
        });
    }

    // ── Blog See More/Show Less (Blog.php) ──
    function initBlogSeeMore() {
        const blogBtn = document.getElementById('blog-see-more-btn');
        if (!blogBtn) return;
        if (blogBtn.dataset.initialized) return;
        blogBtn.dataset.initialized = 'true';
        
        const hiddenBlogs = document.querySelectorAll('.blog-card.blog-hidden');
        let isExpanded = false;
        
        blogBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            this.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    hiddenBlogs.forEach((item, index) => {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('blog-show-animation');
                        }, index * 50);
                    });
                    
                    this.innerHTML = 'Show Less Posts <i class="fas fa-arrow-up"></i>';
                    this.style.border = '1px solid var(--clr-gold)';
                    this.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    hiddenBlogs.forEach((item, index) => {
                        item.classList.remove('blog-show-animation');
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    this.innerHTML = 'See More Posts <i class="fas fa-arrow-down"></i>';
                    this.style.border = '1px solid #333';
                    this.style.color = 'var(--clr-white)';
                    isExpanded = false;
                }
                
                this.classList.remove('loading');
                this.disabled = false;
            }, 200);
        });
    }
    
    // ── Blog Filter Logic ──
    function initBlogFilter() {
        const filterTabs = document.querySelectorAll('#blog-tabs .tab-btn');
        if (!filterTabs.length) return;
        
        filterTabs.forEach(tab => {
            if (tab.dataset.filterBound) return;
            tab.dataset.filterBound = 'true';
            
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                const filter = tab.dataset.blogCat;
                const allBlogs = document.querySelectorAll('#blog-grid .blog-card');
                const seeMoreBtn = document.getElementById('blog-see-more-btn');
                const hiddenBlogs = document.querySelectorAll('.blog-card.blog-hidden');
                
                allBlogs.forEach(blog => {
                    const blogCat = blog.dataset.blogCat;
                    if (filter === 'all' || blogCat === filter) {
                        blog.style.display = 'block';
                    } else {
                        blog.style.display = 'none';
                    }
                });
                
                // Reset see more state when filter changes
                if (seeMoreBtn && seeMoreBtn.dataset.initialized) {
                    const isExpanded = seeMoreBtn.innerHTML.includes('Show Less');
                    if (isExpanded) {
                        hiddenBlogs.forEach(blog => {
                            blog.classList.remove('blog-show-animation');
                            blog.style.display = 'none';
                        });
                        seeMoreBtn.innerHTML = 'See More Posts <i class="fas fa-arrow-down"></i>';
                        seeMoreBtn.style.border = '1px solid #333';
                        seeMoreBtn.style.color = 'var(--clr-white)';
                    }
                }
            });
        });
    }

    // ── Gallery (Homepage) See More/Show Less ──
    function initGallerySeeMore() {
        const galleryBtn = document.getElementById('see-more-services-btn');
        if (galleryBtn) {
            initSeeMoreFunctionality(galleryBtn, 'service-card', 'service-hidden', 'service-show-animation', 'See More Services', 'Show Less');
        }
        
        const offersBtn = document.getElementById('see-more-offers-btn');
        if (offersBtn) {
            initSeeMoreFunctionality(offersBtn, 'offer-card', 'offer-hidden', 'offer-show-animation', 'See More Offers', 'Show Less');
        }
        
        const whyusBtn = document.getElementById('see-more-whyus-btn');
        if (whyusBtn) {
            initSeeMoreFunctionality(whyusBtn, 'why-card', 'whyus-hidden', 'whyus-show-animation', 'See More', 'Show Less');
        }
    }
    
    function initSeeMoreFunctionality(button, cardClass, hiddenClass, animationClass, expandedText, collapsedText) {
        if (button.dataset.initialized) return;
        button.dataset.initialized = 'true';
        
        const hiddenItems = document.querySelectorAll(`.${cardClass}.${hiddenClass}`);
        let isExpanded = false;
        const section = button.closest('section');
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            this.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    hiddenItems.forEach((item, index) => {
                        const displayStyle = window.getComputedStyle(item).display;
                        item.style.display = displayStyle === 'none' ? 'block' : 'flex';
                        setTimeout(() => {
                            item.classList.add(animationClass);
                        }, index * 50);
                    });
                    
                    this.innerHTML = `${collapsedText} <i class="fas fa-arrow-up"></i>`;
                    this.style.border = '1px solid var(--clr-gold)';
                    this.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    const visibleHiddenItems = document.querySelectorAll(`.${cardClass}.${hiddenClass}`);
                    visibleHiddenItems.forEach((item, index) => {
                        item.classList.remove(animationClass);
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    this.innerHTML = `${expandedText} <i class="fas fa-arrow-down"></i>`;
                    this.style.border = '1px solid #333';
                    this.style.color = 'var(--clr-white)';
                    isExpanded = false;
                    
                    if (section) {
                        setTimeout(() => {
                            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);
                    }
                }
                
                this.classList.remove('loading');
                this.disabled = false;
            }, 200);
        });
    }

    // ── Before & After See More (Gallery.php) ──
    function initBeforeAfterSeeMore() {
        const baBtn = document.getElementById('ba-see-more-btn');
        if (!baBtn) return;
        if (baBtn.dataset.initialized) return;
        baBtn.dataset.initialized = 'true';
        
        const hiddenBa = document.querySelectorAll('.ba-card.ba-hidden');
        let isExpanded = false;
        
        baBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            this.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    hiddenBa.forEach((item, index) => {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('ba-show-animation');
                        }, index * 50);
                    });
                    
                    this.innerHTML = 'Show Less Transformations <i class="fas fa-arrow-up"></i>';
                    this.style.border = '1px solid var(--clr-gold)';
                    this.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    hiddenBa.forEach((item, index) => {
                        item.classList.remove('ba-show-animation');
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    this.innerHTML = 'See More Transformations <i class="fas fa-arrow-down"></i>';
                    this.style.border = '1px solid #333';
                    this.style.color = 'var(--clr-white)';
                    isExpanded = false;
                }
                
                this.classList.remove('loading');
                this.disabled = false;
            }, 200);
        });
    }

    // ── Gallery Filter (Gallery.php) ──
    function initGalleryPageFeatures() {
        const filterBtns = document.querySelectorAll('[data-gallery-filter]');
        const galleryItems = document.querySelectorAll('#gallery-grid .gallery-item');
        const seeMoreBtn = document.getElementById('gallery-see-more-btn');
        
        if (!filterBtns.length) return;
        
        let currentFilter = 'all';
        let isExpanded = false;
        const INITIAL_LIMIT = 6;
        
        function updateGalleryVisibility() {
            let filteredItems = [];
            
            galleryItems.forEach(item => {
                const cat = item.dataset.cat;
                const matchesFilter = currentFilter === 'all' || cat === currentFilter;
                
                if (matchesFilter) {
                    filteredItems.push(item);
                } else {
                    item.style.display = 'none';
                }
            });
            
            const limit = isExpanded ? filteredItems.length : INITIAL_LIMIT;
            
            filteredItems.forEach((item, idx) => {
                if (idx < limit) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (seeMoreBtn) {
                const container = seeMoreBtn.closest('.gallery-see-more-container');
                if (container) {
                    container.style.display = filteredItems.length > INITIAL_LIMIT ? 'flex' : 'none';
                }
            }
        }
        
        filterBtns.forEach(btn => {
            if (btn.dataset.filterBound) return;
            btn.dataset.filterBound = 'true';
            
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.galleryFilter;
                isExpanded = false;
                if (seeMoreBtn) {
                    seeMoreBtn.innerHTML = 'See More <i class="fas fa-arrow-down"></i>';
                    seeMoreBtn.style.border = '1px solid #333';
                    seeMoreBtn.style.color = 'var(--clr-white)';
                }
                updateGalleryVisibility();
            });
        });
        
        if (seeMoreBtn && !seeMoreBtn.dataset.galleryBound) {
            seeMoreBtn.dataset.galleryBound = 'true';
            seeMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('loading');
                this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
                this.disabled = true;
                setTimeout(() => {
                    if (!isExpanded) {
                        isExpanded = true;
                        this.innerHTML = 'Show Less <i class="fas fa-arrow-up"></i>';
                        this.style.border = '1px solid var(--clr-gold)';
                        this.style.color = 'var(--clr-gold)';
                    } else {
                        isExpanded = false;
                        this.innerHTML = 'See More <i class="fas fa-arrow-down"></i>';
                        this.style.border = '1px solid #333';
                        this.style.color = 'var(--clr-white)';
                    }
                    updateGalleryVisibility();
                    this.classList.remove('loading');
                    this.disabled = false;
                }, 200);
            });
        }
        
        updateGalleryVisibility();
    }

    // ── Team See More (About.php) ──
    function initTeamSeeMore() {
        const teamBtn = document.getElementById('see-more-team-btn');
        if (!teamBtn) return;
        if (teamBtn.dataset.initialized) return;
        teamBtn.dataset.initialized = 'true';
        
        const hiddenTeam = document.querySelectorAll('.team-card.team-hidden');
        let isExpanded = false;
        const section = teamBtn.closest('section');
        
        teamBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            this.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    hiddenTeam.forEach((item, index) => {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('team-show-animation');
                        }, index * 50);
                    });
                    
                    this.innerHTML = 'Show Less Team <i class="fas fa-arrow-up"></i>';
                    this.style.border = '1px solid var(--clr-gold)';
                    this.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    const visibleHidden = document.querySelectorAll('.team-card.team-hidden');
                    visibleHidden.forEach((item, index) => {
                        item.classList.remove('team-show-animation');
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    this.innerHTML = 'See More Team <i class="fas fa-arrow-down"></i>';
                    this.style.border = '1px solid #333';
                    this.style.color = 'var(--clr-white)';
                    isExpanded = false;
                    
                    if (section) {
                        setTimeout(() => {
                            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);
                    }
                }
                
                this.classList.remove('loading');
                this.disabled = false;
            }, 200);
        });
    }

    // ── Products Filter & See More (Products.php) ──
    function initProducts() {
        const searchInput = document.getElementById('product-search');
        const productsGrid = document.getElementById('products-grid');
        
        if (!productsGrid) return;
        
        const allProducts = Array.from(document.querySelectorAll('.product-card'));
        let currentFilter = 'all';
        let currentSearchTerm = '';
        let isExpanded = false;
        const INITIAL_LIMIT = 8;
        
        const filterTabs = document.querySelectorAll('[data-products-filter]');
        const seeMoreBtn = document.getElementById('products-see-more-btn');
        
        function updateProductsVisibility() {
            let visibleProducts = allProducts.filter(product => {
                const category = product.dataset.category;
                const productName = (product.dataset.productName || product.querySelector('h3')?.textContent || '').toLowerCase();
                
                const matchesCategory = currentFilter === 'all' || category === currentFilter;
                const matchesSearch = !currentSearchTerm || productName.includes(currentSearchTerm);
                
                return matchesCategory && matchesSearch;
            });
            
            const totalMatching = visibleProducts.length;
            
            if (!isExpanded && totalMatching > INITIAL_LIMIT) {
                visibleProducts = visibleProducts.slice(0, INITIAL_LIMIT);
            }
            
            const visibleSet = new Set(visibleProducts);
            
            allProducts.forEach(product => {
                if (visibleSet.has(product)) {
                    product.style.display = '';
                    if (product.classList.contains('product-hidden')) {
                        product.classList.remove('product-hidden');
                        product.classList.add('product-show-animation');
                        setTimeout(() => {
                            product.classList.remove('product-show-animation');
                        }, 500);
                    }
                } else {
                    product.style.display = 'none';
                    product.classList.remove('product-show-animation');
                }
            });
            
            if (seeMoreBtn) {
                const seeMoreContainer = seeMoreBtn.closest('.products-see-more-container');
                const hasHiddenItems = totalMatching > INITIAL_LIMIT;
                
                if (seeMoreContainer) {
                    seeMoreContainer.style.display = hasHiddenItems ? 'flex' : 'none';
                }
                
                if (!hasHiddenItems && isExpanded) {
                    isExpanded = false;
                    seeMoreBtn.innerHTML = 'See More Products <i class="fas fa-arrow-down"></i>';
                    seeMoreBtn.style.border = '1px solid #333';
                    seeMoreBtn.style.color = 'var(--clr-white)';
                }
            }
        }
        
        if (filterTabs.length) {
            filterTabs.forEach(tab => {
                if (tab.dataset.filterBound) return;
                tab.dataset.filterBound = 'true';
                
                tab.addEventListener('click', () => {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentFilter = tab.dataset.productsFilter;
                    if (seeMoreBtn && isExpanded) {
                        isExpanded = false;
                        seeMoreBtn.innerHTML = 'See More Products <i class="fas fa-arrow-down"></i>';
                        seeMoreBtn.style.border = '1px solid #333';
                        seeMoreBtn.style.color = 'var(--clr-white)';
                    }
                    updateProductsVisibility();
                });
            });
        }
        
        if (searchInput) {
            if (searchInput.dataset.searchBound) return;
            searchInput.dataset.searchBound = 'true';
            
            searchInput.addEventListener('input', window.LuxeGlow.debounce(() => {
                currentSearchTerm = searchInput.value.toLowerCase().trim();
                if (seeMoreBtn && isExpanded) {
                    isExpanded = false;
                    seeMoreBtn.innerHTML = 'See More Products <i class="fas fa-arrow-down"></i>';
                    seeMoreBtn.style.border = '1px solid #333';
                    seeMoreBtn.style.color = 'var(--clr-white)';
                }
                updateProductsVisibility();
            }, 300));
        }
        
        if (seeMoreBtn && !seeMoreBtn.dataset.productsBound) {
            seeMoreBtn.dataset.productsBound = 'true';
            
            seeMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('loading');
                this.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
                this.disabled = true;
                
                setTimeout(() => {
                    if (!isExpanded) {
                        isExpanded = true;
                        this.innerHTML = 'Show Less Products <i class="fas fa-arrow-up"></i>';
                        this.style.border = '1px solid var(--clr-gold)';
                        this.style.color = 'var(--clr-gold)';
                    } else {
                        isExpanded = false;
                        this.innerHTML = 'See More Products <i class="fas fa-arrow-down"></i>';
                        this.style.border = '1px solid #333';
                        this.style.color = 'var(--clr-white)';
                    }
                    updateProductsVisibility();
                    this.classList.remove('loading');
                    this.disabled = false;
                }, 200);
            });
        }
        
        updateProductsVisibility();
    }

    // ── FAQ Accordion ─────────────────────────
    function initFAQ() {
        $$('.faq-item').forEach(item => {
            item.querySelector('.faq-question')?.addEventListener('click', () => {
                const isOpen = item.classList.contains('open');
                $$('.faq-item.open').forEach(o => {
                    o.classList.remove('open');
                    o.querySelector('.faq-answer').style.maxHeight = null;
                });
                if (!isOpen) {
                    item.classList.add('open');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });
    }

    // ── Service Category Tabs ──
    function initServiceTabs() {
        const tabsContainer = document.getElementById('service-tabs');
        if (!tabsContainer) return;
        
        tabsContainer.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#service-tabs .tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const cat = btn.dataset.category;
                document.querySelectorAll('[data-category-panel]').forEach(p => {
                    p.style.display = p.dataset.categoryPanel === cat ? 'block' : 'none';
                });
            });
        });
    }

    // ── Init all ─────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        initHeroSlider();
        initTestimonialsSlider();
        initServiceTabs();
        initGallerySeeMore();
        initServicesSeeMore();
        initGuaranteeSeeMore();
        initBlogSeeMore();
        initBlogFilter();
        initBeforeAfterSeeMore();
        initGalleryPageFeatures();
        initTeamSeeMore();
        initProducts();
        initFAQ();
    });

    // ── Fix bfcache ──────────────────────────
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;

        if (typeof gsap !== 'undefined' && document.querySelector('.hero-title')) {
            const heroEye     = document.querySelector('.hero-eyebrow');
            const heroTitle   = document.querySelector('.hero-title');
            const heroSub     = document.querySelector('.hero-subtitle');
            const heroActions = document.querySelector('.hero-actions');
            gsap.set([heroEye, heroTitle, heroSub, heroActions], { clearProps: 'all' });
            gsap.set([heroEye, heroTitle, heroSub, heroActions], { opacity: 0, y: 50 });
            gsap.timeline()
                .to(heroEye,     { opacity: 1, y: 0, duration: .7, ease: 'power3.out' })
                .to(heroTitle,   { opacity: 1, y: 0, duration: .9, ease: 'power3.out' }, '-=.3')
                .to(heroSub,     { opacity: 1, y: 0, duration: .7, ease: 'power3.out' }, '-=.4')
                .to(heroActions, { opacity: 1, y: 0, duration: .6, ease: 'back.out(1.4)' }, '-=.3');
        }

        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
        }

        if (heroSliderTimer)      { clearInterval(heroSliderTimer);      heroSliderTimer = null; }
        if (testimonialsTimer)    { clearInterval(testimonialsTimer);    testimonialsTimer = null; }
        initHeroSlider();
        initTestimonialsSlider();

        $$('[data-countdown]').forEach(el => {
            if (el._cdTimer) { clearInterval(el._cdTimer); el._cdTimer = null; }
            initCountdown(el.dataset.countdown, el);
        });
    });

})();