<?php
require_once __DIR__ . '/php/config.php';
$page_title  = "Contact Us – Muna's Unisex Salon";
$page_desc   = "Contact Muna's Unisex Salon for appointments, queries or feedback. Visit, call or WhatsApp us today.";
$active_page = 'contact';
$IMG = '/images';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_name'])) {
    $name    = htmlspecialchars(trim($_POST['contact_name'] ?? ''));
    $email   = filter_var(trim($_POST['contact_email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone   = htmlspecialchars(trim($_POST['contact_phone'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['contact_subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['contact_message'] ?? ''));
    if ($name && $email && $message) {
        $success = "Thank you, $name! Your message has been sent. We'll reply within 24 hours.";
    } else {
        $error = 'Please fill in all required fields (Name, Email, Message).';
    }
}
include __DIR__ . '/php/header.php';
?>

<!-- HERO -->
<section style="padding:12rem 0 5rem;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;">
    <img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon" style="width:100%;height:100%;object-fit:cover;">
  </div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.92) 0%,rgba(10,6,2,.78) 100%);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <span class="section-eyebrow">We'd Love to Hear from You</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Get in Touch</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Reach us by form, phone, WhatsApp or just walk in — we're always happy to help.</p>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="section">
  <div class="container">
    <div class="contact-grid">

      <!-- INFO SIDE -->
      <div data-reveal="fade-right">
        <h2 style="font-size:var(--fs-2xl);margin-bottom:.5rem;color:var(--clr-white);">Salon Information</h2>
        <div class="gold-divider" style="justify-content:flex-start;margin-block:.75rem 1.5rem;">
          <span class="gold-divider-icon">✦</span>
          <div style="flex:1;max-width:80px;height:1px;background:linear-gradient(to right,var(--clr-gold),transparent);"></div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <h4>Visit Us</h4>
            <p><?=SITE_ADDRESS?></p>
            <a href="https://maps.google.com" target="_blank" style="color:var(--clr-gold);font-size:.8rem;">Get Directions →</a>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
          <div>
            <h4>Call Us</h4>
            <p><a href="tel:<?=preg_replace('/\s+/','',SITE_PHONE)?>" style="color:var(--clr-gold);"><?=SITE_PHONE?></a></p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h4>Email Us</h4>
            <p><a href="mailto:<?=SITE_EMAIL?>" style="color:var(--clr-gold);"><?=SITE_EMAIL?></a></p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h4>Working Hours</h4>
            <p><?=SITE_HOURS?></p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon" style="background:rgba(37,211,102,.15);color:#25d366;"><i class="fab fa-whatsapp"></i></div>
          <div>
            <h4>WhatsApp Us</h4>
            <a href="https://wa.me/<?=SITE_WHATSAPP?>?text=Hi%20Muna's%20Salon!%20I%27d%20like%20to%20inquire." target="_blank" class="btn btn-gold" style="margin-top:.5rem;padding:.5rem 1.2rem;font-size:.72rem;">
              <i class="fab fa-whatsapp"></i> Open WhatsApp Chat
            </a>
          </div>
        </div>

        <!-- Social -->
        <div style="margin-top:2rem;">
          <h4 style="font-size:var(--fs-sm);color:var(--clr-gold);letter-spacing:.15em;text-transform:uppercase;margin-bottom:1rem;">Follow Us</h4>
          <div style="display:flex;gap:.75rem;">
            <?php foreach([['fab fa-instagram','Instagram',SITE_INSTAGRAM,'#e4405f'],['fab fa-facebook-f','Facebook',SITE_FACEBOOK,'#1877f2'],['fab fa-youtube','YouTube',SITE_YOUTUBE,'#ff0000']] as $s): ?>
            <a href="<?=$s[2]?>" target="_blank" rel="noopener" style="width:44px;height:44px;border-radius:50%;border:1px solid rgba(201,169,110,.25);display:flex;align-items:center;justify-content:center;color:var(--clr-gold);font-size:.95rem;transition:all .3s;" aria-label="<?=$s[1]?>"
               onmouseover="this.style.background='<?=$s[3]?>20';this.style.borderColor='<?=$s[3]?>';this.style.color='<?=$s[3]?>'"
               onmouseout="this.style.background='';this.style.borderColor='rgba(201,169,110,.25)';this.style.color='var(--clr-gold)'">
              <i class="<?=$s[0]?>"></i>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Map -->
        <div style="margin-top:2rem;border-radius:var(--radius-lg);overflow:hidden;border:1px solid rgba(201,169,110,.15);">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241316.6433050693!2d72.74109995!3d19.0822508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai!5e0!3m2!1sen!2sin!4v1234567890"
            width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy" title="Muna's Salon location"></iframe>
        </div>
      </div>

      <!-- FORM SIDE -->
      <div data-reveal="fade-left">
        <div class="booking-card">
          <h2 style="font-size:var(--fs-2xl);margin-bottom:.5rem;color:var(--clr-white);">Send Us a Message</h2>
          <p style="font-size:var(--fs-sm);margin-bottom:1.5rem;">Fill in the form below and we'll get back to you within 24 hours.</p>

          <?php if($success): ?>
          <div style="background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.3);border-radius:var(--radius-md);padding:1.2rem;margin-bottom:1.5rem;color:#4ade80;display:flex;align-items:center;gap:.75rem;">
            <i class="fas fa-check-circle" style="font-size:1.3rem;"></i> <?=$success?>
          </div>
          <?php endif; ?>
          <?php if($error): ?>
          <div style="background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.3);border-radius:var(--radius-md);padding:1.2rem;margin-bottom:1.5rem;color:#f87171;display:flex;align-items:center;gap:.75rem;">
            <i class="fas fa-exclamation-circle" style="font-size:1.3rem;"></i> <?=$error?>
          </div>
          <?php endif; ?>

          <form id="contact-form" method="POST" action="/contact.php" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="contact_name">Full Name <span style="color:var(--clr-gold);">*</span></label>
                <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Your full name" required value="<?=htmlspecialchars($_POST['contact_name']??'')?>">
              </div>
              <div class="form-group">
                <label for="contact_email">Email <span style="color:var(--clr-gold);">*</span></label>
                <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="your@email.com" required value="<?=htmlspecialchars($_POST['contact_email']??'')?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="contact_phone">Phone Number</label>
                <input type="tel" id="contact_phone" name="contact_phone" class="form-control" placeholder="+91 98765 43210" value="<?=htmlspecialchars($_POST['contact_phone']??'')?>">
              </div>
              <div class="form-group">
                <label for="contact_subject">Subject</label>
                <select id="contact_subject" name="contact_subject" class="form-control">
                  <option value="">— Select subject —</option>
                  <?php foreach(['Book Appointment','Service Enquiry','Product Enquiry','Bridal Package','Pricing Query','Other'] as $opt):
                    $sel = ($_POST['contact_subject']??'')===$opt?'selected':''; ?>
                  <option <?=$sel?>><?=$opt?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="contact_message">Message <span style="color:var(--clr-gold);">*</span></label>
              <textarea id="contact_message" name="contact_message" class="form-control" rows="5" placeholder="How can we help you today?" required style="resize:vertical;"><?=htmlspecialchars($_POST['contact_message']??'')?></textarea>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:1rem;">
              Send Message <i class="fas fa-paper-plane"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY VISIT US STRIP -->
