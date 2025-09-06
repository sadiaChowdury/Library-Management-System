<?php
include 'db_connect.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM customers WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        
        if ($password === $customer['password']) {
            $_SESSION['customer'] = $username;
            $_SESSION['customer_id'] = $customer['id'];
            header('Location: customer_dashboard.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
if (isset($stmt)) { $stmt->close(); }
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header img {
            height: 50px;
            margin-right: 10px;
        }
        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 60px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background: white;
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="bg-primary text-white py-3">
    <div class="container d-flex align-items-center">
        <a href="index.php" class="d-flex align-items-center text-white text-decoration-none">
            <img src="Pic/logo1.png" alt="Logo">
            <h1 class="h4 mb-0">Library Management System</h1>
        </a>
    </div>
</header>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Customer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="customer_register.php">Registration</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Form -->
<div class="container">
    <div class="login-card">
        <h2 class="text-center mb-4">Customer Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
