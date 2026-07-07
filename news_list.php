<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_news');
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,title,slug,published_at,created_at FROM news ORDER BY created_at DESC');
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage News - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>News</h2>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>
        <a href="news_edit.php" class="btn btn-primary btn-sm">New Article</a>
      </div>
    </div>
    <table class="table table-striped">
      <thead><tr><th>Title</th><th>Published</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($news as $n): ?>
        <tr>
          <td><?=htmlspecialchars($n['title'])?></td>
          <td><?=htmlspecialchars($n['published_at'])?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="news_edit.php?id=<?=$n['id']?>">Edit</a>
            <form method="post" action="news_delete.php" style="display:inline">
              <input type="hidden" name="id" value="<?=$n['id']?>">
              <input type="hidden" name="csrf" value="<?=csrf_token()?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
