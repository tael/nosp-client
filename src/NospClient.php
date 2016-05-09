<?php
namespace Tael\Nosp;

use GuzzleHttp\Client;
use Tael\Nosp\Data\AdInput;
use Tael\Nosp\Data\CreateRequest;
use Tael\Nosp\Data\Credential;
use Tael\Nosp\Data\FashionPriceRequest;

class NospClient
{
    /**
     * @var \GuzzleHttp\Client
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
        $response = $this->client->post(
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
//        {"adMngStep":"AMS01","listAdInput":[{"saleunitId":"1254A_GT1","unitId":"1254A","unitDesc":"M_메인_패션뷰티_컨텐츠형광고","paymentCd":"PM3","adtypeCd":"LA2","adprodexpssCd":"APTX01","adprodCd":"P398","adprodNm":"[모바일]메인_패션뷰티_컨텐츠형","startDttm":"20160718000000","endDttm":"20160724235959","targetCd":"","freqCd":"","freqVal":"","detailMny":"5000000","priceMny":"5000000","finalMny":"5000000","incPct":"1","incMny":"0","decPct":"1","decMny":"0","executePriceId":"11008162","totalInv":"8","availInv":"1","buyQty":"1","tokenId":"","brandsaNo":"0","brandsaQc":"0","brandsaKwdGrpId":"","bidId":"","videolivetokenId":"","parentUnitId":""}],"campId":"1133260"}
//        $realJson='{"adMngStep":"AMS01","listAdInput":[{"saleunitId":"1254A_GT1","unitId":"1254A","unitDesc":"M_메인_패션뷰티_컨텐츠형광고","paymentCd":"PM3","adtypeCd":"LA2","adprodexpssCd":"APTX01","adprodCd":"P398","adprodNm":"[모바일]메인_패션뷰티_컨텐츠형","startDttm":"20160718000000","endDttm":"20160724235959","targetCd":"","freqCd":"","freqVal":"","detailMny":"5000000","priceMny":"5000000","finalMny":"5000000","incPct":"1","incMny":"0","decPct":"1","decMny":"0","executePriceId":"11008162","totalInv":"8","availInv":"1","buyQty":"1","tokenId":"","brandsaNo":"0","brandsaQc":"0","brandsaKwdGrpId":"","bidId":"","videolivetokenId":"","parentUnitId":""}],"campId":"1133260"}';
//        $realJson = json_encode(json_decode($realJson));
//        dump($realJson);
//        die;

        $requestData = new CreateRequest($adMngStep, $campId);
        $requestData->addItem($adInput);
        $json = json_encode($requestData);
//        assert($realJson==$json);
//        dump($json); die;
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
            // TODO: throw?
        }
        dump(json_decode($responseJson));
    }

    public function getPrice(FashionPriceRequest $request)
    {
        $response = $this->client->post(
            'http://nosp.da.naver.com/center/sales/adtool/price?_JSON-TYPE_-REQ_=Y',
            [
                // TODO: ArrayAccess 할줄 몰라서 그냥 노가다 함.
                'form_params' => [
                    'saleunitId' => $request->saleunitId,
                    'unitId' => $request->unitId,
                    'unittypeCd' => $request->unittypeCd,
                    'paymentCd' => $request->paymentCd,
                    'adprodCd' => $request->adprodCd,
                    'bizcatNo' => $request->bizcatNo,
                    'currencyCd' => $request->currencyCd,
                    'startDttm' => $request->startDttm,
                    'endDttm' => $request->endDttm,
                    'targetCd' => $request->targetCd,
                    'detailMny' => $request->detailMny,
                ],
            ]
        );
    }
}