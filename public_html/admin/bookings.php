<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

// Handle status updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'confirm') {
        $db->query("UPDATE bookings SET status = 'confirmed' WHERE id = $id");
    } elseif ($_GET['action'] === 'cancel') {
        $db->query("UPDATE bookings SET status = 'cancelled' WHERE id = $id");
    } elseif ($_GET['action'] === 'delete') {
        $db->query("DELETE FROM bookings WHERE id = $id");
    }
    header('Location: /admin/bookings.php');
    exit;
}

admin_header('Manage Bookings');
?>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date / Time</th>
                    <th>Client Details</th>
                    <th>Service Requested</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bookings = $db->query("SELECT * FROM bookings ORDER BY booking_date DESC, booking_time DESC")->fetchAll();
                if (!$bookings): ?>
                <tr><td colspan="5" style="text-align:center;">No bookings found.</td></tr>
                <?php else: foreach($bookings as $b): ?>
                <tr>
                    <td>
                        <div style="font-weight: 500;"><?= htmlspecialchars($b['booking_date']) ?></div>
                        <div style="color:#aaa; font-size:.85rem;"><?= htmlspecialchars($b['booking_time']) ?></div>
                        <div style="color:#666; font-size:.7rem; margin-top:.3rem;">Booked: <?= date('M d, H:i', strtotime($b['created_at'])) ?></div>
                    </td>
                    <td>
                        <div style="font-weight: 500;"><?= htmlspecialchars($b['name']) ?></div>
                        <div style="color:#aaa; font-size:.85rem;"><i class="fas fa-phone" style="font-size:.7rem;"></i> <?= htmlspecialchars($b['phone']) ?></div>
                        <?php if ($b['email']): ?>
                        <div style="color:#aaa; font-size:.85rem;"><i class="fas fa-envelope" style="font-size:.7rem;"></i> <?= htmlspecialchars($b['email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="color:#c9a96e;"><?= htmlspecialchars($b['service']) ?></div>
                        <?php if ($b['message']): ?>
                        <div style="color:#888; font-size:.8rem; margin-top:.3rem; font-style:italic;">"<?= htmlspecialchars($b['message']) ?>"</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $color = $b['status'] == 'pending' ? '#ca8a04' : ($b['status'] == 'confirmed' ? '#16a34a' : '#dc2626');
                        ?>
                        <span style="padding:.3rem .6rem; border-radius:4px; font-size:.8rem; background: <?= $color ?>20; color: <?= $color ?>; border: 1px solid <?= $color ?>40;">
                            <?= htmlspecialchars(ucfirst($b['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:.5rem;">
                            <?php if ($b['status'] === 'pending'): ?>
                            <a href="?action=confirm&id=<?= $b['id'] ?>" class="btn" style="background:#16a34a;border:none;" title="Confirm"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <?php if ($b['status'] !== 'cancelled'): ?>
                            <a href="?action=cancel&id=<?= $b['id'] ?>" class="btn" style="background:#ea580c;border:none;" title="Cancel"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                            <a href="?action=delete&id=<?= $b['id'] ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this booking?');"><i class="fas fa-trash"></i></a>
                        </div>
                        <div style="margin-top:.5rem;">
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $b['phone']) ?>" target="_blank" class="btn" style="background:#25D366; color:#000; border:none; padding:.3rem .5rem; font-size:.8rem;"><i class="fab fa-whatsapp"></i> Chat</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php admin_footer(); ?>
