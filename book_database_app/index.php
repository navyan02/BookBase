<?php
include 'db.php';
session_start();

$search = "";
$query = "
SELECT Book.BookID, Book.Title, Book.Description, Author.Name AS Author
FROM Book
JOIN Author ON Book.AuthorID = Author.AuthorID
";

if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $query .= " WHERE Book.Title LIKE '%$search%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>
        <title>BookHub</title>

        <style>
                body {
                        font-family: 'Segoe UI', sans-serif;
                        background: #f4f6fb;
                        margin: 0;
                }

                .navbar {
                        background: #6c5ce7;
                        padding: 15px;
                        color: white;
                        display: flex;
                        justify-content: space-between;
                }

                .navbar a {
                        color: white;
                        margin: 0 10px;
                        text-decoration: none;
                        font-weight: bold;
                }

                .container {
                        padding: 20px;
                }

                .search-box {
                        margin-bottom: 20px;
                }

                input {
                        padding: 10px;
                        width: 250px;
                        border-radius: 5px;
                        border: 1px solid #ccc;
                }

                button {
                        padding: 10px;
                        background: #00b894;
                        border: none;
                        color: white;
                        border-radius: 5px;
                        cursor: pointer;
                }

                .grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                        gap: 20px;
                }

                .card {
                        background: white;
                        padding: 15px;
                        border-radius: 12px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        transition: 0.2s;
                }

                .card:hover {
                        transform: scale(1.03);
                }

                .title {
                        font-size: 18px;
                        font-weight: bold;
                }

                .author {
                        color: #636e72;
                }
        </style>
</head>

<body>

        <div class="navbar">
                <div>📚 BookHub</div>
                <div>
                        <a href="index.php">Home</a>
                        <a href="index.php">Search</a>
                        <a href="add_rating.php">Add Rating</a>
                        <a href="add_book.php">Add Book</a>
                </div>
        </div>

        <div class="container">

                <form class="search-box">
                        <input type="text" name="search" placeholder="Search books..." value="<?php echo $search; ?>">
                        <button>Search</button>
                </form>

                <div class="grid">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                                <div class="card">
                                        <div class="title">
                                                <a href="book.php?id=<?php echo $row['BookID']; ?>">
                                                        <?php echo $row['Title']; ?>
                                                </a>
                                        </div>
                                        <div class="author">by <?php echo $row['Author']; ?></div>
                                        <p><?php echo $row['Description']; ?></p>
                                </div>
                        <?php } ?>
                </div>

        </div>

</body>

</html>