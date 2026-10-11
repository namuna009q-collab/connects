<?php
require_once __DIR__ . '/config.php';
if (current_user()) { header('Location: app.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $action = $_POST['action'] ?? 'login';
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
      $error = 'Naam, sahi email aur kam-se-kam 8-character password dein.';
    } else {
      try {
        $pdo = db();
        $id = 'CALL-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt = $pdo->prepare('INSERT INTO users (public_id, name, email, password_hash) VALUES (?, ?, ?, ?)');
        $stmt->execute([$id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        header('Location: app.php'); exit;
      } catch (PDOException $e) {
        $error = str_contains(strtolower($e->getMessage()), 'duplicate') ? 'Is email se account pehle se hai.' : 'Account nahi bana. Database setup check karein.';
      }
    }
  } else {
    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]); $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
      session_regenerate_id(true); $_SESSION['user_id'] = (int)$user['id'];
      header('Location: app.php'); exit;
    }
    $error = 'Email ya password sahi nahi hai.';
  }
}
?>
<!doctype html><html lang="hi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Call — Sign in</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-body"><main class="auth-card"><div class="logo">✦</div><h1>Call<span>AI</span></h1><p class="muted">Smart call · Smart communication</p>
<div class="tabs"><button class="tab active" data-mode="login">Login</button><button class="tab" data-mode="register">Account banayein</button></div>
<?php if ($error): ?><div class="alert"><?= h($error) ?></div><?php endif; ?>
<form method="post" id="authForm">
<?= csrf_field() ?><input type="hidden" name="action" id="action" value="login">
<div id="nameWrap" class="hidden"><label>Poora naam</label><input name="name" autocomplete="name" placeholder="Aapka naam"></div>
<label>Email</label><input type="email" name="email" autocomplete="email" required placeholder="you@example.com">
<label>Password</label><input type="password" name="password" minlength="8" autocomplete="current-password" required placeholder="Kam-se-kam 8 characters">
<p class="hint" id="passwordHint">Apne account mein login karein.</p>
<button class="primary" type="submit" id="submitBtn">Login</button>
</form><p class="small muted">Demo starter: apna password kisi ke saath share na karein.</p></main><script src="assets/auth.js"></script></body></html>
