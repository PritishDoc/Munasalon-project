<?php
require_once __DIR__ . '/php/config.php';
$page_title  = "About Us – Muna's Unisex Salon Story";
$page_desc   = "Learn about Muna's Unisex Salon – 10+ years of premium beauty services, our journey, team and mission.";
$active_page = 'about';
$IMG = '/images';
include __DIR__ . '/php/header.php';
?>

<!-- PAGE HERO -->
<section style="padding:12rem 0 6rem;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;"><img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon interior" style="width:100%;height:100%;object-fit:cover;"></div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.90) 0%,rgba(10,6,2,.75) 100%);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <span class="section-eyebrow">Who We Are</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Our Story of Beauty & Excellence</h1>
    <p class="section-subtitle" style="margin:1rem auto;">From a single chair to a premium brand — 10+ years of passion, expertise and love for beauty.</p>
  </div>
</section>

<!-- STORY -->
<section class="section section-dark">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
      <div data-reveal="fade-right">
        <span class="section-eyebrow">The Beginning</span>
        <h2 class="section-title" style="font-size:var(--fs-3xl);">From a Dream to a Premium Brand</h2>
        <div class="gold-divider"><span class="gold-divider-icon">✦</span></div>
        <p style="margin-bottom:1rem;">Muna's Unisex Salon was founded in 2014 with a simple vision: to make premium beauty services accessible to everyone. What started as a 3-chair setup in a small neighbourhood space has grown into a full-service luxury salon & spa.</p>
        <p style="margin-bottom:1rem;">Our founder, Muna, with over 15 years of experience in the beauty industry, built this salon on the pillars of quality, hygiene, innovation and genuine care.</p>
        <p style="margin-bottom:2rem;">Today, we serve 25,000+ happy clients and employ 50+ certified beauty professionals — but our commitment to personal, heartfelt service remains unchanged.</p>
        <a href="/book-appointment.php" class="btn btn-gold">Book an Appointment</a>
      </div>
      <div data-reveal="fade-left">
        <div class="img-zoom-wrap" style="border-radius:24px;overflow:hidden;border:1px solid rgba(201,169,110,.2);">
          <img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon interior" style="width:100%;height:480px;object-fit:cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats-section section">
  <div class="container">
    <div class="stats-grid" data-stagger>
      <?php foreach([['10+','Years Experience','yr'],['25000','Happy Clients',''],['50+','Beauty Experts',''],['98','Satisfaction Rate','%']] as $s): ?>
      <div class="stat-card">
        <span class="stat-num" data-count="<?=preg_replace('/\D/','',$s[0])?>" data-suffix="<?=preg_replace('/\d/','',$s[0]).$s[2]?>"><?=$s[0].$s[2]?></span>
        <span class="stat-label"><?=$s[1]?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MISSION & VISION -->
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Our Purpose</span>
      <h2 class="section-title">Mission & Vision</h2>
    </div>
    <div class="grid-2" data-stagger>
      <div class="glass-gold" style="padding:2.5rem;border-radius:var(--radius-lg);">
        <div style="font-size:2.5rem;margin-bottom:1rem;">🎯</div>
        <h3 style="font-size:var(--fs-xl);color:var(--clr-gold-light);margin-bottom:.75rem;">Our Mission</h3>
        <p>To provide every client — man, woman or child — with a transformative beauty experience that boosts confidence and well-being, using premium products, expert techniques and genuine care.</p>
      </div>
      <div class="glass-gold" style="padding:2.5rem;border-radius:var(--radius-lg);">
        <div style="font-size:2.5rem;margin-bottom:1rem;">🔭</div>
        <h3 style="font-size:var(--fs-xl);color:var(--clr-gold-light);margin-bottom:.75rem;">Our Vision</h3>
        <p>To be the most loved and trusted unisex salon brand in India — known for innovation, inclusivity, consistency and an unwavering commitment to making every client feel beautiful.</p>
      </div>
    </div>
  </div>
</section>

