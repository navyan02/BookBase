<?php
$pageTitle = 'Book Details';
include 'header.php';
include 'db.php';

$id = intval($_GET['id'] ?? 0);
$_SESSION['last_book_viewed'] = $id;

define('UPLOAD_DIR', __DIR__ . '/uploads/');

define('UPLOAD_PATH', 'uploads/');

// Cover images are fetched automatically (no manual update form)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
        $deleteId = intval($_POST['delete_book']);
        if ($deleteId > 0) {
                $stmt = $conn->prepare("SELECT CoverImage FROM Book WHERE BookID = ?");
                $stmt->bind_param('i', $deleteId);
                $stmt->execute();
                $deleteResult = $stmt->get_result();
                $deleteRow = $deleteResult->fetch_assoc();

                $conn->begin_transaction();
                try {
                        $delRatings = $conn->prepare("DELETE FROM Rating WHERE BookID = ?");
                        $delRatings->bind_param('i', $deleteId);
                        $delRatings->execute();

                        $delBook = $conn->prepare("DELETE FROM Book WHERE BookID = ?");
                        $delBook->bind_param('i', $deleteId);
                        $delBook->execute();

                        $conn->commit();

                        if (!empty($deleteRow['CoverImage'])) {
                                $coverFile = UPLOAD_DIR . basename($deleteRow['CoverImage']);
                                if (is_file($coverFile)) {
                                        @unlink($coverFile);
                                }
                        }

                        header('Location: index.php?deleted=1');
                        exit;
                } catch (Exception $e) {
                        $conn->rollback();
                        echo '<div class="info-pill">Unable to delete book: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
        }
}

$query = "SELECT Book.Title, Book.Description, Book.CoverImage, Author.Name AS Author, Genre.Name AS Genre FROM Book JOIN Author ON Book.AuthorID = Author.AuthorID JOIN Genre ON Book.GenreID = Genre.GenreID WHERE Book.BookID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// If there's no cover set, try to look up on Open Library and persist it so everyone sees it
if ($row && empty($row['CoverImage'])) {
        $queryUrl = "https://openlibrary.org/search.json?title=" . urlencode($row['Title']) . "&author=" . urlencode($row['Author']) . "&limit=1";
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
        $resp = @file_get_contents($queryUrl, false, $ctx);
        if ($resp) {
                $data = json_decode($resp, true);
                if (!empty($data['docs'][0])) {
                        $doc = $data['docs'][0];
                        if (!empty($doc['cover_i'])) {
                                $coverUrl = 'https://covers.openlibrary.org/b/id/' . intval($doc['cover_i']) . '-L.jpg';
                                $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
                                $upd->bind_param('si', $coverUrl, $id);
                                $upd->execute();
                                $row['CoverImage'] = $coverUrl;
                        } elseif (!empty($doc['isbn'][0])) {
                                $isbn = $doc['isbn'][0];
                                $coverUrl = 'https://covers.openlibrary.org/b/isbn/' . urlencode($isbn) . '-L.jpg';
                                $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
                                $upd->bind_param('si', $coverUrl, $id);
                                $upd->execute();
                                $row['CoverImage'] = $coverUrl;
                        }
                }
        }
}

function getCoverUrl($title)
{
        $seed = preg_replace('/[^a-z0-9]+/i', '', strtolower($title));
        return "https://picsum.photos/seed/{$seed}/420/540";
}

if (!$row) {
        echo '<section class="page-panel"><div class="form-card"><h2>Book not found</h2><p>Sorry, that book does not exist.</p></div></section>';
        include 'footer.php';
        exit;
}

$ratingResult = $conn->query("SELECT COUNT(*) AS count, AVG(Score) AS avg_rating FROM Rating WHERE BookID = $id");
$ratingRow = $ratingResult ? $ratingResult->fetch_assoc() : null;
$ratingCount = $ratingRow ? intval($ratingRow['count']) : 0;
$averageRating = $ratingRow && $ratingRow['avg_rating'] !== null ? floatval($ratingRow['avg_rating']) : 0;

$ratings = $conn->query("SELECT Score FROM Rating WHERE BookID = $id");

function renderStars($rating)
{
        $filledStars = max(0, min(5, intval(round($rating))));
        return str_repeat('⭐', $filledStars);
}
?>

<section class="book-detail-page">
        <span class="section-label">Book Spotlight</span>
        <div class="detail-card">
                <div class="cover-frame">
                        <?php $bookCover = !empty($row['CoverImage']) ? $row['CoverImage'] : getCoverUrl($row['Title']); ?>
                        <img class="cover-image" src="<?php echo htmlspecialchars($bookCover); ?>"
                                alt="Cover for <?php echo htmlspecialchars($row['Title']); ?>">
                </div>
                <div class="book-copy">
                        <h2 class="book-title"><?php echo htmlspecialchars($row['Title']); ?></h2>
                        <p class="book-author">by <?php echo htmlspecialchars($row['Author']); ?></p>
                        <p><strong>Genre:</strong> <?php echo htmlspecialchars($row['Genre']); ?></p>
                        <p class="book-description"><?php echo htmlspecialchars($row['Description']); ?></p>
                        <form method="POST" action="book.php?id=<?php echo $id; ?>"
                                onsubmit="return confirm('Delete this book? This action cannot be undone.');">
                                <input type="hidden" name="delete_book" value="<?php echo $id; ?>">
                                <button class="button-secondary" type="submit">Delete Book</button>
                        </form>

                        <!-- Covers are fetched automatically from Open Library when missing -->

                        <div>
                                <h3>Ratings</h3>
                                <?php if ($ratingCount === 0) { ?>
                                        <p class="rating-stars">No ratings yet. Be the first to rate it!</p>
                                <?php } else {
                                        while ($r = $ratings->fetch_assoc()) { ?>
                                                <p class="rating-stars"><?php echo str_repeat('⭐', intval($r['Score'])); ?></p>
                                        <?php }
                                } ?>
                                <?php if ($ratingCount > 0) { ?>
                                        <p>Average rating: <?php echo number_format($averageRating, 1); ?> / 5
                                                (<?php echo $ratingCount; ?> ratings)</p>
                                <?php } ?>
                        </div>
                </div>
        </div>
</section>

<?php include 'footer.php'; ?>