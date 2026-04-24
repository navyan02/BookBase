<?php
$pageTitle = 'Add Book';
include 'header.php';
include 'db.php';
?>

<section class="page-panel">
    <div class="form-card">
        <div class="form-panel-header">
            <span class="section-label">Add a book</span>
            <h2>Share a new story</h2>
            <p>Add a book to the library for others to discover.</p>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $title = trim($_POST['title']);
            $publisher = trim($_POST['publisher']);
            $description = trim($_POST['description']);
<<<<<<< Updated upstream
            $author = trim($_POST['author']);
            $genre = trim($_POST['genre']);
            $cover_path = null;

            // Handle cover image upload
            if (!empty($_FILES['cover']['name'])) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                $file_type = $_FILES['cover']['type'];
                $file_size = $_FILES['cover']['size'];

                if (!in_array($file_type, $allowed_types)) {
                    echo '<div class="info-pill">Cover must be a JPG, PNG, or WEBP image.</div>';
                } elseif ($file_size > 5 * 1024 * 1024) { // 5MB limit
                    echo '<div class="info-pill">Cover image must be less than 5MB.</div>';
                } else {
                    $upload_dir = __DIR__ . '/uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $file_extension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('cover_', true) . '.' . $file_extension;
                    $target_path = $upload_dir . $filename;

                    if (move_uploaded_file($_FILES['cover']['tmp_name'], $target_path)) {
                        $cover_path = 'uploads/' . $filename;
                    } else {
                        echo '<div class="info-pill">Failed to upload cover image.</div>';
                    }
                }
            }

=======
            // No manual cover upload; we'll try to fetch a cover automatically after inserting the book
            $coverPath = null;

>>>>>>> Stashed changes
            if ($title === '' || $author === '' || $genre === '') {
                echo '<div class="info-pill">Please fill in all required fields.</div>';
            } else {

                // Handle Author
                $stmt = $conn->prepare("SELECT AuthorID FROM Author WHERE Name = ?");
                $stmt->bind_param("s", $author);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $author_id = $result->fetch_assoc()['AuthorID'];
                } else {
                    $stmt = $conn->prepare("INSERT INTO Author (Name) VALUES (?)");
                    $stmt->bind_param("s", $author);
                    $stmt->execute();
                    $author_id = $stmt->insert_id;
                }

                // Handle Genre
                $stmt = $conn->prepare("SELECT GenreID FROM Genre WHERE Name = ?");
                $stmt->bind_param("s", $genre);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $genre_id = $result->fetch_assoc()['GenreID'];
                } else {
                    $stmt = $conn->prepare("INSERT INTO Genre (Name) VALUES (?)");
                    $stmt->bind_param("s", $genre);
                    $stmt->execute();
                    $genre_id = $stmt->insert_id;
                }

                // Insert Book
                $stmt = $conn->prepare("
                    INSERT INTO Book (Title, Publisher, Description, AuthorID, GenreID, CoverImage)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("sssiis", $title, $publisher, $description, $author_id, $genre_id, $cover_path);

<<<<<<< Updated upstream
                if ($stmt->execute()) {
                    echo '<div class="info-pill">Book added successfully. <a href="index.php">Return to library</a></div>';
                } else {
                    echo '<div class="info-pill">Error adding book: ' . htmlspecialchars($conn->error) . '</div>';
=======
                    // If no cover was provided, try fetching from Open Library and update the book
                    if (empty($coverPath)) {
                        $bookId = $ins->insert_id;
                        $url = "https://openlibrary.org/search.json?title=" . urlencode($title) . "&author=" . urlencode($author) . "&limit=1";
                        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
                        $resp = @file_get_contents($url, false, $ctx);
                        if ($resp) {
                            $data = json_decode($resp, true);
                            if (!empty($data['docs'][0])) {
                                $doc = $data['docs'][0];
                                if (!empty($doc['cover_i'])) {
                                    $coverUrl = 'https://covers.openlibrary.org/b/id/' . intval($doc['cover_i']) . '-L.jpg';
                                    $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
                                    $upd->bind_param('si', $coverUrl, $bookId);
                                    $upd->execute();
                                } elseif (!empty($doc['isbn'][0])) {
                                    $isbn = $doc['isbn'][0];
                                    $coverUrl = 'https://covers.openlibrary.org/b/isbn/' . urlencode($isbn) . '-L.jpg';
                                    $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
                                    $upd->bind_param('si', $coverUrl, $bookId);
                                    $upd->execute();
                                }
                            }
                        }
                    }

                    $conn->commit();
                    echo '<div class="info-pill">Book added successfully. <a href="index.php">Return to the library</a></div>';
                } catch (Exception $e) {
                    $conn->rollback();
                    echo '<div class="info-pill">Error adding book: ' . htmlspecialchars($e->getMessage()) . '</div>';
>>>>>>> Stashed changes
                }
            }
        }
        ?>

        <form class="book-form" method="POST" action="add_book.php">
            <div class="form-field">
                <label for="title">Title *</label>
                <input class="form-control" id="title" type="text" name="title" required>
            </div>

            <div class="form-field">
                <label for="publisher">Publisher</label>
                <input class="form-control" id="publisher" type="text" name="publisher">
            </div>
<<<<<<< Updated upstream

            <div class="form-field">
                <label for="cover">Cover Image</label>
                <input class="form-control" id="cover" type="file" name="cover" accept="image/*">
            </div>

=======
            <!-- Cover is fetched automatically from Open Library; no upload needed -->
>>>>>>> Stashed changes
            <div class="form-field">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>

            <div class="form-field">
                <label for="author">Author *</label>
                <input class="form-control" id="author" type="text" name="author" required>
            </div>

            <div class="form-field">
                <label for="genre">Genre *</label>
                <input class="form-control" id="genre" type="text" name="genre" required>
            </div>

            <button class="button-primary" type="submit">Add Book</button>
        </form>
    </div>
</section>
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
<?php include 'footer.php'; ?>