<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function requireLogin(): void {
  if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }
}

function requireRole(array $roles): void {
  requireLogin();
  $role = $_SESSION['role'] ?? '';
  if (!in_array($role, $roles, true)) {
    http_response_code(403);
    exit("Forbidden");
  }
}

function currentUserId(): int {
  return (int)($_SESSION['user_id'] ?? 0);
}

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
