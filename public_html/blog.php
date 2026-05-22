<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
$page_title  = 'Blog – Beauty Tips & Guides';
$page_desc   = 'Read expert beauty tips, hair care guides, skin care routines and bridal prep advice on the LuxeGlow blog.';
$active_page = 'blog';
include __DIR__ . '/php/header.php';

$all_blogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
$featured = null;
if (count($all_blogs) > 0) {
    $featured = $all_blogs[0];
    $posts = array_slice($all_blogs, 1);
} else {
    $posts = [];
}
?>

<section style="padding:12rem 0 4rem;background:linear-gradient(135deg,#0a0500,#1a1000);text-align:center;position:relative;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 50% 50%,rgba(201,169,110,.07),transparent);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <span class="section-eyebrow">Beauty Insights</span>
    <h1 class="section-title" style="font-size:var(--fs-4xl);">LuxeGlow Beauty Blog</h1>
    <p class="section-subtitle" style="margin:1rem auto;">Expert tips, trends and guides from our beauty professionals.</p>
  </div>
</section>

<!-- Featured Post -->
<?php if ($featured): ?>
<section class="section section-dark">
  <div class="container">
    <div class="section-header" data-reveal="fade-up"><span class="section-eyebrow">Editor's Pick</span><h2 class="section-title">Featured Article</h2></div>
    <div class="glass-gold" style="border-radius:var(--radius-xl);overflow:hidden;display:grid;grid-template-columns:1fr 1.2fr;gap:0;" data-reveal="fade-up">
      <div style="background:linear-gradient(135deg,#2a1500,#0a0500);display:flex;align-items:center;justify-content:center;min-height:360px;">
        <img src="/images/<?=htmlspecialchars($featured['image'])?>" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div style="padding:3rem;">
        <span class="blog-cat"><?=htmlspecialchars($featured['category'])?></span>
        <h2 style="font-size:var(--fs-2xl);color:var(--clr-white);margin:1rem 0;"><?=htmlspecialchars($featured['title'])?></h2>
        <p style="margin-bottom:1.5rem;"><?=htmlspecialchars($featured['excerpt'])?></p>
        <div class="blog-card-meta" style="margin-bottom:1.5rem;">
          <span><i class="fas fa-calendar"></i> <?=date('M d, Y', strtotime($featured['created_at']))?></span>
        </div>
        <a href="#" class="btn btn-gold">Read Full Article</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- All Posts -->
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal="fade-up"><span class="section-eyebrow">All Articles</span><h2 class="section-title">Latest Posts</h2></div>

    <!-- Category filter -->
    <div class="tabs" style="justify-content:center;margin-bottom:2.5rem;" id="blog-tabs">
      <?php foreach(['All','Hair Care','Skin Care','Bridal','Grooming','Nail Art'] as $c): ?>
      <button class="tab-btn <?=$c==='All'?'active':''?>" data-blog-cat="<?=$c==='All'?'all':strtolower(str_replace(' ','-',$c))?>"><?=$c?></button>
      <?php endforeach; ?>
    </div>

    <div class="grid-3" id="blog-grid" data-stagger>
      <?php foreach($posts as $b):
        $cat_slug = strtolower(str_replace(' ','-',htmlspecialchars($b['category'])));
      ?>
      <div class="blog-card hover-lift" data-blog-cat="<?=$cat_slug?>">
        <div class="blog-card-img">
            <img src="/images/<?=htmlspecialchars($b['image'])?>" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <div class="blog-card-body">
          <div class="blog-card-meta">
            <span class="blog-cat"><?=htmlspecialchars($b['category'])?></span>
            <span><i class="fas fa-clock"></i> <?=date('M d, Y', strtotime($b['created_at']))?></span>
          </div>
          <h3><?=htmlspecialchars($b['title'])?></h3>
          <p><?=htmlspecialchars($b['excerpt'])?></p>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1rem;">
            <a href="#" class="blog-read-more">Read <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Newsletter CTA -->
<section class="cta-section section">
  <div class="container" style="position:relative;z-index:1;text-align:center;" data-reveal="fade-up">
    <span class="section-eyebrow">Stay Updated</span>
    <h2 class="section-title">Never Miss a Beauty Tip</h2>
    <p style="color:rgba(255,255,255,.6);margin:1rem auto 2rem;max-width:440px;">Subscribe to our blog newsletter and get weekly beauty tips delivered to your inbox.</p>
    <form style="display:flex;gap:.5rem;max-width:440px;margin:0 auto;background:rgba(255,255,255,.05);border:1px solid rgba(201,169,110,.2);border-radius:var(--radius-full);padding:.3rem .3rem .3rem 1.2rem;" onsubmit="event.preventDefault();window.LuxeGlow?.showToast('Subscribed!','success');">
      <input type="email" placeholder="Your email" style="flex:1;background:none;border:none;color:#fff;font-family:var(--font-sans);font-size:var(--fs-sm);outline:none;min-width:0;" required>
      <button class="btn btn-gold" style="padding:.6rem 1.4rem;font-size:.75rem;">Subscribe</button>
    </form>
  </div>
</section>

<script>
document.getElementById('blog-tabs')?.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click',()=>{
    document.querySelectorAll('#blog-tabs .tab-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    const f = btn.dataset.blogCat;
    document.querySelectorAll('#blog-grid .blog-card').forEach(c=>{
      c.style.display = f==='all'||c.dataset.blogCat===f?'':'none';
    });
  });
});
</script>

<?php include __DIR__ . '/php/footer.php'; ?>
