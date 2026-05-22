<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title   = "Muna's Unisex Salon – Premium Hair, Skin & Spa";
$page_desc    = "Muna's Unisex Salon – trusted premium salon for hair, bridal, spa & grooming services in Mumbai.";
$active_page  = 'home';
$IMG = '/images';
include __DIR__ . '/php/header.php';
?>

<!-- ═══════════════════════════════════════
     HERO SLIDER
════════════════════════════════════════ -->
<section class="hero" id="hero">
  <div class="hero-slider">
    <div class="hero-slide active" style="background-image:url('<?=$IMG?>/hero1.jpg');background-size:cover;background-position:center;"></div>
    <div class="hero-slide" style="background-image:url('<?=$IMG?>/hero2.jpg');background-size:cover;background-position:center;"></div>
    <div class="hero-slide" style="background-image:url('<?=$IMG?>/about-interior.jpg');background-size:cover;background-position:center;"></div>
  </div>
  <!-- overlay -->
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.82) 0%,rgba(20,12,4,.55) 60%,transparent 100%);z-index:1;"></div>
  <div class="hero-deco hero-deco-1"></div>
  <div class="hero-deco hero-deco-2"></div>

  <div class="container" style="position:relative;z-index:2;">
    <div class="hero-content">
      <div class="hero-eyebrow">Muna's Unisex Salon & Beauty Parlour</div>
      <h1 class="hero-title">Where Beauty<br><span>Meets</span><br>Excellence</h1>
      <p class="hero-subtitle">Premium salon experience for men & women. Expert professionals, luxury products, results you'll love.</p>
      <div class="hero-actions">
        <a href="/book-appointment.php" class="btn btn-gold">✦ Book Appointment</a>
        <a href="/services.php" class="btn btn-outline">Explore Services</a>
      </div>
    </div>
  </div>

  <div class="hero-slider-dots">
    <button class="hero-dot active" aria-label="Slide 1"></button>
    <button class="hero-dot" aria-label="Slide 2"></button>
    <button class="hero-dot" aria-label="Slide 3"></button>
  </div>
  <div class="hero-scroll-hint"><div class="scroll-dot"></div><span>Scroll</span></div>
</section>

<!-- ═══════════════════════════════════════
     BRANDS MARQUEE
════════════════════════════════════════ -->
<section class="brands-section">
  <p class="brands-label">Trusted Brands We Use</p>
  <div style="overflow:hidden;">
    <div class="marquee-track">
      <?php $brands=[["L'Oréal",'loreal.com'],['Lakmé','lakmeindia.com'],['Mamaearth','mamaearth.in'],['WOW','buywow.in'],['Garnier','garnier.in'],['Nivea','nivea.com'],['Matrix','matrix.com'],['Schwarzkopf','schwarzkopf.com'],['Lotus','lotusherbals.com'],['Biotique','biotique.com'],["L'Oréal",'loreal.com'],['Lakmé','lakmeindia.com'],['Mamaearth','mamaearth.in'],['WOW','buywow.in'],['Garnier','garnier.in'],['Nivea','nivea.com'],['Matrix','matrix.com'],['Schwarzkopf','schwarzkopf.com'],['Lotus','lotusherbals.com'],['Biotique','biotique.com']];
      foreach($brands as $b): ?>
      <div class="brand-logo-item">
        <div class="brand-logo-icon">
          <img src="https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://<?=$b[1]?>&size=128" alt="<?=$b[0]?> Logo" style="width:65%;height:65%;object-fit:contain;border-radius:4px;">
        </div>
        <span class="brand-logo-name"><?=$b[0]?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     STATS
════════════════════════════════════════ -->
<section class="stats-section section">
  <div class="container">
    <div class="stats-grid" data-stagger>
      <?php foreach([['10+','Years Experience','yr'],['25000','Happy Clients',''],['50+','Beauty Experts',''],['100+','Services','+']] as $s): ?>
      <div class="stat-card">
        <span class="stat-num" data-count="<?=preg_replace('/\D/','',$s[0])?>" data-suffix="<?=preg_replace('/\d/','',$s[0]).$s[2]?>"><?=$s[0].$s[2]?></span>
        <span class="stat-label"><?=$s[1]?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     ABOUT PREVIEW
