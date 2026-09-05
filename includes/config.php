<?php
// Load local environment variables before reading configuration.
require_once __DIR__ . '/env.php';

// Database configuration.
// Environment variables are used when provided; these defaults keep XAMPP local setup simple.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'foodsave_db');

// Google Maps API key MUST come from the environment.
// Never hardcode a real API key in this repository.
define('GOOGLE_MAPS_API_KEY', getenv('GOOGLE_MAPS_API_KEY') ?: '');

// JWT secret. A real deployment should always provide JWT_SECRET.
define('JWT_SECRET', getenv('JWT_SECRET') ?: '');

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch(PDOException $e) {
        // Do not expose database credentials/details to users.
        error_log("FoodSave database connection failed: " . $e->getMessage());
        die("Database connection failed. Please check the server configuration.");
    }
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserType() {
    return $_SESSION['user_type'] ?? null;
}

function redirectTo($url) {
    header("Location: $url");
    exit();
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Email notification function (placeholder)
function sendNotification($to, $subject, $message) {
    // Configure a real SMTP provider for production.
    return true;
}

// Get Google Maps API key
function getGoogleMapsApiKey() {
    return GOOGLE_MAPS_API_KEY;
}
?>
