<?php
// admin/layout.php
function admin_header($title) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #0a0a0a; color: #fff; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #111; border-right: 1px solid #222; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 1.5rem; font-size: 1.2rem; font-weight: 600; color: #c9a96e; border-bottom: 1px solid #222; text-align: center; }
        .nav { list-style: none; padding: 0; margin: 0; flex: 1; }
        .nav-item { border-bottom: 1px solid #1a1a1a; }
        .nav-link { display: block; padding: 1rem 1.5rem; color: #aaa; text-decoration: none; transition: .2s; }
        .nav-link:hover, .nav-link.active { background: #1a1a1a; color: #c9a96e; border-left: 3px solid #c9a96e; }
        .nav-link i { width: 24px; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #111; padding: 1rem 2rem; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; }
        .topbar-title { margin: 0; font-size: 1.2rem; font-weight: 500; }
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .content { padding: 2rem; overflow-y: auto; flex: 1; }
        .card { background: #151515; border: 1px solid #222; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #222; }
        th { color: #888; font-weight: 500; font-size: .85rem; text-transform: uppercase; }
        .btn { display: inline-block; padding: .5rem 1rem; background: #222; color: #fff; text-decoration: none; border-radius: 4px; border: 1px solid #333; cursor: pointer; }
        .btn:hover { background: #333; }
        .btn-gold { background: #c9a96e; color: #000; border: none; }
        .btn-gold:hover { background: #e3c48e; }
        .btn-danger { background: #ef4444; color: #fff; border: none; }
        .btn-danger:hover { background: #dc2626; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-size: .9rem; color: #aaa; }
        input[type="text"], input[type="email"], input[type="date"], input[type="time"], input[type="number"], select, textarea { width: 100%; padding: .75rem; border: 1px solid #333; border-radius: 4px; background: #222; color: #fff; box-sizing: border-box; font-family: inherit; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #c9a96e; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">✦ Admin Panel</div>
        <ul class="nav">
            <li class="nav-item"><a href="/admin/index.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a href="/admin/bookings.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Bookings</a></li>
            <li class="nav-item"><a href="/admin/services.php" class="nav-link"><i class="fas fa-spa"></i> Services</a></li>
            <li class="nav-item"><a href="/admin/products.php" class="nav-link"><i class="fas fa-box"></i> Products</a></li>
            <li class="nav-item"><a href="/admin/gallery.php" class="nav-link"><i class="fas fa-images"></i> Gallery</a></li>
            <li class="nav-item"><a href="/admin/blogs.php" class="nav-link"><i class="fas fa-newspaper"></i> Blog</a></li>
        </ul>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <h2 class="topbar-title"><?= htmlspecialchars($title) ?></h2>
            <div class="user-info">
                <span>Hello, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                <a href="/admin/logout.php" class="btn">Logout</a>
                <a href="/" target="_blank" class="btn btn-gold">View Site</a>
            </div>
        </header>
        <div class="content">
<?php
}

function admin_footer() {
?>
        </div>
    </main>
</body>
</html>
<?php
}
?>
