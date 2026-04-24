<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Book Database Web Application</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="search.php">Search</a>
        <a href="add_rating.php">Add Rating</a>
        <a href="add_book.php">Add Book</a>
    </nav>
    <hr>
    <?php
    // Flash messages (stored in session) — displayed once
    if (isset($_SESSION['flash'])) {
        echo '<div class="flash">' . htmlspecialchars($_SESSION['flash']) . '</div>';
        unset($_SESSION['flash']);
    }

    // Show last viewed book quick link if available
    if (isset($_SESSION['last_book_viewed'])) {
        $lastId = intval($_SESSION['last_book_viewed']);
        echo '<p class="last-viewed">Last viewed: <a href="book.php?id=' . $lastId . '">View</a></p>';
    }
    ?>
