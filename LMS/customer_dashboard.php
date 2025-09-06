<?php
include 'db_connect.php'; 
session_start();
if (!isset($_SESSION['customer'])) {
    header('Location: customer_login.php');
    exit;
}

// Queries
$sqlPopular = "SELECT * FROM books ORDER BY popularity DESC LIMIT 10"; 
$resultPopular = $conn->query($sqlPopular);

$sqlBiography = "SELECT * FROM books WHERE genre = 'Biography' ORDER BY popularity DESC LIMIT 10";
$resultBiography = $conn->query($sqlBiography);

$sqlThriller = "SELECT * FROM books WHERE genre = 'Thriller' ORDER BY popularity DESC LIMIT 10";
$resultThriller = $conn->query($sqlThriller);

$sqlSciFi = "SELECT * FROM books WHERE genre = 'Science Fiction' ORDER BY popularity DESC LIMIT 10";
$resultSciFi = $conn->query($sqlSciFi);

function renderSlider($result) {
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            echo '<div class="slide">
                    <a href="read_book.php?id='.$book['id'].'">
                        <img src="'.htmlspecialchars($book['image']).'" alt="'.htmlspecialchars($book['title']).'">
                        <p>'.htmlspecialchars($book['title']).'</p>
                    </a>
                  </div>';
        }
        mysqli_data_seek($result, 0);
        while ($book = $result->fetch_assoc()) {
            echo '<div class="slide">
                    <a href="read_book.php?id='.$book['id'].'">
                        <img src="'.htmlspecialchars($book['image']).'" alt="'.htmlspecialchars($book['title']).'">
                        <p>'.htmlspecialchars($book['title']).'</p>
                    </a>
                  </div>';
        }
    } else {
        echo "<p>No books available.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; margin:0; padding:0; background:#fff; }
    header { background:#3498db; color:white; padding:15px; text-align:center; }
    header img { width:50px; vertical-align:middle; }
    header h1 { display:inline-block; margin:0; font-size:22px; vertical-align:middle; }
    .navbar { background:#3498db; text-align:center; padding:15px; }
    .navbar a { color:#f2f2f2; padding:10px 15px; text-decoration:none; font-size:16px; margin:0 10px; transition:0.3s; display:inline-block; }
    .navbar a:hover { background:#ddd; color:black; transform:scale(1.1); }
    .container { text-align:center; padding:30px 20px; }
    .container h1 { font-size:36px; margin-bottom:10px; }
    .container p { font-size:18px; color:#555; }
    .book-section { text-align:center; padding:30px 20px; background:#f8f9fa; }
    .book-section h3 { font-size:28px; margin-bottom:15px; color:#333; }
    .slider { overflow:hidden; white-space:nowrap; max-width:100%; position:relative; }
    .slide-track { display:flex; animation: scroll 25s linear infinite; }
    @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    .slide { background:#fff; display:inline-block; width:140px; margin:0 8px; }
    .slide img { width:120px; height:160px; object-fit:cover; border-radius:8px; box-shadow:0 3px 6px rgba(0,0,0,0.1); }
    .slide p { margin-top:6px; font-size:14px; color:#555; }
    .slide a { text-decoration:none; color:inherit; }
    .slide a:hover { color:#3498db; }
</style>
</head>
<body>

<header>
    <img src="Pic/logo1.png" alt="Logo">
    <h1>Library Management System</h1>
</header>

<div class="navbar">
    <a href="view_books_customer.php">View Books</a>
    <a href="my_borrowed_books.php">My Borrowed Books</a>
    <a href="customer_logout.php">Logout</a>
</div>

<div class="container">
    <h1>Welcome to Your Dashboard</h1>
    <p>Explore the most popular and interesting books.</p>
</div>

<!-- Most Popular -->
<div class="book-section">
    <h3>Most Popular Books</h3>
    <div class="slider"><div class="slide-track"><?php renderSlider($resultPopular); ?></div></div>
</div>

<!-- Biography -->
<div class="book-section">
    <h3>Biography Books</h3>
    <div class="slider"><div class="slide-track"><?php renderSlider($resultBiography); ?></div></div>
</div>

<!-- Thriller -->
<div class="book-section">
    <h3>Thriller Books</h3>
    <div class="slider"><div class="slide-track"><?php renderSlider($resultThriller); ?></div></div>
</div>

<!-- Science Fiction -->
<div class="book-section">
    <h3>Science Fiction Books</h3>
    <div class="slider"><div class="slide-track"><?php renderSlider($resultSciFi); ?></div></div>
</div>

</body>
</html>
<?php $conn->close(); ?>