<section class="section section-dark">
  <div class="container">
    <div class="grid-3" data-stagger>
      <?php foreach([
        ['🅿️','Free Parking','Complimentary parking available right next to our salon.'],
        ['💳','All Payments','Cash, cards, UPI, wallets — all payment modes accepted.'],
        ['📅','Easy Booking','Book online, call or WhatsApp. Same-day slots often available.'],
      ] as $w): ?>
      <div class="why-card">
        <div class="why-icon"><?=$w[0]?></div>
        <h3><?=$w[1]?></h3>
        <p><?=$w[2]?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section section" style="background-image:url('<?=$IMG?>/about-interior.jpg');background-size:cover;position:relative;">
  <div style="position:absolute;inset:0;background:rgba(5,3,1,.88);"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <h2 class="section-title">Ready to Book Your Appointment?</h2>
    <p style="color:rgba(255,255,255,.65);max-width:460px;margin:1rem auto 0;">Skip the wait — book online in seconds and secure your preferred time slot.</p>
    <div class="cta-actions" style="margin-top:2rem;">
      <a href="/book-appointment.php" class="btn btn-gold">✦ Book Appointment</a>
      <a href="https://wa.me/<?=SITE_WHATSAPP?>?text=Hi%20Muna's%20Salon!" target="_blank" class="btn btn-outline"><i class="fab fa-whatsapp"></i> WhatsApp</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/php/footer.php'; ?>
