<?php
namespace Tael\Nosp;

use GuzzleHttp\Client;
use Tael\Nosp\Data\AdInput;
use Tael\Nosp\Data\Campaign;
use Tael\Nosp\Data\CreateRequest;
use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\PriceRequest;
use Tael\Nosp\Data\PriceResponse;
use Tael\Nosp\Data\PriceResult;

class NospClient
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Credential
     */
    private $credential;


    public function __construct(Client $client, Credential $credential)
    {
        $this->client = $client;
        $this->credential = $credential;
    }

    public function auth()
    {
        $this->client->post(
            'https://ndim.da.naver.com/sso/auth',
            [
                'form_params' => [
                    'return_url' => '',
                    'encPw' => $this->credential->getEncPw(),
                    'targetUrl' => 'http://nosp.da.naver.com/center/home',
                    'userId' => $this->credential->getId(),
                    'userPw' => $this->credential->getPw(),
                ],
            ]
        );
        // TODO: keep-alive
        // TODO: how to check success?
    }

    public function create(AdInput $adInput, $campId, $adMngStep)
    {
        $requestData = new CreateRequest($adMngStep, $campId);
        $requestData->addItem($adInput);
        $json = json_encode($requestData);
//        dump($json);die;
        $response = $this->client->post(
            'http://nosp.da.naver.com/center/sales/campaign/adline/create?_JSON-TYPE_-REQ_=Y',
            [
                'body' => $json,
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => 'application/json',
                ],
            ]
        );
        $responseJson = $response->getBody();
        $responseObject = json_decode($responseJson);
        if ($responseObject->success != true) {
            dump($responseObject);
            throw new NospException("Unknown: create result = failed");
        }
//        dump($responseObject);
        $resultData = $responseObject->resultData;

        foreach ($resultData as $item) {
            $adResult = $item->adResult;
            dump($adResult);
            if ($adResult->message == "실패 (사유 : 인벤토리 기간 범위를 벗어남)") {
                throw new InventoryRangeException();
            }
        }

    }

    public function getPrice(PriceRequest $request)
    {
        $response = $this->client->post(
            'http://nosp.da.naver.com/center/sales/adtool/price?_JSON-TYPE_-REQ_=Y',
            ['form_params' => (array)$request]
        );
        // TODO: 에러핸들링
        /** @var PriceResponse $priceResponse */
        $priceResponse = json_decode($response->getBody());
        /** @var PriceResult $resultData */
        $resultData = $priceResponse->resultData;
        $executePriceId = $resultData->executePriceId;
        if ($executePriceId == "") {
            dump($priceResponse);
            dump($resultData);
            throw new \Exception("can not find executeId");
        }
        return $executePriceId;
    }

    /**
     * @param $campId
     * @return Campaign
     */
    public function loadCampaign($campId)
    {
        $response = $this->client->get(
            'http://nosp.da.naver.com/center/sales/campaign/list/data.json'
            . '?_JSON-TYPE_-REQ_=Y'
            . '&mntgstatCd=&campstartYmdt=&campendYmdt=&statCd=B1&statCd=B2&statCd=B3&statCd=B6'
            . '&cond=campId&campNm=&campId=' . $campId
            . '&brandNm=&sponsorNm=&reguserNm=&condVal=' . $campId
            . '&_search=false&nd=1462935536906&rows=10&page=1&sidx=&sord=&_JSON-TYPE_-REQ_=Y'
        );
        /** @var Campaign[] $list */
        $list = json_decode($response->getBody())->data->list;
        foreach ($list as $item) {
            if ($item->campId == $campId) {
                return $item;
            }
        }
        throw new CampaignNotFoundException("can not find campaign");
    }

    public static function createDefaultClient(Credential $credential)
    {
        return new self(
            new Client([
                'cookies' => true,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', // IE10
                ]
            ]),
            $credential);
    }
}