<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = "Gallery – Muna's Unisex Salon Work";
$page_desc   = "Browse Muna's Salon gallery — stunning hair styles, bridal looks, nail art, spa sessions and before/after transformations.";
$active_page = 'gallery';
$IMG = '/images';
include __DIR__ . '/php/header.php';

$gallery_items = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
$heights=['280px','240px','300px','250px','270px','240px','260px','290px','240px','260px','250px','280px'];
?>

<section style="padding:12rem 0 5rem;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;"><img src="<?=$IMG?>/gallery-hair.jpg" alt="" style="width:100%;height:100%;object-fit:cover;"></div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.92),rgba(10,6,2,.80));"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <span class="section-eyebrow">Our Portfolio</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Our Work Gallery</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Real transformations. Real clients. Real confidence.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <!-- Filter -->
    <div class="gallery-filter" id="gallery-filter-btns">
      <?php foreach(['all'=>'All Work','hair'=>'Hair','bridal'=>'Bridal','nails'=>'Nails','spa'=>'Spa','grooming'=>'Grooming','skin'=>'Skin'] as $k=>$v): ?>
      <button class="tab-btn <?=$k==='all'?'active':''?>" data-filter="<?=$k?>"><?=$v?></button>
      <?php endforeach; ?>
    </div>

    <!-- Masonry -->
    <div class="gallery-grid" id="gallery-grid">
      <?php foreach($gallery_items as $i=>$item): 
        $cat_key = strtolower(trim($item['category']));
      ?>
      <div class="gallery-item img-zoom-wrap" data-cat="<?=htmlspecialchars($cat_key)?>" style="height:<?=$heights[$i % count($heights)]?>">
        <img src="<?=$IMG?>/<?=htmlspecialchars($item['image'])?>" alt="<?=htmlspecialchars($item['title'])?>" loading="lazy">
        <div class="gallery-overlay">
          <div style="text-align:center;color:#fff;padding:1rem;">
            <div class="gallery-zoom-icon" style="margin:0 auto .5rem;"><i class="fas fa-expand"></i></div>
            <span style="font-size:.75rem;letter-spacing:.1em;text-transform:uppercase;"><?=htmlspecialchars($item['title'])?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Before / After -->
<section class="section section-dark">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Transformations</span>
      <h2 class="section-title">Before & After</h2>
      <p class="section-subtitle">Witness the Muna's difference — stunning real transformations.</p>
    </div>
    <div class="grid-3">
      <?php $ba=[
        ['service-haircolor.jpg','gallery-hair.jpg','Hair Colour Transformation','Dull to vibrant — full balayage colour makeover'],
        ['service-facial.jpg','service-facial.jpg','Skin Glow Treatment','Dull skin to radiant glow after gold facial'],
        ['service-bridal.jpg','service-bridal.jpg','Bridal Makeover','Natural look to full flawless bridal makeup'],
      ]; foreach($ba as $b): ?>
      <div class="glass-gold" style="border-radius:var(--radius-lg);overflow:hidden;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
          <div style="position:relative;">
            <img src="<?=$IMG?>/<?=$b[0]?>" alt="Before" style="width:100%;height:200px;object-fit:cover;filter:grayscale(60%);">
            <div style="position:absolute;bottom:.5rem;left:.5rem;background:rgba(0,0,0,.7);color:#999;font-size:.65rem;letter-spacing:.1em;padding:.2rem .5rem;border-radius:4px;">BEFORE</div>
          </div>
          <div style="position:relative;">
            <img src="<?=$IMG?>/<?=$b[1]?>" alt="After" style="width:100%;height:200px;object-fit:cover;">
            <div style="position:absolute;bottom:.5rem;right:.5rem;background:rgba(201,169,110,.9);color:#000;font-size:.65rem;letter-spacing:.1em;padding:.2rem .5rem;border-radius:4px;font-weight:700;">AFTER</div>
          </div>
        </div>
        <div style="padding:1.2rem;text-align:center;">
          <h3 style="font-size:var(--fs-md);margin-bottom:.3rem;"><?=$b[2]?></h3>
          <p style="font-size:var(--fs-sm);"><?=$b[3]?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

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

<script>
(function(){
  const btns  = document.querySelectorAll('#gallery-filter-btns .tab-btn');
  const items = document.querySelectorAll('#gallery-grid .gallery-item[data-cat]');

  // Normalise: strip spaces, lowercase — so "Hair Care" → "hair care" matches filter "hair"
  function catMatches(itemCat, filter) {
    if (filter === 'all') return true;
    // exact match first
    if (itemCat === filter) return true;
    // partial match: "hair care" should match filter "hair"
    return itemCat.includes(filter);
  }

  function applyFilter(filter) {
    items.forEach(item => {
      const cat = (item.dataset.cat || '').toLowerCase().trim();
      const show = catMatches(cat, filter);
      // Use display:none so masonry columns reflow correctly
      item.style.display    = show ? 'block' : 'none';
      item.style.marginBottom = show ? '' : '0';
    });
  }

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      applyFilter(btn.dataset.filter);
    });
  });

  // Show all on load
  applyFilter('all');

  // Lightbox
  const lb    = document.getElementById('lightbox');
  const lbImg = lb?.querySelector('.lightbox-img');
  let allSrcs = [];
  let cur = 0;

  function buildSrcs() {
    // Only visible items in lightbox
    allSrcs = [...items]
      .filter(i => i.style.display !== 'none')
      .map(i => i.querySelector('img')?.src)
      .filter(Boolean);
  }

  items.forEach(item => {
    item.addEventListener('click', () => {
      buildSrcs();
      const src = item.querySelector('img')?.src;
      cur = allSrcs.indexOf(src);
      if (cur < 0) cur = 0;
      if (lbImg) lbImg.src = allSrcs[cur];
      lb?.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });

  lb?.querySelector('.lightbox-close')?.addEventListener('click', () => {
    lb.classList.remove('active'); document.body.style.overflow = '';
  });
  lb?.querySelector('.lightbox-prev')?.addEventListener('click', () => {
    cur = (cur - 1 + allSrcs.length) % allSrcs.length;
    if (lbImg) lbImg.src = allSrcs[cur];
  });
  lb?.querySelector('.lightbox-next')?.addEventListener('click', () => {
    cur = (cur + 1) % allSrcs.length;
    if (lbImg) lbImg.src = allSrcs[cur];
  });
  lb?.addEventListener('click', e => {
    if (e.target === lb) { lb.classList.remove('active'); document.body.style.overflow = ''; }
  });
  document.addEventListener('keydown', e => {
    if (!lb?.classList.contains('active')) return;
    if (e.key === 'Escape') { lb.classList.remove('active'); document.body.style.overflow = ''; }
    if (e.key === 'ArrowLeft') lb.querySelector('.lightbox-prev')?.click();
    if (e.key === 'ArrowRight') lb.querySelector('.lightbox-next')?.click();
  });
})();
</script>

<?php include __DIR__ . '/php/footer.php'; ?>