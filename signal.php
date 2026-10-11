<?php
require_once __DIR__.'/../config.php';
$me=require_user();
if ($_SERVER['REQUEST_METHOD']!=='POST') json_out(['error'=>'POST required'],405);
$b=json_decode(file_get_contents('php://input'),true) ?: [];
if (!hash_equals(csrf_token(),$b['csrf']??'')) json_out(['error'=>'CSRF invalid'],403);
$room=preg_replace('/[^a-f0-9]/','',strtolower($b['room']??''));
$s=db()->prepare('SELECT * FROM calls WHERE room_id=? AND (caller_id=? OR callee_id=?)');
$s->execute([$room,$me['id'],$me['id']]); $call=$s->fetch();
if (!$call) json_out(['error'=>'Call not found'],404);
$action=$b['action']??'';
if ($action==='send') {
  $type=$b['type']??''; $payload=$b['payload']??null;
  if (!in_array($type,['offer','answer','candidate'],true) || !is_array($payload)) json_out(['error'=>'Invalid signal'],400);
  $ins=db()->prepare('INSERT INTO signals (room_id,sender_id,type,payload) VALUES (?,?,?,?)');
  $ins->execute([$room,$me['id'],$type,json_encode($payload)]);
  json_out(['ok'=>true]);
}
if ($action==='poll') {
  $after=max(0,(int)($b['after']??0));
  $q=db()->prepare('SELECT id,type,payload FROM signals WHERE room_id=? AND sender_id<>? AND id>? ORDER BY id ASC LIMIT 100');
  $q->execute([$room,$me['id'],$after]);
  json_out(['ok'=>true,'signals'=>array_map(function($r){$r['payload']=json_decode($r['payload'],true);return $r;},$q->fetchAll())]);
}
json_out(['error'=>'Unknown action'],400);
