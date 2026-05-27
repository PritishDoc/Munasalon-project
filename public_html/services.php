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
              <div style="display:flex; gap:0.5rem;">
                <a href="#" onclick="openServiceModal(<?= $s['id'] ?>); return false;" class="btn btn-outline" style="padding:.45rem 1rem;font-size:.7rem;">Details</a>
                <a href="/book-appointment.php?service=<?= urlencode($s['title']) ?>" class="btn btn-gold" style="padding:.45rem 1rem;font-size:.7rem;">Book</a>
              </div>
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

<!-- Service Modal -->
<div id="serviceModal" class="service-modal">
  <div class="service-modal-content">
    <button class="service-modal-close" onclick="closeServiceModal()">&times;</button>
    <div id="serviceModalBody" class="service-modal-body">
      <div style="text-align:center; padding: 4rem 0; color: #c9a96e;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
        <p style="margin-top:1rem;">Loading details...</p>
      </div>
    </div>
  </div>
</div>

<style>
/* Service Modal Styles */
.service-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0,0,0,0.8);
  backdrop-filter: blur(5px);
}
.service-modal-content {
  position: relative;
  background-color: #0a0500;
  margin: 2% auto; /* 5% from the top and centered */
  width: 95%;
  max-width: 800px;
  height: 90%;
  border-radius: 8px;
  box-shadow: 0 5px 30px rgba(0,0,0,0.5);
  border: 1px solid #c9a96e;
  display: flex;
  flex-direction: column;
}
.service-modal-close {
  position: absolute;
  top: 15px;
  right: 20px;
  color: #fff;
  background: rgba(0,0,0,0.5);
  border: 1px solid #333;
  font-size: 28px;
  font-weight: bold;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 10000;
  transition: all 0.3s ease;
}
.service-modal-close:hover,
.service-modal-close:focus {
  color: #c9a96e;
  border-color: #c9a96e;
}
.service-modal-body {
  flex: 1;
  overflow-y: auto;
  border-radius: 8px;
}
/* Ensure the inner sticky bar stays at the bottom of the modal content */
.service-modal-body .sticky-action-bar {
  position: sticky;
  bottom: 0;
}
@media (max-width: 768px) {
  .service-modal-content {
    width: 100%;
    height: 100%;
    margin: 0;
    border-radius: 0;
    border: none;
  }
}
</style>

<script>
function openServiceModal(id) {
  const modal = document.getElementById("serviceModal");
  const modalBody = document.getElementById("serviceModalBody");
  
  modal.style.display = "block";
  document.body.style.overflow = "hidden"; // Prevent background scrolling
  
  // Show loading state
  modalBody.innerHTML = `
    <div style="text-align:center; padding: 6rem 0; color: #c9a96e;">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
      <p style="margin-top:1rem;">Loading details...</p>
    </div>
  `;
  
  // Fetch content via AJAX
  fetch('/service_detail.php?id=' + id + '&ajax=true')
    .then(response => response.text())
    .then(html => {
      modalBody.innerHTML = html;
      // Remove data-reveal attributes from newly injected HTML so elements aren't hidden by opacity:0
      const newReveals = modalBody.querySelectorAll('[data-reveal]');
      newReveals.forEach(el => {
        el.removeAttribute('data-reveal');
      });
    })
    .catch(err => {
      modalBody.innerHTML = `
        <div style="text-align:center; padding: 4rem 0; color: red;">
          <p>Failed to load service details. Please try again later.</p>
        </div>
      `;
    });
}

function closeServiceModal() {
  const modal = document.getElementById("serviceModal");
  modal.style.display = "none";
  document.body.style.overflow = "auto"; // Restore background scrolling
}

// Close modal when clicking outside of it
window.onclick = function(event) {
  const modal = document.getElementById("serviceModal");
  if (event.target == modal) {
    closeServiceModal();
  }
}
</script>

<?php include __DIR__ . '/php/footer.php'; ?>