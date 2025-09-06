<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Borrowed books query (only not returned)
$sql = "SELECT books.id AS book_id, books.title, books.author, books.genre, books.image, 
               borrowed_books.borrow_date, borrowed_books.due_date
        FROM borrowed_books
        JOIN books ON borrowed_books.book_id = books.id
        WHERE borrowed_books.customer_id = ? 
          AND borrowed_books.return_date IS NULL
        ORDER BY borrowed_books.borrow_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Borrowed Books</title>
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
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 60px;
        }
        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
        form {
            margin: 0;
        }
        button.return-btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.return-btn:hover {
            background-color: #c82333;
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
        <h2>My Borrowed Books</h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Book Image"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['genre']); ?></td>
                        <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                        <td>
                            <form action="return_book.php" method="POST">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                <button type="submit" name="return" class="return-btn">Return</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No borrowed books found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
