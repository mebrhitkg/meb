<?php
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
session_start();
require_once __DIR__.'/../backend/auth.php';
require_permission('manage_users');
if($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Method not allowed');
if(!csrf_check($_POST['csrf'] ?? '')) exit('Invalid CSRF');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id){
  // prevent deleting yourself
  if($id == $_SESSION['user_id']){ header('Location: users_list.php'); exit; }
  $pdo = getPDO();
  $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
  $stmt->execute([$id]);
  log_activity($_SESSION['user_id'],'user_deleted','user',$id,null);
}
header('Location: users_list.php'); exit;
