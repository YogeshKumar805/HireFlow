<?php
declare(strict_types=1);
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) { http_response_code(404); exit("Candidate not found"); }

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $salary = trim($_POST['salary'] ?? '');
  $joining_date = trim($_POST['joining_date'] ?? '');
  $status = strtoupper(trim($_POST['status'] ?? 'PENDING'));

  $allowed = ['PENDING','APPROVED','REJECTED'];
  if (!in_array($status, $allowed, true)) $status = 'PENDING';

  // Allow empty salary/date unless approving
  if ($status === 'APPROVED') {
    if ($salary === '' || $joining_date === '') {
      $error = "For APPROVED status, salary and joining date are required.";
    }
  }

  if (!$error) {
    $stmt = $pdo->prepare("
      UPDATE candidates
      SET salary = ?, joining_date = ?, status = ?
      WHERE id = ?
    ");
    $stmt->execute([
      $salary === '' ? null : (float)$salary,
      $joining_date === '' ? null : $joining_date,
      $status,
      $id
    ]);

    header("Location: candidate_view.php?id=" . $id);
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Update Candidate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">
  <div class="d-flex justify-content-between mb-3">
    <h3>Update: <?=h($c['name'])?> (<?=h($c['emp_id'])?>)</h3>
    <a class="btn btn-secondary" href="candidate_view.php?id=<?=$id?>">Back</a>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?=h($error)?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Salary (CTC)</label>
          <input class="form-control" name="salary" value="<?=h($c['salary'] ?? '')?>" placeholder="e.g. 600000">
        </div>
        <div class="col-md-6">
          <label class="form-label">Joining Date</label>
          <input class="form-control" name="joining_date" type="date" value="<?=h($c['joining_date'] ?? '')?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <?php foreach (['PENDING','APPROVED','REJECTED'] as $s): ?>
              <option value="<?=$s?>" <?=($c['status']===$s?'selected':'')?>>
                <?=$s?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <button class="btn btn-primary">Save</button>
          <a class="btn btn-outline-secondary" href="candidate_view.php?id=<?=$id?>">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
