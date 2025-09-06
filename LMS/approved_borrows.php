<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Approved Borrow Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-primary">✅ Approved Borrow Requests</h2>
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Book Title</th>
        <th>Customer</th>
        <th>Borrow Date</th>
        <th>Approved By</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT bb.*, b.title, c.username AS customer_name, a.username AS admin_name
              FROM borrowed_books bb
              JOIN books b ON bb.book_id = b.id
              JOIN customers c ON bb.customer_id = c.id
              LEFT JOIN admin a ON bb.approved_by_admin_id = a.id
              WHERE bb.approved_by_admin_id IS NOT NULL
              ORDER BY bb.borrow_date DESC";
      $result = $conn->query($sql);
      $count = 1;
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$count}</td>
                  <td>".htmlspecialchars($row['title'])."</td>
                  <td>".htmlspecialchars($row['customer_name'])."</td>
                  <td>{$row['borrow_date']}</td>
                  <td>".htmlspecialchars($row['admin_name'])."</td>
                </tr>";
          $count++;
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
<?php $conn->close(); ?>