════════════════════════════════════════ -->
<section class="section section-dark" id="about-preview">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
      <div data-reveal="fade-right">
        <span class="section-eyebrow">Our Story</span>
        <h2 class="section-title">10+ Years of Trusted Beauty</h2>
        <div class="gold-divider"><span class="gold-divider-icon">✦</span></div>
        <p style="margin-bottom:1rem;">Muna's Unisex Salon started from a single chair and a passion for making people feel their best. Today, we're one of Mumbai's most trusted premium beauty destinations.</p>
        <p style="margin-bottom:2rem;">Our highly trained professionals bring expertise, care and love to every service — because you deserve nothing less than excellence.</p>
        <a href="/about.php" class="btn btn-gold">Discover Our Story</a>
      </div>
      <div data-reveal="fade-left" style="position:relative;">
        <div class="img-zoom-wrap" style="border-radius:24px;overflow:hidden;border:1px solid rgba(201,169,110,.2);">
          <img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon interior" style="width:100%;height:420px;object-fit:cover;">
        </div>
        <div class="glass-gold" style="position:absolute;bottom:-1.5rem;right:-1.5rem;padding:1.2rem 1.8rem;border-radius:16px;">
          <div style="font-family:var(--font-serif);font-size:2rem;color:var(--clr-gold);">4.9★</div>
          <div style="font-size:.72rem;color:var(--clr-gray-400);letter-spacing:.12em;">CUSTOMER RATING</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     SERVICES
════════════════════════════════════════ -->
<section class="section" id="services-preview">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">What We Offer</span>
      <h2 class="section-title">Our Premium Services</h2>
      <p class="section-subtitle">From hair to skin, bridal to grooming — every need covered by certified experts.</p>
    </div>
    <div class="services-grid" data-stagger>
      <?php
      $services = $db->query("SELECT * FROM services ORDER BY id ASC LIMIT 8")->fetchAll();
      foreach($services as $s): ?>
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
    <div style="text-align:center;margin-top:3rem;" data-reveal="fade-up">
      <a href="/services.php" class="btn btn-outline">View All Services</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     OFFERS
════════════════════════════════════════ -->
<section class="section section-dark" id="offers">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Limited Time Deals</span>
      <h2 class="section-title">Exclusive Offers</h2>
    </div>
    <div class="offers-grid" data-stagger>
      <?php $offers=[
        ['service-bridal.jpg','Flat 30% OFF','Bridal Packages','Complete bridal makeover at 30% off. Limited slots available!','2026-06-30'],
        ['service-haircolor.jpg','Free Hair Spa','With Hair Color','Complimentary hair spa with any hair coloring service you book.','2026-06-15'],
        ['service-spa.jpg','Couple Spa','Special Package','A romantic spa experience for couples at exclusive pricing.','2026-06-20'],
        ['service-facial.jpg','Festival Beauty','Special Package','Complete festive makeover — hair, skin & makeup in one package.','2026-07-01'],
      ]; foreach($offers as $o): ?>
      <div class="offer-card">
        <img class="offer-card-bg" src="<?=$IMG?>/<?=$o[0]?>" alt="<?=$o[2]?>" loading="lazy">
        <div class="offer-card-content">
          <span class="offer-badge"><?=$o[1]?></span>
          <h3><?=$o[2]?></h3>
          <p><?=$o[3]?></p>
          <div class="countdown" data-countdown="<?=$o[4]?>"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     WHY CHOOSE US
