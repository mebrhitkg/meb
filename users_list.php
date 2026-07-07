<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_users');
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Users - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Users</h2>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>
        <a href="user_edit.php" class="btn btn-primary btn-sm">New User</a>
      </div>
    </div>
    <table class="table table-striped">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td><?=htmlspecialchars($u['name'])?></td>
          <td><?=htmlspecialchars($u['email'])?></td>
          <td><?=htmlspecialchars($u['role'])?></td>
          <td><?=htmlspecialchars($u['created_at'])?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="user_edit.php?id=<?=$u['id']?>">Edit</a>
            <form method="post" action="user_delete.php" style="display:inline">
              <input type="hidden" name="id" value="<?=$u['id']?>">
              <input type="hidden" name="csrf" value="<?=csrf_token()?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete user?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
