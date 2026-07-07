<?php
require_once __DIR__.'/../backend/auth.php';
require_once __DIR__.'/../backend/csrf.php';
require_permission('manage_newsletter');
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Newsletter Subscribers</title><link href="/assets/css/style.css" rel="stylesheet"></head>
<body>
<div class="container my-5"><h2>Newsletter Subscribers (placeholder)</h2><p>Implement subscriber list and export here.</p><a href="dashboard.php" class="btn btn-secondary">Back</a></div>
</body></html>
