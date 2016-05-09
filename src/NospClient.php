<?php
namespace Tael\Nosp;

use GuzzleHttp\Client;

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
//POST /sso/auth HTTP/1.1
//Host: ndim.da.naver.com
//Connection: keep-alive
//Content-Length: 366
//Cache-Control: max-age=0
//Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
//Origin: http://nosp.da.naver.com
//Upgrade-Insecure-Requests: 1
//User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36
//Content-Type: application/x-www-form-urlencoded
//Referer: http://nosp.da.naver.com/center/login/form;jsessionid=9EAE6CD6100422F7FE64D110D449E280
//Accept-Encoding: gzip, deflate
//Accept-Language: ko-KR,ko;q=0.8,en-US;q=0.6,en;q=0.4
//Cookie: npic=WQM/Mc0OPEFI87az7fRX1UXZyBWsER2ewYfQpEEE+VqEA2/OWdie62JmZL474gpXCA==; NNB=APOZ6IPK7N5VK

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
//        dump($response->getStatusCode());
//        echo($response->getBody());
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

        $requestData = new CreateRequestData($adMngStep, $campId);
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
}