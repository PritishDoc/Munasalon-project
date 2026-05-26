/* ============================================
   LuxeGlow Salon – Navbar & UI Shell JS
   js/navbar.js  |  v2.0.1
   ============================================ */
'use strict';

(function () {
    const { $, $$ } = window.LuxeGlow;

    // ── Loading Screen ──────────────────────
    const loadingScreen = document.getElementById('loading-screen');
    window.addEventListener('load', () => {
        setTimeout(() => {
            loadingScreen?.classList.add('hidden');
            document.body.classList.add('page-loaded');
        }, 1800);
    });

    // ── Navbar scroll state ─────────────────
    const navbar = document.getElementById('navbar');
    let lastScroll = 0;
    const onScroll = window.LuxeGlow.throttle(() => {
        const y = window.scrollY;
        navbar?.classList.toggle('scrolled', y > 60);
        // Hide/show on scroll direction
        if (y > 300) {
            navbar?.classList.toggle('nav-hidden', y > lastScroll + 10);
        } else {
            navbar?.classList.remove('nav-hidden');
        }
        lastScroll = y;
    }, 80);
    window.addEventListener('scroll', onScroll, { passive: true });

    // Add CSS for nav-hidden state
    const style = document.createElement('style');
    style.textContent = `.navbar.nav-hidden { transform: translateY(-100%); } .navbar { transition: transform .4s ease, height .4s ease, background .4s ease, box-shadow .4s ease; }`;
    document.head.appendChild(style);

    // ── Mobile Hamburger ────────────────────
    const hamburger  = document.getElementById('hamburger');
    const navLinks   = document.getElementById('nav-links');
    const navOverlay = document.getElementById('nav-overlay');

    function openMenu() {
        navLinks?.classList.add('open');
        navOverlay?.classList.add('active');
        hamburger?.classList.add('open');
        hamburger?.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        navLinks?.classList.remove('open');
        navOverlay?.classList.remove('active');
        hamburger?.classList.remove('open');
        hamburger?.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    hamburger?.addEventListener('click', () => {
        navLinks?.classList.contains('open') ? closeMenu() : openMenu();
    });
    navOverlay?.addEventListener('click', closeMenu);
    $$('.nav-link').forEach(link => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

    // ── Scroll to Top ───────────────────────
    const scrollBtn = document.getElementById('scroll-top');
    window.addEventListener('scroll', window.LuxeGlow.throttle(() => {
        scrollBtn?.classList.toggle('visible', window.scrollY > 400);
    }, 100), { passive: true });
    scrollBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // ── Cookie Consent ──────────────────────
    const cookieBanner = document.getElementById('cookie-banner');
    if (cookieBanner && !window.LuxeGlow.Cookies.get('luxe_cookies')) {
        setTimeout(() => cookieBanner.classList.add('visible'), 1500);
    }
    document.getElementById('cookie-accept')?.addEventListener('click', () => {
        window.LuxeGlow.Cookies.set('luxe_cookies', '1', 365);
        cookieBanner.classList.remove('visible');
        window.LuxeGlow.showToast('Preferences saved!', 'success');
    });
    document.getElementById('cookie-decline')?.addEventListener('click', () => {
        window.LuxeGlow.Cookies.set('luxe_cookies', '0', 30);
        cookieBanner.classList.remove('visible');
    });

    // ── Newsletter form (footer) ─────────────
    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterMsg  = document.getElementById('newsletter-msg');
    newsletterForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('newsletter-email')?.value.trim();
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            if (newsletterMsg) newsletterMsg.textContent = 'Please enter a valid email.';
            return;
        }
        // Simulate async subscription
        const btn = newsletterForm.querySelector('button');
        if (btn) btn.textContent = 'Subscribing…';
        setTimeout(() => {
            if (newsletterMsg) newsletterMsg.textContent = "🎉 You're subscribed! Check your inbox.";
            if (btn) btn.textContent = 'Subscribed ✓';
            btn?.setAttribute('disabled', 'true');
            document.getElementById('newsletter-email').value = '';
        }, 1200);
    });

    // ── Smooth scroll for anchor links ───────
    $$('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--navbar-height')) || 80;
            window.scrollTo({ top: target.offsetTop - offset, behavior: 'smooth' });
        });
    });

    // ── Active nav link on scroll ─────────────
    const sections = $$('section[id]');
    const navLinksAll = $$('.nav-link');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                navLinksAll.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
                });
            }
        });
    }, { rootMargin: '-40% 0px -40% 0px' });
    sections.forEach(s => observer.observe(s));

    // ── Ripple on all .btn elements ──────────
    $$('.btn, .btn-gold, .btn-book, .btn-outline').forEach(window.LuxeGlow.addRipple);

    // ── Fix bfcache (back/forward) style loss ──
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;

        // 0. FIRST: purge any orphaned .ripple spans that survived bfcache.
        //    This is the PRIMARY cause of the button shape collapse — a ripple
        //    span injected on click, frozen mid-animation at scale(4) inside the
        //    button, then restored by bfcache still at that bloated size.
        //    _purgeAllRipples() is defined in utils.js and also runs on pagehide,
        //    but we run it here too as an absolute safety net.
        document.querySelectorAll('.ripple').forEach(r => r.remove());

        // 1. Loading screen: ALWAYS force-hide immediately — no conditional.
        loadingScreen?.classList.add('hidden');
        document.body.classList.add('page-loaded');

        // 2. Body overflow + mobile menu: reset unconditionally.
        document.body.style.overflow = '';
        closeMenu();

        // 3. Navbar scroll state.
        const y = window.scrollY;
        navbar?.classList.toggle('scrolled', y > 60);
        navbar?.classList.remove('nav-hidden');
        lastScroll = y;

        // 4. Ripple: idempotent (data-ripple-bound guard in utils.js).
        $$('.btn, .btn-gold, .btn-book, .btn-outline').forEach(window.LuxeGlow.addRipple);

        // 5. Scroll reveal.
        window.LuxeGlow.initReveal();
    });

})();