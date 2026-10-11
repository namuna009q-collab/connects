<?php
header('Content-Type: application/json; charset=utf-8');
$base='http://127.0.0.1:5001/health';
$ctx=stream_context_create(['http'=>['timeout'=>2]]);
$result=@file_get_contents($base,false,$ctx);
if ($result===false) { http_response_code(503); echo json_encode(['ok'=>false,'message'=>'not work . Setup README dekhein.']); }
else echo $result;