════════════════════════════════════════ -->
<section class="section" id="why-us">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Why Muna's</span>
      <h2 class="section-title">The Muna's Difference</h2>
    </div>
    <div class="grid-3" data-stagger>
      <?php foreach([
        ['🏆','Certified Professionals','Every stylist is certified and trained in latest international techniques.'],
        ['🧹','Hygienic Environment','Hospital-grade sterilization. Fresh towels, tools and products every visit.'],
        ['💎','Premium Products','We only use globally trusted brands — L\'Oréal, Schwarzkopf, Lakmé.'],
        ['💰','Affordable Luxury','World-class salon experience at prices that won\'t break your budget.'],
        ['✨','Personalized Service','Tailored consultation for every client. Your beauty, your way.'],
        ['❤️','10+ Years of Trust','Over a decade of happy clients who keep coming back.'],
      ] as $w): ?>
      <div class="why-card"><div class="why-icon"><?=$w[0]?></div><h3><?=$w[1]?></h3><p><?=$w[2]?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════ -->
<section class="section section-dark" id="testimonials">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Client Love</span>
      <h2 class="section-title">What Our Clients Say</h2>
    </div>
    <div class="testimonials-slider">
      <div class="testimonials-track">
        <?php $reviews=[
          ['P','Priya Sharma','Bride','I had my bridal makeup done at Muna\'s and looked absolutely stunning. The team was professional, patient, and made my wedding day unforgettable!'],
          ['R','Rahul Verma','Regular Client','Best haircut and beard styling I\'ve ever had. The barbers here are real artists. I won\'t go anywhere else now.'],
          ['A','Anita Joshi','Happy Customer','The gold facial left my skin glowing for weeks! Premium products, relaxing atmosphere — totally worth it.'],
          ['S','Sneha Patel','Loyal Client','3 years of visiting Muna\'s and the quality never drops. They remember your preferences. That\'s rare and special.'],
          ['M','Mohan Das','First Timer','First visit and I\'m completely blown away. Ambiance, service, results — everything is 10/10. Already booked next appointment!'],
          ['K','Kavya Nair','Spa Lover','The aromatherapy spa session was pure bliss. I left completely rejuvenated. This is now my monthly self-care ritual.'],
        ]; foreach($reviews as $r): ?>
        <div class="testimonial-card">
          <p class="testimonial-text"><?=$r[3]?></p>
          <div class="testimonial-author">
            <div class="testimonial-avatar-placeholder"><?=$r[0]?></div>
            <div class="testimonial-info">
              <h4><?=$r[1]?></h4>
              <span><?=$r[2]?></span>
              <div class="stars" style="font-size:.8rem;margin-top:.2rem;">★★★★★</div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="slider-controls">
      <button class="slider-btn prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
      <div class="slider-dots-row">
        <?php for($i=0;$i<count($reviews);$i++): ?><button class="slider-dot-sm" aria-label="Testimonial <?=$i+1?>"></button><?php endfor; ?>
      </div>
      <button class="slider-btn next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
    </div>

    <!-- Ratings stats -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-top:3rem;border-top:1px solid rgba(255,255,255,.06);padding-top:2rem;" data-stagger>
      <?php foreach([['4.9','Average Rating','⭐'],['15k+','Repeat Clients','👥'],['98%','Satisfaction Rate','✅']] as $rs): ?>
      <div style="text-align:center;">
        <div style="font-size:3rem;margin-bottom:.3rem;"><?=$rs[2]?></div>
        <div style="font-family:var(--font-serif);font-size:2.2rem;color:var(--clr-gold);"><?=$rs[0]?></div>
        <div style="font-size:.75rem;color:var(--clr-gray-400);letter-spacing:.1em;text-transform:uppercase;"><?=$rs[1]?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     GALLERY PREVIEW
════════════════════════════════════════ -->
<section class="section" id="gallery-preview">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Our Work</span>
      <h2 class="section-title">Gallery Highlights</h2>
    </div>
    <?php
    $gal = $db->query("SELECT * FROM gallery ORDER BY id DESC LIMIT 6")->fetchAll();
    $heights=['300px','240px','260px','250px','280px','240px'];
    ?>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;" data-stagger>
      <?php foreach($gal as $i=>$g): ?>
      <div class="gallery-item img-zoom-wrap" style="height:<?=$heights[$i % count($heights)]?>" data-cat="<?=htmlspecialchars($g['category'])?>">
        <img src="<?=$IMG?>/<?=htmlspecialchars($g['image'])?>" alt="<?=htmlspecialchars($g['title'])?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
        <div class="gallery-overlay">
          <div class="gallery-zoom-icon"><i class="fas fa-expand"></i></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2rem;" data-reveal="fade-up">
      <a href="/gallery.php" class="btn btn-outline">View Full Gallery</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     BLOG PREVIEW
