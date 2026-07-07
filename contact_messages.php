<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_contacts');
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,name,email,subject,is_read,created_at FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Messages - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Contact Messages</h2>
      <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <table class="table table-striped">
      <thead><tr><th>From</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($messages as $m): ?>
        <tr>
          <td><?=htmlspecialchars($m['name'])?></td>
          <td><?=htmlspecialchars($m['email'])?></td>
          <td><?=htmlspecialchars($m['subject'])?></td>
          <td><?=htmlspecialchars($m['created_at'])?></td>
          <td><?= $m['is_read'] ? '<span class="badge bg-secondary">Read</span>' : '<span class="badge bg-primary">Unread</span>' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="contact_view.php?id=<?=$m['id']?>">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

