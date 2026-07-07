<?php
require_once __DIR__.'/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
  if(!$email){ header('Location: /?error=invalid'); exit; }
  $pdo = getPDO();
  $stmt = $pdo->prepare('INSERT IGNORE INTO newsletter_subscribers (email,created_at) VALUES (?, NOW())');
  $stmt->execute([$email]);
  header('Location: /?subscribed=1');
}
