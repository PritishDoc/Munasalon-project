/* ============================================
   LuxeGlow Salon – Utility Helpers
   js/utils.js  |  v2.0.1
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
function addRipple(btn) {
    btn.addEventListener('click', (e) => {
        const rect = btn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.cssText = `width:${size}px;height:${size}px;left:${x}px;top:${y}px`;
        btn.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
    });
}

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
function initCountdown(endDate, el) {
    const update = () => {
        const diff = new Date(endDate) - new Date();
        if (diff <= 0) { el.textContent = 'Offer Ended'; return; }
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
    return setInterval(update, 1000);
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
