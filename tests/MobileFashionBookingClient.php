<?php
require __DIR__ . '/bootstrap.php';
$campaignId = "1134074";

$id = getenv('NOSP_ID');
$pw = getenv('NOSP_PW');
$encPw = getenv('NOSP_SECRET');
$client = new \Tael\Nosp\MobileFashionBookingClient($id, $pw, $encPw, $campaignId);
$client->waitOpenTime();
//$client->repeat();
echo 'DONE';