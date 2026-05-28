<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $category = $_POST['category'] ?? 'General';
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $image = $_POST['current_image'] ?? '';

    // Handle JSON fields
    $attributes = '[]';
    if (isset($_POST['attr_keys']) && isset($_POST['attr_values'])) {
        $attrs = [];
        foreach ($_POST['attr_keys'] as $i => $key) {
            if (trim($key) !== '' && trim($_POST['attr_values'][$i]) !== '') {
                $attrs[] = ['key' => trim($key), 'value' => trim($_POST['attr_values'][$i])];
            }
        }
        $attributes = json_encode($attrs);
    }
    
    $amenities = isset($_POST['amenities']) ? json_encode($_POST['amenities']) : '[]';
    $related_services = isset($_POST['related_services']) ? json_encode($_POST['related_services']) : '[]';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../images/';
        $filename = 'service_' . time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = $filename;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE services SET title=?, category=?, description=?, price=?, duration=?, image=?, attributes=?, amenities=?, related_services=? WHERE id=?");
        $stmt->execute([$title, $category, $description, $price, $duration, $image, $attributes, $amenities, $related_services, $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO services (title, category, description, price, duration, image, attributes, amenities, related_services) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $description, $price, $duration, $image, $attributes, $amenities, $related_services]);
    }
    header('Location: /admin/services.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM services WHERE id = $id");
    header('Location: /admin/services.php');
    exit;
}

// Check if we need to pre-populate default services
$count = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
if ($count == 0 && isset($_GET['populate'])) {
    $defaults = [
        ['General', 'Hair Cut & Styling','Expert cuts for men & women tailored to your face shape and personality.','₹299','30 min', 'service-haircut.jpg'],
        ['General', 'Hair Coloring','Global brands for vibrant, long-lasting colour — balayage, highlights & more.','₹1,499','90 min', 'service-haircolor.jpg'],
        ['General', 'Bridal Makeup','Flawless bridal looks crafted for your most special day with luxury products.','₹5,999','180 min', 'service-bridal.jpg'],
        ['General', 'Facial & Cleanup','Rejuvenating facials and deep pore cleansing for glowing, youthful skin.','₹999','60 min', 'service-facial.jpg'],
        ['General', 'Spa & Massage','Full-body relaxation and skin rejuvenation in our tranquil spa suite.','₹2,999','120 min', 'service-spa.jpg'],
        ['General', 'Nail Art & Care','Creative nail designs, manicure and pedicure by expert nail artists.','₹499','60 min', 'service-nails.jpg'],
        ['General', 'Keratin Treatment','Smooth, frizz-free hair that lasts 3–6 months using premium keratin.','₹3,999','120 min', 'service-haircut.jpg'],
        ['General', 'Pre-Bridal Package','Complete 3-session beauty prep from engagement to wedding day.','₹14,999','Multiple', 'service-bridal.jpg']
    ];
    $stmt = $db->prepare("INSERT INTO services (category, title, description, price, duration, image) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($defaults as $d) { $stmt->execute($d); }
    header('Location: /admin/services.php');
    exit;
}

$edit_service = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_service = $stmt->fetch();
}

admin_header('Manage Services');
?>

