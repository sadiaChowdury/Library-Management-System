<?php
include 'db_connect.php'; 
session_start(); 

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title     = $_POST['title'];
    $author    = $_POST['author'];
    $genre     = $_POST['genre'];
    $content   = $_POST['content']; 
    $popularity = (int)$_POST['popularity'];

    // IMAGE UPLOAD
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/books/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $error = "❌ Failed to upload image.";
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, genre, image, content, popularity) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssi", $title, $author, $genre, $image_path, $content, $popularity);
        
        if ($stmt->execute()) {
            $success = "✅ Book added successfully!";
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        header img {
            max-width: 50px;
            height: auto;
        }
        header h1 {
            font-size: 22px;
            margin: 0;
        }
        .navbar {
            background-color: #3498db; 
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 10px;
            margin-top: 70px;
        }
        .navbar a {
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            background-color: #2980b9;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .navbar a:hover {
            background-color: #1f6690;
            transform: scale(1.05);
        }
        .container {
            max-width: 600px;
            margin: 20px auto 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        form button {
            background-color: #3498db;
            color: white;
            border: none;
            margin-top: 15px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #2980b9;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        @media (max-width: 480px) {
            header {
                flex-direction: column;
                padding: 10px;
            }
            header h1 {
                font-size: 18px;
            }
            .navbar {
                margin-top: 100px;
            }
            .container {
                margin: 15px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="Pic/logo1.png" alt="Logo">
        <h1>Library Management System</h1>
    </header>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_books.php">View Books</a>
        <a href="admin_logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Add Book</h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" name="title" required>
            
            <label for="author">Author</label>
            <input type="text" name="author" required>
            
            <label for="genre">Genre</label>
            <input type="text" name="genre" required>
            
            <label for="image">Upload Book Image</label>
            <input type="file" name="image" accept="image/*" required>
            
            <label for="content">Book Content</label>
            <textarea name="content" rows="6" required></textarea>

            <label for="popularity">Popularity</label>
            <input type="number" name="popularity" min="0" value="0" required>
            
            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>
