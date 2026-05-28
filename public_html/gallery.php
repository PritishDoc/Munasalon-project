<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = "Gallery – Muna's Unisex Salon Work";
$page_desc   = "Browse Muna's Salon gallery — stunning hair styles, bridal looks, nail art, spa sessions and before/after transformations.";
$active_page = 'gallery';
$IMG = '/images';
include __DIR__ . '/php/header.php';

/* ============================================================
   GALLERY ITEMS — DUMMY DATA (for UI demo / team review)
   Each category has 7–8 items so See More / Show Less is testable.

   ➜ WHEN CONNECTING TO DB: replace $gallery_items below with:
       $gallery_items = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
   ============================================================ */
$gallery_items = [
    // ── Hair (8 items) ──────────────────────────────────────────
    ['id'=>1001, 'category'=>'hair',     'image'=>'gallery-hair.jpg',      'title'=>'Silk Blowout'],
    ['id'=>1002, 'category'=>'hair',     'image'=>'service-haircolor.jpg', 'title'=>'Balayage Colour'],
    ['id'=>1003, 'category'=>'hair',     'image'=>'gallery-hair.jpg',      'title'=>'Keratin Treatment'],
    ['id'=>1004, 'category'=>'hair',     'image'=>'service-haircolor.jpg', 'title'=>'Ombre Style'],
    ['id'=>1005, 'category'=>'hair',     'image'=>'gallery-hair.jpg',      'title'=>'Deep Conditioning'],
    ['id'=>1006, 'category'=>'hair',     'image'=>'service-haircolor.jpg', 'title'=>'Hair Spa'],
    ['id'=>1007, 'category'=>'hair',     'image'=>'gallery-hair.jpg',      'title'=>'Scalp Treatment'],
    ['id'=>1008, 'category'=>'hair',     'image'=>'service-haircolor.jpg', 'title'=>'Highlights'],
    // ── Bridal (7 items) ────────────────────────────────────────
    ['id'=>2001, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Bridal Glow Makeup'],
    ['id'=>2002, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Reception Look'],
    ['id'=>2003, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Engagement Glam'],
    ['id'=>2004, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Mehndi Look'],
    ['id'=>2005, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Haldi Look'],
    ['id'=>2006, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Sangeet Night'],
    ['id'=>2007, 'category'=>'bridal',   'image'=>'service-bridal.jpg',    'title'=>'Wedding Updo'],
    // ── Nails (7 items) ─────────────────────────────────────────
    ['id'=>3001, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'Gel Nails'],
    ['id'=>3002, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'French Tips'],
    ['id'=>3003, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'Nail Art'],
    ['id'=>3004, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'Ombre Nails'],
    ['id'=>3005, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'3D Nail Design'],
    ['id'=>3006, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'Chrome Powder'],
    ['id'=>3007, 'category'=>'nails',    'image'=>'service-nails.jpg',     'title'=>'Acrylic Extension'],
    // ── Spa (7 items) ───────────────────────────────────────────
    ['id'=>4001, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Gold Facial'],
    ['id'=>4002, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Body Polish'],
    ['id'=>4003, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Aroma Massage'],
    ['id'=>4004, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'De-Tan Pack'],
    ['id'=>4005, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Head Massage'],
    ['id'=>4006, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Foot Reflexology'],
    ['id'=>4007, 'category'=>'spa',      'image'=>'service-facial.jpg',    'title'=>'Back Massage'],
    // ── Grooming (7 items) ──────────────────────────────────────
    ['id'=>5001, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Beard Trim'],
    ['id'=>5002, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Clean Shave'],
    ['id'=>5003, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Hair & Beard Combo'],
    ['id'=>5004, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Fade Cut'],
    ['id'=>5005, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Skin Fade'],
    ['id'=>5006, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Undercut Style'],
    ['id'=>5007, 'category'=>'grooming', 'image'=>'service-groom.jpg',     'title'=>'Beard Colour'],
    // ── Skin (7 items) ──────────────────────────────────────────
    ['id'=>6001, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Acne Treatment'],
    ['id'=>6002, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Pigmentation Fix'],
    ['id'=>6003, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Anti-Ageing Glow'],
    ['id'=>6004, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Hydra Facial'],
    ['id'=>6005, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Microdermabrasion'],
    ['id'=>6006, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Vitamin C Boost'],
    ['id'=>6007, 'category'=>'skin',     'image'=>'service-facial.jpg',    'title'=>'Brightening Peel'],
];

/* ── Gallery grid config ────────────────────────────────────── */
$GALLERY_SHOW_LIMIT = 6; // cards shown per filter before "See More"

// Before & After items
$before_after_items = [
    ['service-haircolor.jpg', 'gallery-hair.jpg',   'Hair Colour Transformation', 'Dull to vibrant — full balayage colour makeover'],
    ['service-facial.jpg',    'service-facial.jpg', 'Skin Glow Treatment',        'Dull skin to radiant glow after gold facial'],
    ['service-bridal.jpg',    'service-bridal.jpg', 'Bridal Makeover',            'Natural look to full flawless bridal makeup'],
    ['service-haircolor.jpg', 'gallery-hair.jpg',   'Keratin Transformation',     'Frizzy to silky smooth after keratin treatment'],
    ['service-facial.jpg',    'service-facial.jpg', 'De-Tan Treatment',           'Tanned skin restored to even, glowing tone'],
    ['service-bridal.jpg',    'service-bridal.jpg', 'Engagement Look',            'Everyday look elevated to soft glam for engagement'],
    ['service-haircolor.jpg', 'gallery-hair.jpg',   'Highlights Makeover',        'Plain hair transformed with golden highlights'],
];
$BA_SHOW_LIMIT = 3;
?>

<!-- HERO SECTION -->
<section style="padding:12rem 0 5rem;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;"><img src="<?=$IMG?>/gallery-hair.jpg" alt="" style="width:100%;height:100%;object-fit:cover;"></div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.92),rgba(10,6,2,.80));"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <span class="section-eyebrow">Our Portfolio</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Our Work Gallery</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Real transformations. Real clients. Real confidence.</p>
  </div>
</section>

<!-- GALLERY GRID SECTION -->
<section class="section" id="gallery-section">
  <div class="container">
    <!-- Filter buttons -->
    <div class="gallery-filter" id="gallery-filter-btns">
      <?php foreach(['all'=>'All Work','hair'=>'Hair','bridal'=>'Bridal','nails'=>'Nails','spa'=>'Spa','grooming'=>'Grooming','skin'=>'Skin'] as $k=>$v): ?>
      <button class="tab-btn <?=$k==='all'?'active':''?>" data-gallery-filter="<?=$k?>"><?=$v?></button>
      <?php endforeach; ?>
    </div>

    <!-- Gallery Grid - Masonry Style -->
    <div class="gallery-grid" id="gallery-grid">
      <?php 
      $allItems = [];
      foreach($gallery_items as $item):
        $allItems[] = $item;
      ?>
      <div class="gallery-item" data-cat="<?=$item['category']?>" data-all-index="<?=count($allItems)?>">
        <img src="<?=$IMG?>/<?=$item['image']?>" alt="<?=$item['title']?>" loading="lazy">
        <div class="gallery-overlay">
          <div class="gallery-zoom-icon"><i class="fas fa-expand"></i></div>
          <div class="gallery-title"><?=$item['title']?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- See More / Show Less Button -->
    <div class="gallery-see-more-container" id="gallery-see-more-wrap" data-reveal="fade-up">
      <button id="gallery-see-more-btn" class="btn-see-more">
        See More <i class="fas fa-arrow-down"></i>
      </button>
    </div>
  </div>
</section>

<!-- BEFORE & AFTER SECTION -->
<section class="section section-dark" id="before-after-section">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Transformations</span>
      <h2 class="section-title">Before &amp; After</h2>
      <p class="section-subtitle">Witness the Muna's difference — stunning real transformations.</p>
    </div>

    <div class="before-after-grid" id="before-after-grid">
      <?php foreach($before_after_items as $idx => $b): 
        $isHidden = $idx >= $BA_SHOW_LIMIT;
      ?>
      <div class="ba-card <?=$isHidden ? 'ba-hidden' : ''?>" data-ba-index="<?=$idx?>">
        <div class="ba-images">
          <div class="ba-before">
            <img src="<?=$IMG?>/<?=$b[0]?>" alt="Before">
            <span class="ba-label before-label">BEFORE</span>
          </div>
          <div class="ba-after">
            <img src="<?=$IMG?>/<?=$b[1]?>" alt="After">
            <span class="ba-label after-label">AFTER</span>
          </div>
        </div>
        <div class="ba-content">
          <h3><?=$b[2]?></h3>
          <p><?=$b[3]?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if(count($before_after_items) > $BA_SHOW_LIMIT): ?>
    <div class="ba-see-more-container" data-reveal="fade-up" id="ba-see-more-container">
      <button id="ba-see-more-btn" class="btn-see-more">
        See More Transformations <i class="fas fa-arrow-down"></i>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section section" style="background-image:url('<?=$IMG?>/about-interior.jpg');background-size:cover;position:relative;">
  <div style="position:absolute;inset:0;background:rgba(5,3,1,.88);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <h2 class="section-title">Ready for Your Transformation?</h2>
    <div class="cta-actions" style="margin-top:2rem;">
      <a href="/book-appointment.php" class="btn btn-gold">✦ Book Appointment</a>
      <a href="/services.php" class="btn btn-outline">View Services</a>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true">
  <button class="lightbox-close"><i class="fas fa-times"></i></button>
  <button class="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
  <img class="lightbox-img" src="" alt="">
  <button class="lightbox-next"><i class="fas fa-chevron-right"></i></button>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>