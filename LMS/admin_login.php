<?php
include 'db_connect.php'; 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        
        if ($password === $admin['password']) {
            $_SESSION['admin'] = $username;
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:aquamarine;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #3498db; 
            overflow: hidden;
            text-align: center;
            padding: 30px;
            /* Adjust as needed */ 
        }

        .navbar a {
            display: inline-block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
            transform: scale(1.2);
        }

        .container {
            text-align: center;
            padding: 60px 20px;
            margin-top: 50px; 
        }

        .container h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 24px;
            color: #555;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        </style>
</head>
<body>
<header>
    <a href="index.php">
        <img src="Pic/logo1.png" alt="Logo">
        <h1>Library Management System</h1>
    </a>
</header>
<div class="navbar">
        <a href="customer_register.php">Registration</a>
        <a href="customer_login.php">Customer Login</a>
    </div>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Admin Login</h2>
            <label for="username">Username</label>
            <input type="text" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" required>
            
            <button type="submit">Login</button>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
