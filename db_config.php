<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: ''; 
$db   = getenv('DB_NAME') ?: 'aura_db';

try {
    // Initial connection to create DB if missing
    $pdo_setup = new PDO("mysql:host=$host", $user, $pass);
    $pdo_setup->exec("CREATE DATABASE IF NOT EXISTS $db");
    
    // Connect to actual database
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Ensure tables exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        person VARCHAR(20) NOT NULL,
        date DATE NOT NULL,
        time VARCHAR(20) NOT NULL,
        status ENUM('pending', 'confirmed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS chats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        msg TEXT NOT NULL,
        time VARCHAR(50) NOT NULL,
        is_admin TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    try { $pdo->exec("ALTER TABLE chats ADD COLUMN is_admin TINYINT(1) DEFAULT 0"); } catch(PDOException $e) {}

    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        profile_pic VARCHAR(255) DEFAULT './assets/images/Mequ.jpg',
        forgot_token VARCHAR(255) DEFAULT NULL,
        otp_code VARCHAR(6) DEFAULT NULL,
        otp_expiry DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // New Tables: Menu Items and Gallery
    $pdo->exec("CREATE TABLE IF NOT EXISTS menu_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        image VARCHAR(255) DEFAULT './assets/images/menu-default.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image VARCHAR(255) NOT NULL,
        caption VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert Default Sample Data
    $pdo->exec("INSERT IGNORE INTO menu_items (id, title, category, price, description, image) VALUES 
        (1, 'Greek Salad', 'Breakfast', 25.50, 'Tomatoes, green bell pepper, onion, olives, and feta cheese.', './assets/images/menu-1.png'),
        (2, 'Lasagne', 'Main Course', 40.00, 'Vegetables, cheeses, ground meats, and seasonings.', './assets/images/menu-2.png'),
        (3, 'Tokusen Wagyu', 'Main Course', 39.00, 'Vegetables, cheeses, and premium wagyu meat.', './assets/images/menu-4.png')");

    $pdo->exec("INSERT IGNORE INTO gallery (id, image, caption) VALUES 
        (1, './assets/images/service-1.jpg', 'Gourmet Breakfast Service'),
        (2, './assets/images/service-2.jpg', 'Modern Restaurant Atmosphere'),
        (3, './assets/images/service-3.jpg', 'Premium Bar & Drinks Selection')");

    // Self-heal: add otp columns if table already exists without them
    try { $pdo->exec("ALTER TABLE admins ADD COLUMN otp_code VARCHAR(6) DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE admins ADD COLUMN otp_expiry DATETIME DEFAULT NULL"); } catch(PDOException $e) {}

} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>
