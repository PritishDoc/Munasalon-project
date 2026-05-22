<?php
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

// Fetch quick stats
$total_bookings = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pending_bookings = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$total_services = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();

admin_header('Dashboard');
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <h3 style="margin-top: 0; color: #888; font-size: .9rem; text-transform: uppercase;">Total Bookings</h3>
        <div style="font-size: 2.5rem; font-weight: 600; color: #c9a96e;"><?= $total_bookings ?></div>
    </div>
    <div class="card" style="text-align: center;">
        <h3 style="margin-top: 0; color: #888; font-size: .9rem; text-transform: uppercase;">Pending Bookings</h3>
        <div style="font-size: 2.5rem; font-weight: 600; color: #ef4444;"><?= $pending_bookings ?></div>
    </div>
    <div class="card" style="text-align: center;">
        <h3 style="margin-top: 0; color: #888; font-size: .9rem; text-transform: uppercase;">Total Services</h3>
        <div style="font-size: 2.5rem; font-weight: 600; color: #fff;"><?= $total_services ?></div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0;">Recent Bookings</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent = $db->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5")->fetchAll();
                if (!$recent): ?>
                <tr><td colspan="5" style="text-align:center;">No bookings yet.</td></tr>
                <?php else: foreach($recent as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['booking_date']) ?></td>
                    <td><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['service']) ?></td>
                    <td>
                        <span style="padding:.2rem .5rem; border-radius:4px; font-size:.8rem; background: <?= $b['status'] == 'pending' ? '#3f3f46' : '#14532d' ?>;">
                            <?= htmlspecialchars(ucfirst($b['status'])) ?>
                        </span>
                    </td>
                    <td><a href="/admin/bookings.php" class="btn">View</a></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php admin_footer(); ?>
