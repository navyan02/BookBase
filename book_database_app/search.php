<?php include 'header.php'; include 'db.php'; ?>
<h2>Search Books</h2>
<form method="GET" action="search.php">
    <label>Search term:</label><br>
    <input type="text" name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" required>
    <br>
    <label>Search by:</label><br>
    <select name="type">
        <option value="title" <?= (isset($_GET['type']) && $_GET['type']=='title') ? 'selected' : '' ?>>Title</option>
        <option value="author" <?= (isset($_GET['type']) && $_GET['type']=='author') ? 'selected' : '' ?>>Author</option>
        <option value="genre" <?= (isset($_GET['type']) && $_GET['type']=='genre') ? 'selected' : '' ?>>Genre</option>
        <option value="publisher" <?= (isset($_GET['type']) && $_GET['type']=='publisher') ? 'selected' : '' ?>>Publisher</option>
    </select>
    <br>
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['q']) && isset($_GET['type'])) {
    $q = "%" . $_GET['q'] . "%";
    $type = $_GET['type'];

    $base = "SELECT Book.BookID, Book.Title, Author.Name AS AuthorName, Genre.Name AS GenreName, Book.Publisher
             FROM Book
             JOIN Author ON Book.AuthorID = Author.AuthorID
             JOIN Genre ON Book.GenreID = Genre.GenreID
             WHERE ";

    if ($type == 'author') {
        $sql = $base . "Author.Name LIKE ?";
    } elseif ($type == 'genre') {
        $sql = $base . "Genre.Name LIKE ?";
    } elseif ($type == 'publisher') {
        $sql = $base . "Book.Publisher LIKE ?";
    } else {
        $sql = $base . "Book.Title LIKE ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h3>Search Results</h3>";
    echo "<table><tr><th>Title</th><th>Author</th><th>Genre</th><th>Publisher</th><th>Details</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['AuthorName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['GenreName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Publisher']) . "</td>";
        echo "<td><a href='book.php?id=" . $row['BookID'] . "'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<?php include 'footer.php'; ?>
