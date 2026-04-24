<?php
$pageTitle = 'Add Book';
include 'header.php';
include 'db.php';
?>
<section class="page-panel">
    <div class="form-card">
        <div class="form-panel-header">
            <span class="section-label">Add new story</span>
            <h2>Bring a fresh read into the library</h2>
            <p>Share the title, author, genre, and a short description for adventurous readers.</p>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $author = trim($_POST['author']);
            $genre = trim($_POST['genre']);
            $publisher = trim($_POST['publisher']);
            $description = trim($_POST['description']);
            $coverPath = null;

            if (!empty($_FILES['cover']['name'])) {
                if ($_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
                    echo '<div class="info-pill">Cover upload failed with error code ' . intval($_FILES['cover']['error']) . '.</div>';
                } else {
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed, true)) {
                        echo '<div class="info-pill">Cover must be a JPG, PNG, or WEBP image.</div>';
                    } else {
                        $uploadDir = __DIR__ . '/uploads/';
                        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                            echo '<div class="info-pill">Unable to create upload directory.</div>';
                        } else {
                            $filename = uniqid('cover_', true) . '.' . $ext;
                            $target = $uploadDir . $filename;
                            if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
                                $coverPath = 'uploads/' . $filename;
                            } else {
                                echo '<div class="info-pill">Failed to save uploaded cover image.</div>';
                            }
                        }
                    }
                }
            }

            if ($title === '' || $author === '' || $genre === '') {
                echo '<div class="info-pill">Please provide title, author, and genre.</div>';
            } else {
                $conn->begin_transaction();
                try {
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

                    $ins = $conn->prepare("INSERT INTO Book (Title, Publisher, Description, AuthorID, GenreID, CoverImage) VALUES (?, ?, ?, ?, ?, ?)");
                    $ins->bind_param('sssiis', $title, $publisher, $description, $authorID, $genreID, $coverPath);
                    $ins->execute();

                    $conn->commit();
                    echo '<div class="info-pill">Book added successfully. <a href="index.php">Return to the library</a></div>';
                } catch (Exception $e) {
                    $conn->rollback();
                    echo '<div class="info-pill">Error adding book: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
        }
        ?>

        <form class="book-form" method="POST" action="add_book.php" enctype="multipart/form-data">
            <div class="form-field">
                <label for="title">Title</label>
                <input class="form-control" id="title" type="text" name="title" required>
            </div>
            <div class="form-field">
                <label for="author">Author</label>
                <input class="form-control" id="author" type="text" name="author" required>
            </div>
            <div class="form-field">
                <label for="genre">Genre</label>
                <input class="form-control" id="genre" type="text" name="genre" required>
            </div>
            <div class="form-field">
                <label for="publisher">Publisher</label>
                <input class="form-control" id="publisher" type="text" name="publisher">
            </div>
            <div class="form-field">
                <label for="cover">Cover image</label>
                <input class="form-control" id="cover" type="file" name="cover" accept="image/*">
            </div>
            <div class="form-field">
                <label for="description">Description</label>
                <textarea class="textarea-control" id="description" name="description"></textarea>
            </div>
            <button class="button-primary" type="submit">Add Book</button>
        </form>
    </div>
</section>
<?php include 'footer.php'; ?>

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
=======
$pageTitle = 'Add Book';
include 'header.php';
include 'db.php';
>>>>>>> 76a3628 (Add cover image upload, delete book feature, and UI improvements)
?>
        <section class="page-panel">
            <div class="form-card">
                <div class="form-panel-header">
                    <span class="section-label">Add new story</span>
                    <h2>Bring a fresh read into the library</h2>
                    <p>Share the title, author, genre, and a short description for adventurous readers.</p>
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $title = trim($_POST['title']);
                    $author = trim($_POST['author']);
                    $genre = trim($_POST['genre']);
                    $publisher = trim($_POST['publisher']);
                    $description = trim($_POST['description']);
                    $coverPath = null;

                    if (!empty($_FILES['cover']['name'])) {
                        if ($_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
                            echo '<div class="info-pill">Cover upload failed with error code ' . intval($_FILES['cover']['error']) . '.</div>';
                        } else {
                            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                            $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
                            if (!in_array($ext, $allowed, true)) {
                                echo '<div class="info-pill">Cover must be a JPG, PNG, or WEBP image.</div>';
                            } else {
                                $uploadDir = __DIR__ . '/uploads/';
                                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                                    echo '<div class="info-pill">Unable to create upload directory.</div>';
                                } else {
                                    $filename = uniqid('cover_', true) . '.' . $ext;
                                    $target = $uploadDir . $filename;
                                    if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
                                        $coverPath = 'uploads/' . $filename;
                                    } else {
                                        echo '<div class="info-pill">Failed to save uploaded cover image.</div>';
                                    }
                                }
                            }
                        }
                    }

                    if ($title === '' || $author === '' || $genre === '') {
                        echo '<div class="info-pill">Please provide title, author, and genre.</div>';
                    } else {
                        $conn->begin_transaction();
                        try {
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

                            $ins = $conn->prepare("INSERT INTO Book (Title, Publisher, Description, AuthorID, GenreID, CoverImage) VALUES (?, ?, ?, ?, ?, ?)");
                            $ins->bind_param('sssiis', $title, $publisher, $description, $authorID, $genreID, $coverPath);
                            $ins->execute();

                            $conn->commit();
                            echo '<div class="info-pill">Book added successfully. <a href="index.php">Return to the library</a></div>';
                        } catch (Exception $e) {
                            $conn->rollback();
                            echo '<div class="info-pill">Error adding book: ' . htmlspecialchars($e->getMessage()) . '</div>';
                        }
                    }
                }
                ?>

                <form class="book-form" method="POST" action="add_book.php" enctype="multipart/form-data">
                    <div class="form-field">
                        <label for="title">Title</label>
                        <input class="form-control" id="title" type="text" name="title" required>
                    </div>
                    <div class="form-field">
                        <label for="author">Author</label>
                        <input class="form-control" id="author" type="text" name="author" required>
                    </div>
                    <div class="form-field">
                        <label for="genre">Genre</label>
                        <input class="form-control" id="genre" type="text" name="genre" required>
                    </div>
                    <div class="form-field">
                        <label for="publisher">Publisher</label>
                        <input class="form-control" id="publisher" type="text" name="publisher">
                    </div>
                    <div class="form-field">
                        <label for="cover">Cover image</label>
                        <input class="form-control" id="cover" type="file" name="cover" accept="image/*">
                    </div>
                    <div class="form-field">
                        <label for="description">Description</label>
                        <textarea class="textarea-control" id="description" name="description"></textarea>
                    </div>
                    <button class="button-primary" type="submit">Add Book</button>
                </form>
            </div>
        </section>
        <?php include 'footer.php'; ?>