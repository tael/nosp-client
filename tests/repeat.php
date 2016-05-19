<?php
use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionAdInput;
use Tael\Nosp\Data\FashionPriceRequest;
use Tael\Nosp\NospClient;

$nosp = NospClient::createDefaultClient(new Credential(getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET')));
$nosp->auth();

$campaign = $nosp->loadCampaign($campaignId);
$adInput = new FashionAdInput(
    $campaign->getStartDateTime(),
    $campaign->getEndDateTime(),
    $nosp->getPrice(new FashionPriceRequest($campaign->getStartDateTime(), $campaign->getEndDateTime()))
);

$repeat = true;
while ($repeat) {
    try {
        $nosp->create($adInput, $campaign->campId, "AMS01");
        $repeat = false;
    } catch (\Tael\Nosp\InventoryRangeException $e) {
        // 기간 실패일 경우 반복 재시도
        // retry after 0.1 sec
        usleep(100000);
//    } catch (\Tael\Nosp\InventoryNotEnoughException $e) {
//        usleep(100000);
    }
}
