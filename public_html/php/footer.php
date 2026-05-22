<?php
// php/footer.php – Shared site footer
if (!defined('SITE_NAME')) { require_once __DIR__ . '/config.php'; }
?>
</main><!-- /main-content -->

<!-- ── FOOTER ──────────────────────────────── -->
<footer class="site-footer" role="contentinfo">

    <!-- Newsletter Banner -->
    <div class="footer-newsletter">
        <div class="container">
            <div class="newsletter-inner">
                <div class="newsletter-text">
                    <h3>Get Exclusive Beauty Tips & Offers</h3>
                    <p>Subscribe and receive 20% OFF your first service.</p>
                </div>
                <form class="newsletter-form" id="newsletter-form" novalidate>
                    <div class="newsletter-input-wrap">
                        <input type="email" id="newsletter-email" placeholder="Your email address" required aria-label="Email address">
                        <button type="submit" class="btn-gold">Subscribe <i class="fas fa-paper-plane"></i></button>
                    </div>
                    <p class="newsletter-msg" id="newsletter-msg"></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Main -->
    <div class="footer-main">
        <div class="container footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="/" class="footer-logo">
                    <span class="logo-icon">✦</span>
                    <span class="logo-text">Muna's Salon</span>
                </a>
                <p class="footer-desc">Muna's Unisex Salon – where every visit is a luxury experience. Expert professionals, premium products, and personalized care for men & women.</p>
                <div class="footer-social">
                    <a href="<?= SITE_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="<?= SITE_FACEBOOK ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?= SITE_YOUTUBE ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://twitter.com/luxeglowsalon" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://pinterest.com/luxeglowsalon" target="_blank" rel="noopener" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-links">
                    <?php foreach ($nav_links as $link): ?>
                    <li><a href="<?= $link['href'] ?>"><?= htmlspecialchars($link['label']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="/book-appointment.php">Book Appointment</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-col">
                <h4 class="footer-heading">Our Services</h4>
                <ul class="footer-links">
                    <li><a href="/services.php#hair">Hair Cut & Styling</a></li>
                    <li><a href="/services.php#color">Hair Coloring</a></li>
                    <li><a href="/services.php#bridal">Bridal Makeup</a></li>
                    <li><a href="/services.php#facial">Facial & Cleanup</a></li>
                    <li><a href="/services.php#spa">Full Body Spa</a></li>
                    <li><a href="/services.php#keratin">Keratin Treatment</a></li>
                    <li><a href="/services.php#nails">Manicure & Pedicure</a></li>
                    <li><a href="/services.php#grooming">Men's Grooming</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h4 class="footer-heading">Contact & Hours</h4>
                <ul class="footer-contact-list">
                    <li><i class="fas fa-map-marker-alt"></i><span><?= SITE_ADDRESS ?></span></li>
                    <li><i class="fas fa-phone-alt"></i><a href="tel:<?= preg_replace('/\s/','',$_ENV['SITE_PHONE'] ?? SITE_PHONE) ?>"><?= SITE_PHONE ?></a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></li>
                    <li><i class="fas fa-clock"></i><span><?= SITE_HOURS ?></span></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved. Crafted with <i class="fas fa-heart" style="color:#c9a96e"></i></p>
            <ul class="footer-legal">
                <li><a href="/privacy.php">Privacy Policy</a></li>
                <li><a href="/terms.php">Terms of Service</a></li>
                <li><a href="/sitemap.php">Sitemap</a></li>
            </ul>
        </div>
    </div>

</footer>

<!-- ── Scripts ─────────────────────────────── -->
<script src="<?= asset_url('/js/utils.js') ?>"></script>
<script src="<?= asset_url('/js/navbar.js') ?>"></script>
<script src="<?= asset_url('/js/animations.js') ?>"></script>
<?php if (!empty($extra_js)): foreach ($extra_js as $js): ?>
<script src="<?= asset_url($js) ?>"></script>
<?php endforeach; endif; ?>
<script src="<?= asset_url('/js/main.js') ?>"></script>

</body>
</html>
