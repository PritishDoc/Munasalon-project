<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = "Book Appointment – Muna's Unisex Salon";
$page_desc   = "Book your premium salon appointment at Muna's Unisex Salon. Choose your service, expert, date & time.";
$active_page = 'book';
$IMG = '/images';

$booking_success = false;
$booking_ref = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['book_service'])) {
    // Basic validation
    if (!empty($_POST['book_name']) && !empty($_POST['book_phone']) && !empty($_POST['book_service']) && !empty($_POST['book_date']) && !empty($_POST['book_time'])) {
        
        $name = $_POST['book_name'];
        $email = $_POST['book_email'] ?? '';
        $phone = $_POST['book_phone'];
        $service = $_POST['book_service'] . ' (Expert: ' . ($_POST['book_staff'] ?? 'Any') . ')';
        $date = $_POST['book_date'];
        $time = $_POST['book_time'];
        $message = $_POST['book_notes'] ?? '';

        try {
            $stmt = $db->prepare("INSERT INTO bookings (name, email, phone, service, booking_date, booking_time, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$name, $email, $phone, $service, $date, $time, $message]);
            
            $booking_success = true;
            $booking_ref = 'MNA-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } catch(PDOException $e) {
            // Silently fail or log for now
        }
    }
}
include __DIR__ . '/php/header.php';
?>

<!-- HERO -->
<section style="padding:12rem 0 5rem;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;">
    <img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon" style="width:100%;height:100%;object-fit:cover;">
  </div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(5,3,1,.92),rgba(10,6,2,.80));"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <span class="section-eyebrow">Reserve Your Seat</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">Book Your Appointment</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Choose your service, pick your expert and select a time that works for you.</p>
  </div>
</section>

