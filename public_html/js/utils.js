/* ============================================
   LuxeGlow Salon – Utility Helpers
   js/utils.js  |  v2.0.2
   ============================================ */
'use strict';

// ── DOM helpers ──────────────────────────────
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

// ── Debounce ─────────────────────────────────
function debounce(fn, ms = 150) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

// ── Throttle ─────────────────────────────────
function throttle(fn, ms = 100) {
    let last = 0;
    return (...args) => { const now = Date.now(); if (now - last >= ms) { last = now; fn(...args); } };
}

// ── Toast Notification ────────────────────────
function showToast(msg, type = 'info', duration = 3500) {
    let toast = document.getElementById('toast-el');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-el';
        toast.className = 'toast';
        toast.innerHTML = '<i class="fas fa-sparkles toast-icon"></i><span id="toast-msg"></span>';
        document.body.appendChild(toast);
    }
    const iconMap = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
    toast.querySelector('.toast-icon').className = `fas ${iconMap[type] || iconMap.info} toast-icon`;
    document.getElementById('toast-msg').textContent = msg;
    toast.className = `toast ${type}`;
    requestAnimationFrame(() => toast.classList.add('show'));
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), duration);
}

// ── Ripple on click ───────────────────────────
// Orphaned ripple fix: when the user clicks a button and the page
// navigates immediately, the .6s ripple animation never completes,
// animationend never fires, and the ripple <span> stays in the DOM.
// bfcache freezes that bloated span inside the button — on restore
// it renders at scale(4), completely destroying the button shape.
//
// Two-pronged fix:
//   1. pagehide: remove ALL ripple spans before the page enters bfcache.
//   2. pageshow: remove any that somehow survived, then force-repaint.
//   3. addRipple guard: never stack duplicate listeners (data-ripple-bound).
//   4. Added CSS containment to prevent ripple from affecting button size
function _purgeAllRipples() {
    document.querySelectorAll('.ripple').forEach(r => r.remove());
}
window.addEventListener('pagehide', _purgeAllRipples);
window.addEventListener('pageshow', _purgeAllRipples);

