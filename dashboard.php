<?php
require_once __DIR__.'/../backend/db.php';
session_start();
if(empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
  header('Location: login.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - TVT</title>
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Dashboard</h1>
      <div>
        <span class="me-3">Hello, <?=htmlspecialchars($_SESSION['user_name'] ?? '')?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
      </div>
    </div>
    <div class="list-group">
      <a href="news_list.php" class="list-group-item">Manage News</a>
      <a href="events_list.php" class="list-group-item">Manage Events</a>
      <a href="teams_list.php" class="list-group-item">Manage Teams</a>
      <a href="players_list.php" class="list-group-item">Manage Players</a>
      <a href="fixtures_list.php" class="list-group-item">Fixtures & Results</a>
      <a href="standings.php" class="list-group-item">League Standings</a>
      <a href="users_list.php" class="list-group-item">Manage Users</a>
      <a href="roles_list.php" class="list-group-item">Manage Roles</a>
      <a href="gallery_list.php" class="list-group-item">Gallery</a>
      <a href="sponsors_list.php" class="list-group-item">Sponsors</a>
      <a href="documents_list.php" class="list-group-item">Documents</a>
      <a href="courses_list.php" class="list-group-item">Courses</a>
      <a href="memberships_list.php" class="list-group-item">Memberships</a>
      <a href="contact_messages.php" class="list-group-item">Contact Messages</a>
      <a href="newsletter_subscribers.php" class="list-group-item">Newsletter Subscribers</a>
      <a href="activity_logs.php" class="list-group-item">Activity Logs</a>
      <a href="settings.php" class="list-group-item">Settings</a>
      <a href="seo_settings.php" class="list-group-item">SEO Settings</a>
      <a href="backup_restore.php" class="list-group-item">Backup & Restore</a>
    </div>
  </div>
</body>
</html>
