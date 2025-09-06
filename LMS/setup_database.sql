-- database
CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

--  admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- customers table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

--  books table
CREATE TABLE IF NOT EXISTS books (
  
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    content TEXT NOT NULL, 
    popularity INT DEFAULT 0
);

CREATE TABLE borrowed_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    customer_id INT,
    borrow_date DATE,
    return_date DATE DEFAULT NULL,
    borrow_reason TEXT, -- Reason for borrowing
    borrow_duration INT, -- Duration in days
    phone_number VARCHAR(15) NOT NULL, -- New field for phone number
    address TEXT NOT NULL, -- New field for address
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

INSERT INTO admin (username, password) VALUES 
('admin', '1234');


INSERT INTO customers (username, password) VALUES 
('Bashirul', '1723');

ALTER TABLE borrowed_books ADD due_date DATE AFTER borrow_date;
ALTER TABLE books ADD available TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE borrowed_books
DROP FOREIGN KEY borrowed_books_ibfk_1;

ALTER TABLE borrowed_books
ADD CONSTRAINT borrowed_books_ibfk_1
FOREIGN KEY (book_id) REFERENCES books(id)
ON DELETE CASCADE;

ALTER TABLE customers ADD email VARCHAR(255) AFTER username;

ALTER TABLE books MODIFY id INT AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE books
ADD added_by_admin_id INT AFTER available,
ADD FOREIGN KEY (added_by_admin_id) REFERENCES admin(id);

ALTER TABLE borrowed_books
ADD approved_by_admin_id INT AFTER customer_id,
ADD FOREIGN KEY (approved_by_admin_id) REFERENCES admin(id);


CREATE TABLE borrowed_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    customer_id INT NOT NULL,
    approved_by_admin_id INT,
    borrow_date DATE NOT NULL,
    due_date DATE,
    return_date DATE DEFAULT NULL,
    borrow_reason TEXT,
    borrow_duration INT,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES admin(id)
);

CREATE TABLE book_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    book_title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    genre VARCHAR(100),
    request_date DATE DEFAULT CURRENT_DATE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by_admin_id INT,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES admin(id)
);

