<?php
require_once __DIR__.'/db.php';
if(session_status() === PHP_SESSION_NONE) session_start();

function current_user(){
  if(!empty($_SESSION['user_id'])){
    return ['id'=>$_SESSION['user_id'],'name'=>$_SESSION['user_name'] ?? '','role'=>$_SESSION['user_role'] ?? ''];
  }
  return null;
}

function require_login(){
  if(empty($_SESSION['user_id'])){
    header('Location: /admin/login.php'); exit;
  }
}

function has_permission($permission){
  if(empty($_SESSION['user_role'])) return false;
  $role = $_SESSION['user_role'];
  if($role === 'admin') return true; // admin shortcut
  $pdo = getPDO();
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM role_permissions WHERE role = ? AND permission = ?');
  $stmt->execute([$role,$permission]);
  return $stmt->fetchColumn() > 0;
}

function require_permission($permission){
  require_login();
  if(!has_permission($permission)){
    http_response_code(403);
    echo 'Forbidden';
    exit;
  }
}

function log_activity($user_id, $action, $target_type = null, $target_id = null, $details = null){
  try{
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO activity_logs (user_id,action,target_type,target_id,details,created_at) VALUES (?,?,?,?,?,NOW())');
    $stmt->execute([$user_id,$action,$target_type,$target_id,$details]);
  } catch(Exception $e){
    // ignore logging errors
  }
}
