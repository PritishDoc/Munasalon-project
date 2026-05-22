<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $category = $_POST['category'] ?? 'General';
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    $image = $_POST['current_image'] ?? '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../images/';
        $filename = 'blog_' . time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = $filename;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE blogs SET title=?, category=?, excerpt=?, content=?, image=? WHERE id=?");
        $stmt->execute([$title, $category, $excerpt, $content, $image, $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO blogs (title, category, excerpt, content, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $excerpt, $content, $image]);
    }
    header('Location: /admin/blogs.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM blogs WHERE id = $id");
    header('Location: /admin/blogs.php');
    exit;
}

// Check if we need to pre-populate default blogs
$count = $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
if ($count == 0 && isset($_GET['populate'])) {
    $defaults = [
        ['Bridal', 'Top 5 Bridal Makeup Trends for 2026', 'Discover the most sought-after bridal looks this wedding season, from glass skin to bold lips.', '<p>Full content goes here...</p>', 'blog-1.jpg'],
        ['Haircare', 'How to Maintain Keratin Treated Hair', 'Essential tips to keep your keratin treatment lasting longer and your hair looking silky smooth.', '<p>Full content goes here...</p>', 'blog-2.jpg'],
        ['Skincare', 'The Benefits of a 24K Gold Facial', 'Why gold facials are the ultimate luxury treatment for glowing, youthful skin.', '<p>Full content goes here...</p>', 'blog-3.jpg']
    ];
    $stmt = $db->prepare("INSERT INTO blogs (category, title, excerpt, content, image) VALUES (?, ?, ?, ?, ?)");
    foreach ($defaults as $d) { $stmt->execute($d); }
    header('Location: /admin/blogs.php');
    exit;
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_item = $stmt->fetch();
}

admin_header('Manage Blogs');
?>

<div style="display:flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
    <div class="card" style="flex: 2; min-width: 300px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin-top:0;">All Blog Posts</h3>
            <?php if ($count == 0): ?>
                <a href="?populate=1" class="btn btn-gold">Load Defaults</a>
            <?php else: ?>
                <a href="/admin/blogs.php" class="btn">Add New Post</a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title & Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items = $db->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
                    if (!$items): ?>
                    <tr><td colspan="4" style="text-align:center;">No blog posts found.</td></tr>
                    <?php else: foreach($items as $s): ?>
                    <tr>
                        <td>
                            <?php if($s['image']): ?>
                                <img src="/images/<?= htmlspecialchars($s['image']) ?>" style="width:60px; height:40px; object-fit:cover; border-radius:4px; border:1px solid #333;">
                            <?php else: ?>
                                <div style="width:60px; height:40px; background:#222; border-radius:4px;"></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($s['title']) ?></div>
                            <div style="font-size:.75rem; color:#888;"><?= htmlspecialchars($s['category']) ?></div>
                        </td>
                        <td>
                            <div style="color:#aaa; font-size:.85rem;"><?= date('M d, Y', strtotime($s['created_at'])) ?></div>
                        </td>
                        <td>
                            <a href="?edit=<?= $s['id'] ?>" class="btn" style="padding:.4rem .6rem;"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger" style="padding:.4rem .6rem;" onclick="return confirm('Delete post?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 300px; position: sticky; top: 2rem;">
        <h3 style="margin-top:0;"><?= $edit_item ? 'Edit Post' : 'Add New Post' ?></h3>
        <form method="post" enctype="multipart/form-data">
            <?php if($edit_item): ?>
                <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($edit_item['image']) ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Post Title</label>
                <input type="text" name="title" required value="<?= $edit_item ? htmlspecialchars($edit_item['title']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" placeholder="e.g. Haircare" required value="<?= $edit_item ? htmlspecialchars($edit_item['category']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Short Excerpt</label>
                <textarea name="excerpt" rows="2" required><?= $edit_item ? htmlspecialchars($edit_item['excerpt']) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label>Full Content (HTML allowed)</label>
                <textarea name="content" rows="6" required><?= $edit_item ? htmlspecialchars($edit_item['content']) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label>Cover Image</label>
                <input type="file" name="image" accept="image/*" style="padding:.5rem; background:#111;">
                <?php if($edit_item && $edit_item['image']): ?>
                    <p style="font-size:.8rem; color:#aaa; margin-top:.5rem;">Current: <?= htmlspecialchars($edit_item['image']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;"><?= $edit_item ? 'Update Post' : 'Publish Post' ?></button>
            <?php if($edit_item): ?>
                <a href="/admin/blogs.php" class="btn" style="width:100%; text-align:center; margin-top:1rem; box-sizing:border-box;">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php admin_footer(); ?>
