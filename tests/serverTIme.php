<?php
require __DIR__ . '/../vendor/autoload.php';
$nosp = new \Tael\Nosp\ServerTime(new \GuzzleHttp\Client());
$serverDateTime = $nosp->getServerDateTime('http://nosp.da.naver.com');
$localDateTime = new DateTime();

$diff = $serverDateTime->diff($localDateTime);
dump($serverDateTime);
dump($localDateTime);
dump($diff);

echo 'DONE';
