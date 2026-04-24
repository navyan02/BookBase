BOOK DATABASE WEB APPLICATION - SETUP STEPS

1. Install XAMPP.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Copy the folder book_database_app into:
   Windows: C:\xampp\htdocs\book_database_app
   Mac: /Applications/XAMPP/htdocs/book_database_app
4. Open phpMyAdmin in your browser:
   http://localhost/phpmyadmin
5. Click SQL, paste the contents of schema.sql, then click Go.
6. Open the app:
   http://localhost/book_database_app/index.php
7. Test the required functions:
   - Browse all books on index.php
   - Search by title on search.php
   - Search by author on search.php
   - Search by genre on search.php
   - View selected book details from index.php or search.php
   - Add rating on add_rating.php

Dynamic queries included:
1. Search by title
2. Search by author
3. Search by genre
4. Search by publisher
5. View book details by selected BookID
6. View ratings by selected BookID
7. Insert new rating

Join queries included:
1. index.php joins Book, Author, Genre, and Rating
2. search.php joins Book, Author, and Genre
3. book.php joins Book, Author, and Genre

Session included:
book.php stores the last viewed book ID in $_SESSION['last_book_viewed'].
