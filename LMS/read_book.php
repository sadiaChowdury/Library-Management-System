<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$book) {
        echo "Book not found.";
        exit;
    }
} else {
    header('Location: view_books_customer.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Read <?php echo htmlspecialchars($book['title']); ?></title>
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
            text-align: center;
        }
        .nav-bar a {
            display: inline-block;
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
            background-color: #3498db;
            margin: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .nav-bar a:hover {
            background-color: #2980b9;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .book-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .book-header img {
            max-width: 200px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .book-content {
            text-align: justify;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            white-space: pre-line;
        }
        .end-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .end-buttons a {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-dashboard { background-color: #3498db; }
        .btn-view { background-color: #9b59b6; }
        .btn-home { background-color: #2ecc71; }
        .end-buttons a:hover { opacity: 0.9; }
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
        <a href="customer_dashboard.php">Dashboard</a>
        <a href="view_books_customer.php">View Books</a>
        <a href="customer_logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="book-header">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <h3>By: <?php echo htmlspecialchars($book['author']); ?></h3>
        </div>

        <div class="book-content">
            <?php echo nl2br(htmlspecialchars($book['content'])); ?>
        </div>

        <!-- End Navigation Buttons -->
        <div class="end-buttons">
            <a href="customer_dashboard.php" class="btn-dashboard">Go to Dashboard</a>
            <a href="view_books_customer.php" class="btn-view">View Books</a>
            <a href="index.php" class="btn-home">Go to Home</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
