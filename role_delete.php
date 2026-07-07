<?php
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
$session_block=
session_start();
require_once __DIR__.'/../backend/auth.php';
require_permission('manage_roles');
if($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Method not allowed');
if(!csrf_check($_POST['csrf'] ?? '')) exit('Invalid CSRF');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id){
  $pdo = getPDO();
  // prevent deleting the admin role by name
  $stmt = $pdo->prepare('SELECT name FROM roles WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $r = $stmt->fetch(PDO::FETCH_ASSOC);
  if($r && $r['name'] === 'admin'){
    header('Location: roles_list.php'); exit;
  }
  $stmt = $pdo->prepare('DELETE FROM roles WHERE id = ?');
  $stmt->execute([$id]);
  log_activity($_SESSION['user_id'],'role_deleted','role',$id,json_encode($r));
}
header('Location: roles_list.php'); exit;
