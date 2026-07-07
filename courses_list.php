<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_courses');
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Courses</title><link href="/assets/css/style.css" rel="stylesheet"></head>
<body>
<div class="container my-5"><h2>Courses (placeholder)</h2><p>Implement courses management here.</p><a href="dashboard.php" class="btn btn-secondary">Back</a></div>
</body></html>
