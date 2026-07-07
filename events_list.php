<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_events');
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,title,slug,type,start_date,status,created_at FROM events ORDER BY start_date DESC');
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Events - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Events</h2>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>
        <a href="events_edit.php" class="btn btn-primary btn-sm">New Event</a>
      </div>
    </div>
    <table class="table table-striped">
      <thead><tr><th>Title</th><th>Type</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($events as $e): ?>
        <tr>
          <td><?=htmlspecialchars($e['title'])?></td>
          <td><?=htmlspecialchars($e['type'])?></td>
          <td><?=htmlspecialchars($e['start_date'])?></td>
          <td><span class="badge bg-info"><?=htmlspecialchars($e['status'])?></span></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="events_edit.php?id=<?=$e['id']?>">Edit</a>
            <form method="post" action="events_delete.php" style="display:inline">
              <input type="hidden" name="id" value="<?=$e['id']?>">
              <input type="hidden" name="csrf" value="<?=csrf_token()?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete event?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

