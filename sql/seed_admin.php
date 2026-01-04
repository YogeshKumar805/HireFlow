<?php
require __DIR__ . '/../config/db.php';

$name = "Admin";
$email = "admin@pw.live";
$password = "Admin@123"; // change after login
$role = "ADMIN";

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)");
$stmt->execute([$name, $email, $hash, $role]);

echo "Admin created: {$email} / {$password}";
