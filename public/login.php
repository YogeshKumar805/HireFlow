<?php
declare(strict_types=1);
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid credentials";
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - CRM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:520px">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h3 class="mb-3">Login</h3>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?=h($error)?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" type="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" name="password" type="password" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
