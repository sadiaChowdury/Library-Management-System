<?php
include 'db_connect.php'; 
session_start();

// কাস্টমার লগইন চেক
if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

// URL থেকে book_id নেওয়া
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : null;
if (!$book_id) {
    echo "Invalid book ID.";
    exit;
}

// বইয়ের ডিটেইলস আনা
$bookQuery = $conn->prepare("SELECT * FROM books WHERE id = ?");
$bookQuery->bind_param("i", $book_id);
$bookQuery->execute();
$book = $bookQuery->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        .nav-bar {
            background-color: #444;
            overflow: hidden;
        }
        .nav-bar a {
            float: left;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .nav-bar a:hover {
            background-color: #575757;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
        }
        input, textarea, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="Pic/logo1.png" alt="Logo">
        <h1>Library Management System</h1>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <a href="index.php">Home</a>
        <a href="view_books_customer.php">View Books</a>
        <a href="my_borrowed_books.php">My Borrowed Books</a>
        <a href="customer_logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Borrow Book: <?php echo htmlspecialchars($book['title']); ?></h2>
        <form action="process_borrow.php" method="POST">
            <!-- Hidden fields -->
            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
            <input type="hidden" name="borrow_date" value="<?php echo date('Y-m-d'); ?>">

            <!-- Phone Number -->
            <label for="phone_number">Your Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required pattern="\d{10,15}" placeholder="Enter your phone number">

            <!-- Address -->
            <label for="address">Your Address:</label>
            <textarea id="address" name="address" required placeholder="Enter your address"></textarea>

            <!-- Borrow Reason -->
            <label for="borrow_reason">Why do you want to borrow this book?</label>
            <textarea id="borrow_reason" name="borrow_reason" required></textarea>

            <!-- Borrow Duration -->
            <label for="borrow_duration">How long do you want to borrow this book for? (in days)</label>
            <input type="number" id="borrow_duration" name="borrow_duration" required min="1">

            <button type="submit">Submit and Borrow</button>
        </form>
    </div>
</body>
</html>