════════════════════════════════════════ -->
<section class="section section-dark" id="blog-preview">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Beauty Insights</span>
      <h2 class="section-title">From Our Blog</h2>
    </div>
    <div class="grid-3" data-stagger>
      <?php 
      $blogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3")->fetchAll();
      foreach($blogs as $b): ?>
      <div class="blog-card hover-lift">
        <div class="blog-card-img">
          <img src="<?=$IMG?>/<?=htmlspecialchars($b['image'])?>" alt="<?=htmlspecialchars($b['title'])?>" loading="lazy">
        </div>
        <div class="blog-card-body">
          <div class="blog-card-meta">
            <span class="blog-cat"><?=htmlspecialchars($b['category'])?></span>
            <span><i class="fas fa-calendar"></i> <?=date('M Y', strtotime($b['created_at']))?></span>
          </div>
          <h3><?=htmlspecialchars($b['title'])?></h3>
          <p><?=htmlspecialchars($b['excerpt'])?></p>
          <a href="/blog.php" class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem;" data-reveal="fade-up">
      <a href="/blog.php" class="btn btn-outline">View All Blogs</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     CTA
════════════════════════════════════════ -->
<section class="cta-section section" style="background-image:url('<?=$IMG?>/about-interior.jpg');background-size:cover;background-position:center;position:relative;">
  <div style="position:absolute;inset:0;background:rgba(5,3,1,.88);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <span class="section-eyebrow">Ready for a Transformation?</span>
    <h2 class="section-title" style="font-size:var(--fs-4xl);">Book Your Premium Salon<br>Experience Today</h2>
    <p style="color:rgba(255,255,255,.65);max-width:500px;margin:1rem auto 0;">Join 25,000+ happy clients who trust Muna's for their beauty needs.</p>
    <div class="cta-actions" style="margin-top:2rem;">
      <a href="/book-appointment.php" class="btn btn-gold">✦ Book Appointment</a>
      <a href="https://wa.me/<?=SITE_WHATSAPP?>" target="_blank" class="btn btn-outline"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
      <a href="tel:<?=preg_replace('/\s+/','',SITE_PHONE)?>" class="btn btn-dark"><i class="fas fa-phone-alt"></i> Call Now</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     FAQ
════════════════════════════════════════ -->
<section class="section" id="faq">
  <div class="container container-narrow">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Common Questions</span>
      <h2 class="section-title">Frequently Asked</h2>
    </div>
    <div class="faq-list" data-reveal="fade-up">
      <?php $faqs=[
        ['Do I need an appointment?','Walk-ins are welcome but appointments are recommended to avoid waiting. Book online or call/WhatsApp us anytime.'],
        ['What payment methods do you accept?','We accept cash, all major cards, UPI (GPay, PhonePe, Paytm) and net banking.'],
        ['Do you offer home services?','Yes! We offer home bridal packages and select services. Contact us to discuss your requirements.'],
        ['What brands do you use?',"We exclusively use premium brands — L'Oréal, Schwarzkopf, Lakmé, Kerastase and Mamaearth."],
        ['Is parking available?','Yes, free client parking is available right next to our salon.'],
        ['Do you have a loyalty programme?','Yes! Regular clients earn points on every visit redeemable for free services and discounts.'],
      ]; foreach($faqs as $faq): ?>
      <div class="faq-item">
        <div class="faq-question" role="button" tabindex="0">
          <h3><?=$faq[0]?></h3>
          <i class="fas fa-plus faq-icon"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner"><?=$faq[1]?></div></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
  <button class="lightbox-close" aria-label="Close"><i class="fas fa-times"></i></button>
  <button class="lightbox-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
  <img class="lightbox-img" src="" alt="Gallery image">
  <button class="lightbox-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
