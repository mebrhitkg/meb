<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_roles');
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,name,description FROM roles ORDER BY name');
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Roles - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Roles</h2>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>
        <a href="role_edit.php" class="btn btn-primary btn-sm">New Role</a>
      </div>
    </div>
    <table class="table table-striped">
      <thead><tr><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($roles as $r): ?>
        <tr>
          <td><?=htmlspecialchars($r['name'])?></td>
          <td><?=htmlspecialchars($r['description'])?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="role_edit.php?id=<?=$r['id']?>">Edit</a>
            <form method="post" action="role_delete.php" style="display:inline">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <input type="hidden" name="csrf" value="<?=csrf_token()?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete role?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
