<?php
declare(strict_types=1);
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';
requireLogin();

$error = "";
$success = "";

function generateEmpId(PDO $pdo): string {
  $year = date('Y');
  $prefix = "PW-{$year}-";

  $stmt = $pdo->prepare("SELECT emp_id FROM candidates WHERE emp_id LIKE ? ORDER BY id DESC LIMIT 1");
  $stmt->execute([$prefix . "%"]);
  $last = $stmt->fetchColumn();

  $nextNum = 1;
  if ($last) {
    $parts = explode('-', (string)$last);
    $numPart = (int)end($parts);
    $nextNum = $numPart + 1;
  }

  return $prefix . str_pad((string)$nextNum, 4, "0", STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $mobile = trim($_POST['mobile'] ?? '');
  $whatsapp = trim($_POST['whatsapp'] ?? '');
  $designation = trim($_POST['designation'] ?? '');
  $interview_taken_by = trim($_POST['interview_taken_by'] ?? '');

  if (!$name || !$email || !$mobile || !$whatsapp || !$designation || !$interview_taken_by) {
    $error = "All fields are required";
  } else {
    $emp_id = generateEmpId($pdo);

    $stmt = $pdo->prepare("
      INSERT INTO candidates
      (emp_id, name, email, mobile, whatsapp, designation, interview_taken_by, created_by_user_id)
      VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
      $emp_id, $name, $email, $mobile, $whatsapp, $designation, $interview_taken_by, currentUserId()
    ]);

    header("Location: candidate_view.php?id=" . $pdo->lastInsertId());
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create Candidate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">
  <div class="d-flex justify-content-between mb-3">
    <h3>Create Candidate</h3>
    <a class="btn btn-secondary" href="dashboard.php">Back</a>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?=h($error)?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" type="email" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Mobile</label>
          <input class="form-control" name="mobile" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">WhatsApp Number</label>
          <input class="form-control" name="whatsapp" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Designation</label>
          <input class="form-control" name="designation" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Interview Taken By</label>
          <input class="form-control" name="interview_taken_by" required>
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
