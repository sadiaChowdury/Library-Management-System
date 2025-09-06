<?php
include 'db_connect.php'; 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM customers WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        // Insert new customer
        $stmt = $conn->prepare("INSERT INTO customers (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['customer'] = $username;
            header('Location: customer_dashboard.php');
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: antiquewhite;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #3498db; 
            overflow: hidden;
            text-align: center;
            padding: 27px; 
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
<header>
    <a href="index.php">
    <img src="Pic/logo1.png" alt="Logo">
    <h1>Library Management System</h1>
    </a>
</header>
<div class="navbar">
<a href="customer_login.php">Already have an account? Login</a>
    </div>
<body>
    <div class="register-container">
        <form method="POST" class="register-form">
            <h2>Customer Register</h2>
            <label for="username">Username</label>
            <input type="text" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register</button>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
        
    </div>
</body>
</html>
