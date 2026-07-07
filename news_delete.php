<?php
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
require_permission('manage_news');
if(empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
  header('Location: login.php'); exit;
}
if($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Method not allowed');
if(!csrf_check($_POST['csrf'] ?? '')) exit('Invalid CSRF');
  // fetch title for audit
  $sel = $pdo->prepare('SELECT title FROM news WHERE id = ? LIMIT 1'); $sel->execute([$id]); $row = $sel->fetch(PDO::FETCH_ASSOC);
  $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
  $stmt->execute([$id]);
  log_activity($_SESSION['user_id'],'news_deleted','news',$id,json_encode($row));
  $pdo = getPDO();
  $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
  $stmt->execute([$id]);
}
header('Location: news_list.php'); exit;
