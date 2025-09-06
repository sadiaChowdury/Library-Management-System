<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

if (isset($_POST['return'])) {
    $customer_id = $_SESSION['customer_id'];
    $book_id = intval($_POST['book_id']);

    // 1️⃣ borrowed_books টেবিলে return_date সেট করা
    $sql = "UPDATE borrowed_books 
            SET return_date = NOW() 
            WHERE book_id = ? AND customer_id = ? AND return_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $book_id, $customer_id);

    if ($stmt->execute()) {
        // 2️⃣ books টেবিলে available = 1 করা
        $updateBook = $conn->prepare("UPDATE books SET available = 1 WHERE id = ?");
        $updateBook->bind_param("i", $book_id);
        $updateBook->execute();
        $updateBook->close();

        // 3️⃣ success মেসেজ সহ রিডাইরেক্ট
        header('Location: my_borrowed_books.php?success=Book returned successfully');
        exit;
    } else {
        header('Location: my_borrowed_books.php?error=Failed to return the book');
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
