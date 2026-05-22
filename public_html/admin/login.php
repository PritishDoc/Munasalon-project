<?php
require_once __DIR__ . '/config.php';

if (is_logged_in()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header('Location: /admin/index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Muna's Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { background: #1a1a1a; padding: 2rem; border-radius: 8px; border: 1px solid rgba(201,169,110,.2); width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,.5); }
        h1 { margin-top: 0; font-size: 1.5rem; text-align: center; color: #c9a96e; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-size: .85rem; color: #aaa; }
        input { width: 100%; padding: .75rem; border: 1px solid #333; border-radius: 4px; background: #222; color: #fff; box-sizing: border-box; }
        input:focus { outline: none; border-color: #c9a96e; }
        .btn { width: 100%; padding: .75rem; background: #c9a96e; color: #000; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; margin-top: 1rem; }
        .btn:hover { background: #e3c48e; }
        .error { color: #ff6b6b; font-size: .85rem; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Admin Panel</h1>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
