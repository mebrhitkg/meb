<?php
$require_block=
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
session_start();
require_permission('manage_users');
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$name = '';
$email = '';
$role = 'viewer';
// fetch available roles
$roleStmt = $pdo->query('SELECT name FROM roles ORDER BY name');
$roles = $roleStmt->fetchAll(PDO::FETCH_COLUMN);
if($id){
  $stmt = $pdo->prepare('SELECT id,name,email,role FROM users WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row){ $name = $row['name']; $email = $row['email']; $role = $row['role']; }
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(!csrf_check($_POST['csrf'] ?? '')){ die('Invalid CSRF'); }
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $role = trim($_POST['role'] ?? 'viewer');
  $password = $_POST['password'] ?? '';
  if($id){
    // detect role change
    $oldRole = isset($row['role']) ? $row['role'] : null;
    if($password !== ''){
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('UPDATE users SET name=?,email=?,password=?,role=? WHERE id=?');
      $stmt->execute([$name,$email,$hash,$role,$id]);
    } else {
      $stmt = $pdo->prepare('UPDATE users SET name=?,email=?,role=? WHERE id=?');
      $stmt->execute([$name,$email,$role,$id]);
    }
    // log role change
    if($oldRole !== null && $oldRole !== $role){
      log_activity($_SESSION['user_id'],'role_changed','user',$id,"$oldRole => $role");
    } else {
      log_activity($_SESSION['user_id'],'user_updated','user',$id,null);
    }
  } else {
    $hash = password_hash($password ?: bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role,created_at) VALUES (?,?,?,?,NOW())');
    $stmt->execute([$name,$email,$hash,$role]);
    $id = $pdo->lastInsertId();
    log_activity($_SESSION['user_id'],'user_created','user',$id,json_encode(['name'=>$name,'email'=>$email,'role'=>$role]));
  }
  header('Location: users_list.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Edit' : 'New' ?> User</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:720px">
    <h2><?= $id ? 'Edit' : 'New' ?> User</h2>
    <form method="post">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="<?=htmlspecialchars($name)?>" required></div>
      <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" value="<?=htmlspecialchars($email)?>" required></div>
      <div class="mb-3"><label>Role</label>
        <select name="role" class="form-select">
          <?php foreach($roles as $r): ?>
            <option value="<?=htmlspecialchars($r)?>" <?= $r === $role ? 'selected' : '' ?>><?=htmlspecialchars($r)?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control"><small class="form-text text-muted">Leave blank to keep current password (when editing)</small></div>
      <button class="btn btn-primary">Save</button>
      <a href="users_list.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
