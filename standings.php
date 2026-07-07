<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_standings');
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>League Standings</title><link href="/assets/css/style.css" rel="stylesheet"></head>
<body>
<div class="container my-5"><h2>League Standings (placeholder)</h2><p>Implement standings view and calculations here.</p><a href="dashboard.php" class="btn btn-secondary">Back</a></div>
</body></html>
