<?php
// Read database credentials from environment variables with defaults
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'book_user';
$pass = getenv('DB_PASS') ?: 'bookpass';
$dbname = getenv('DB_NAME') ?: 'book_database';

// Attempt connection and handle errors with helpful message
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
} catch (mysqli_sql_exception $e) {
    $msg = "Database connection failed: " . $e->getMessage() . "\n";
    $msg .= "Check your DB credentials and/or run './start_local.sh' to set up a local DB.\n";
    $msg .= "Or set environment variables: DB_HOST, DB_USER, DB_PASS, DB_NAME.\n";
    die(nl2br(htmlspecialchars($msg)));
}

$checkBook = $conn->query("SHOW TABLES LIKE 'Book'");
if ($checkBook && $checkBook->num_rows > 0) {
    $checkCover = $conn->query("SHOW COLUMNS FROM Book LIKE 'CoverImage'");
    if ($checkCover && $checkCover->num_rows === 0) {
        $conn->query("ALTER TABLE Book ADD COLUMN CoverImage VARCHAR(255) NULL");
    }
}
?>