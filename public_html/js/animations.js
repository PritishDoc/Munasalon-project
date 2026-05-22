/* ============================================
   LuxeGlow Salon – GSAP Animations
   js/animations.js  |  v2.0.1
   ============================================ */
'use strict';

(function () {
    // ── Wait for GSAP ────────────────────────
    function initGSAP() {
        if (typeof gsap === 'undefined') return;
        gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

        // ── Hero text reveal ─────────────────
        const heroTitle = document.querySelector('.hero-title');
        const heroSub   = document.querySelector('.hero-subtitle');
        const heroActions = document.querySelector('.hero-actions');
        const heroEye   = document.querySelector('.hero-eyebrow');

        if (heroTitle) {
            gsap.set([heroEye, heroTitle, heroSub, heroActions], { opacity: 0, y: 50 });
            const tl = gsap.timeline({ delay: 2 }); // after loading screen
            tl.to(heroEye,     { opacity: 1, y: 0, duration: .7, ease: 'power3.out' })
              .to(heroTitle,   { opacity: 1, y: 0, duration: .9, ease: 'power3.out' }, '-=.3')
              .to(heroSub,     { opacity: 1, y: 0, duration: .7, ease: 'power3.out' }, '-=.4')
              .to(heroActions, { opacity: 1, y: 0, duration: .6, ease: 'back.out(1.4)' }, '-=.3');
        }

        // ── Hero parallax ────────────────────
        gsap.utils.toArray('.hero-slide').forEach(slide => {
            gsap.to(slide, {
                backgroundPositionY: '30%',
                ease: 'none',
                scrollTrigger: { trigger: '.hero', scrub: true }
            });
        });

        // ── Section headings ─────────────────
        gsap.utils.toArray('.section-title').forEach(el => {
            gsap.from(el, {
                opacity: 0, y: 40, duration: 1, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 85%' }
            });
        });

        // ── Service cards stagger ─────────────
        ScrollTrigger.batch('.service-card', {
            onEnter: elements => gsap.from(elements, {
                opacity: 0, y: 50, stagger: .08, duration: .7, ease: 'power3.out'
            }),
            start: 'top 88%'
        });

        // ── Product cards stagger ─────────────
        ScrollTrigger.batch('.product-card', {
            onEnter: elements => gsap.from(elements, {
                opacity: 0, scale: .92, stagger: .07, duration: .6, ease: 'back.out(1.2)'
            }),
            start: 'top 88%'
        });

        // ── Stats counter trigger ─────────────
        document.querySelectorAll('[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count, 10);
            const suffix = el.dataset.suffix || '';
            ScrollTrigger.create({
                trigger: el,
                start: 'top 88%',
                once: true,
                onEnter: () => window.LuxeGlow.animateCounter(el, target, 2200, suffix)
            });
        });

        // ── Why cards ────────────────────────
        ScrollTrigger.batch('.why-card', {
            onEnter: elements => gsap.from(elements, {
                opacity: 0, y: 40, rotation: 2, stagger: .1, duration: .7, ease: 'back.out(1.3)'
            }),
            start: 'top 88%'
        });

        // ── Blog cards ───────────────────────
        ScrollTrigger.batch('.blog-card', {
            onEnter: elements => gsap.from(elements, {
                opacity: 0, y: 30, stagger: .1, duration: .6, ease: 'power3.out'
            }),
            start: 'top 88%'
        });

        // ── Image parallax inside sections ────
        gsap.utils.toArray('.parallax-img').forEach(img => {
            gsap.from(img, {
                yPercent: -15,
                ease: 'none',
                scrollTrigger: { trigger: img.closest('.parallax-container') || img, scrub: 1.2 }
            });
        });

        // ── CTA section decoration ────────────
        const ctaDeco = document.querySelectorAll('.cta-deco');
        ctaDeco.forEach((el, i) => {
            gsap.to(el, { rotate: i % 2 === 0 ? 360 : -360, duration: 20, ease: 'none', repeat: -1 });
        });

        // ── Gallery items ─────────────────────
        ScrollTrigger.batch('.gallery-item', {
            onEnter: elements => gsap.from(elements, {
                opacity: 0, scale: .88, stagger: .06, duration: .6, ease: 'power3.out'
            }),
            start: 'top 90%'
        });

        // ── Testimonial cards ─────────────────
        gsap.from('.testimonial-card', {
            opacity: 0, x: 60, stagger: .12, duration: .8, ease: 'power3.out',
            scrollTrigger: { trigger: '.testimonials-slider', start: 'top 85%' }
        });
    }

    // GSAP might be deferred — wait for it
    if (typeof gsap !== 'undefined') {
        initGSAP();
    } else {
        document.querySelectorAll('script[src*="gsap"]').forEach(s => s.addEventListener('load', initGSAP));
        window.addEventListener('load', initGSAP); // fallback
    }

    // ── Pure-CSS scroll reveal fallback ──────
    // (for environments without GSAP)
    window.LuxeGlow.initReveal();

})();
