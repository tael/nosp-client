<?php
require __DIR__ . '/../vendor/autoload.php';

use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionAdInput;
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
// 캠페인 아이디
$campId = "1133235";
// 날짜
$startDttm = "20160718000000";
$endDttm = "20160724235959";
// 집행금액 아이디
// from #price by $campId
$ex = $nosp->getPrice(new \Tael\Nosp\Data\FashionPriceRequest($startDttm, $endDttm));
echo $ex;
die;
$executePriceId = "11008162";


$adInput = new FashionAdInput();
$adInput->executePriceId = $executePriceId;
$adInput->startDttm = $startDttm;
$adInput->endDttm = $endDttm;


$nosp->create($adInput, $campId, "AMS01");

echo 'DONE';

