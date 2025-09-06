-- Create database
CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Customers table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255),
    password VARCHAR(255) NOT NULL
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    popularity INT DEFAULT 0,
    available TINYINT(1) NOT NULL DEFAULT 1,
    added_by_admin_id INT,
    FOREIGN KEY (added_by_admin_id) REFERENCES admin(id)
);

-- Borrowed Books table
CREATE TABLE IF NOT EXISTS borrowed_books (
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

-- Book Requests table
CREATE TABLE IF NOT EXISTS book_requests (
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

-- Initial data
INSERT INTO admin (username, password) VALUES 
('admin', '1234');

INSERT INTO customers (username, email, password) VALUES 
('Bashirul', 'bashirul@example.com', '1723');
