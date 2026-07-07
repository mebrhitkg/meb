<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_news');
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = '';
$content = '';
$slug = '';
$published_at = null;
if($id){
  $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row){
    $title = $row['title']; $content = $row['content']; $slug = $row['slug']; $published_at = $row['published_at'];
  }
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(!csrf_check($_POST['csrf'] ?? '')){ die('Invalid CSRF'); }
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $slug = trim($_POST['slug'] ?? '');
  $published = !empty($_POST['published']) ? $_POST['published'] : null;
  if($id){
    $stmt = $pdo->prepare('UPDATE news SET title=?,slug=?,content=?,published_at=? WHERE id=?');
    $stmt->execute([$title,$slug,$content,$published,$id]);
    log_activity($_SESSION['user_id'],'news_updated','news',$id,json_encode(['title'=>$title]));
  } else {
    $stmt = $pdo->prepare('INSERT INTO news (title,slug,content,published_at,created_at) VALUES (?,?,?,?,NOW())');
    $stmt->execute([$title,$slug,$content,$published]);
    $id = $pdo->lastInsertId();
      log_activity($_SESSION['user_id'],'news_created','news',$id,json_encode(['title'=>$title]));
  }
  header('Location: news_list.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Edit' : 'New' ?> Article</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:900px">
    <h2><?= $id ? 'Edit' : 'New' ?> Article</h2>
    <form method="post">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?=htmlspecialchars($title)?>"></div>
      <div class="mb-3"><label>Slug</label><input name="slug" class="form-control" value="<?=htmlspecialchars($slug)?>"></div>
      <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="8"><?=htmlspecialchars($content)?></textarea></div>
      <div class="mb-3"><label>Published at (YYYY-MM-DD HH:MM:SS)</label><input name="published" class="form-control" value="<?=htmlspecialchars($published_at)?>"></div>
      <button class="btn btn-primary">Save</button>
      <a href="news_list.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
