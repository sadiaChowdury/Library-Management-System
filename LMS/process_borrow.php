<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : null;
    $borrow_reason = trim($_POST['borrow_reason'] ?? '');
    $borrow_duration = intval($_POST['borrow_duration'] ?? 0);
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!$book_id || !$borrow_duration || empty($phone_number) || empty($address)) {
        header("Location: borrow_form.php?book_id={$book_id}&error=Please fill in all required fields");
        exit;
    }

    // চেক করো বই আগে এই কাস্টমার নিয়েছে কিনা এবং ফেরত দেয়নি কিনা
    $checkBorrow = $conn->prepare("SELECT * FROM borrowed_books WHERE book_id = ? AND customer_id = ? AND return_date IS NULL");
    $checkBorrow->bind_param("ii", $book_id, $customer_id);
    $checkBorrow->execute();
    if ($checkBorrow->get_result()->num_rows > 0) {
        header("Location: my_borrowed_books.php?error=You already borrowed this book");
        exit;
    }

    $borrow_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime("+$borrow_duration days"));

    // Borrow রেকর্ড ইনসার্ট (return_date NULL)
    $insertBorrow = $conn->prepare("INSERT INTO borrowed_books 
        (customer_id, book_id, borrow_date, return_date, borrow_reason, phone_number, address, due_date) 
        VALUES (?, ?, ?, NULL, ?, ?, ?, ?)");
    $insertBorrow->bind_param("iisssss", $customer_id, $book_id, $borrow_date, $borrow_reason, $phone_number, $address, $due_date);

    if ($insertBorrow->execute()) {
        // বই unavailable করা
        $update = $conn->prepare("UPDATE books SET available = 0 WHERE id = ?");
        $update->bind_param("i", $book_id);
        $update->execute();

        header("Location: my_borrowed_books.php?success=1");
        exit;
    } else {
        header("Location: borrow_form.php?book_id={$book_id}&error=Failed to borrow the book");
        exit;
    }
}
?>
