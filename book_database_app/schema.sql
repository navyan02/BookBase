CREATE DATABASE IF NOT EXISTS book_database;
USE book_database;

DROP TABLE IF EXISTS Rating;
DROP TABLE IF EXISTS Book;
DROP TABLE IF EXISTS Author;
DROP TABLE IF EXISTS Genre;

CREATE TABLE Author (
  AuthorID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100) NOT NULL
);

CREATE TABLE Genre (
  GenreID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100) NOT NULL
);

CREATE TABLE Book (
  BookID INT AUTO_INCREMENT PRIMARY KEY,
  Title VARCHAR(150) NOT NULL,
  Publisher VARCHAR(100),
  Description TEXT,
  AuthorID INT NOT NULL,
  GenreID INT NOT NULL,
  FOREIGN KEY (AuthorID) REFERENCES Author(AuthorID),
  FOREIGN KEY (GenreID) REFERENCES Genre(GenreID)
);

CREATE TABLE Rating (
  RatingID INT AUTO_INCREMENT PRIMARY KEY,
  Score INT NOT NULL CHECK (Score BETWEEN 1 AND 5),
  BookID INT NOT NULL,
  FOREIGN KEY (BookID) REFERENCES Book(BookID) ON DELETE CASCADE
);

INSERT INTO Author (Name) VALUES
('J.K. Rowling'), ('George Orwell'), ('Jane Austen'), ('Rick Riordan'), ('Suzanne Collins');

INSERT INTO Genre (Name) VALUES
('Fantasy'), ('Dystopian'), ('Classic'), ('Adventure'), ('Science Fiction');

INSERT INTO Book (Title, Publisher, Description, AuthorID, GenreID) VALUES
('Harry Potter and the Sorcerer''s Stone', 'Bloomsbury', 'A young wizard discovers his magical heritage.', 1, 1),
('1984', 'Secker & Warburg', 'A dystopian novel about surveillance and control.', 2, 2),
('Pride and Prejudice', 'T. Egerton', 'A classic novel about love, class, and society.', 3, 3),
('The Lightning Thief', 'Disney-Hyperion', 'A boy discovers he is the son of a Greek god.', 4, 4),
('The Hunger Games', 'Scholastic', 'A dystopian survival story set in Panem.', 5, 2);

INSERT INTO Rating (Score, BookID) VALUES
(5,1),(4,1),(5,2),(4,2),(5,3),(4,4),(5,5),(3,5);
