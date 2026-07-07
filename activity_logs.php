<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('view_activity_logs');
$pdo = getPDO();
$stmt = $pdo->query('SELECT al.*, u.email AS user_email FROM activity_logs al LEFT JOIN users u ON u.id = al.user_id ORDER BY al.created_at DESC LIMIT 200');
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Activity Logs</title><link href="/assets/css/style.css" rel="stylesheet"></head>
<body>
<div class="container my-5">
  <h2>Activity Logs</h2>
  <table class="table table-sm table-striped">
  <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Target</th><th>Details</th></tr></thead>
  <tbody>
  <?php foreach($logs as $l): ?>
    <tr>
      <td><?=htmlspecialchars($l['created_at'])?></td>
      <td><?=htmlspecialchars($l['user_email'] ?? 'system')?></td>
      <td><?=htmlspecialchars($l['action'])?></td>
      <td><?=htmlspecialchars(($l['target_type']?:'').' '.($l['target_id']?:''))?></td>
      <td><?=htmlspecialchars($l['details'])?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body></html>
