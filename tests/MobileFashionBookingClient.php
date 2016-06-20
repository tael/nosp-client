<?php
require __DIR__ . '/bootstrap.php';
$campaignId = "1134074";

$client = new \Tael\Nosp\MobileFashionBookingClient(
    getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'),
    $campaignId);
// 대충 3~5초 전
$client->waitOpenTime();
$client->repeat();
echo 'DONE';