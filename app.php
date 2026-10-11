<?php
require_once __DIR__.'/config.php';
$me = current_user();
if (!$me) { header('Location: index.php'); exit; }
?>
<!doctype html><html lang="hi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CallAI</title><link rel="stylesheet" href="assets/style.css"></head>
<body><main class="shell">
<header class="topbar"><div class="brand-mark">✦</div><div><div class="brand">Call<span>AI</span></div><div class="tagline">Smart call · Smart communication</div></div><a class="quiet" href="logout.php">Logout</a></header>
<section class="profile-card"><div class="avatar"><?= h(strtoupper(substr($me['name'],0,1))) ?></div><div class="profile-copy"><div class="muted">Namaste,</div><h1><?= h($me['name']) ?></h1><div class="id-line">Aapki Call ID: <strong id="myId"><?= h($me['public_id']) ?></strong><button class="copy-btn" id="copyId">Copy</button></div></div><span class="online-dot"></span></section>
<div class="layout">
<section class="panel"><h2>Kisi ko call karein</h2><p class="muted">Dost ki Call ID enter karein. Dono users ko app mein login rehna hoga.</p>
<form id="callForm"><label for="friendId">Dost ki ID</label><input id="friendId" placeholder="CALL-XXXXXXXX" required autocomplete="off"><button class="primary" type="submit">📹 Video Call</button></form>
<div id="status" class="notice">Ready. Call karne se pehle camera/microphone permission allow karein.</div>
<div id="incoming" class="incoming hidden"><strong id="incomingText">Incoming call</strong><div class="button-row"><button id="acceptCall" class="primary">Accept</button><button id="declineCall" class="danger">Decline</button></div></div>
</section>
<section class="panel video-panel"><div class="video-grid"><div class="video-wrap"><video id="remoteVideo" autoplay playsinline></video><span class="video-label" id="remoteLabel">Doosra person</span></div><div class="video-wrap local-wrap"><video id="localVideo" autoplay muted playsinline></video><span class="video-label">Aap</span></div></div>
<div class="controls"><button id="micBtn" class="round" title="Microphone">🎙</button><button id="cameraBtn" class="round" title="Camera">📷</button><button id="hangupBtn" class="round hangup" title="Call end">☎</button></div></section>
<section class="panel"><h2>AI insights</h2><p class="muted">Expression aur gesture labels ko anumaan samjhein, kisi ki pakki feeling nahi.</p><div class="insight-grid"><div class="insight"><span>☺</span><strong>Face expressions</strong><small id="emotionText">AI service abhi configured nahi</small></div><div class="insight"><span>✋</span><strong>Gesture to text</strong><small id="gestureText">Gesture model abhi configured nahi</small></div></div><button id="checkAI" class="secondary">AI service check karein</button><div id="aiStatus" class="small muted"></div></section>
</div><footer>CallAI · PHP + MySQL + WebRTC · Prototype</footer></main>
<script>window.CALL_APP={myId:<?= json_encode($me['public_id']) ?>,csrf:<?= json_encode(csrf_token()) ?>};</script><script src="assets/call.js"></script></body></html>
