<?php
require_once __DIR__.'/../backend/db.php';
require_once __DIR__.'/../backend/csrf.php';
require_once __DIR__.'/../backend/auth.php';
require_permission('manage_events');
if($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Method not allowed');
if(!csrf_check($_POST['csrf'] ?? '')) exit('Invalid CSRF');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id){
  $pdo = getPDO();
  $sel = $pdo->prepare('SELECT title FROM events WHERE id = ? LIMIT 1'); 
  $sel->execute([$id]); 
  $row = $sel->fetch(PDO::FETCH_ASSOC);
  $stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
  $stmt->execute([$id]);
  log_activity($_SESSION['user_id'],'event_deleted','event',$id,json_encode($row));
}
header('Location: events_list.php'); exit;
