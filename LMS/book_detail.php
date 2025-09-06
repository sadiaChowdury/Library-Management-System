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
} else {
    header('Location: view_books_customer.php'); 
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="Pic/logo1.png" alt="Logo">
        <h1>Library Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="customer_dashboard.php">Dashboard</a></li>
            <li><a href="view_books_customer.php">View Books</a></li>
            <li><a href="customer_logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <div class="book-details">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-image">
            <div class="info">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
                
                <!-- Link to Read Book -->
                <a href="read_book.php?id=<?php echo $book['id']; ?>" class="button-link">Read Book</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
