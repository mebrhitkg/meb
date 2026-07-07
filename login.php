<?php
require_once __DIR__.'/../backend/db.php';
session_start();
// redirect if already logged in
if(!empty($_SESSION['user_id'])){
  header('Location: dashboard.php'); exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'] ?? '';
  if(!$email || !$password){ $error = 'Invalid credentials'; }
  else{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, password, role, name FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password, $user['password'])){
      if($user['role'] !== 'admin'){ $error = 'Not authorized'; }
      else{
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: dashboard.php'); exit;
      }
    } else { $error = 'Invalid credentials'; }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - TVT</title>
  <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:420px">
    <h2>Admin Login</h2>
    <?php if(!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
      <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</body>
</html>
