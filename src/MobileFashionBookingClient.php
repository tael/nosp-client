<?php

namespace Tael\Nosp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
        $second = new \DateInterval('PT1S'); // 1 sec
        $nosp = new ServerTime(new Client());
        $serverTime = $nosp->getServerDateTime('http://nosp.da.naver.com');

        $openTime = (new \DateTime());
        $openTime->setTimezone($serverTime->getTimezone());
        $openTime->setTime(11, 00, 00);
        $openTime->sub($second);

        $waiter = new TimeWaiter($serverTime);
        $waiter->waitUntil($openTime);
    }

    public function repeat($ignoreInventoryNotEnoughException = false)
    {
        $retry = true;
        while ($retry) {
            try {
                $this->nospClient->create($this->adInput, $this->campaign->campId);
                $retry = false;
            } catch (InventoryNotEnoughException $e) {
                if ($ignoreInventoryNotEnoughException === false) {
                    throw $e;
                }
                // ignore on 12
                echo 'InventoryNotEnoughException was raised: ignore it but please stop this process with KILL command when you need to done.' . PHP_EOL . $this->campaign->campId . ' : ' . $e->getMessage();
                usleep(200000);
            } catch (InventoryRangeException $e) {
                // 기간 실패일 경우 반복 재시도
                //retry after 0.01 sec
                usleep(100000);
            } catch (RequestException $e) {
                echo 'ERROR RequestException: ' . $this->campaign->campId . ' : ' . $e->getMessage();
            }
        }
    }
}