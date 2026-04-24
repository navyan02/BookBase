<?php include 'header.php'; include 'db.php'; ?>
<h2>Add Rating</h2>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookID = intval($_POST['book_id']);
    $score = intval($_POST['score']);

    if ($score >= 1 && $score <= 5) {
        $sql = "INSERT INTO Rating (Score, BookID) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $score, $bookID);
        $stmt->execute();
        echo "<p>Rating added successfully.</p>";
    } else {
        echo "<p>Score must be between 1 and 5.</p>";
    }
}

$selectedBook = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$books = $conn->query("SELECT BookID, Title FROM Book ORDER BY Title");
?>
<form method="POST" action="add_rating.php">
    <label>Book:</label><br>
    <select name="book_id" required>
        <?php while($book = $books->fetch_assoc()): ?>
        <option value="<?= $book['BookID'] ?>" <?= $selectedBook == $book['BookID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($book['Title']) ?>
        </option>
        <?php endwhile; ?>
    </select><br>

    <label>Rating:</label><br>
    <select name="score" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select><br>
    <button type="submit">Submit Rating</button>
</form>
<?php include 'footer.php'; ?>
