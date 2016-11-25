<?php
namespace Tael\Nosp;

use GuzzleHttp\Client;
use JsonMapper;
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
        $this->jsonMapper = new JsonMapper();
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

    /**
     * @param AdInput $adInput
     * @param $campId
     */
    public function create(AdInput $adInput, $campId)
    {
        $requestData = new CreateRequest($campId);
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
        // response 에 대한 클래스는 귀찮아서 생략한다.
        $responseObject = json_decode($responseJson);
        if ($responseObject->success != true) {
            if ($responseObject->message == "집행전, 집행중 상태의 캠페인만 광고등록이 가능합니다.") {
                throw new CreateFailException($responseObject->message);
            }
        }
        $resultData = $responseObject->resultData;

        foreach ($resultData as $item) {
            $adResult = $item->adResult;
            dump($adResult);
            // result 에 대한 클래스는 귀찮아서 생략한다.
            if ($adResult->message == "실패 (사유 : 인벤토리 기간 범위를 벗어남)") {
                throw new InventoryRangeException();
            }
            if ($adResult->message == "실패 (사유 : 가용 인벤토리 확보 실패)") {
                throw new InventoryNotEnoughException();
            }
            if ($adResult->message == "실패 (사유 : 업종 서비스금액 제외 구매 가능 금액 초과)") {
                throw new NeedMoreMoneyException();
            }
            // TODO: 그 외 "성공" 이 아닐경우 에러처리
//            if ($adResult->message != "성공") {
//                throw new CreateFailException($adResult->message);
//            }
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
            throw new NospException("can not find executeId");
        }
        return $executePriceId;
    }

    /**
     * @param $campId
     * @return Campaign
     */
    public function loadCampaign($campId)
    {
//        $x='http://nosp.da.naver.com/center/sales/adtool/form/unit/grid/data.json
//        ?_JSON-TYPE_-REQ_=Y
//        &adMngStep=AMS03
//        &campId=
//        &prplId=
//        &bizcatNo=10046
//        &adprodtpNo=15
//        &unitDesc=
//        &guaranteeCd=
//        &paymentCd=
//        &timeboardYn=N
//        &_search=false
//        &nd=1475203504325
//        &rows=10
//        &page=1
//        &sidx=
//        &sord=asc
//        &_JSON-TYPE_-REQ_=Y';
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
                dump($item);
                return $this->jsonMapper->map($item, new Campaign());
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