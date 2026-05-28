<?php
// php/db.php

// ──────────────────────────────────────────────────────────
// Hostinger MySQL Database Credentials
// ──────────────────────────────────────────────────────────
$host     = '193.203.184.197';
$dbname   = 'u527069138_salon_database';
$username = 'u527069138_munna_salon';
$password = 'p#~DWOE0';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create tables if they don't exist (MySQL Syntax)
    $db->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            price VARCHAR(100),
            duration VARCHAR(100),
            image VARCHAR(255),
            attributes TEXT,
            amenities TEXT,
            related_services TEXT
        );

        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(255),
            title VARCHAR(255) NOT NULL,
            description TEXT,
            price VARCHAR(100),
            old_price VARCHAR(100),
            rating VARCHAR(50),
            image VARCHAR(255)
        );

        CREATE TABLE IF NOT EXISTS gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(255),
            title VARCHAR(255),
            image VARCHAR(255)
        );

        CREATE TABLE IF NOT EXISTS blogs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(255),
            title VARCHAR(255) NOT NULL,
            excerpt TEXT,
            content TEXT,
            image VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255),
            phone VARCHAR(255) NOT NULL,
            service VARCHAR(255),
            booking_date DATE,
            booking_time TIME,
            message TEXT,
            status VARCHAR(50) DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Insert default admin if the table is completely empty
    $stmt = $db->query("SELECT COUNT(*) FROM admin_users");
    if ($stmt && $stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO admin_users (username, password) VALUES ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "')");
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