<!-- TIMELINE -->
<section class="section section-dark">
  <div class="container container-narrow">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Our Journey</span>
      <h2 class="section-title">A Decade of Growth</h2>
    </div>
    <div class="timeline" data-reveal="fade-up">
      <?php $timeline=[
        ['2014','Humble Beginnings','Opened our first 3-chair salon with a team of 4 and a big dream.'],
        ['2016','First 1,000 Clients','Crossed 1,000 happy clients. Expanded to 10 chairs and added bridal services.'],
        ['2018','Premium Rebrand','Rebranded to a luxury concept with premium interiors, spa services and brand partnerships.'],
        ['2020','Digital Expansion','Launched online booking and home service offerings to serve clients anywhere.'],
        ['2022','Award Recognition','Won Best Unisex Salon at the Mumbai Beauty Awards and expanded our flagship location.'],
        ['2024','25k+ Happy Clients','Celebrated a milestone of 25,000 clients served by 50+ expert professionals.'],
      ]; foreach($timeline as $t): ?>
      <div class="timeline-item">
        <div class="timeline-year"><?=$t[0]?></div>
        <h3><?=$t[1]?></h3>
        <p><?=$t[2]?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Meet the Experts</span>
      <h2 class="section-title">Our Star Team</h2>
      <p class="section-subtitle">Certified professionals passionate about making you look and feel your absolute best.</p>
    </div>
    <div class="team-grid" id="team-grid" data-stagger>
      <?php 
      $allTeam = [
        ['team-founder.jpg','Muna','Founder & Creative Director','15+ yrs experience'],
        ['service-haircut.jpg','Arjun Shah','Master Hair Stylist','International certified'],
        ['service-bridal.jpg','Meena Rao','Bridal Makeup Artist','500+ brides served'],
        ['service-spa.jpg','Dev Patel','Spa & Wellness Expert','Aromatherapy specialist'],
        ['service-nails.jpg','Riya Joshi','Nail Art Specialist','Award-winning artist'],
        ['gallery-grooming.jpg','Kiran Nair','Men\'s Grooming Expert','Barber of the Year 2023'],
        ['service-facial.jpg','Sunita Verma','Skincare Specialist','Dermatologist trained'],
        ['service-haircolor.jpg','Rohan Das','Color Technician','L\'Oréal certified master'],
        ['service-bridal.jpg','Anjali Mehta','Bridal & Editorial MUA','HD & Airbrush expert'],
        ['service-spa.jpg','Vikram Raj','Senior Massage Therapist','Ayurveda specialist'],
        ['service-nails.jpg','Neha Singh','Nail Technician','Gel & Nail Art pro'],
        ['service-haircut.jpg','Kabir Khan','Barber','Precision & fades'],
      ];
      $initialTeam = array_slice($allTeam, 0, 8); // Show first 8 initially
      $hiddenTeam = array_slice($allTeam, 8);    // Remaining hidden
      foreach($initialTeam as $t): ?>
        <div class="team-card" data-reveal="fade-up">
          <div class="team-img-wrap">
            <img src="<?=$IMG?>/<?=$t[0]?>" alt="<?=$t[1]?>">
          </div>
          <h3><?=$t[1]?></h3>
          <div class="team-role"><?=$t[2]?></div>
          <p style="font-size:.8rem;color:var(--clr-gold);margin-top:.3rem;"><?=$t[3]?></p>
        </div>
      <?php endforeach; ?>
      
      <?php foreach($hiddenTeam as $t): ?>
        <div class="team-card team-hidden" data-reveal="fade-up">
          <div class="team-img-wrap">
            <img src="<?=$IMG?>/<?=$t[0]?>" alt="<?=$t[1]?>">
          </div>
          <h3><?=$t[1]?></h3>
          <div class="team-role"><?=$t[2]?></div>
          <p style="font-size:.8rem;color:var(--clr-gold);margin-top:.3rem;"><?=$t[3]?></p>
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- See More / Show Less Button for Team -->
    <?php if(count($hiddenTeam) > 0): ?>
    <div class="team-see-more-container" data-reveal="fade-up">
      <button id="see-more-team-btn" class="btn-see-more">
        See More Team <i class="fas fa-arrow-down"></i>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- FOUNDER MESSAGE -->
<section class="section section-dark">
  <div class="container" style="display:grid;grid-template-columns:280px 1fr;gap:4rem;align-items:center;">
    <div data-reveal="fade-right">
      <img src="<?=$IMG?>/team-founder.jpg" alt="Muna – Founder" style="width:100%;border-radius:var(--radius-lg);border:2px solid rgba(201,169,110,.3);">
    </div>
    <div data-reveal="fade-left">
      <span class="section-eyebrow">A Word from Our Founder</span>
      <blockquote style="font-family:var(--font-serif);font-size:var(--fs-xl);color:var(--clr-gold-light);line-height:1.7;margin:1.5rem 0;font-style:italic;">
        "Beauty is not about perfection — it's about expressing your authentic self with confidence. At Muna's, we don't just do hair or makeup. We help every client discover their most beautiful, confident version. That has always been, and always will be, our purpose."
      </blockquote>
      <p style="color:var(--clr-gold);font-weight:600;font-family:var(--font-sans);">— Muna, Founder & Creative Director</p>
    </div>
  </div>
</section>

<section class="cta-section section" style="background-image:url('<?=$IMG?>/about-interior.jpg');background-size:cover;position:relative;">
  <div style="position:absolute;inset:0;background:rgba(5,3,1,.88);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <h2 class="section-title">Experience the Muna's Difference</h2>
    <div class="cta-actions" style="margin-top:2rem;">
      <a href="/book-appointment.php" class="btn btn-gold">✦ Book Appointment</a>
      <a href="/services.php" class="btn btn-outline">View Services</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/php/footer.php'; ?>