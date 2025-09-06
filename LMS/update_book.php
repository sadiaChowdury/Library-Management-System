<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

// Step 1: Get book ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid book ID.");
}
$book_id = intval($_GET['id']);

// Step 2: Fetch book data
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found.");
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $image = $_POST['image']; // যদি শুধু URL হয়, না হলে file upload logic লাগবে

    $update_stmt = $conn->prepare("UPDATE books SET title=?, author=?, genre=?, image=? WHERE id=?");
    $update_stmt->bind_param("ssssi", $title, $author, $genre, $image, $book_id);

    if ($update_stmt->execute()) {
        header("Location: view_books.php");
        exit;
    } else {
        echo "Error updating book: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Update Book</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" value="<?php echo htmlspecialchars($book['genre']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL</label>
            <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($book['image']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_books.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
<?php $conn->close(); ?>
