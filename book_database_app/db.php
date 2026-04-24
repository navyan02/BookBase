<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "book_database";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
