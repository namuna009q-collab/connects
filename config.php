<?php
declare(strict_types=1);
session_start();

/* XAMPP defaults: user=root, blank password. Change for any real deployment. */
const DB_HOST = '127.0.0.1';
const DB_NAME = 'call_app';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) return $pdo;
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ]);
  return $pdo;
}
function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function current_user(): ?array {
  if (empty($_SESSION['user_id'])) return null;
  $s = db()->prepare('SELECT id, public_id, name, email FROM users WHERE id=?');
  $s->execute([$_SESSION['user_id']]); $u = $s->fetch();
  return $u ?: null;
}
function require_user(): array {
  $u = current_user();
  if (!$u) { http_response_code(401); exit('Login required'); }
  return $u;
}
function csrf_token(): string {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.h(csrf_token()).'">'; }
function verify_csrf(): void {
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(403); exit('CSRF token invalid. Page refresh karke dobara try karein.');
  }
}
function json_out(array $data, int $status=200): never {
  http_response_code($status); header('Content-Type: application/json; charset=utf-8');
  header('Cache-Control: no-store'); echo json_encode($data); exit;
}
