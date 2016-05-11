<?php
require __DIR__ . '/../vendor/autoload.php';

use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionAdInput;
use Tael\Nosp\Data\FashionPriceRequest;
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
$campaign = $nosp->getCampaign($campId);

$executePriceId = $nosp->getPrice(
    new FashionPriceRequest(
        date_create($campaign->campstartYmdt),
        date_create($campaign->campendYmdt)
    )
);


$adInput = new FashionAdInput();
$adInput->executePriceId = $executePriceId;
$adInput->startDttm = date_create($campaign->campstartYmdt);
$adInput->endDttm = date_create($campaign->campendYmdt);

die;
$nosp->create($adInput, $campaign->campId, "AMS01");

echo 'DONE';

