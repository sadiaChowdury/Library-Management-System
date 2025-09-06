<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

$admin_username = $_SESSION['admin'];

// Dashboard stats
$total_admins = $conn->query("SELECT COUNT(*) AS total_admins FROM admin")->fetch_assoc()['total_admins'];
$total_books = $conn->query("SELECT COUNT(*) AS total_books FROM books")->fetch_assoc()['total_books'];
$total_customers = $conn->query("SELECT COUNT(*) AS total_customers FROM customers")->fetch_assoc()['total_customers'];
$recently_added_book = $conn->query("SELECT title FROM books ORDER BY id DESC LIMIT 1")->fetch_assoc()['title'] ?? 'N/A';
$recently_borrowed_book = $conn->query("SELECT books.title FROM borrowed_books 
                                        JOIN books ON borrowed_books.book_id = books.id 
                                        ORDER BY borrowed_books.borrow_date DESC LIMIT 1")->fetch_assoc()['title'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand img { height: 40px; }
        .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="Pic/logo1.png" alt="Logo" style="height: 40px;" class="me-2"> <span class="fw-bold">LMS Admin</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="d-flex flex-wrap gap-2">
        <a href="view_books.php" class="btn btn-outline-light">View Books</a>
        <a href="add_book.php" class="btn btn-outline-light">Add Book</a>
        <a href="view_customers.php" class="btn btn-outline-light">View Customers</a>
        <a href="approved_borrows.php" class="btn btn-outline-light">Approved Borrows</a>
        <a href="view_book_requests.php" class="btn btn-outline-light">Book Requests</a>
        <a href="admin_logout.php" class="btn btn-light text-primary fw-bold">Logout</a>
      </div>
    </div>
  </div>
</nav>


<!-- Welcome Section -->
<div class="container mt-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold">Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>
        <p class="text-muted">Library Management System - Admin Dashboard</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Admins</h5>
                    <p class="display-6 fw-bold"><?php echo $total_admins; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Total Books</h5>
                    <p class="display-6 fw-bold"><?php echo $total_books; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">Total Customers</h5>
                    <p class="display-6 fw-bold"><?php echo $total_customers; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">Recently Added Book</h5>
                    <p class="fs-5"><?php echo $recently_added_book; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title text-danger">Recently Borrowed Book</h5>
                    <p class="fs-5"><?php echo $recently_borrowed_book; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
