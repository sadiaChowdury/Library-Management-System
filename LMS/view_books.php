<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

$sql = "SELECT * FROM books";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f9f9;
        }
        .card img {
            height: 250px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="admin_dashboard.php">
        <img src="Pic/logo1.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
        Library Management
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Header -->
<div class="container text-center my-4">
    <h2 class="fw-bold">Available Books</h2>
</div>

<!-- Book List -->
<div class="container">
    <div class="row g-4">
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($book = $result->fetch_assoc()) { ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <div class="card-body">
                            <h6 class="text-muted">ID: <?php echo htmlspecialchars($book['id']); ?></h6>
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="mb-1"><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="mb-2"><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                            <a href="update_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning">Update</a>
                            <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="text-center">No books found.</p>
        <?php } ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
