<?php include 'header.php'; include 'db.php'; ?>
<h2>All Books</h2>
<p>This page dynamically retrieves all books with their authors, genres, and average ratings.</p>
<?php
$sql = "SELECT Book.BookID, Book.Title, Book.Publisher, Author.Name AS AuthorName, Genre.Name AS GenreName,
        ROUND(AVG(Rating.Score), 2) AS AvgRating
        FROM Book
        JOIN Author ON Book.AuthorID = Author.AuthorID
        JOIN Genre ON Book.GenreID = Genre.GenreID
        LEFT JOIN Rating ON Book.BookID = Rating.BookID
        GROUP BY Book.BookID, Book.Title, Book.Publisher, Author.Name, Genre.Name";
$result = $conn->query($sql);
?>
<table>
<tr><th>Title</th><th>Author</th><th>Genre</th><th>Publisher</th><th>Average Rating</th><th>Details</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['Title']) ?></td>
<td><?= htmlspecialchars($row['AuthorName']) ?></td>
<td><?= htmlspecialchars($row['GenreName']) ?></td>
<td><?= htmlspecialchars($row['Publisher']) ?></td>
<td><?= $row['AvgRating'] ? htmlspecialchars($row['AvgRating']) : 'No ratings' ?></td>
<td><a href="book.php?id=<?= $row['BookID'] ?>">View</a></td>
</tr>
<?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>
