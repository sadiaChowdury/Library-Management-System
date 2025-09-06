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
  <title>Customer Book Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-success">📚 Customer Book Requests</h2>
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-success">
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Book Title</th>
        <th>Request Date</th>
        <th>Status</th>
        <th>Approved By</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT br.*, c.username AS customer_name, a.username AS admin_name
              FROM book_requests br
              JOIN customers c ON br.customer_id = c.id
              LEFT JOIN admin a ON br.approved_by_admin_id = a.id
              ORDER BY br.request_date DESC";
      $result = $conn->query($sql);
      $count = 1;
      while ($row = $result->fetch_assoc()) {
          $status = $row['approved_by_admin_id'] ? '✅ Approved' : '⏳ Pending';
          echo "<tr>
                  <td>{$count}</td>
                  <td>".htmlspecialchars($row['customer_name'])."</td>
                  <td>".htmlspecialchars($row['book_title'])."</td>
                  <td>{$row['request_date']}</td>
                  <td>{$status}</td>
                  <td>".($row['admin_name'] ?? '—')."</td>
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
