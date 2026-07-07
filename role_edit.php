<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_roles');
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$name = '';
$description = '';
if($id){
  $stmt = $pdo->prepare('SELECT * FROM roles WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row){ $name = $row['name']; $description = $row['description']; }
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(!csrf_check($_POST['csrf'] ?? '')){ die('Invalid CSRF'); }
  $name = trim($_POST['name'] ?? '');
  $description = trim($_POST['description'] ?? '');
  if($id){
    // fetch old
    $old = $pdo->prepare('SELECT name,description FROM roles WHERE id=?'); $old->execute([$id]); $oldRow = $old->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare('UPDATE roles SET name=?,description=? WHERE id=?');
    $stmt->execute([$name,$description,$id]);
    log_activity($_SESSION['user_id'],'role_updated','role',$id,json_encode(['old'=>$oldRow,'new'=>['name'=>$name,'description'=>$description]]));
  } else {
    $stmt = $pdo->prepare('INSERT INTO roles (name,description) VALUES (?,?)');
    $stmt->execute([$name,$description]);
    $id = $pdo->lastInsertId();
    log_activity($_SESSION['user_id'],'role_created','role',$id,json_encode(['name'=>$name,'description'=>$description]));
  }
  header('Location: roles_list.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Edit' : 'New' ?> Role</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:720px">
    <h2><?= $id ? 'Edit' : 'New' ?> Role</h2>
    <form method="post">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="<?=htmlspecialchars($name)?>" required></div>
      <div class="mb-3"><label>Description</label><input name="description" class="form-control" value="<?=htmlspecialchars($description)?>"></div>
      <button class="btn btn-primary">Save</button>
      <a href="roles_list.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
