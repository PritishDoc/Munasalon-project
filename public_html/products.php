<?php
require_once __DIR__ . '/php/config.php';
$page_title  = 'Products – Premium Beauty & Hair Care';
$page_desc   = 'Shop salon-grade beauty and hair care products at LuxeGlow. Face wash, serums, shampoos, oils and more.';
$active_page = 'products';
include __DIR__ . '/php/header.php';

$products = [
  ['🧴','Face Wash','Gentle Foaming Cleanser','Daily gentle cleanser for all skin types.','₹299','₹399','skincare','4.8',42,'Bestseller'],
  ['💧','Moisturizer','Daily Hydrating Cream','Light, non-greasy daily moisturizer.','₹449','₹599','skincare','4.7',38,'New'],
  ['🧴','Face Wash','Gentle Foaming Cleanser','Daily gentle cleanser for all skin types.','₹299','₹399','skincare','4.8',42,'Bestseller'],
  ['💧','Moisturizer','Daily Hydrating Cream','Light, non-greasy daily moisturizer.','₹449','₹599','skincare','4.7',38,'New'],
  ['🧴','Face Wash','Gentle Foaming Cleanser','Daily gentle cleanser for all skin types.','₹299','₹399','skincare','4.8',42,'Bestseller'],
  ['💧','Moisturizer','Daily Hydrating Cream','Light, non-greasy daily moisturizer.','₹449','₹599','skincare','4.7',38,'New'],
  ['✨','Hair Serum','Frizz Control Serum','Anti-frizz serum for smooth, shiny hair.','₹599','₹799','hair','4.9',65,'Bestseller'],
  ['🧼','Shampoo','Nourishing Repair Shampoo','Deep repair formula for damaged hair.','₹349','₹450','hair','4.6',29,''],
  ['🌸','Conditioner','Moisture Conditioner','Intense hydration for smooth, soft hair.','₹299','₹399','hair','4.7',51,'Organic'],
  ['🪥','Beard Oil','Premium Grooming Oil','Nourishing beard oil for men.','₹399','₹549','grooming','4.8',33,'Bestseller'],
  ['☀️','Sunscreen','SPF 50+ Protection','Lightweight broad-spectrum sun protection.','₹499','₹650','skincare','4.9',72,'Bestseller'],
  ['💆','Hair Oil','Ayurvedic Strength Oil','Traditional herbs for strong, healthy hair.','₹249','₹349','hair','4.5',28,'Organic'],
  ['🌿','Face Cream','Anti-Aging Night Cream','Retinol-enriched night repair cream.','₹799','₹999','skincare','4.8',19,'New'],
  ['💅','Nail Care','Strengthening Nail Kit','Complete kit for healthy, strong nails.','₹349','₹499','nails','4.6',24,''],
  ['💋','Lip Care','Luxury Lip Balm Set','Hydrating lip balms in 5 flavors.','₹199','₹299','skincare','4.9',87,'Bestseller'],
  ['🧖','Body Spa Cream','Luxury Body Butter','Rich body cream for silky smooth skin.','₹599','₹799','body','4.7',41,'New'],
  ['💎','Hair Mask','Keratin Repair Mask','Deep conditioning for damaged hair.','₹449','₹599','hair','4.8',34,'Bestseller'],
  ['🌹','Rose Water','Natural Toner','Hydrating and soothing rose water mist.','₹199','₹299','skincare','4.6',56,'Organic'],
  ['🪒','Shaving Kit','Premium Shaving Set','Complete kit with razor, brush and cream.','₹899','₹1299','grooming','4.7',23,'New'],
  ['💪','Body Lotion','Intense Hydration Lotion','24-hour moisture for soft skin.','₹349','₹499','body','4.5',41,''],
];

$PRODUCTS_SHOW_LIMIT = 8;
?>

<section style="padding:12rem 0 4rem;background:linear-gradient(135deg,#0a0500,#1a1000);text-align:center;position:relative;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 50% 50%,rgba(201,169,110,.07),transparent);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <span class="section-eyebrow">Beauty Store</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Premium Beauty Products</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Salon-grade products for professional results at home.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <!-- Search -->
    <div class="product-search"><i class="fas fa-search"></i><input type="text" id="product-search" placeholder="Search products..."></div>

    <!-- Filter Tabs - Using data-products-filter -->
    <div class="tabs" style="justify-content:center;margin-bottom:2rem;" id="products-filter-tabs">
      <button class="tab-btn active" data-products-filter="all">All</button>
      <button class="tab-btn" data-products-filter="hair">Hair</button>
      <button class="tab-btn" data-products-filter="skincare">Skincare</button>
      <button class="tab-btn" data-products-filter="grooming">Grooming</button>
      <button class="tab-btn" data-products-filter="body">Body</button>
      <button class="tab-btn" data-products-filter="nails">Nails</button>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" id="products-grid">
      <?php foreach($products as $idx => $p): ?>
      <div class="product-card" data-category="<?=$p[6]?>" data-product-name="<?=htmlspecialchars($p[1])?>">
        <div class="product-card-img" style="background:linear-gradient(135deg,#1a1000,#2a1800);display:flex;align-items:center;justify-content:center;font-size:4.5rem;">
          <?=$p[0]?>
          <div class="product-card-badges">
            <?php if($p[9]): ?>
              <span class="badge <?=$p[9]==='Organic'?'badge-green':($p[9]==='New'?'badge-rose':'badge-gold')?>"><?=$p[9]?></span>
            <?php endif; ?>
          </div>
          <div class="product-card-actions">
            <button class="product-action-btn" data-action="wishlist" title="Wishlist"><i class="far fa-heart"></i></button>
            <button class="product-action-btn" title="Quick View"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="product-card-body">
          <h3><?=htmlspecialchars($p[1])?></h3>
          <p style="font-size:.78rem;color:var(--clr-gray-400);margin-bottom:.5rem;"><?=htmlspecialchars($p[2])?><br><span style="font-size:.72rem;opacity:.7;"><?=htmlspecialchars($p[3])?></span></p>
          <div class="product-rating">
            <span class="stars">★★★★★</span><span><?=$p[7]?> (<?=$p[8]?>)</span>
          </div>
          <div class="product-price-row">
            <span class="product-price"><?=$p[4]?></span>
            <span class="product-price-old"><?=$p[5]?></span>
            <span class="badge badge-gold" style="font-size:.6rem;"><?=round((1-(intval(preg_replace('/\D/','',$p[4]))/intval(preg_replace('/\D/','',$p[5]))))*100)?>% OFF</span>
          </div>
          <button class="product-add-btn">Add to Cart</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- See More / Show Less Button for Products -->
    <?php if(count($products) > $PRODUCTS_SHOW_LIMIT): ?>
    <div class="products-see-more-container" data-reveal="fade-up">
      <button id="products-see-more-btn" class="btn-see-more">
        See More Products <i class="fas fa-arrow-down"></i>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/php/footer.php'; ?>