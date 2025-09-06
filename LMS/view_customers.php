<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

// Delete customer if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $customer_id = intval($_GET['delete']);
    $conn->query("DELETE FROM customers WHERE id = $customer_id");
    header("Location: view_customers.php");
    exit;
}

// Fetch customers with borrowed books count & last borrow date
$query = "
    SELECT c.id, c.username, c.email, 
           COUNT(bb.id) AS borrowed_count,
           MAX(bb.borrow_date) AS last_borrow_date
    FROM customers c
    LEFT JOIN borrowed_books bb ON c.id = bb.customer_id
    GROUP BY c.id, c.username, c.email
    ORDER BY c.id ASC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Customers</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 95%; margin: 20px auto; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #3498db; color: white; }
        a.delete-btn { color: white; background: red; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        a.delete-btn:hover { background: darkred; }
        a.view-btn { color: white; background: green; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        a.view-btn:hover { background: darkgreen; }
        .navbar { background-color: #3498db; text-align: center; padding: 15px; }
        .navbar a { color: white; margin: 0 10px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_books.php">View Books</a>
        <a href="add_book.php">Add Book</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <h2 style="text-align:center;">All Customers</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Borrowed Books</th>
            <th>Last Borrow Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo $row['borrowed_count']; ?></td>
                <td>
                    <?php 
                        echo $row['last_borrow_date'] 
                            ? date("d M Y", strtotime($row['last_borrow_date'])) 
                            : 'N/A'; 
                    ?>
                </td>
                <td>
                    <a class="view-btn" href="customer_borrowed_books.php?id=<?php echo $row['id']; ?>">View Borrowed Books</a>
                    <a class="delete-btn" href="view_customers.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this customer?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