<section class="section">
  <div class="container" style="display:grid;grid-template-columns:1fr 380px;gap:3rem;align-items:start;">

    <!-- FORM COL -->
    <div>
      <?php if($booking_success): ?>
      <!-- SUCCESS STATE -->
      <div style="background:linear-gradient(135deg,#0a1a0a,#0d1f0d);border:1px solid rgba(74,222,128,.2);border-radius:var(--radius-xl);padding:3rem;text-align:center;">
        <div style="font-size:4rem;margin-bottom:1.5rem;">🎉</div>
        <h2 style="font-size:var(--fs-3xl);color:#4ade80;margin-bottom:.75rem;">Booking Confirmed!</h2>
        <div style="display:inline-block;background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.2);border-radius:var(--radius-md);padding:.6rem 1.5rem;margin-bottom:1.5rem;">
          <span style="font-size:.7rem;color:#4ade80;letter-spacing:.2em;">REFERENCE: <?=$booking_ref?></span>
        </div>
        <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:var(--radius-md);padding:1.5rem;margin-bottom:2rem;text-align:left;">
          <table style="width:100%;border-collapse:collapse;">
            <?php foreach(['Service'=>htmlspecialchars($_POST['book_service']),'Expert'=>htmlspecialchars($_POST['book_staff']??'No preference'),'Date'=>htmlspecialchars($_POST['book_date']),'Time'=>htmlspecialchars($_POST['book_time']),'Name'=>htmlspecialchars($_POST['book_name']),'Phone'=>htmlspecialchars($_POST['book_phone'])] as $k=>$v): ?>
            <tr>
              <td style="padding:.5rem 0;color:var(--clr-gray-400);font-size:.82rem;width:35%;"><?=$k?></td>
              <td style="color:var(--clr-white);font-weight:500;font-size:.9rem;"><?=$v?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div>
        <p style="margin-bottom:1.5rem;">Our team will confirm your appointment within 1 hour via call or WhatsApp.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a href="/" class="btn btn-gold">Back to Home</a>
          <a href="https://wa.me/<?=SITE_WHATSAPP?>" target="_blank" class="btn btn-outline"><i class="fab fa-whatsapp"></i> Chat with Us</a>
        </div>
      </div>

      <?php else: ?>
      <!-- BOOKING FORM -->
      <div class="booking-card">
        <!-- Steps -->
        <div class="booking-steps" id="booking-steps">
          <?php foreach(['Choose Service','Your Details','Confirm'] as $i=>$step): ?>
          <div class="booking-step <?=$i===0?'active':''?>" id="step-indicator-<?=$i+1?>">
            <div class="step-num"><?=$i+1?></div>
            <span class="step-label"><?=$step?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <form id="booking-form" method="POST" action="/book-appointment.php" novalidate>

          <!-- ── STEP 1: Service & Schedule ── -->
          <div class="booking-step-panel" id="panel-1">
            <h3 style="font-size:var(--fs-xl);color:var(--clr-gold-light);margin-bottom:1.5rem;">Select Your Service</h3>

            <div class="form-group">
              <label for="book_service">Service <span style="color:var(--clr-gold);">*</span></label>
              <select id="book_service" name="book_service" class="form-control" required>
                <option value="">— Select a service —</option>
                <?php
                $all_services = $db->query("SELECT * FROM services ORDER BY category ASC, title ASC")->fetchAll();
                $services_by_category = [];
                foreach ($all_services as $s) {
                    $services_by_category[$s['category']][] = $s;
                }
                foreach ($services_by_category as $cat => $srvs):
                ?>
                <optgroup label="<?= htmlspecialchars($cat) ?>">
                  <?php foreach ($srvs as $s): ?>
                  <option value="<?= htmlspecialchars($s['title'] . ' – ' . $s['price']) ?>"><?= htmlspecialchars($s['title'] . ' – ' . $s['price']) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="book_staff">Preferred Expert</label>
              <select id="book_staff" name="book_staff" class="form-control">
                <option value="">No preference (assign best available)</option>
                <?php foreach(['Muna (Founder) – All Services','Arjun Shah – Hair Specialist','Meena Rao – Bridal & Makeup','Dev Patel – Spa & Wellness','Riya Joshi – Nail Art','Kiran Nair – Men\'s Grooming','Sunita Verma – Skincare'] as $st): ?>
                <option><?=$st?></option><?php endforeach; ?>
              </select>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="book_date">Preferred Date <span style="color:var(--clr-gold);">*</span></label>
                <input type="date" id="book_date" name="book_date" class="form-control" min="<?=date('Y-m-d')?>" required>
              </div>
              <div class="form-group">
                <label for="book_time">Preferred Time <span style="color:var(--clr-gold);">*</span></label>
                <select id="book_time" name="book_time" class="form-control" required>
                  <option value="">— Select time —</option>
                  <?php foreach(['9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM','6:30 PM','7:00 PM','7:30 PM'] as $t): ?>
                  <option><?=$t?></option><?php endforeach; ?>
                </select>
              </div>
            </div>

            <button type="button" class="btn btn-gold" style="width:100%;justify-content:center;" onclick="bookGoStep(2)">
              Next: Your Details <i class="fas fa-arrow-right"></i>
            </button>
          </div>

          <!-- ── STEP 2: Personal Details ── -->
          <div class="booking-step-panel" id="panel-2" style="display:none;">
            <h3 style="font-size:var(--fs-xl);color:var(--clr-gold-light);margin-bottom:1.5rem;">Your Contact Details</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="book_name">Full Name <span style="color:var(--clr-gold);">*</span></label>
                <input type="text" id="book_name" name="book_name" class="form-control" placeholder="Your full name" required>
              </div>
              <div class="form-group">
                <label for="book_email">Email Address</label>
                <input type="email" id="book_email" name="book_email" class="form-control" placeholder="your@email.com">
              </div>
            </div>
            <div class="form-group">
              <label for="book_phone">Phone Number <span style="color:var(--clr-gold);">*</span></label>
              <input type="tel" id="book_phone" name="book_phone" class="form-control" placeholder="+91 98765 43210" required>
            </div>
            <div class="form-group">
              <label for="book_notes">Special Requests / Notes</label>
              <textarea id="book_notes" name="book_notes" class="form-control" rows="3" placeholder="Allergies, skin sensitivities, preferences or any special requests..." style="resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:1rem;">
              <button type="button" class="btn btn-dark" style="flex:1;justify-content:center;" onclick="bookGoStep(1)">
                <i class="fas fa-arrow-left"></i> Back
              </button>
              <button type="button" class="btn btn-gold" style="flex:2;justify-content:center;" onclick="bookGoStep(3)">
                Review Booking <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ── STEP 3: Confirm ── -->
          <div class="booking-step-panel" id="panel-3" style="display:none;">
            <h3 style="font-size:var(--fs-xl);color:var(--clr-gold-light);margin-bottom:1.5rem;">Confirm Your Booking</h3>
            <div id="booking-summary" style="background:rgba(201,169,110,.05);border:1px solid rgba(201,169,110,.15);border-radius:var(--radius-md);padding:1.5rem;margin-bottom:1.5rem;"></div>
            <p style="font-size:.82rem;color:var(--clr-gray-500);margin-bottom:1.5rem;">
              <i class="fas fa-info-circle" style="color:var(--clr-gold);"></i>
              By booking you agree to our cancellation policy. You may cancel up to 2 hours before your appointment.
            </p>
            <div style="display:flex;gap:1rem;">
              <button type="button" class="btn btn-dark" style="flex:1;justify-content:center;" onclick="bookGoStep(2)">
                <i class="fas fa-edit"></i> Edit
              </button>
              <button type="submit" class="btn btn-gold" style="flex:2;justify-content:center;">
                ✦ Confirm Appointment
              </button>
            </div>
          </div>

        </form>
      </div>
      <?php endif; ?>
    </div>

    <!-- SIDEBAR -->
    <div style="position:sticky;top:100px;" data-reveal="fade-left">
      <div class="glass-gold" style="border-radius:var(--radius-xl);overflow:hidden;">
        <img src="<?=$IMG?>/about-interior.jpg" alt="Muna's Salon" style="width:100%;height:180px;object-fit:cover;">
        <div style="padding:1.5rem;">
          <h3 style="font-size:var(--fs-lg);color:var(--clr-gold-light);margin-bottom:1rem;">Quick Contact</h3>
          <div style="display:flex;flex-direction:column;gap:.75rem;">
            <a href="tel:<?=preg_replace('/\s+/','',SITE_PHONE)?>" class="btn btn-dark" style="justify-content:center;font-size:.75rem;"><i class="fas fa-phone-alt"></i> <?=SITE_PHONE?></a>
            <a href="https://wa.me/<?=SITE_WHATSAPP?>?text=Hi!%20I'd%20like%20to%20book%20an%20appointment." target="_blank" class="btn btn-outline" style="justify-content:center;font-size:.75rem;"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
          </div>
          <hr style="border:none;border-top:1px solid rgba(255,255,255,.08);margin:1.25rem 0;">
          <h4 style="font-size:var(--fs-sm);color:var(--clr-gold);margin-bottom:.75rem;">Salon Hours</h4>
          <p style="font-size:.82rem;line-height:1.6;"><?=SITE_HOURS?></p>
          <hr style="border:none;border-top:1px solid rgba(255,255,255,.08);margin:1.25rem 0;">
          <div style="text-align:center;">
            <div style="font-family:var(--font-serif);font-size:2rem;color:var(--clr-gold);">4.9 ★</div>
            <div style="font-size:.72rem;color:var(--clr-gray-400);letter-spacing:.12em;">2,400+ REVIEWS</div>
          </div>
        </div>
      </div>

      <!-- Popular services -->
      <div style="margin-top:1.5rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:var(--radius-lg);padding:1.25rem;">
        <h4 style="font-size:var(--fs-sm);color:var(--clr-gold);margin-bottom:1rem;">Most Booked Services</h4>
        <?php foreach(['Hair Cut & Styling – ₹299','Facial & Cleanup – ₹999','Bridal Makeup – ₹5,999','Full Body Spa – ₹2,999'] as $ps): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:.8rem;">
          <span>✦ <?=explode('–',$ps)[0]?></span>
          <span style="color:var(--clr-gold);">₹<?=explode('₹',$ps)[1]?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- POLICY -->
