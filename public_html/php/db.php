<?php
// php/db.php
$db_file = __DIR__ . '/../database.sqlite';
$is_new = !file_exists($db_file);

try {
    $db = new PDO("sqlite:" . $db_file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if ($is_new) {
        // Create tables
        $db->exec("
            CREATE TABLE admin_users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL
            );

            CREATE TABLE services (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                title TEXT NOT NULL,
                description TEXT,
                price TEXT,
                duration TEXT,
                image TEXT,
                attributes TEXT,
                amenities TEXT,
                related_services TEXT
            );

            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT,
                title TEXT NOT NULL,
                description TEXT,
                price TEXT,
                old_price TEXT,
                rating TEXT,
                image TEXT
            );

            CREATE TABLE gallery (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT,
                title TEXT,
                image TEXT
            );

            CREATE TABLE blogs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT,
                title TEXT NOT NULL,
                excerpt TEXT,
                content TEXT,
                image TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE bookings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT,
                phone TEXT NOT NULL,
                service TEXT,
                booking_date DATE,
                booking_time TIME,
                message TEXT,
                status TEXT DEFAULT 'pending',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            -- Insert default admin (admin / admin123)
            INSERT INTO admin_users (username, password) VALUES ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "');
        ");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
