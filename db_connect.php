<?php
/**
 * Database Connection File
 * 
 * This file connects to the MySQL database using PDO.
 * Include this file in any page that needs database access.
 * 
 * EUR to BGN conversion rate: 1.9558 (official fixed rate)
 */

// Set UTF-8 encoding for proper Bulgarian text display
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Database configuration (XAMPP defaults)
$db_host = 'localhost';
$db_name = 'artvision';
$db_user = 'root';
$db_pass = '';

// EUR to BGN conversion rate
define('EUR_TO_BGN', 1.9558);

try {
    // Create PDO connection
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Show friendly error message
    die("Database connection failed. Please make sure MySQL is running and the 'artvision' database exists.");
}

/**
 * Helper function to convert EUR to BGN
 */
function eurToBgn($eur)
{
    return round($eur * EUR_TO_BGN, 2);
}

/**
 * Helper function to format price display (EUR first, then BGN)
 */
function formatPrice($eur)
{
    $bgn = eurToBgn($eur);
    return "€" . number_format($eur, 0) . " / " . number_format($bgn, 0) . " лв.";
}
?>