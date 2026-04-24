<?php
include 'db.php';

$id = $_GET['id'];

$query = "
SELECT Book.Title, Book.Description, Author.Name AS Author, Genre.Name AS Genre
FROM Book
JOIN Author ON Book.AuthorID = Author.AuthorID
JOIN Genre ON Book.GenreID = Genre.GenreID
WHERE Book.BookID = $id
";

$result = $conn->query($query);
$row = $result->fetch_assoc();

$ratings = $conn->query("SELECT * FROM Rating WHERE BookID = $id");
?>

<!DOCTYPE html>
<html>

<head>
        <style>
                body {
                        font-family: Arial;
                        background: #f4f6fb;
                        padding: 20px;
                }

                .card {
                        background: white;
                        padding: 20px;
                        border-radius: 12px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                }

                .star {
                        color: gold;
                }
        </style>
</head>

<body>

        <div class="card">
                <h1><?php echo $row['Title']; ?></h1>
                <p><b>Author:</b> <?php echo $row['Author']; ?></p>
                <p><b>Genre:</b> <?php echo $row['Genre']; ?></p>
                <p><?php echo $row['Description']; ?></p>

                <h3>Ratings:</h3>

                <?php while ($r = $ratings->fetch_assoc()) { ?>
                        <div>
                                <?php
                                for ($i = 0; $i < $r['Score']; $i++)
                                        echo "⭐";
                                ?>
                        </div>
                <?php } ?>

        </div>

</body>

</html>