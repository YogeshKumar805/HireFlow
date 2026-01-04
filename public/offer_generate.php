<?php
declare(strict_types=1);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';
requireLogin();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/mail.php';

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  exit("Invalid candidate id.");
}

// Fetch candidate
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$c) {
  http_response_code(404);
  exit("Candidate not found");
}

// Validation
if (($c['status'] ?? '') !== 'APPROVED') {
  exit("Offer can be generated only when status is APPROVED.");
}
if (empty($c['salary']) || empty($c['joining_date'])) {
  exit("Salary and Joining Date must be filled before generating offer.");
}

// Token for download link
$token = bin2hex(random_bytes(24)); // 48 chars

/* ------------------------------------------------------------------
   TEMPLATE VARIABLES (THIS FIXES YOUR UNDEFINED VARIABLE WARNINGS)
------------------------------------------------------------------- */
$today = date('d M Y');

// DOJ format for letter
$doj = date('d M Y', strtotime((string)$c['joining_date']));

// REF ID format (you can change this pattern)
$refId = "NG/IT/" . date('Y') . "/" . ($c['emp_id'] ?? $c['id']);

// RFID / unique id for footer
$rfid = strtoupper(bin2hex(random_bytes(4))); // 8 chars

// Stipend: if you don’t have in DB yet keep 0 or set fixed value
$stipend = 0;

// Salary breakup from monthly salary field (adjust logic if salary is CTC/year)
$totalMonthly = (float)$c['salary'];
$salary = [
  'total'      => $totalMonthly,
  'fixed'      => round($totalMonthly * 0.70),
  'variable'   => round($totalMonthly * 0.15),
  'attendance' => round($totalMonthly * 0.05),
  'incentive'  => round($totalMonthly * 0.05),
  'allowance'  => round($totalMonthly * 0.05),
];

// Render HTML template (template will use: $c, $today, $refId, $rfid, $doj, $stipend, $salary)
ob_start();
include __DIR__ . '/../templates/offer_letter_template.php';
$html = ob_get_clean();

// Generate PDF
$tempDir = __DIR__ . '/../storage';
$offerDir = __DIR__ . '/../storage/offers';

if (!is_dir($tempDir)) {
  mkdir($tempDir, 0775, true);
}
if (!is_dir($offerDir)) {
  mkdir($offerDir, 0775, true);
}

try {
  $mpdf = new Mpdf([
    'tempDir'      => $tempDir,
    'format'       => 'A4',
    'margin_top'    => 12,
    'margin_bottom' => 12,
    'margin_left'   => 12,
    'margin_right'  => 12,
  ]);

  $mpdf->WriteHTML($html);

  $filename = "OFFER_" . ($c['emp_id'] ?? $c['id']) . ".pdf";
  $fullPath = $offerDir . "/" . $filename;

  $mpdf->Output($fullPath, Destination::FILE);
} catch (Throwable $e) {
  echo "<h3>PDF ERROR</h3>";
  echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
  exit;
}

$relativePath = "storage/offers/" . $filename;

// Update DB with path + token
$upd = $pdo->prepare("
  UPDATE candidates
  SET offer_pdf_path = ?, offer_generated_at = NOW(), offer_download_token = ?
  WHERE id = ?
");
$upd->execute([$relativePath, $token, $id]);

// Email candidate with attachment + download link
try {
  $mail = makeMailer();
  $mail->addAddress((string)$c['email'], (string)$c['name']);

  $mail->Subject = "Offer Letter - " . ($c['designation'] ?? '') . " (" . ($c['emp_id'] ?? $c['id']) . ")";

  // Build download link
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

  // basePath points to /crm/public
  $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');

  $downloadUrl = "{$scheme}://{$host}{$basePath}/offer_download.php?token={$token}";

  $mail->Body = "
    <p>Hi <b>" . h($c['name']) . "</b>,</p>
    <p>Congratulations! Please find your offer letter attached.</p>
    <p><b>Salary:</b> " . h((string)$c['salary']) . "<br>
       <b>Joining Date:</b> " . h((string)$c['joining_date']) . "</p>
    <p>You can also download it here: <a href='" . h($downloadUrl) . "'>" . h($downloadUrl) . "</a></p>
    <p>Regards,<br>HR Team</p>
  ";

  $mail->addAttachment($fullPath, $filename);
  $mail->send();
} catch (Throwable $e) {
  echo "<h3>MAIL ERROR</h3>";
  echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
  exit;
}

header("Location: candidate_view.php?id={$id}&mail=sent");
exit;
