<?php include 'header.php';
include 'db.php'; ?>
<h2>Add Rating</h2>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book = trim($_POST['book']);
    $score = trim($_POST['score']);

    if ($book === '' || $score === '') {
        echo "<p>Please provide both book ID and score.</p>";
    } else {
        if ($conn->query("INSERT INTO Rating (Score, BookID) VALUES ($score, $book)")) {
            echo "<p>Rating added successfully. <a href=\"index.php\">Return to list</a></p>";
        } else {
            echo "<p>Error adding rating: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}
?>

<form method="POST" action="add_rating.php">
    <label>Book ID:</label><br>
    <input type="number" name="book" required><br>

    <label>Score (1-5):</label><br>
    <input type="number" name="score" min="1" max="5" required><br>

    <button type="submit">Add Rating</button>
</form>

<?php include 'footer.php'; ?>