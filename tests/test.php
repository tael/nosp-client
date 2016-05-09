<?php
require __DIR__ . '/../vendor/autoload.php';

use Tael\Nosp\Credential;
use Tael\Nosp\FashionAdInput;
use Tael\Nosp\NospClient;

$e = new Dotenv\Dotenv(__DIR__ . '/..');
$e->load();

$nosp = new NospClient(
    new GuzzleHttp\Client([
        'cookies' => true,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', // IE10
        ]
    ]),
    new Credential(getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'))
);

$nosp->auth();


// from #USER
$campId = "1133235";
// 날짜
// from #USER
$startDttm = "20160718000000";
$endDttm = "20160724235959";
// 집행금액 아이디
// from #price by $campId
$executePriceId = "11008162";



$adInput = new FashionAdInput();
$adInput->executePriceId = $executePriceId;
$adInput->startDttm = $startDttm;
$adInput->endDttm = $endDttm;


$nosp->create($adInput, $campId, "AMS01");

echo 'DONE';

