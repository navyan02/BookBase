<?php
$pageTitle = 'Add Rating';
include 'header.php';
include 'db.php';
?>
<section class="page-panel">
    <div class="form-card">
        <div class="form-panel-header">
            <span class="section-label">Rate a book</span>
            <h2>Share your reading score</h2>
            <p>Tell the community which stories deserve more stars.</p>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $bookID = intval($_POST['book_id']);
            $score = intval($_POST['score']);

            if ($score >= 1 && $score <= 5) {
                $sql = "INSERT INTO Rating (Score, BookID) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $score, $bookID);
                if ($stmt->execute()) {
                    echo '<div class="info-pill">Rating added successfully. <a href="index.php">Return to library</a></div>';
                } else {
                    echo '<div class="info-pill">Error adding rating: ' . htmlspecialchars($conn->error) . '</div>';
                }
            } else {
                echo '<div class="info-pill">Score must be between 1 and 5.</div>';
            }
        }

        $selectedBook = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
        $books = $conn->query("SELECT BookID, Title FROM Book ORDER BY Title");
        ?>

        <form class="book-form" method="POST" action="add_rating.php">
            <div class="form-field">
                <label for="book_id">Book</label>
                <select class="select-control" id="book_id" name="book_id" required>
                    <?php while ($book = $books->fetch_assoc()): ?>
                        <option value="<?php echo $book['BookID']; ?>" <?php echo $selectedBook == $book['BookID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($book['Title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-field">
                <label for="score">Score (1-5)</label>
                <select class="select-control" id="score" name="score" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button class="button-primary" type="submit">Add Rating</button>
        </form>
    </div>
</section>
<?php include 'footer.php'; ?>
</form>
=======
<?php
$pageTitle = 'Add Rating';
include 'header.php';
include 'db.php';
?>
<section class="page-panel">
    <div class="form-card">
        <div class="form-panel-header">
            <span class="section-label">Rate a book</span>
            <h2>Share your reading score</h2>
            <p>Tell the community which stories deserve more stars.</p>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $book = trim($_POST['book']);
            $score = trim($_POST['score']);

            if ($book === '' || $score === '') {
                echo '<div class="info-pill">Please provide both book ID and score.</div>';
            } else {
                if ($conn->query("INSERT INTO Rating (Score, BookID) VALUES ($score, $book)")) {
                    echo '<div class="info-pill">Rating added successfully. <a href="index.php">Return to the library</a></div>';
                } else {
                    echo '<div class="info-pill">Error adding rating: ' . htmlspecialchars($conn->error) . '</div>';
                }
            }
        }
        ?>

        <form class="book-form" method="POST" action="add_rating.php">
            <div class="form-field">
                <label for="book">Book ID</label>
                <input class="form-control" id="book" type="number" name="book" required>
            </div>
            <div class="form-field">
                <label for="score">Score (1-5)</label>
                <input class="form-control" id="score" type="number" name="score" min="1" max="5" required>
            </div>
            <button class="button-primary" type="submit">Add Rating</button>
        </form>
    </div>
</section>
>>>>>>> 76a3628 (Add cover image upload, delete book feature, and UI improvements)
<?php include 'footer.php'; ?>