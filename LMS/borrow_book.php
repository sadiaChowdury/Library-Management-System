<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

// Get the customer ID from the session
$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];

    // Check if the request is to return the book
    if (isset($_POST['return'])) {
        // Mark the book as returned
        $stmt = $conn->prepare("UPDATE borrowed_books SET return_date = NOW() WHERE book_id = ? AND customer_id = ? AND return_date IS NULL");
        $stmt->bind_param("ii", $book_id, $customer_id);

        if ($stmt->execute()) {
            echo "Book returned successfully!";
            header("Location: view_books_customer.php");
            exit;
        } else {
            echo "Error returning book.";
        }
        $stmt->close();
    } else {
        // Handle book borrowing process
        // First, check if the book is already borrowed by the customer
        $checkBorrow = $conn->prepare("SELECT * FROM borrowed_books WHERE book_id = ? AND customer_id = ? AND return_date IS NULL");
        $checkBorrow->bind_param("ii", $book_id, $customer_id);
        $checkBorrow->execute();
        $alreadyBorrowed = $checkBorrow->get_result()->num_rows > 0;
        $checkBorrow->close();

        if ($alreadyBorrowed) {
            echo "You have already borrowed this book and not yet returned it.";
        } else {
            // Proceed with borrowing the book
            $borrowDate = date('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO borrowed_books (book_id, customer_id, borrow_date) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $book_id, $customer_id, $borrowDate);

            if ($stmt->execute()) {
                echo "Book borrowed successfully!";
                header("Location: view_books_customer.php");
                exit;
            } else {
                echo "Error borrowing book.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
