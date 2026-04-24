<?php
session_start();
$pageTitle = $pageTitle ?? 'BookBase';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="site-shell">
        <header class="site-header">
            <div class="brand">
                <span class="brand-icon">📚</span>
                <div>
                    <span class="brand-name">BookBase</span>
                    <span class="brand-tag">Curated for young readers</span>
                </div>
            </div>
            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="search.php">Search</a>
                <a href="add_rating.php">Add Rating</a>
                <a href="add_book.php">Add Book</a>
            </nav>
        </header>
        <main class="container">
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
