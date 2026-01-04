<?php
declare(strict_types=1);
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
  http_response_code(404);
  exit("Candidate not found");
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Candidate - <?=h($c['emp_id'])?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4" style="max-width:1000px">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?=h($c['name'])?> <small class="text-muted">(<?=h($c['emp_id'])?>)</small></h3>
    <div class="d-flex gap-2">
      <a class="btn btn-secondary" href="dashboard.php">Back</a>
      <a class="btn btn-primary" href="candidate_update.php?id=<?=$c['id']?>">Update</a>
      <?php if ($c['status'] === 'APPROVED' && $c['offer_pdf_path']): ?>
        <a class="btn btn-success" href="offer_download.php?token=<?=h($c['offer_download_token'] ?? '')?>">Download Offer</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Details</h5>
          <p class="mb-1"><b>Email:</b> <?=h($c['email'])?></p>
          <p class="mb-1"><b>Mobile:</b> <?=h($c['mobile'])?></p>
          <p class="mb-1"><b>WhatsApp:</b> <?=h($c['whatsapp'])?></p>
          <p class="mb-1"><b>Designation:</b> <?=h($c['designation'])?></p>
          <p class="mb-1"><b>Interview Taken By:</b> <?=h($c['interview_taken_by'])?></p>
          <p class="mb-1"><b>Salary:</b> <?=h($c['salary'] ? (string)$c['salary'] : '-')?></p>
          <p class="mb-1"><b>Joining Date:</b> <?=h($c['joining_date'] ?? '-')?></p>
          <p class="mb-1"><b>Status:</b> <span class="badge bg-info"><?=h($c['status'])?></span></p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Offer Letter</h5>
          <p class="mb-2"><b>PDF:</b> <?= $c['offer_pdf_path'] ? h($c['offer_pdf_path']) : '-' ?></p>
          <p class="mb-2"><b>Generated At:</b> <?= $c['offer_generated_at'] ? h($c['offer_generated_at']) : '-' ?></p>

          <?php if ($c['status'] === 'APPROVED'): ?>
            <div class="alert alert-success">
              Candidate approved. You can regenerate the offer if needed.
            </div>
            <a class="btn btn-outline-success" href="offer_generate.php?id=<?=$c['id']?>">Generate / Re-Generate Offer + Email</a>
          <?php else: ?>
            <div class="alert alert-warning">
              Offer generates only after status = APPROVED and salary + joining date are filled.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