<section class="section section-dark">
  <div class="container">
    <div class="section-header" data-reveal="fade-up">
      <span class="section-eyebrow">Good to Know</span>
      <h2 class="section-title">Booking Policies</h2>
    </div>
    <div class="grid-3" data-stagger>
      <?php foreach([
        ['📅','Cancellation Policy','Cancel or reschedule up to 2 hours before your appointment for a full refund.'],
        ['⏰','Arrival Time','Please arrive 5–10 minutes early. Late arrivals may result in reduced service time.'],
        ['💳','Payment','Pay at the salon after your service — cash, card or UPI all accepted.'],
      ] as $p): ?>
      <div class="why-card"><div class="why-icon"><?=$p[0]?></div><h3><?=$p[1]?></h3><p><?=$p[2]?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
function bookGoStep(n) {
  const total = 3;
  for (let i = 1; i <= total; i++) {
    const panel = document.getElementById('panel-' + i);
    const ind   = document.getElementById('step-indicator-' + i);
    if (panel) panel.style.display = i === n ? '' : 'none';
    if (ind)   { ind.classList.toggle('active', i === n); ind.classList.toggle('done', i < n); }
  }
  // Build summary on step 3
  if (n === 3) {
    const f = (id) => document.getElementById(id)?.value || '—';
    const rows = [
      ['Service',  f('book_service')],
      ['Expert',   f('book_staff') || 'No preference'],
      ['Date',     f('book_date')],
      ['Time',     f('book_time')],
      ['Name',     f('book_name')],
      ['Phone',    f('book_phone')],
      ['Notes',    f('book_notes') || 'None'],
    ];
    document.getElementById('booking-summary').innerHTML =
      '<table style="width:100%;border-collapse:collapse;">' +
      rows.map(r => `<tr><td style="padding:.45rem 0;color:var(--clr-gray-400);font-size:.82rem;width:35%">${r[0]}</td><td style="color:#fff;font-size:.9rem;font-weight:500;">${r[1]}</td></tr>`).join('') +
      '</table>';
  }
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php include __DIR__ . '/php/footer.php'; ?>
