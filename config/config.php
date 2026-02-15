<?php
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'floodguard_db');

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// ensure users table exists with unique email
$createUsers = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createUsers);

// create a default admin account if it does not exist
$adminEmail = 'admin@floodguard.com';
$adminPass  = password_hash('admin1234', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (first_name,last_name,email,phone,address,password_hash,role) VALUES ('Admin','User','$adminEmail','','','$adminPass','admin')");

// automatically create reports table if it doesn't already exist
$createReports = "CREATE TABLE IF NOT EXISTS `reports` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_email` VARCHAR(255) NULL,
    `location` VARCHAR(255) NOT NULL,
    `status` ENUM('Safe','In Danger','Alert','Danger') NOT NULL,
    `description` TEXT NULL,
    `image_path` VARCHAR(255) NULL,
    `post_news` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$conn->query($createReports);
?>
