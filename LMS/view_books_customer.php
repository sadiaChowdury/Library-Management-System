<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : '';

$genreQuery = "SELECT DISTINCT genre FROM books";
$genreResult = $conn->query($genreQuery);

if ($searchQuery && $selectedGenre) {
    $sql = "SELECT * FROM books WHERE (title LIKE ? OR author LIKE ?) AND genre = ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $selectedGenre);
} elseif ($searchQuery) {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} elseif ($selectedGenre) {
    $sql = "SELECT * FROM books WHERE genre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedGenre);
} else {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
}

if (isset($stmt)) {
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Books</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background-color: #f4f6f8;
    }
    header {
        background-color: #3498db;
        color: white;
        padding: 15px;
        text-align: center;
    }
    header img {
        max-width: 50px;
        vertical-align: middle;
    }
    header h1 {
        display: inline;
        margin-left: 10px;
        font-size: 22px;
    }
    .navbar {
        background-color: #2980b9;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding: 10px;
    }
    .navbar a {
        color: white;
        padding: 8px 14px;
        text-decoration: none;
        border-radius: 5px;
        background-color: #1f6690;
        transition: background 0.3s ease;
    }
    .navbar a:hover {
        background-color: #145374;
    }
    .search-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding: 15px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .search-container input, .search-container select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .search-container button {
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .search-container button:hover {
        background-color: #2980b9;
    }
    /* Responsive compact cards */
    .book-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
        padding: 20px;
    }
    .book-item {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease;
    }
    .book-item:hover {
        transform: translateY(-3px);
    }
    .book-item img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
    .book-info {
        padding: 10px;
        flex-grow: 1;
    }
    .book-info h2 {
        font-size: 16px;
        margin: 0 0 5px;
    }
    .book-info p {
        font-size: 13px;
        margin: 2px 0;
        color: #555;
    }
    .book-info a {
        display: inline-block;
        margin-top: 5px;
        font-size: 12px;
        color: #3498db;
        text-decoration: none;
    }
    .book-info a:hover {
        text-decoration: underline;
    }
    .book-actions {
        padding: 10px;
        display: flex;
        gap: 5px;
    }
    .borrow-btn, .return-btn {
        flex: 1;
        padding: 6px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        font-size: 13px;
    }
    .borrow-btn {
        background-color: #28a745;
    }
    .borrow-btn:hover {
        background-color: #218838;
    }
    .return-btn {
        background-color: #e74c3c;
    }
    .return-btn:hover {
        background-color: #c0392b;
    }
</style>
</head>
<body>
<header>
    <img src="Pic/logo1.png" alt="Logo">
    <h1>Library Management System</h1>
</header>
<div class="navbar">
    <a href="customer_dashboard.php">Dashboard</a>
    <a href="my_borrowed_books.php">My Borrowed Books</a>
    <a href="customer_logout.php">Logout</a>
</div>
<div class="search-container">
    <form action="view_books_customer.php" method="GET" style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
        <input type="text" name="query" placeholder="Search for books..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <select name="genre">
            <option value="">All Genres</option>
            <?php while ($genre = $genreResult->fetch_assoc()) { ?>
                <option value="<?php echo htmlspecialchars($genre['genre']); ?>" <?php if ($selectedGenre == $genre['genre']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($genre['genre']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Search</button>
    </form>
</div>
<div class="book-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($book = $result->fetch_assoc()) { 
            $book_id = $book['id'];
            $checkBorrow = $conn->prepare("SELECT id FROM borrowed_books WHERE book_id = ? AND customer_id = ? AND return_date IS NULL");
            $checkBorrow->bind_param("ii", $book_id, $customer_id);
            $checkBorrow->execute();
            $borrowed = $checkBorrow->get_result()->num_rows > 0;
            $checkBorrow->close();
        ?>
        <div class="book-item">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            <div class="book-info">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                <a href="book_detail.php?id=<?php echo $book['id']; ?>">View Details</a>
            </div>
            <div class="book-actions">
                <?php if ($borrowed): ?>
                    <form method="POST" action="borrow_book.php" style="flex:1;">
                        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                        <button class="return-btn" type="submit" name="return">Return</button>
                    </form>
                <?php else: ?>
                    <form method="GET" action="borrow_form.php" style="flex:1;">
                        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                        <button class="borrow-btn" type="submit">Borrow</button>
                                        </form>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>
    <?php else: ?>
        <p style="text-align:center; padding:20px;">
            No results found
            <?php 
                if ($searchQuery !== '') { 
                    echo ' for "' . htmlspecialchars($searchQuery) . '"'; 
                } 
                if ($selectedGenre !== '') { 
                    echo ' in "' . htmlspecialchars($selectedGenre) . '"'; 
                } 
            ?>.
        </p>
    <?php endif; ?>
</div>
</body>
</html>
