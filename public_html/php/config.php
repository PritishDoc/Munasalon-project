<?php
// =============================================
// Muna's Unisex Salon – Site Configuration
// =============================================

define('SITE_NAME', "Muna's Unisex Salon");
define('SITE_TAGLINE', 'Where Beauty Meets Excellence');
define('SITE_URL', 'https://yourdomain.com'); // Change to your domain
define('SITE_EMAIL', 'hello@munasalon.com');
define('SITE_PHONE', '+91 98765 43210');
define('SITE_WHATSAPP', '919876543210');
define('SITE_ADDRESS', '12, Beauty Avenue, Style Street, Mumbai – 400001');
define('SITE_HOURS', 'Mon–Sat: 9 AM – 8 PM | Sun: 10 AM – 6 PM');
define('SITE_INSTAGRAM', 'https://instagram.com/munasalon');
define('SITE_FACEBOOK', 'https://facebook.com/munasalon');
define('SITE_YOUTUBE', 'https://youtube.com/munasalon');

// ── Cache Busting Version ──────────────────────
// Update this string whenever you change CSS/JS files.
// Can be a date, build number, or hash.
define('ASSET_VERSION', '2.1.0');  // ← bump to clear old cached CSS/JS

// Helper function to append version to asset URLs
function asset_url(string $path): string {
    return $path . '?v=' . ASSET_VERSION;
}

// ── Navigation Links ──────────────────────────
$nav_links = [
    ['label' => 'Home',             'href' => '/'],
    ['label' => 'About',            'href' => '/about.php'],
    ['label' => 'Services',         'href' => '/services.php'],
    ['label' => 'Products',         'href' => '/products.php'],
    ['label' => 'Gallery',          'href' => '/gallery.php'],
    ['label' => 'Blog',             'href' => '/blog.php'],
    ['label' => 'Contact',          'href' => '/contact.php'],
];

// ── Page meta helper ──────────────────────────
function page_meta(string $title, string $desc = '', string $keywords = ''): void {
    $site = SITE_NAME;
    $full_title = $title ? "$title | $site" : $site;
    $desc = $desc ?: "Muna's Unisex Salon – premium hair, skin, bridal & grooming services for men and women.";
    $keywords = $keywords ?: "Muna's salon, unisex salon, hair salon, bridal makeup, spa, grooming, beauty parlour";
    echo "<title>" . htmlspecialchars($full_title) . "</title>\n";
    echo '<meta name="description" content="' . htmlspecialchars($desc) . '">' . "\n";
    echo '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
}
?>
