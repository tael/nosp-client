<?php
require __DIR__ . '/../vendor/autoload.php';
$e = new Dotenv\Dotenv(__DIR__ . '/..');
$e->load();

use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionAdInput;
use Tael\Nosp\Data\FashionPriceRequest;
use Tael\Nosp\NospClient;

################
$campId = "1133542";
################

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
$campaign = $nosp->createCampaign($campId);

$adInput = new FashionAdInput();
$adInput->startDttm = date_create($campaign->campstartYmdt)->format('YmdHis');
$adInput->endDttm = date_create($campaign->campendYmdt)->format('YmdHis');
$adInput->executePriceId = $nosp->getPrice(
    new FashionPriceRequest(
        date_create($campaign->campstartYmdt),
        date_create($campaign->campendYmdt)
    )
);


$repeat = true;
while ($repeat) {
    try {
        $nosp->create($adInput, $campaign->campId, "AMS01");
        $repeat = false;
        echo 'DONE';
    } catch (\Tael\Nosp\InventoryRangeException $e) {
        // retry after 0.1 sec
        usleep(100000);
    }
}
// TODO: 기간 실패일 경우 반복 재시도



