<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: /services.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    header('Location: /services.php');
    exit;
}

$page_title  = htmlspecialchars($service['title']) . " – Muna's Unisex Salon";
$page_desc   = htmlspecialchars(substr($service['description'], 0, 150)) . '...';
$active_page = 'services';
$IMG = '/images';
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] == 'true';

if (!$is_ajax) {
    include __DIR__ . '/php/header.php';
}

$attributes = !empty($service['attributes']) ? json_decode($service['attributes'], true) : [];
$amenities = !empty($service['amenities']) ? json_decode($service['amenities'], true) : [];
$related_ids = !empty($service['related_services']) ? json_decode($service['related_services'], true) : [];

$amenity_icons = [
    'Pet Friendly' => 'fas fa-paw',
    'Waiting Chair' => 'fas fa-chair',
    'Work Friendly' => 'fas fa-laptop',
    'Hand Sanitiser' => 'fas fa-hands-wash',
    'AC' => 'fas fa-snowflake',
    'WiFi' => 'fas fa-wifi',
    'Coffee/Tea' => 'fas fa-coffee',
    'Parking' => 'fas fa-parking'
];
?>

<style>
/* Service Detail Styles */
.service-detail-header {
    padding: 12rem 0 4rem;
    background: linear-gradient(135deg, #0a0500, #1a1000);
    text-align: center;
    position: relative;
}
.service-detail-header img {
    max-width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    border: 1px solid #c9a96e;
}
.service-meta {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1rem;
    font-size: 1.2rem;
    color: #c9a96e;
    font-weight: 500;
}
.service-content-section {
    padding: 4rem 0;
    background: #000;
}
.service-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #ddd;
    margin-bottom: 3rem;
}
.attributes-list {
    list-style: none;
    padding: 0;
    margin: 0 0 3rem 0;
}
.attributes-list li {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #222;
    font-size: 1.05rem;
    color: #ccc;
}
.attributes-list li::before {
    content: '';
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid #c9a96e;
    border-radius: 50%;
}
.amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 1.5rem;
    margin-bottom: 4rem;
    text-align: center;
}
.amenity-item {
    background: #111;
    padding: 1.5rem 1rem;
    border-radius: 50%;
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: 1px solid #333;
    transition: all 0.3s ease;
}
.amenity-item:hover {
    border-color: #c9a96e;
    transform: translateY(-5px);
}
.amenity-item i {
    font-size: 1.8rem;
    color: #c9a96e;
}
.amenity-item span {
    font-size: 0.8rem;
    color: #aaa;
}
.related-services-section {
    padding: 4rem 0 8rem; /* Extra padding for sticky bar */
    background: #0a0500;
}
.sticky-action-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #111;
    border-top: 1px solid #333;
    padding: 1rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
    z-index: 1000;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.5);
}
.sticky-action-bar .btn {
    flex: 1;
    max-width: 200px;
    padding: 0.8rem;
    font-size: 1rem;
}
.btn-whatsapp {
    background: #25D366;
    color: #fff;
    border: none;
}
.btn-whatsapp:hover {
    background: #1da851;
}
</style>

<section class="service-detail-header">
    <div class="container" style="position:relative; z-index:1;">
        <span class="section-eyebrow" data-reveal="fade-up"><?= htmlspecialchars($service['category']) ?></span>
        <h1 class="section-title" data-reveal="fade-up" style="margin-bottom:1rem;"><?= htmlspecialchars($service['title']) ?></h1>
        <div class="service-meta" data-reveal="fade-up">
            <span><?= htmlspecialchars($service['price']) ?></span>
            <span><i class="fas fa-clock"></i> <?= htmlspecialchars($service['duration']) ?></span>
        </div>
        <?php if(!empty($service['image'])): ?>
            <img src="<?= $IMG ?>/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" data-reveal="fade-up">
        <?php endif; ?>
    </div>
</section>

<section class="service-content-section">
    <div class="container" style="max-width: 800px;">
        <p class="service-description" data-reveal="fade-up">
            <?= nl2br(htmlspecialchars($service['description'])) ?>
        </p>

        <?php if(!empty($attributes)): ?>
        <h3 style="color:#fff; margin-bottom:1.5rem;" data-reveal="fade-up">Service Details</h3>
        <ul class="attributes-list" data-reveal="fade-up">
            <?php foreach($attributes as $attr): ?>
            <li>
                <strong><?= htmlspecialchars($attr['key']) ?>:</strong> 
                <span><?= htmlspecialchars($attr['value']) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if(!empty($amenities)): ?>
        <h3 style="color:#fff; margin-bottom:1.5rem;" data-reveal="fade-up">Amenities</h3>
        <div class="amenities-grid" data-reveal="fade-up">
            <?php foreach($amenities as $amenity): 
                $icon = $amenity_icons[$amenity] ?? 'fas fa-check';
            ?>
            <div class="amenity-item">
                <i class="<?= $icon ?>"></i>
                <span><?= htmlspecialchars($amenity) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if(!empty($related_ids)): 
    // Fetch related services
    $in = str_repeat('?,', count($related_ids) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM services WHERE id IN ($in)");
    $stmt->execute($related_ids);
    $related_services = $stmt->fetchAll();

    if(!empty($related_services)):
?>
<section class="related-services-section">
    <div class="container">
        <div class="section-header" data-reveal="fade-up">
            <h2 class="section-title" style="font-size:2rem;">Related Services</h2>
        </div>
        <div class="services-grid">
            <?php foreach($related_services as $rs): ?>
            <div class="service-card hover-lift" data-reveal="fade-up">
                <div class="service-card-img">
                    <img src="<?= $IMG ?>/<?= htmlspecialchars($rs['image']) ?>" alt="<?= htmlspecialchars($rs['title']) ?>" loading="lazy">
                </div>
                <div class="service-card-body">
                    <h3><?= htmlspecialchars($rs['title']) ?></h3>
                    <p style="font-size:0.9rem; margin-bottom:1rem;"><?= htmlspecialchars(substr($rs['description'], 0, 80)) ?>...</p>
                    <div class="service-card-footer">
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <span class="service-price" style="font-size:1rem;"><?= htmlspecialchars($rs['price']) ?></span>
                        </div>
                        <a href="/service_detail.php?id=<?= $rs['id'] ?>" class="btn btn-outline" style="padding:.45rem 1rem;font-size:.7rem;">View</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php 
    endif;
endif; 
?>

<!-- Sticky Action Bar -->
<div class="sticky-action-bar">
    <a href="tel:+919876543210" class="btn" style="background:#333; color:#fff; border:1px solid #555;"><i class="fas fa-phone-alt"></i> Show Number</a>
    <a href="/book-appointment.php?service=<?= urlencode($service['title']) ?>" class="btn btn-gold"><i class="fas fa-calendar-check"></i> Enquire Now</a>
    <a href="https://wa.me/919876543210?text=I'm%20interested%20in%20your%20<?= urlencode($service['title']) ?>%20service." class="btn btn-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
</div>

<?php 
if (!$is_ajax) {
    include __DIR__ . '/php/footer.php'; 
}
?>
