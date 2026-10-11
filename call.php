<?php
require_once __DIR__.'/../config.php';
$me = require_user();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error'=>'POST required'],405);
$body = json_decode(file_get_contents('php://input'), true) ?: [];
if (!hash_equals(csrf_token(), $body['csrf'] ?? '')) json_out(['error'=>'CSRF invalid'],403);
$action = $body['action'] ?? '';
if ($action === 'start') {
  $peerId = strtoupper(trim($body['peer_id'] ?? ''));
  if (!$peerId || $peerId === $me['public_id']) json_out(['error'=>'Valid dost ki ID daalein'],400);
  $q=db()->prepare('SELECT id, public_id, name FROM users WHERE public_id=?'); $q->execute([$peerId]); $peer=$q->fetch();
  if (!$peer) json_out(['error'=>'Is ID ka user nahi mila. Dono ko pehle account banana hoga.'],404);
  $room=bin2hex(random_bytes(16));
  $s=db()->prepare("INSERT INTO calls (room_id, caller_id, callee_id, status) VALUES (?,?,?,'ringing')");
  $s->execute([$room,$me['id'],$peer['id']]);
  json_out(['ok'=>true,'room'=>$room,'peer_name'=>$peer['name'],'role'=>'caller']);
}
if ($action === 'poll') {
  $s=db()->prepare("SELECT c.room_id,c.status,c.caller_id,c.callee_id,(c.callee_id=?) AS is_callee,u.public_id,u.name FROM calls c JOIN users u ON u.id=IF(c.caller_id=?,c.callee_id,c.caller_id) WHERE (c.caller_id=? OR c.callee_id=?) AND c.status IN ('ringing','active') ORDER BY c.id DESC LIMIT 1");
  $s->execute([$me['id'],$me['id'],$me['id'],$me['id']]); $c=$s->fetch();
  json_out(['ok'=>true,'call'=>$c ?: null]);
}
if ($action === 'accept' || $action === 'decline' || $action === 'end') {
  $room = preg_replace('/[^a-f0-9]/','',strtolower($body['room'] ?? ''));
  $s=db()->prepare('SELECT * FROM calls WHERE room_id=? AND (caller_id=? OR callee_id=?)');
  $s->execute([$room,$me['id'],$me['id']]); $c=$s->fetch();
  if (!$c) json_out(['error'=>'Call nahi mili'],404);
  $status = $action==='accept' ? 'active' : 'ended';
  db()->prepare('UPDATE calls SET status=? WHERE room_id=?')->execute([$status,$room]);
  json_out(['ok'=>true,'status'=>$status]);
}
json_out(['error'=>'Unknown action'],400);
