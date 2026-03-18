<?php
// configuration flag: allow unauthenticated visitors to view report details
// set to true to let anyone see the popup, false to require login (option 2)
define('ALLOW_REPORT_DETAILS_ANONYMOUS', true);

// PhilSMS API Configuration
define('PHILSMS_API_TOKEN', '1846|Eygb0YJE2EP1wABlI7A8STJ4IQKMK4Ostte5FPU1de405ad3');
define('PHILSMS_API_URL', 'https://dashboard.philsms.com/api/v3/sms/send');
define('PHILSMS_SENDER_ID', 'PhilSMS');


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
    `phone_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createUsers);

// create a default admin account if it does not exist
$adminEmail = 'admin@floodguard.com';
$adminPass  = password_hash('admin1234', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (first_name,last_name,email,phone,address,password_hash,role) VALUES ('Admin','User','$adminEmail','','','$adminPass','admin')");

// Add/ensure phone verification column
$conn->query("ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone_verified` TINYINT(1) NOT NULL DEFAULT 0");

// Clean up legacy columns no longer used
$conn->query("ALTER TABLE `users` DROP COLUMN IF EXISTS `verification_code`");
$conn->query("ALTER TABLE `users` DROP COLUMN IF EXISTS `otp_code`");
$conn->query("ALTER TABLE `users` DROP COLUMN IF EXISTS `otp_expires_at`");

// automatically create reports table if it doesn't already exist
$createReports = "CREATE TABLE IF NOT EXISTS `reports` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_email` VARCHAR(255) NULL,
    `location` VARCHAR(255) NOT NULL,
    `status` ENUM('Safe','In Danger','Alert','Danger') NOT NULL,
    `description` TEXT NULL,
    `image` MEDIUMBLOB NULL,
    `post_news` TINYINT(1) NOT NULL DEFAULT 0,
    `sms_sent_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$conn->query($createReports);

// Add sms_sent_at column if it doesn't exist
$conn->query("ALTER TABLE `reports` ADD COLUMN IF NOT EXISTS `sms_sent_at` DATETIME NULL");

// Create flood_alerts_sent table for tracking flood alert SMS notifications
$createFloodAlerts = "CREATE TABLE IF NOT EXISTS `flood_alerts_sent` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `area_name` VARCHAR(255) NOT NULL,
    `alert_status` ENUM('warning','danger','critical') NOT NULL,
    `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `recipients_count` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX (`area_name`, `alert_status`, `sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createFloodAlerts);

// Create verification_codes table for phone verification
$createVerificationCodes = "CREATE TABLE IF NOT EXISTS `verification_codes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `verification_code` VARCHAR(6) NOT NULL,
    `code_type` ENUM('phone_verification','password_reset') NOT NULL DEFAULT 'phone_verification',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` DATETIME NOT NULL,
    `used` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX (`email`, `phone`, `verification_code`, `code_type`, `used`),
    INDEX (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createVerificationCodes);
?>
