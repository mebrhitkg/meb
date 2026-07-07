<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_contacts');
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id){
  $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $msg = $stmt->fetch(PDO::FETCH_ASSOC);
  if(!$msg) exit('Message not found');
  if(!$msg['is_read']){
    $upd = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $upd->execute([$id]);
  }
} else {
  exit('No message ID');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Message - Admin</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:900px">
    <h2>Contact Message</h2>
    <div class="card">
      <div class="card-body">
        <p><strong>From:</strong> <?=htmlspecialchars($msg['name'])?></p>
        <p><strong>Email:</strong> <a href="mailto:<?=htmlspecialchars($msg['email'])?>"><?=htmlspecialchars($msg['email'])?></a></p>
        <p><strong>Subject:</strong> <?=htmlspecialchars($msg['subject'])?></p>
        <p><strong>Date:</strong> <?=htmlspecialchars($msg['created_at'])?></p>
        <hr>
        <h5>Message</h5>
        <p><?=nl2br(htmlspecialchars($msg['message']))?></p>
      </div>
    </div>
    <a href="contact_messages.php" class="btn btn-secondary mt-3">Back to Messages</a>
  </div>
</body>
</html>
