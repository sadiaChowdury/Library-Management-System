<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_customers.php');
    exit;
}

$customer_id = intval($_GET['id']);

// Get customer info
$customer = $conn->query("SELECT username, email FROM customers WHERE id = $customer_id")->fetch_assoc();

// Get borrowed books
$query = "
    SELECT b.title, b.author, bb.borrow_date, bb.return_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.customer_id = $customer_id
    ORDER BY bb.borrow_date DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Books - <?php echo htmlspecialchars($customer['username']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #3498db; color: white; }
        .navbar { background-color: #3498db; text-align: center; padding: 15px; }
        .navbar a { color: white; margin: 0 10px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="view_customers.php">Back to Customers</a>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <h2 style="text-align:center;">Borrowed Books of <?php echo htmlspecialchars($customer['username']); ?></h2>
    <p style="text-align:center;">Email: <?php echo htmlspecialchars($customer['email']); ?></p>

    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
        </tr>
        <?php if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo date("d M Y", strtotime($row['borrow_date'])); ?></td>
                    <td><?php echo $row['return_date'] ? date("d M Y", strtotime($row['return_date'])) : 'Not Returned'; ?></td>
                </tr>
        <?php } } else { ?>
            <tr><td colspan="4">No borrowed books found.</td></tr>
        <?php } ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
