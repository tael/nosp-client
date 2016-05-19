<?php

namespace Tael\Nosp;

use GuzzleHttp\Client;
use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionAdInput;
use Tael\Nosp\Data\FashionPriceRequest;

class MobileFashionBookingClient
{

    /**
     * @var FashionAdInput
     */
    private $adInput;

    /**
     * @var Data\Campaign
     */
    private $campaign;

    /**
     * @var NospClient
     */
    private $nospClient;

    public function __construct($id, $pw, $encPw, $campaignId)
    {
        $this->nospClient = NospClient::createDefaultClient(new Credential($id, $pw, $encPw));
        $this->nospClient->auth();

        $this->campaign = $this->nospClient->loadCampaign($campaignId);
        $executePriceId = $this->nospClient->getPrice(
            new FashionPriceRequest(
                $this->campaign->getStartDateTime(),
                $this->campaign->getEndDateTime())
        );
        $this->adInput = new FashionAdInput(
            $this->campaign->getStartDateTime(),
            $this->campaign->getEndDateTime(),
            $executePriceId
        );
    }

    public function waitOpenTime()
    {
        $second = new \DateInterval('PT3S'); // 3 sec
        $nosp = new ServerTime(new Client());
        $serverTime = $nosp->getServerDateTime('nosp.da.naver.com');
        $serverTime->sub($second);

        $openTime = (new \DateTime())->setTime(10, 59, 59);

        $waiter = new TimeWaiter($serverTime);
        $waiter->waitUntil($openTime);
    }

    public function repeat()
    {
        $retry = true;
        while ($retry) {
            try {
                $this->nospClient->create($this->adInput, $this->campaign->campId, "AMS01");
                $retry = false;
            } catch (\Tael\Nosp\InventoryRangeException $e) {
                // 기간 실패일 경우 반복 재시도
                // retry after 0.1 sec
                usleep(100000);
            }
        }
    }
}