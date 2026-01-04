<?php
declare(strict_types=1);

// Avoid function name conflict with auth.php
if (!function_exists('esc')) {
  function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
  }
}

if (!function_exists('money_inr')) {
  function money_inr($v): string {
    return "₹" . number_format((float)$v, 0);
  }
}

// Safety defaults (no warnings even if something missing)
$today   = $today   ?? date('d M Y');
$refId   = $refId   ?? 'NA';
$rfid    = $rfid    ?? 'NA';
$doj     = $doj     ?? '__________';
$stipend = $stipend ?? 0;
$salary  = $salary  ?? ['total'=>0,'fixed'=>0,'variable'=>0,'attendance'=>0,'incentive'=>0,'allowance'=>0];

$name = $c['name'] ?? 'Candidate';
$designation = $c['designation'] ?? 'Trainee';
$reporting = $c['interview_taken_by'] ?? 'Project Manager / Technical Head';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; line-height: 1.35; }
  .center { text-align:center; }
  .title { font-weight:700; font-size: 13pt; }
  .sub { font-size: 9pt; margin-top: 2px; }
  .divider { border-top: 1px solid #000; margin: 10px 0 10px; }
  table.meta { width:100%; border-collapse: collapse; }
  table.meta td { vertical-align: top; padding: 2px 0; }
  h3 { font-size: 12pt; margin: 12px 0 6px; }
  p { margin: 6px 0; }
  ul { margin: 4px 0 8px 18px; }
  ol { margin: 4px 0 8px 18px; }
  .box { border: 1px solid #000; padding: 10px; margin-top: 8px; }
  .small { font-size: 9pt; }
  .page-break { page-break-after: always; }
</style>
</head>

<body>

<div class="center title">NEMESIS GROUP - A Channel of Multiplier</div>
<div class="center sub">OUR VALUES : GROWTH | OWNERSHIP | AUTHENTICITY | COLLABORATION</div>
<div class="center sub">www.nemesisgroup.in | EMAIL-service@nemesisgroup.in</div>
<div class="center sub">REG- ADD- S.NO 3RD TAJPUR ROAD BHAMIAN KALAN NEAR HUNDAL CHOWK LUDHIANA PUNJAB 141015</div>

<div class="divider"></div>

<table class="meta">
  <tr>
    <td style="width:60%;">
      <b>To,</b><br>
      Mr. <?=esc($name)?>
    </td>
    <td style="width:40%; text-align:right;">
      <b>Date:</b> <?=esc($today)?><br>
      <b>REF ID:</b> <?=esc($refId)?>
    </td>
  </tr>
</table>

<p><b>Subject:</b> Letter of Intent (LOI) – Mandatory Training & Conditional Engagement</p>

<p>Dear <?=esc($name)?>,</p>

<p>
With reference to your application and interview, we are pleased to issue this Letter of Intent (LOI)
for engagement as <b><?=esc($designation)?></b>.
</p>

<h3>1. ENGAGEMENT DETAILS</h3>
<ul>
  <li><b>Designation:</b> <?=esc($designation)?></li>
  <li><b>Department:</b> IT / Software Development</li>
  <li><b>Mode:</b> Work From Home</li>
  <li><b>Joining Date (Training DOJ):</b> <?=esc($doj)?></li>
  <li><b>Working Hours:</b> 09:30 AM – 07:00 PM</li>
  <li><b>Reporting Authority:</b> <?=esc($reporting)?></li>
</ul>

<h3>2. TRAINING</h3>
<p>
You shall undergo a mandatory training and evaluation period of <b>35–37 working days</b>, commencing from <b><?=esc($doj)?></b>.
</p>

<p><b>Training Stipend:</b> <?=money_inr($stipend)?></p>

<h3>3. PAYMENT STRUCTURE (MONTHLY)</h3>
<div class="box">
  <p style="margin:0;"><b>Total Monthly:</b> <?=money_inr($salary['total'])?></p>
  <ul>
    <li>Fixed: <?=money_inr($salary['fixed'])?></li>
    <li>Variable: <?=money_inr($salary['variable'])?></li>
    <li>Attendance: <?=money_inr($salary['attendance'])?></li>
    <li>Incentive: <?=money_inr($salary['incentive'])?></li>
    <li>Allowance: <?=money_inr($salary['allowance'])?></li>
  </ul>
</div>

<p class="small">
Salary is payable only upon meeting attendance, productivity, and compliance requirements.
Deductions as per law. Management may revise structure.
</p>

<h3>4. CONFIDENTIALITY & TERMINATION</h3>
<p>
All company data, code, documents, and credentials are confidential.
Breach may result in termination and legal action.
</p>

<p><b>Warm Regards,</b><br>
NEMESIS GROUP<br>
HR TEAM</p>

<p class="small">
System Generated | Sole ID: 019 | RFID: <?=esc($rfid)?>
</p>

<div class="page-break"></div>

<h3>TERMS & CONDITIONS OF ENGAGEMENT</h3>
<p>Acceptance of this LOI automatically implies acceptance of all company policies, confidentiality obligations, and performance rules.</p>

</body>
</html>
