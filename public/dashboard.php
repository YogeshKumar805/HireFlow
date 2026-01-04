<?php
declare(strict_types=1);
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';

requireLogin();

$status = strtoupper(trim($_GET['status'] ?? ''));
$allowed = ['PENDING','APPROVED','REJECTED'];
$where = "";
$params = [];

if (in_array($status, $allowed, true)) {
  $where = "WHERE status = ?";
  $params[] = $status;
}

$stmt = $pdo->prepare("SELECT * FROM candidates $where ORDER BY created_at DESC LIMIT 200");
$stmt->execute($params);
$candidates = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - CRM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">CRM</a>
    <div class="d-flex gap-2">
      <span class="navbar-text text-white">Hi, <?=h($_SESSION['name'] ?? '')?> (<?=h($_SESSION['role'] ?? '')?>)</span>
      <a class="btn btn-outline-light btn-sm" href="candidate_create.php">+ Candidate</a>
      <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex gap-2 mb-3">
    <a class="btn btn-sm btn-secondary" href="dashboard.php">All</a>
    <a class="btn btn-sm btn-warning" href="dashboard.php?status=PENDING">Pending</a>
    <a class="btn btn-sm btn-success" href="dashboard.php?status=APPROVED">Approved</a>
    <a class="btn btn-sm btn-danger" href="dashboard.php?status=REJECTED">Rejected</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Candidates <?= $status ? "(".h($status).")" : "" ?></h5>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Emp ID</th>
              <th>Name</th>
              <th>Designation</th>
              <th>Status</th>
              <th>Created</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($candidates as $c): ?>
            <tr>
              <td><?=h($c['emp_id'])?></td>
              <td><?=h($c['name'])?></td>
              <td><?=h($c['designation'])?></td>
              <td>
                <?php
                  $badge = 'secondary';
                  if ($c['status'] === 'PENDING') $badge = 'warning';
                  if ($c['status'] === 'APPROVED') $badge = 'success';
                  if ($c['status'] === 'REJECTED') $badge = 'danger';
                ?>
                <span class="badge bg-<?=$badge?>"><?=h($c['status'])?></span>
              </td>
              <td><?=h($c['created_at'])?></td>
              <td>
                <a class="btn btn-sm btn-primary" href="candidate_view.php?id=<?=$c['id']?>">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
