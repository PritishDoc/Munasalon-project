<?php
// php/header.php – Shared site header + navbar
// Usage: include __DIR__ . '/php/header.php';
// Before including, set $page_title, $page_desc, $page_keywords, $active_page
if (!defined('SITE_NAME')) { require_once __DIR__ . '/config.php'; }
$active_page = $active_page ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php page_meta($page_title ?? '', $page_desc ?? '', $page_keywords ?? ''); ?>

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc ?? '') ?>">
    <meta property="og:image" content="<?= SITE_URL ?>/images/og-cover.jpg">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('/images/favicon.svg') ?>">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= asset_url('/css/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/animations.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/sections.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('/css/footer.css') ?>">
    <?php if (!empty($extra_css)): foreach ($extra_css as $css): ?>
    <link rel="stylesheet" href="<?= asset_url($css) ?>">
    <?php endforeach; endif; ?>

    <!-- GSAP (no defer – must be available before animations.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>

    <style>
        /* Critical inline – loading screen */
        #loading-screen {
            position: fixed; inset: 0; z-index: 9999;
            background: #0a0a0a;
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; gap: 1rem;
            transition: opacity .6s ease, visibility .6s ease;
        }
        #loading-screen.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .loader-logo { font-family: 'Cormorant Garamond', serif; font-size: 2.4rem; color: #c9a96e; letter-spacing: .2em; }
        .loader-bar { width: 180px; height: 2px; background: #222; border-radius: 2px; overflow: hidden; }
        .loader-fill { height: 100%; width: 0; background: linear-gradient(90deg,#c9a96e,#f5dfa5,#c9a96e); animation: fillBar 1.8s ease forwards; }
        @keyframes fillBar { to { width: 100%; } }
    </style>
</head>
<body class="<?= htmlspecialchars($active_page) ?>-page">


<!-- ── Cookie Consent ──────────────────────── -->
<div id="cookie-banner" class="cookie-banner" role="dialog" aria-label="Cookie consent">
    <div class="cookie-inner">
        <p><i class="fa-solid fa-cookie-bite"></i> We use cookies to elevate your experience. <a href="/privacy.php">Privacy Policy</a></p>
        <div class="cookie-btns">
            <button id="cookie-accept" class="btn-gold-sm">Accept All</button>
            <button id="cookie-decline" class="btn-ghost-sm">Decline</button>
        </div>
    </div>
</div>

<!-- ── Navbar ──────────────────────────────── -->
<nav id="navbar" class="navbar" role="navigation" aria-label="Main navigation">
    <div class="nav-container">
        <!-- Logo -->
        <a href="/" class="nav-logo" aria-label="Muna's Salon Home">
            <span class="logo-icon">✦</span>
            <span class="logo-text">Muna's</span>
            <span class="logo-sub">Unisex Salon</span>
        </a>

        <!-- Links -->
        <ul class="nav-links" id="nav-links" role="list">
            <?php foreach ($nav_links as $link):
                $is_active = ($active_page === strtolower($link['label']));
            ?>
            <li role="listitem">
                <a href="<?= $link['href'] ?>"
                   class="nav-link <?= $is_active ? 'active' : '' ?>"
                   <?= $is_active ? 'aria-current="page"' : '' ?>>
                    <?= htmlspecialchars($link['label']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <!-- CTA + Hamburger -->
        <div class="nav-actions">
            <a href="/book-appointment.php" class="btn-book">Book Now</a>
            <button id="hamburger" class="hamburger" aria-label="Toggle menu" aria-expanded="false" aria-controls="nav-links">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div id="nav-overlay" class="nav-overlay" aria-hidden="true"></div>

<!-- ── WhatsApp Float ──────────────────────── -->
<a href="https://wa.me/<?= SITE_WHATSAPP ?>?text=Hi%20Muna%27s%20Salon!%20I%27d%20like%20to%20book%20an%20appointment."
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
    <span class="wa-tooltip">Chat with us</span>
</a>

<!-- ── Scroll to Top ──────────────────────── -->
<button id="scroll-top" class="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- ── Sticky Social Icons ─────────────────── -->
<div class="sticky-social" aria-label="Social links">
    <a href="<?= SITE_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
    <a href="<?= SITE_FACEBOOK ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
    <a href="<?= SITE_YOUTUBE ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
    <a href="tel:<?= preg_replace('/\s/', '', SITE_PHONE) ?>" aria-label="Call us"><i class="fas fa-phone-alt"></i></a>
</div>

<main id="main-content">
