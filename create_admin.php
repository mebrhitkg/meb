<?php
// Usage: php create_admin.php email password
require_once __DIR__.'/db.php';
if(PHP_SAPI !== 'cli'){
  echo "Run from CLI\n"; exit;
}
$email = $argv[1] ?? 'admin@example.com';
$password = $argv[2] ?? 'Admin@123';
$name = $argv[3] ?? 'Administrator';
try{
  $pdo = getPDO();
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role,created_at) VALUES (?, ?, ?, ?, NOW())');
  $stmt->execute([$name,$email,$hash,'admin']);
  echo "Created admin: $email\n";
}catch(Exception $e){
  echo "Error: ".$e->getMessage()."\n";
}
