<?php include 'header.php'; include 'db.php'; ?>
<?php
if (!isset($_GET['id'])) { die("No book selected."); }
$bookID = intval($_GET['id']);
$_SESSION['last_book_viewed'] = $bookID;

$sql = "SELECT Book.Title, Book.Publisher, Book.Description, Author.Name AS AuthorName, Genre.Name AS GenreName
        FROM Book
        JOIN Author ON Book.AuthorID = Author.AuthorID
        JOIN Genre ON Book.GenreID = Genre.GenreID
        WHERE Book.BookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookID);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) { die("Book not found."); }
?>
<h2><?= htmlspecialchars($book['Title']) ?></h2>
<div class="card">
<p><strong>Author:</strong> <?= htmlspecialchars($book['AuthorName']) ?></p>
<p><strong>Genre:</strong> <?= htmlspecialchars($book['GenreName']) ?></p>
<p><strong>Publisher:</strong> <?= htmlspecialchars($book['Publisher']) ?></p>
<p><strong>Description:</strong> <?= htmlspecialchars($book['Description']) ?></p>
</div>

<h3>Ratings</h3>
<?php
$ratingSql = "SELECT Score FROM Rating WHERE BookID = ?";
$ratingStmt = $conn->prepare($ratingSql);
$ratingStmt->bind_param("i", $bookID);
$ratingStmt->execute();
$ratings = $ratingStmt->get_result();
?>
<ul>
<?php while($r = $ratings->fetch_assoc()): ?>
<li><?= htmlspecialchars($r['Score']) ?>/5</li>
<?php endwhile; ?>
</ul>
<p><a href="add_rating.php?book_id=<?= $bookID ?>">Add a rating for this book</a></p>
<?php include 'footer.php'; ?>
