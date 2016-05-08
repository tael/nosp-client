<?php
require __DIR__ . '/../vendor/autoload.php';

use Tael\Nosp\NospClient;
use Tael\Nosp\Credential;

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
$nosp->create();

//dump($nosp);
echo 'OK';

