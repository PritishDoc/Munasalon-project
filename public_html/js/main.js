/* ============================================
   LuxeGlow Salon – Main Interactions
   js/main.js  |  v2.0.1
   ============================================ */
'use strict';

(function () {
    const { $$, showToast, initCountdown } = window.LuxeGlow;

    // ── Hero Slider ──────────────────────────
    let heroSliderTimer = null; // module-level so pageshow can clear it
    function initHeroSlider() {
        const slides = $$('.hero-slide');
        const dots   = $$('.hero-dot');
        if (!slides.length) return;

        // Clear any running timer from a previous init (bfcache restore)
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

        // Guard: attach dot listeners only once
        dots.forEach((d, i) => {
            if (d.dataset.sliderBound) return;
            d.dataset.sliderBound = '1';
            d.addEventListener('click', () => {
                clearInterval(heroSliderTimer);
                goTo(i);
                heroSliderTimer = setInterval(next, 5500);
            });
        });

        // Swipe: attach only once
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
    let testimonialsTimer = null; // module-level so pageshow can clear it
    function initTestimonialsSlider() {
        const track = document.querySelector('.testimonials-track');
        const items = $$('.testimonial-card', track?.parentElement);
        if (!track || !items.length) return;

        if (testimonialsTimer) { clearInterval(testimonialsTimer); testimonialsTimer = null; }

        let cur = 0;
        const dots = $$('.slider-dot-sm');
        const GAP = 24; // 1.5rem = 24px — must match the CSS gap on .testimonials-track
        const perView = () => window.innerWidth < 600 ? 1 : window.innerWidth < 900 ? 2 : 3;

        function goTo(idx) {
            const pv = perView();
            const max = Math.max(0, items.length - pv);
            cur = Math.max(0, Math.min(idx, max));
            // FIX: translate by card width + gap, not by percentage
            const cardWidth = (track.parentElement.offsetWidth - GAP * (pv - 1)) / pv;
            const offset = cur * (cardWidth + GAP);
            track.style.transform = `translateX(-${offset}px)`;
            dots.forEach((d, i) => d.classList.toggle('active', i === cur));
        }

        const restart = () => { clearInterval(testimonialsTimer); testimonialsTimer = setInterval(() => goTo(cur + 1), 4000); };

        // Guard: attach prev/next/dot listeners only once
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

    // ── See More Services Button ──────────────────
    function initSeeMoreServices() {
        const seeMoreBtn = document.getElementById('see-more-services-btn');
        if (!seeMoreBtn) return;
        
        const hiddenServices = document.querySelectorAll('.service-card.service-hidden');
        let isExpanded = false;
        
        seeMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading state
            seeMoreBtn.classList.add('loading');
            seeMoreBtn.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            seeMoreBtn.disabled = true;
            
            // Simulate a slight delay for smooth animation (micro-interaction)
            setTimeout(() => {
                if (!isExpanded) {
                    // Show hidden services with staggered animation
                    hiddenServices.forEach((service, index) => {
                        service.style.display = 'flex'; // Use flex to maintain card layout
                        // Add animation class with delay
                        setTimeout(() => {
                            service.classList.add('service-show-animation');
                        }, index * 50);
                    });
                    
                    // Update button text and state
                    seeMoreBtn.innerHTML = 'Show Less <i class="fas fa-arrow-up"></i>';
                    seeMoreBtn.style.border = '1px solid var(--clr-gold)';
                    seeMoreBtn.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    // Hide services with reverse animation
                    const visibleHiddenServices = document.querySelectorAll('.service-card.service-hidden');
                    visibleHiddenServices.forEach((service, index) => {
                        service.classList.remove('service-show-animation');
                        setTimeout(() => {
                            service.style.display = 'none';
                        }, 150);
                    });
                    
                    // Update button text and state
                    seeMoreBtn.innerHTML = 'See More Services <i class="fas fa-arrow-down"></i>';
                    seeMoreBtn.style.border = '1px solid #333';
                    seeMoreBtn.style.color = 'var(--clr-white)';
                    isExpanded = false;
                }
                
                // Remove loading state
                seeMoreBtn.classList.remove('loading');
                seeMoreBtn.innerHTML = isExpanded ? 'Show Less <i class="fas fa-arrow-up"></i>' : 'See More Services <i class="fas fa-arrow-down"></i>';
                seeMoreBtn.disabled = false;
                
                // Smooth scroll to see the newly revealed services if expanding
                if (isExpanded && hiddenServices.length > 0) {
                    const firstRevealedService = hiddenServices[0];
                    const offset = 100; // Offset for navbar
                    const elementPosition = firstRevealedService.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - offset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }, 200);
        });
    }

    // ── Tabs (services/products/gallery) ─────
    function initTabs() {
        $$('[data-tabs]').forEach(container => {
            const tabs   = $$('.tab-btn', container);
            const panels = $$('.tab-panel', container);
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;
                    tabs.forEach(t => t.classList.toggle('active', t === tab));
                    panels.forEach(p => p.classList.toggle('active', p.dataset.panel === target));
                });
            });
            // Activate first
            tabs[0]?.classList.add('active');
            panels[0]?.classList.add('active');
        });
    }

    // ── Gallery Filter + Lightbox ─────────────
    function initGallery() {
        const filterBtns = $$('[data-filter]');
        const items = $$('.gallery-item[data-cat]');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const cat = btn.dataset.filter;
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                items.forEach(item => {
                    const show = cat === 'all' || item.dataset.cat === cat;
                    item.style.opacity = show ? '1' : '0';
                    item.style.pointerEvents = show ? '' : 'none';
                });
            });
        });

        // Lightbox
        const lightboxEl = document.getElementById('lightbox');
        if (!lightboxEl) return;
        const lb = new window.LuxeGlow.Lightbox();
        const imgSrcs = [...items].map(i => i.querySelector('img')?.src).filter(Boolean);
        items.forEach((item, idx) => {
            item.addEventListener('click', () => lb.open(imgSrcs, idx));
        });
    }

    // ── Products filter ───────────────────────
    function initProducts() {
        const searchInput = document.getElementById('product-search');
        const cards = $$('.product-card[data-cat]');

        function filterProducts() {
            const q = searchInput?.value.toLowerCase() ?? '';
            const activeTab = document.querySelector('[data-tabs] .tab-btn.active')?.dataset.tab ?? 'all';
            cards.forEach(card => {
                const matchCat = activeTab === 'all' || card.dataset.cat === activeTab;
                const matchQ   = !q || card.dataset.name?.toLowerCase().includes(q);
                card.style.display = matchCat && matchQ ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', window.LuxeGlow.debounce(filterProducts, 200));
        document.querySelectorAll('[data-tabs] .tab-btn').forEach(btn => btn.addEventListener('click', filterProducts));

        // Add to cart buttons
        $$('.product-add-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.closest('.product-card')?.dataset.name ?? 'Product';
                showToast(`${name} added to cart!`, 'success');
                btn.textContent = 'Added ✓';
                setTimeout(() => btn.textContent = 'Add to Cart', 2000);
            });
        });

        // Wishlist
        $$('.product-action-btn[data-action="wishlist"]').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.classList.toggle('active');
                const icon = btn.querySelector('i');
                if (btn.classList.contains('active')) {
                    icon.className = 'fas fa-heart';
                    btn.style.background = 'rgba(212,112,138,.3)';
                    showToast('Added to wishlist!', 'info');
                } else {
                    icon.className = 'far fa-heart';
                    btn.style.background = '';
                }
            });
        });
    }

    // ── FAQ Accordion ──────────────────────────
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

    // ── Booking form multi-step ────────────────
    function initBooking() {
        const form  = document.getElementById('booking-form');
        if (!form) return;
        let step = 1;
        const totalSteps = form.querySelectorAll('.booking-step-panel').length;

        function showStep(n) {
            form.querySelectorAll('.booking-step-panel').forEach((p, i) => {
                p.style.display = i + 1 === n ? '' : 'none';
            });
            form.querySelectorAll('.booking-step').forEach((s, i) => {
                s.classList.toggle('active', i + 1 === n);
                s.classList.toggle('done', i + 1 < n);
            });
        }

        form.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', () => { if (step < totalSteps) showStep(++step); });
        });
        form.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', () => { if (step > 1) showStep(--step); });
        });
        showStep(1);

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            showToast("Appointment booked! We'll contact you shortly.", 'success', 5000);
            document.getElementById('booking-confirm-popup')?.classList.add('active');
        });
    }

    // ── Contact Form ──────────────────────────
    function initContact() {
        const form = document.getElementById('contact-form');
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('[type=submit]');
            const orig = btn.textContent;
            btn.textContent = 'Sending…'; btn.disabled = true;
            // Simulate send (replace with real PHP endpoint)
            await new Promise(r => setTimeout(r, 1500));
            showToast("Message sent! We'll reply within 24 hrs.", 'success', 5000);
            btn.textContent = orig; btn.disabled = false;
            form.reset();
        });
    }

    // ── Countdown timers ──────────────────────
    $$('[data-countdown]').forEach(el => {
        initCountdown(el.dataset.countdown, el);
    });

    // ── Init all ──────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        initHeroSlider();
        initTestimonialsSlider();
        initTabs();
        initGallery();
        initProducts();
        initFAQ();
        initBooking();
        initContact();
        initSeeMoreServices(); // Add this line
    });

    // ── Fix bfcache (back/forward) style loss ──
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;

        // 1. Hero GSAP: clear inline styles set by GSAP then re-run the reveal.
        //    Without clearProps the elements stay at opacity:0/y:50 forever.
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

        // 2. ScrollTrigger: refresh positions after bfcache scroll-position restore.
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
        }

        // 3. Sliders: clear stale intervals then restart with clean state.
        //    The init functions themselves guard against duplicate DOM listeners.
        if (heroSliderTimer)      { clearInterval(heroSliderTimer);      heroSliderTimer = null; }
        if (testimonialsTimer)    { clearInterval(testimonialsTimer);    testimonialsTimer = null; }
        initHeroSlider();
        initTestimonialsSlider();

        // 4. Countdown: el._cdTimer was stored by initCountdown; clear before re-init
        //    to prevent multiple intervals ticking on the same element.
        $$('[data-countdown]').forEach(el => {
            if (el._cdTimer) { clearInterval(el._cdTimer); el._cdTimer = null; }
            initCountdown(el.dataset.countdown, el);
        });

        // NOTE: tabs, gallery, products, FAQ, booking, contact do NOT need
        // re-initialising — their DOM listeners are guarded and survive bfcache.
    });

})();