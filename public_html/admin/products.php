<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $category = $_POST['category'] ?? 'Retail';
    $price = $_POST['price'];
    $old_price = $_POST['old_price'] ?? '';
    $rating = $_POST['rating'] ?? '5.0';
    $image = $_POST['current_image'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../images/';
        $filename = 'product_' . time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = $filename;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE products SET title=?, category=?, price=?, old_price=?, rating=?, image=? WHERE id=?");
        $stmt->execute([$title, $category, $price, $old_price, $rating, $image, $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO products (title, category, price, old_price, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $price, $old_price, $rating, $image]);
    }
    header('Location: /admin/products.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM products WHERE id = $id");
    header('Location: /admin/products.php');
    exit;
}

// Check if we need to pre-populate default products
$count = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
if ($count == 0 && isset($_GET['populate'])) {
    $defaults = [
        ['Retail', 'LuxeGlow Keratin Shampoo', '₹899', '₹1,299', '4.9', 'product-1.jpg'],
        ['Retail', 'Argan Oil Hair Serum', '₹599', '₹899', '4.8', 'product-2.jpg'],
        ['Retail', '24K Gold Glow Facial Kit', '₹1,499', '₹2,199', '4.9', 'product-3.jpg'],
        ['Retail', 'Vitamin C Skin Serum', '₹799', '₹1,099', '4.7', 'product-4.jpg']
    ];
    $stmt = $db->prepare("INSERT INTO products (category, title, price, old_price, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($defaults as $d) { $stmt->execute($d); }
    header('Location: /admin/products.php');
    exit;
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_item = $stmt->fetch();
}

admin_header('Manage Products');
?>

<div style="display:flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
    <div class="card" style="flex: 2; min-width: 300px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin-top:0;">All Products</h3>
            <?php if ($count == 0): ?>
                <a href="?populate=1" class="btn btn-gold">Load Defaults</a>
            <?php else: ?>
                <a href="/admin/products.php" class="btn">Add New</a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price Details</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items = $db->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
                    if (!$items): ?>
                    <tr><td colspan="4" style="text-align:center;">No products found.</td></tr>
                    <?php else: foreach($items as $s): ?>
                    <tr>
                        <td>
                            <?php if($s['image']): ?>
                                <img src="/images/<?= htmlspecialchars($s['image']) ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px; border:1px solid #333;">
                            <?php else: ?>
                                <div style="width:50px; height:50px; background:#222; border-radius:4px;"></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($s['title']) ?></div>
                            <div style="font-size:.75rem; color:#888;">Rating: <?= htmlspecialchars($s['rating']) ?> ★</div>
                        </td>
                        <td>
                            <div style="color:#c9a96e;"><?= htmlspecialchars($s['price']) ?></div>
                            <?php if($s['old_price']): ?>
                            <div style="font-size:.8rem; color:#aaa; text-decoration:line-through;"><?= htmlspecialchars($s['old_price']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?= $s['id'] ?>" class="btn" style="padding:.4rem .6rem;"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger" style="padding:.4rem .6rem;" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 300px; position: sticky; top: 2rem;">
        <h3 style="margin-top:0;"><?= $edit_item ? 'Edit Product' : 'Add New Product' ?></h3>
        <form method="post" enctype="multipart/form-data">
            <?php if($edit_item): ?>
                <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($edit_item['image']) ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" required value="<?= $edit_item ? htmlspecialchars($edit_item['title']) : '' ?>">
            </div>
            <div style="display:flex; gap:1rem;">
                <div class="form-group" style="flex:1;">
                    <label>Current Price</label>
                    <input type="text" name="price" placeholder="e.g. ₹999" required value="<?= $edit_item ? htmlspecialchars($edit_item['price']) : '' ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Old Price (Optional)</label>
                    <input type="text" name="old_price" placeholder="e.g. ₹1,299" value="<?= $edit_item ? htmlspecialchars($edit_item['old_price']) : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Rating (e.g. 4.9)</label>
                <input type="text" name="rating" required value="<?= $edit_item ? htmlspecialchars($edit_item['rating']) : '5.0' ?>">
            </div>
            <div class="form-group">
                <label>Image Upload</label>
                <input type="file" name="image" accept="image/*" style="padding:.5rem; background:#111;">
                <?php if($edit_item && $edit_item['image']): ?>
                    <p style="font-size:.8rem; color:#aaa; margin-top:.5rem;">Current: <?= htmlspecialchars($edit_item['image']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;"><?= $edit_item ? 'Update' : 'Save' ?></button>
            <?php if($edit_item): ?>
                <a href="/admin/products.php" class="btn" style="width:100%; text-align:center; margin-top:1rem; box-sizing:border-box;">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php admin_footer(); ?>
