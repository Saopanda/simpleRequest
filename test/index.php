<?php
require_once "../vendor/autoload.php";

use saopanda\client;

$client = client::new([
    'timeout'=>'10',
    'VERIFYHOST'=>true,
    'VERIFYPEER'=>true
]);

$url = 'http://g.com/api/login';
$params = [
    'code'=>'081mN10w3FI4yV2QuM2w3b9Npx1mN10u',
    'grant_type'=>'authorization_code'
];
$headers = [
    'Authorization: Bearer eyJ0eXAiOiJKV'
];

//$res = $client->get($url,$params,$headers);

//$res = $client->timeout(1)
//    ->header($headers)
//    ->params($params)
//    ->get($url);

$res = $client->headers($headers)
    ->formData($params)
    ->post($url);



var_dump($res);
