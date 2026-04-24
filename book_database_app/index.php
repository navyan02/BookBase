<?php
$pageTitle = 'BookHub';
include 'header.php';
include 'db.php';

$search = trim($_GET['search'] ?? '');
$query = "SELECT Book.BookID, Book.Title, Book.Description, Book.CoverImage, Author.Name AS Author FROM Book JOIN Author ON Book.AuthorID = Author.AuthorID";

if ($search !== '') {
        $query .= " WHERE Book.Title LIKE ?";
        $stmt = $conn->prepare($query);
        $like = "%{$search}%";
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $result = $stmt->get_result();
} else {
        $result = $conn->query($query);
}

function getCoverUrl($title)
{
        $seed = preg_replace('/[^a-z0-9]+/i', '', strtolower($title));
        return "https://picsum.photos/seed/{$seed}/420/540";
}
?>

<section class="page-hero">
        <div class="hero-copy">
                <span class="section-label">Discover new reads</span>
                <h1>Find your next favorite story.</h1>
                <p>Browse a fresh collection of books with vivid covers, playful details, and reading energy made for
                        young book lovers.</p>

                <form class="search-form" method="GET" action="index.php">
                        <input class="search-input" type="text" name="search" placeholder="Search by book title..."
                                value="<?php echo htmlspecialchars($search); ?>">
                        <button class="button-primary" type="submit">Search</button>
                </form>
        </div>
</section>

<section class="book-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
                <article class="book-card">
                        <div class="cover-frame">
                                <?php $coverUrl = !empty($row['CoverImage']) ? $row['CoverImage'] : getCoverUrl($row['Title']); ?>
                                <img class="cover-image" src="<?php echo htmlspecialchars($coverUrl); ?>"
                                        alt="Cover for <?php echo htmlspecialchars($row['Title']); ?>">
                        </div>
                        <div class="book-copy">
                                <h2 class="book-title"><a
                                                href="book.php?id=<?php echo $row['BookID']; ?>"><?php echo htmlspecialchars($row['Title']); ?></a>
                                </h2>
                                <p class="book-author">by <?php echo htmlspecialchars($row['Author']); ?></p>
                                <p class="book-description"><?php echo htmlspecialchars($row['Description']); ?></p>
                        </div>
                </article>
        <?php } ?>
</section>

<?php include 'footer.php'; ?>