<div style="display:flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
    <!-- List Services -->
    <div class="card" style="flex: 2; min-width: 300px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin-top:0;">All Services</h3>
            <?php if ($count == 0): ?>
                <a href="?populate=1" class="btn btn-gold">Load Default Services</a>
            <?php else: ?>
                <a href="/admin/services.php" class="btn">Add New</a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price/Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $services = $db->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
                    if (!$services): ?>
                    <tr><td colspan="4" style="text-align:center;">No services found.</td></tr>
                    <?php else: foreach($services as $s): ?>
                    <tr>
                        <td>
                            <?php if($s['image']): ?>
                                <img src="/images/<?= htmlspecialchars($s['image']) ?>" alt="" style="width:50px; height:50px; object-fit:cover; border-radius:4px; border:1px solid #333;">
                            <?php else: ?>
                                <div style="width:50px; height:50px; background:#222; border-radius:4px;"></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($s['title']) ?></div>
                            <div style="font-size:.75rem; color:#888; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($s['description']) ?></div>
                        </td>
                        <td>
                            <div style="color:#c9a96e;"><?= htmlspecialchars($s['price']) ?></div>
                            <div style="font-size:.8rem; color:#aaa;"><i class="fas fa-clock"></i> <?= htmlspecialchars($s['duration']) ?></div>
                        </td>
                        <td>
                            <a href="?edit=<?= $s['id'] ?>" class="btn" style="padding:.4rem .6rem;"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger" style="padding:.4rem .6rem;" onclick="return confirm('Delete this service?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Form -->
    <div class="card" style="flex: 1; min-width: 300px; position: sticky; top: 2rem;">
        <h3 style="margin-top:0;"><?= $edit_service ? 'Edit Service' : 'Add New Service' ?></h3>
        <form method="post" enctype="multipart/form-data">
            <?php if($edit_service): ?>
                <input type="hidden" name="id" value="<?= $edit_service['id'] ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($edit_service['image']) ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Service Title</label>
                <input type="text" name="title" required value="<?= $edit_service ? htmlspecialchars($edit_service['title']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required style="width:100%; padding:0.5rem; background:#111; color:#fff; border:1px solid #333;">
                    <?php 
                    $cats = [
                        'General', 
                        'Hair Styling Services', 
                        'Beard Grooming Services', 
                        'Manicure & Pedicure Services', 
                        'Face Care Services', 
                        'Hair Care Services', 
                        'Waxing Services', 
                        'Hair Treatment Services', 
                        'Make Up Services'
                    ];
                    $current_cat = $edit_service ? $edit_service['category'] : 'General';
                    foreach($cats as $c): 
                    ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= $c === $current_cat ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" required><?= $edit_service ? htmlspecialchars($edit_service['description']) : '' ?></textarea>
            </div>
            <div style="display:flex; gap:1rem;">
                <div class="form-group" style="flex:1;">
                    <label>Price</label>
                    <input type="text" name="price" placeholder="e.g. ₹999" required value="<?= $edit_service ? htmlspecialchars($edit_service['price']) : '' ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Duration</label>
                    <input type="text" name="duration" placeholder="e.g. 60 min" required value="<?= $edit_service ? htmlspecialchars($edit_service['duration']) : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Attributes (e.g., Color type: Black)</label>
                <div id="attributes-container">
                    <?php 
                    $attrs = $edit_service && $edit_service['attributes'] ? json_decode($edit_service['attributes'], true) : [];
                    if (!empty($attrs)):
                        foreach ($attrs as $attr):
                    ?>
                    <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;" class="attr-row">
                        <input type="text" name="attr_keys[]" placeholder="Key (e.g. Hair Type)" value="<?= htmlspecialchars($attr['key']) ?>" style="flex:1; padding:0.5rem; background:#111; color:#fff; border:1px solid #333;">
                        <input type="text" name="attr_values[]" placeholder="Value (e.g. Fine)" value="<?= htmlspecialchars($attr['value']) ?>" style="flex:1; padding:0.5rem; background:#111; color:#fff; border:1px solid #333;">
                        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()" style="padding:0.5rem;"><i class="fas fa-trash"></i></button>
                    </div>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="btn" onclick="addAttrRow()" style="margin-top:0.5rem; font-size:0.8rem;">+ Add Attribute</button>
                <script>
                function addAttrRow() {
                    const div = document.createElement('div');
                    div.style.display = 'flex';
                    div.style.gap = '0.5rem';
                    div.style.marginBottom = '0.5rem';
                    div.className = 'attr-row';
                    div.innerHTML = `
                        <input type="text" name="attr_keys[]" placeholder="Key" style="flex:1; padding:0.5rem; background:#111; color:#fff; border:1px solid #333;">
                        <input type="text" name="attr_values[]" placeholder="Value" style="flex:1; padding:0.5rem; background:#111; color:#fff; border:1px solid #333;">
                        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()" style="padding:0.5rem;"><i class="fas fa-trash"></i></button>
                    `;
                    document.getElementById('attributes-container').appendChild(div);
                }
                </script>
            </div>

            <div class="form-group">
                <label>Amenities</label>
                <div style="display:flex; flex-wrap:wrap; gap:1rem;">
                    <?php 
                    $amenity_options = [
                        'Pet Friendly' => 'fas fa-paw',
                        'Waiting Chair' => 'fas fa-chair',
                        'Work Friendly' => 'fas fa-laptop',
                        'Hand Sanitiser' => 'fas fa-hands-wash',
                        'AC' => 'fas fa-snowflake',
                        'WiFi' => 'fas fa-wifi',
                        'Coffee/Tea' => 'fas fa-coffee',
                        'Parking' => 'fas fa-parking'
                    ];
                    $selected_amenities = $edit_service && isset($edit_service['amenities']) ? json_decode($edit_service['amenities'], true) : [];
                    if (!is_array($selected_amenities)) $selected_amenities = [];
                    foreach ($amenity_options as $aname => $aicon): 
                    ?>
                    <label style="display:flex; align-items:center; gap:0.5rem; background:#111; padding:0.5rem; border-radius:4px; cursor:pointer;">
                        <input type="checkbox" name="amenities[]" value="<?= htmlspecialchars($aname) ?>" <?= in_array($aname, $selected_amenities) ? 'checked' : '' ?>>
                        <i class="<?= $aicon ?>"></i> <?= $aname ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Related Services</label>
                <select name="related_services[]" multiple style="width:100%; height:120px; background:#111; color:#fff; border:1px solid #333; padding:0.5rem;">
                    <?php
                    $all_srvs = $db->query("SELECT id, title FROM services ORDER BY title ASC")->fetchAll();
                    $selected_related = $edit_service && isset($edit_service['related_services']) ? json_decode($edit_service['related_services'], true) : [];
                    if (!is_array($selected_related)) $selected_related = [];
                    foreach ($all_srvs as $srv):
                        if ($edit_service && $srv['id'] == $edit_service['id']) continue;
                    ?>
                    <option value="<?= $srv['id'] ?>" <?= in_array($srv['id'], $selected_related) ? 'selected' : '' ?>><?= htmlspecialchars($srv['title']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#888;">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
            </div>

            <div class="form-group">
                <label>Image Upload</label>
                <input type="file" name="image" accept="image/*" style="padding:.5rem; background:#111;">
                <?php if($edit_service && $edit_service['image']): ?>
                    <p style="font-size:.8rem; color:#aaa; margin-top:.5rem;">Current: <?= htmlspecialchars($edit_service['image']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;"><?= $edit_service ? 'Update Service' : 'Save Service' ?></button>
            <?php if($edit_service): ?>
                <a href="/admin/services.php" class="btn" style="width:100%; text-align:center; margin-top:1rem; box-sizing:border-box;">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php admin_footer(); ?>
