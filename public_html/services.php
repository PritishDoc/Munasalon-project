<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = "Services & Menu – Muna's Unisex Salon";
$page_desc   = 'Explore LuxeGlow\'s full range of premium salon services including hair, skin, bridal, spa, nail art and men\'s grooming.';
$active_page = 'services';
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
      <div class="services-grid" data-stagger>
        <?php foreach($services as $s): ?>
        <div class="service-card hover-lift">
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
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<!-- Why book with us -->
<section class="section section-dark">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Our Promise</span>
      <h2 class="section-title">Service Guarantee</h2>
    </div>
    <div class="grid-3" data-stagger>
      <?php foreach([['⏱️','On-Time Service','We respect your time. Services start on schedule, always.'],['💯','Satisfaction Guaranteed','Not happy? We\'ll redo the service at no extra charge.'],['🔒','Safe & Hygienic','Hospital-grade sterilization for every tool and surface.']] as $g): ?>
      <div class="why-card"><div class="why-icon"><?=$g[0]?></div><h3><?=$g[1]?></h3><p><?=$g[2]?></p></div>
      <?php endforeach; ?>
    </div>
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

<script>
document.getElementById('service-tabs')?.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const cat = btn.dataset.category;
    document.querySelectorAll('[data-category-panel]').forEach(p => {
      p.style.display = p.dataset.categoryPanel === cat ? '' : 'none';
    });
  });
});
</script>

<?php include __DIR__ . '/php/footer.php'; ?>
