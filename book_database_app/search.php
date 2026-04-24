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

    $base = "SELECT Book.BookID, Book.Title, Book.CoverImage, Author.Name AS AuthorName, Genre.Name AS GenreName, Book.Publisher
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

    function getCoverUrl($title) {
        $seed = preg_replace('/[^a-z0-9]+/i', '', strtolower($title));
        return "https://picsum.photos/seed/{$seed}/120/160";
    }

    echo "<h3>Search Results</h3>";
    echo "<table><tr><th>Cover</th><th>Title</th><th>Author</th><th>Genre</th><th>Publisher</th><th>Details</th></tr>";
    while ($row = $result->fetch_assoc()) {
        // If no cover set, try Open Library lookup and persist it so others see it
        if (empty($row['CoverImage'])) {
            $lookupUrl = "https://openlibrary.org/search.json?title=" . urlencode($row['Title']) . "&author=" . urlencode($row['AuthorName']) . "&limit=1";
            $ctx = stream_context_create(['http' => ['timeout' => 5]]);
            $resp = @file_get_contents($lookupUrl, false, $ctx);
            if ($resp) {
                $data = json_decode($resp, true);
                if (!empty($data['docs'][0])) {
                    $doc = $data['docs'][0];
                    if (!empty($doc['cover_i'])) {
                        $coverUrl = 'https://covers.openlibrary.org/b/id/' . intval($doc['cover_i']) . '-M.jpg';
                    } elseif (!empty($doc['isbn'][0])) {
                        $coverUrl = 'https://covers.openlibrary.org/b/isbn/' . urlencode($doc['isbn'][0]) . '-M.jpg';
                    }
                    if (!empty($coverUrl)) {
                        $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
                        $upd->bind_param('si', $coverUrl, $row['BookID']);
                        $upd->execute();
                        $row['CoverImage'] = $coverUrl;
                    }
                }
            }
        }

        echo "<tr>";
        $cover = !empty($row['CoverImage']) ? $row['CoverImage'] : getCoverUrl($row['Title']);
        echo "<td><img src='" . htmlspecialchars($cover) . "' alt='cover' style='width:60px;height:auto;'></td>";
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
