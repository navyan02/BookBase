<?php include 'header.php'; include 'db.php'; ?>
<h2>Add Book</h2>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $publisher = trim($_POST['publisher']);
    $description = trim($_POST['description']);

    if ($title === '' || $author === '' || $genre === '') {
        $_SESSION['flash'] = 'Please provide title, author, and genre.';
        header('Location: add_book.php');
        exit;
    } else {
        // Use transaction: create/get author, create/get genre, insert book
        $conn->begin_transaction();
        try {
            // Author: get or create
            $stmt = $conn->prepare("SELECT AuthorID FROM Author WHERE Name = ?");
            $stmt->bind_param('s', $author);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $authorID = $row['AuthorID'];
            } else {
                $ins = $conn->prepare("INSERT INTO Author (Name) VALUES (?)");
                $ins->bind_param('s', $author);
                $ins->execute();
                $authorID = $ins->insert_id;
            }

            // Genre: get or create
            $stmt = $conn->prepare("SELECT GenreID FROM Genre WHERE Name = ?");
            $stmt->bind_param('s', $genre);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $genreID = $row['GenreID'];
            } else {
                $ins = $conn->prepare("INSERT INTO Genre (Name) VALUES (?)");
                $ins->bind_param('s', $genre);
                $ins->execute();
                $genreID = $ins->insert_id;
            }

            // Insert Book
            $ins = $conn->prepare("INSERT INTO Book (Title, Publisher, Description, AuthorID, GenreID) VALUES (?, ?, ?, ?, ?)");
            $ins->bind_param('sssii', $title, $publisher, $description, $authorID, $genreID);
            $ins->execute();

            $conn->commit();
            $_SESSION['flash'] = 'Book added successfully.';
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "<p>Error adding book: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>

<form method="POST" action="add_book.php">
    <label>Title:</label><br>
    <input type="text" name="title" required><br>

    <label>Author:</label><br>
    <input type="text" name="author" required><br>

    <label>Genre:</label><br>
    <input type="text" name="genre" required><br>

    <label>Publisher:</label><br>
    <input type="text" name="publisher"><br>

    <label>Description:</label><br>
    <textarea name="description"></textarea><br>

    <button type="submit">Add Book</button>
</form>

<?php include 'footer.php'; ?>
