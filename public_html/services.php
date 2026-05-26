<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = "Services & Menu – Muna's Unisex Salon";
$page_desc   = 'Explore LuxeGlow\'s full range of premium salon services including hair, skin, bridal, spa, nail art and men\'s grooming.';
$active_page = 'services';
$IMG = '/images';
include __DIR__ . '/php/header.php';

$all_services = $db->query("SELECT * FROM services ORDER BY category ASC, id ASC")->fetchAll();
$categories = [];
foreach ($all_services as $s) {
    $categories[$s['category']][] = $s;
}
// Ensure we have at least 'General' if db is empty
if (empty($categories)) {
    $categories['General'] = [];
}
?>

<section style="padding:12rem 0 4rem;background:linear-gradient(135deg,#0a0500,#1a1000);text-align:center;position:relative;">
  <div class="container" style="position:relative;z-index:1;">
    <span class="section-eyebrow" data-reveal="fade-up">Our Expertise</span>
    <h1 class="section-title" data-reveal="fade-up" style="margin-bottom:1rem;">Premium Salon Menu</h1>
    <p class="section-subtitle" data-reveal="fade-up">From signature haircuts to luxurious spa treatments, discover our full range of services crafted to perfection.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <!-- Category tabs -->
    <div class="tabs" style="justify-content:center;" id="service-tabs">
      <?php 
      $first_cat = array_keys($categories)[0] ?? 'General';
      foreach(array_keys($categories) as $i=>$cat): ?>
      <button class="tab-btn <?=$cat===$first_cat?'active':''?>" data-category="<?=htmlspecialchars($cat)?>"><?=htmlspecialchars($cat)?></button>
      <?php endforeach; ?>
    </div>

    <?php foreach($categories as $cat=>$services): ?>
    <div class="services-cat-panel <?=$cat===$first_cat?'active':''?>" data-category-panel="<?=htmlspecialchars($cat)?>" style="<?=$cat!==$first_cat?'display:none':''?>">
      <div class="services-grid align-start" id="services-grid-<?=htmlspecialchars($cat)?>">
        <?php 
        // For "General" category, apply see more logic (show first 6, hide rest)
        $isGeneral = ($cat === 'General');
        $visibleCount = $isGeneral ? 6 : count($services);
        $serviceIndex = 0;
        foreach($services as $s): 
          $isHidden = $isGeneral && $serviceIndex >= $visibleCount;
        ?>
        <div class="service-card hover-lift <?=$isHidden ? 'service-hidden' : ''?>" data-service-index="<?=$serviceIndex?>">
          <div class="service-card-img">
            <img src="<?=$IMG?>/<?=htmlspecialchars($s['image'])?>" alt="<?=htmlspecialchars($s['title'])?>" loading="lazy">
          </div>
          <div class="service-card-body">
            <h3><?=htmlspecialchars($s['title'])?></h3>
            <p><?=htmlspecialchars($s['description'])?></p>
            <div class="service-card-footer">
              <div style="display:flex; align-items:center; gap:1rem;">
                <span class="service-price"><?=htmlspecialchars($s['price'])?></span>
                <span class="service-duration"><i class="fas fa-clock"></i> <?=htmlspecialchars($s['duration'])?></span>
              </div>
              <a href="/book-appointment.php" class="btn btn-gold" style="padding:.45rem 1rem;font-size:.7rem;">Book</a>
            </div>
          </div>
        </div>
        <?php 
          $serviceIndex++;
        endforeach; 
        ?>
      </div>
      
      <!-- See More / Show Less Button for General Category -->
      <?php if($isGeneral && count($services) > $visibleCount): ?>
      <div class="services-see-more-container" data-category="<?=htmlspecialchars($cat)?>" id="services-see-more-container-<?=htmlspecialchars($cat)?>">
        <button class="btn-see-more services-see-more-btn" data-category-see-more="<?=htmlspecialchars($cat)?>">
          See More Services <i class="fas fa-arrow-down"></i>
        </button>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<!-- Why book with us (Service Guarantee) -->
<section class="section section-dark" id="service-guarantee-section">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Our Promise</span>
      <h2 class="section-title">Service Guarantee</h2>
    </div>
    <div class="guarantee-grid" id="guarantee-grid">
      <?php 
      $allGuarantees = [
        ['⏱️','On-Time Service','We respect your time. Services start on schedule, always.'],
        ['💯','Satisfaction Guaranteed','Not happy? We\'ll redo the service at no extra charge.'],
        ['🔒','Safe & Hygienic','Hospital-grade sterilization for every tool and surface.'],
        ['✨','Premium Products','Only the finest luxury brands used for every service.'],
        ['👥','Expert Stylists','All our professionals are certified with 5+ years experience.'],
        ['🎯','Personalized Care','Every service is tailored to your unique needs and preferences.'],
        ['🔄','Easy Rescheduling','Free rescheduling up to 24 hours before appointment.'],
        ['🏆','Award-Winning Service','Recognized as Best Unisex Salon 2024.'],
      ];
      $visibleGuarantees = array_slice($allGuarantees, 0, 6);
      $hiddenGuarantees = array_slice($allGuarantees, 6);
      ?>
      
      <?php foreach($visibleGuarantees as $g): ?>
      <div class="guarantee-card" data-reveal="fade-up">
        <div class="why-icon"><?=$g[0]?></div>
        <h3><?=$g[1]?></h3>
        <p><?=$g[2]?></p>
      </div>
      <?php endforeach; ?>
      
      <?php foreach($hiddenGuarantees as $g): ?>
      <div class="guarantee-card guarantee-hidden" data-reveal="fade-up" data-guarantee-hidden="true">
        <div class="why-icon"><?=$g[0]?></div>
        <h3><?=$g[1]?></h3>
        <p><?=$g[2]?></p>
      </div>
      <?php endforeach; ?>
    </div>
    
    <?php if(count($hiddenGuarantees) > 0): ?>
    <div class="guarantee-see-more-container" data-reveal="fade-up" id="guarantee-see-more-container">
      <button id="see-more-guarantee-btn" class="btn-see-more">
        See More Benefits <i class="fas fa-arrow-down"></i>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="cta-section section">
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <h2 class="section-title">Ready to Pamper Yourself?</h2>
    <div class="cta-actions">
      <a href="/book-appointment.php" class="btn btn-gold">✦ Book Now</a>
      <a href="/contact.php" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/php/footer.php'; ?>