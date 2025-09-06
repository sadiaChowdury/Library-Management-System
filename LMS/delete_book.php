<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Step 1: Delete from borrowed_books first
    $stmt1 = $conn->prepare("DELETE FROM borrowed_books WHERE book_id = ?");
    $stmt1->bind_param("i", $book_id);
    $stmt1->execute();
    $stmt1->close();

    // Step 2: Delete from books
    $stmt2 = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt2->bind_param("i", $book_id);
    if ($stmt2->execute()) {
        header("Location: view_books.php");
        exit;
    } else {
        echo "Error deleting book: " . $conn->error;
    }
    $stmt2->close();
} else {
    echo "Invalid book ID.";
}

$conn->close();
?>