function addRipple(btn) {
    if (btn.dataset.rippleBound) return; // never stack listeners
    btn.dataset.rippleBound = '1';
    
    // Ensure button has proper overflow and position for ripple containment
    if (getComputedStyle(btn).position === 'static') {
        btn.style.position = 'relative';
    }
    if (getComputedStyle(btn).overflow !== 'hidden') {
        btn.style.overflow = 'hidden';
    }
    
    btn.addEventListener('click', (e) => {
        // Remove any leftover ripple spans on this button first
        btn.querySelectorAll('.ripple').forEach(r => r.remove());
        
        const rect = btn.getBoundingClientRect();
        // Use the smaller dimension to prevent oversized ripples
        const size = Math.min(rect.width, rect.height) * 1.5;
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        // Critical: set max-width/height to prevent button expansion
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            pointer-events: none;
            transform: scale(0);
            animation: rippleEffect 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            z-index: 1;
            will-change: transform, opacity;
        `;
        
        btn.appendChild(ripple);
        
        // Primary cleanup: animationend
        ripple.addEventListener('animationend', () => {
            if (ripple.parentNode) ripple.remove();
        }, { once: true });
        
        // Safety net: force-remove after animation duration + buffer
        setTimeout(() => {
            if (ripple.parentNode) ripple.remove();
        }, 600);
    });
}

// Add global style for ripple animation if not already present
(function addRippleAnimationStyle() {
    if (!document.querySelector('#ripple-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-style';
        style.textContent = `
            @keyframes rippleEffect {
                0% {
                    transform: scale(0);
                    opacity: 0.6;
                }
                100% {
                    transform: scale(1);
                    opacity: 0;
                }
            }
            .btn, .btn-gold, .btn-book, .btn-outline {
                position: relative;
                overflow: hidden;
                isolation: isolate;
            }
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.4);
                pointer-events: none;
                transform: scale(0);
                animation: rippleEffect 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                z-index: 1;
            }
        `;
        document.head.appendChild(style);
    }
})();

// ── Animated counter ──────────────────────────
function animateCounter(el, target, duration = 2000, suffix = '') {
    const start = performance.now();
    const update = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 4); // easeOutQuart
        const val = Math.round(ease * target);
        el.textContent = val.toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
}

// ── Countdown Timer ───────────────────────────
// Stores the interval ID on el._cdTimer so callers can clear it
// before re-initialising (critical for bfcache restore safety).
function initCountdown(endDate, el) {
    if (el._cdTimer) { clearInterval(el._cdTimer); el._cdTimer = null; }
    const update = () => {
        const diff = new Date(endDate) - new Date();
        if (diff <= 0) { el.textContent = 'Offer Ended'; clearInterval(el._cdTimer); return; }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const fmt = (n) => String(n).padStart(2, '0');
        el.innerHTML = `
            <div class="countdown-unit"><span class="countdown-num">${fmt(d)}</span><span class="countdown-lbl">Days</span></div>
            <div class="countdown-unit"><span class="countdown-num">${fmt(h)}</span><span class="countdown-lbl">Hrs</span></div>
            <div class="countdown-unit"><span class="countdown-num">${fmt(m)}</span><span class="countdown-lbl">Min</span></div>
            <div class="countdown-unit"><span class="countdown-num">${fmt(s)}</span><span class="countdown-lbl">Sec</span></div>`;
    };
    update();
    el._cdTimer = setInterval(update, 1000);
    return el._cdTimer;
}

// ── Format currency ───────────────────────────
const fmtINR = (n) => '₹' + Number(n).toLocaleString('en-IN');

// ── Cookie helpers ────────────────────────────
const Cookies = {
    set(name, val, days = 365) {
        const d = new Date(); d.setTime(d.getTime() + days * 864e5);
        document.cookie = `${name}=${val};expires=${d.toUTCString()};path=/;SameSite=Lax`;
    },
    get(name) {
        return document.cookie.split('; ').find(r => r.startsWith(name + '='))?.split('=')[1] ?? null;
    },
    remove(name) { document.cookie = `${name}=;expires=Thu,01 Jan 1970 00:00:00 GMT;path=/`; }
};

// ── Scroll Reveal Observer ────────────────────
function initReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    $$('[data-reveal], [data-stagger]').forEach(el => observer.observe(el));
}

// ── Lightbox ──────────────────────────────────
class Lightbox {
    constructor() {
        this.el    = document.getElementById('lightbox');
        this.img   = this.el?.querySelector('.lightbox-img');
        this.items = [];
        this.idx   = 0;
        if (!this.el) return;
        this.el.querySelector('.lightbox-close').addEventListener('click', () => this.close());
        this.el.querySelector('.lightbox-prev')?.addEventListener('click', () => this.prev());
        this.el.querySelector('.lightbox-next')?.addEventListener('click', () => this.next());
        this.el.addEventListener('click', e => { if (e.target === this.el) this.close(); });
        document.addEventListener('keydown', e => {
            if (!this.el.classList.contains('active')) return;
            if (e.key === 'Escape') this.close();
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });
    }
    open(items, idx = 0) {
        this.items = items; this.idx = idx; this.render();
        this.el.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    close() { this.el.classList.remove('active'); document.body.style.overflow = ''; }
    prev() { this.idx = (this.idx - 1 + this.items.length) % this.items.length; this.render(); }
    next() { this.idx = (this.idx + 1) % this.items.length; this.render(); }
    render() { if (this.img) this.img.src = this.items[this.idx]; }
}

// ── Export to global scope ────────────────────
window.LuxeGlow = window.LuxeGlow || {};
Object.assign(window.LuxeGlow, { $, $$, debounce, throttle, showToast, addRipple, animateCounter, initCountdown, fmtINR, Cookies, initReveal, Lightbox });