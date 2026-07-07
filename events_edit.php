<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_events');
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = '';
$description = '';
$slug = '';
$type = 'match';
$start_date = '';
$end_date = '';
$location = '';
$team1_id = '';
$team2_id = '';
$status = 'scheduled';
$result_team1 = '';
$result_team2 = '';

if($id){
  $stmt = $pdo->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row){
    $title = $row['title']; $description = $row['description']; $slug = $row['slug'];
    $type = $row['type']; $start_date = $row['start_date']; $end_date = $row['end_date'];
    $location = $row['location']; $team1_id = $row['team1_id']; $team2_id = $row['team2_id'];
    $status = $row['status']; $result_team1 = $row['result_team1']; $result_team2 = $row['result_team2'];
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(!csrf_check($_POST['csrf'] ?? '')){ die('Invalid CSRF'); }
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $slug = trim($_POST['slug'] ?? '');
  $type = trim($_POST['type'] ?? 'match');
  $start_date = trim($_POST['start_date'] ?? '');
  $end_date = trim($_POST['end_date'] ?? '');
  $location = trim($_POST['location'] ?? '');
  $team1_id = !empty($_POST['team1_id']) ? (int)$_POST['team1_id'] : null;
  $team2_id = !empty($_POST['team2_id']) ? (int)$_POST['team2_id'] : null;
  $status = trim($_POST['status'] ?? 'scheduled');
  $result_team1 = !empty($_POST['result_team1']) ? (int)$_POST['result_team1'] : null;
  $result_team2 = !empty($_POST['result_team2']) ? (int)$_POST['result_team2'] : null;

  if($id){
    $stmt = $pdo->prepare('UPDATE events SET title=?,slug=?,description=?,type=?,start_date=?,end_date=?,location=?,team1_id=?,team2_id=?,status=?,result_team1=?,result_team2=? WHERE id=?');
    $stmt->execute([$title,$slug,$description,$type,$start_date,$end_date,$location,$team1_id,$team2_id,$status,$result_team1,$result_team2,$id]);
    log_activity($_SESSION['user_id'],'event_updated','event',$id,json_encode(['title'=>$title]));
  } else {
    $stmt = $pdo->prepare('INSERT INTO events (title,slug,description,type,start_date,end_date,location,team1_id,team2_id,status,result_team1,result_team2,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())');
    $stmt->execute([$title,$slug,$description,$type,$start_date,$end_date,$location,$team1_id,$team2_id,$status,$result_team1,$result_team2]);
    $id = $pdo->lastInsertId();
    log_activity($_SESSION['user_id'],'event_created','event',$id,json_encode(['title'=>$title,'type'=>$type]));
  }
  header('Location: events_list.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Edit' : 'New' ?> Event</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5" style="max-width:900px">
    <h2><?= $id ? 'Edit' : 'New' ?> Event</h2>
    <form method="post">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?=htmlspecialchars($title)?>" required></div>
          <div class="mb-3"><label>Slug</label><input name="slug" class="form-control" value="<?=htmlspecialchars($slug)?>"></div>
          <div class="mb-3"><label>Type</label>
            <select name="type" class="form-select">
              <option value="match" <?= $type === 'match' ? 'selected' : '' ?>>Match</option>
              <option value="tournament" <?= $type === 'tournament' ? 'selected' : '' ?>>Tournament</option>
              <option value="training" <?= $type === 'training' ? 'selected' : '' ?>>Training</option>
              <option value="workshop" <?= $type === 'workshop' ? 'selected' : '' ?>>Workshop</option>
            </select>
          </div>
          <div class="mb-3"><label>Start Date</label><input name="start_date" type="datetime-local" class="form-control" value="<?=htmlspecialchars(str_replace(' ', 'T', $start_date))?>" required></div>
          <div class="mb-3"><label>End Date</label><input name="end_date" type="datetime-local" class="form-control" value="<?=htmlspecialchars(str_replace(' ', 'T', $end_date))?>"></div>
        </div>
        <div class="col-md-6">
          <div class="mb-3"><label>Location</label><input name="location" class="form-control" value="<?=htmlspecialchars($location)?>"></div>
          <div class="mb-3"><label>Team 1 ID</label><input name="team1_id" type="number" class="form-control" value="<?=htmlspecialchars($team1_id)?>"></div>
          <div class="mb-3"><label>Team 2 ID</label><input name="team2_id" type="number" class="form-control" value="<?=htmlspecialchars($team2_id)?>"></div>
          <div class="mb-3"><label>Status</label>
            <select name="status" class="form-select">
              <option value="scheduled" <?= $status === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
              <option value="ongoing" <?= $status === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
              <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
              <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="4"><?=htmlspecialchars($description)?></textarea></div>
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3"><label>Team 1 Result</label><input name="result_team1" type="number" class="form-control" value="<?=htmlspecialchars($result_team1)?>"></div>
        </div>
        <div class="col-md-6">
          <div class="mb-3"><label>Team 2 Result</label><input name="result_team2" type="number" class="form-control" value="<?=htmlspecialchars($result_team2)?>"></div>
        </div>
      </div>
      <button class="btn btn-primary">Save</button>
      <a href="events_list.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
