<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $category = $_POST['category'] ?? 'General';
    $title = $_POST['title'] ?? '';
    $image = $_POST['current_image'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../images/';
        $filename = 'gallery_' . time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = $filename;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE gallery SET category=?, title=?, image=? WHERE id=?");
        $stmt->execute([$category, $title, $image, $id]);
    } else {
        if ($image) {
            $stmt = $db->prepare("INSERT INTO gallery (category, title, image) VALUES (?, ?, ?)");
            $stmt->execute([$category, $title, $image]);
        }
    }
    header('Location: /admin/gallery.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM gallery WHERE id = $id");
    header('Location: /admin/gallery.php');
    exit;
}

$count = $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
if ($count == 0 && isset($_GET['populate'])) {
    $defaults = [
        ['Hair', 'Premium Haircut', 'gallery-hair1.jpg'],
        ['Hair', 'Color Transformation', 'gallery-hair2.jpg'],
        ['Bridal', 'Traditional Bridal', 'gallery-bridal1.jpg'],
        ['Bridal', 'Modern Reception Look', 'gallery-bridal2.jpg'],
        ['Makeup', 'Party Makeup', 'gallery-makeup1.jpg'],
        ['Skin', 'Rejuvenating Facial', 'gallery-skin1.jpg']
    ];
    $stmt = $db->prepare("INSERT INTO gallery (category, title, image) VALUES (?, ?, ?)");
    foreach ($defaults as $d) { $stmt->execute($d); }
    header('Location: /admin/gallery.php');
    exit;
}

admin_header('Manage Gallery');
?>

<div style="display:flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
    <div class="card" style="flex: 2; min-width: 300px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin:0;">Gallery Images</h3>
            <?php if ($count == 0): ?>
                <a href="?populate=1" class="btn btn-gold">Load Defaults</a>
            <?php endif; ?>
        </div>
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
            <?php
            $items = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
            if (!$items): ?>
                <p>No images found.</p>
            <?php else: foreach($items as $s): ?>
                <div style="background:#222; border-radius:8px; overflow:hidden; position:relative; group;">
                    <img src="/images/<?= htmlspecialchars($s['image']) ?>" style="width:100%; height:150px; object-fit:cover; display:block;">
                    <div style="padding:.5rem; font-size:.8rem;">
                        <div style="color:#c9a96e;"><?= htmlspecialchars($s['category']) ?></div>
                        <div style="color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($s['title']) ?></div>
                    </div>
                    <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger" style="position:absolute; top:5px; right:5px; padding:.2rem .4rem;" onclick="return confirm('Delete image?');"><i class="fas fa-trash"></i></a>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 300px; position: sticky; top: 2rem;">
        <h3 style="margin-top:0;">Upload Image</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option>Hair</option>
                    <option>Bridal</option>
                    <option>Makeup</option>
                    <option>Skin</option>
                    <option>Spa</option>
                    <option>Nails</option>
                </select>
            </div>
            <div class="form-group">
                <label>Title / Caption (Optional)</label>
                <input type="text" name="title">
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*" required style="padding:.5rem; background:#111;">
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;">Upload</button>
        </form>
    </div>
</div>

<?php admin_footer(); ?>
