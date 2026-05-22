/* ============================================
   LuxeGlow Salon – Main Interactions
   js/main.js  |  v2.0.1
   ============================================ */
'use strict';

(function () {
    const { $$, showToast, initCountdown } = window.LuxeGlow;

    // ── Hero Slider ──────────────────────────
    function initHeroSlider() {
        const slides = $$('.hero-slide');
        const dots   = $$('.hero-dot');
        if (!slides.length) return;
        let current = 0, timer;
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
        timer = setInterval(next, 5500);
        dots.forEach((d, i) => d.addEventListener('click', () => { clearInterval(timer); goTo(i); timer = setInterval(next, 5500); }));

        // Swipe support
        let startX = 0;
        const heroEl = document.querySelector('.hero');
        heroEl?.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        heroEl?.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) { clearInterval(timer); goTo(current + (diff > 0 ? 1 : -1)); timer = setInterval(next, 5500); }
        }, { passive: true });
    }

    // ── Testimonials Slider ──────────────────
    function initTestimonialsSlider() {
        const track = document.querySelector('.testimonials-track');
        const items = $$('.testimonial-card', track?.parentElement);
        if (!track || !items.length) return;
        let cur = 0, autoTimer;
        const dots = $$('.slider-dot-sm');
        const perView = () => window.innerWidth < 600 ? 1 : window.innerWidth < 900 ? 2 : 3;

        function goTo(idx) {
            const pv = perView();
            const max = Math.max(0, items.length - pv);
            cur = Math.max(0, Math.min(idx, max));
            const pct = 100 / pv * cur;
            track.style.transform = `translateX(-${pct}%)`;
            dots.forEach((d, i) => d.classList.toggle('active', i === cur));
        }

        document.querySelector('.slider-btn.prev')?.addEventListener('click', () => { clearInterval(autoTimer); goTo(cur - 1); autoTimer = setInterval(() => goTo(cur + 1), 4000); });
        document.querySelector('.slider-btn.next')?.addEventListener('click', () => { clearInterval(autoTimer); goTo(cur + 1); autoTimer = setInterval(() => goTo(cur + 1), 4000); });
        dots.forEach((d, i) => d.addEventListener('click', () => { clearInterval(autoTimer); goTo(i); autoTimer = setInterval(() => goTo(cur + 1), 4000); }));
        autoTimer = setInterval(() => goTo(cur + 1), 4000);
        window.addEventListener('resize', window.LuxeGlow.debounce(() => goTo(cur), 200));
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
    });

})();
