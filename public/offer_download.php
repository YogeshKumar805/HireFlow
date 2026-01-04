<?php
declare(strict_types=1);

require __DIR__ . '/../config/db.php';

$token = trim($_GET['token'] ?? '');
if ($token === '') {
  http_response_code(400);
  exit("Missing token");
}

$stmt = $pdo->prepare("SELECT offer_pdf_path, emp_id FROM candidates WHERE offer_download_token = ? AND status='APPROVED' LIMIT 1");
$stmt->execute([$token]);
$row = $stmt->fetch();

if (!$row || empty($row['offer_pdf_path'])) {
  http_response_code(404);
  exit("Invalid token or offer not found");
}

$path = __DIR__ . '/../' . $row['offer_pdf_path'];
if (!file_exists($path)) {
  http_response_code(404);
  exit("File not found");
}

$filename = "OFFER_" . $row['emp_id'] . ".pdf";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
