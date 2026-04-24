-- Drop tables if they already exist
DROP TABLE IF EXISTS Rating;
DROP TABLE IF EXISTS Book;
DROP TABLE IF EXISTS Author;
DROP TABLE IF EXISTS Genre;

-- Create tables

CREATE TABLE Author (
    AuthorID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100)
);
  
CREATE TABLE Genre (
    GenreID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100)
);

CREATE TABLE Book (
    BookID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(200),
    Publisher VARCHAR(100),
    Description TEXT,
    AuthorID INT,
    GenreID INT,
    FOREIGN KEY (AuthorID) REFERENCES Author(AuthorID),
    FOREIGN KEY (GenreID) REFERENCES Genre(GenreID)
);

CREATE TABLE Rating (
    RatingID INT PRIMARY KEY AUTO_INCREMENT,
    Score INT,
    BookID INT,
    FOREIGN KEY (BookID) REFERENCES Book(BookID)
);

-- Insert Authors
INSERT INTO Author VALUES
(1, 'J.K. Rowling'),
(2, 'George Orwell'),
(3, 'J.R.R. Tolkien'),
(4, 'Dan Brown'),
(5, 'Colleen Hoover');

-- Insert Genres
INSERT INTO Genre VALUES
(1, 'Fantasy'),
(2, 'Dystopian'),
(3, 'Adventure'),
(4, 'Thriller'),
(5, 'Romance');

-- Insert Books
INSERT INTO Book VALUES
(1, 'Harry Potter', 'Bloomsbury', 'Wizard story about a young boy discovering magic.', 1, 1),
(2, '1984', 'Secker & Warburg', 'A dystopian society under constant surveillance.', 2, 2),
(3, 'Lord of the Rings', 'Allen & Unwin', 'An epic journey to destroy the One Ring.', 3, 3),
(4, 'Da Vinci Code', 'Doubleday', 'A mystery thriller involving hidden religious secrets.', 4, 4),
(5, 'It Ends With Us', 'Atria Books', 'A romance novel dealing with complex relationships.', 5, 5),
(6, 'Animal Farm', 'Secker & Warburg', 'A political satire using farm animals.', 2, 2),
(7, 'The Hobbit', 'Allen & Unwin', 'A fantasy adventure of Bilbo Baggins.', 3, 1),
(8, 'Inferno', 'Doubleday', 'A fast-paced thriller set in Europe.', 4, 4);

-- Insert Ratings
INSERT INTO Rating (Score, BookID) VALUES
(5, 1),
(4, 1),
(5, 2),
(3, 3),
(4, 4),
(5, 5),
(4, 6),
(5, 7),
(3, 8);