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

    public function create()
    {
        $requestData = new CreateRequestData("AMS01", "1132997");
        $requestData->addItem(new AdInput());
        $json = json_encode($requestData);
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
//        {"success":true,"body":null,"message":null,"returnCode":null,"detailMessage":null,"detailMessageMap":null,"resultData":[{"adprodexpssCd":"APTX01","unitDesc":"M_메인_리빙푸드_컨텐츠형광고","adprodNm":"[모바일]메인_리빙푸드_컨텐츠형","startDttm":"2016.07.11.","endDttm":"2016.07.17.","target":"없음","buyQty":"1구좌","occupyPct":"6.66","detailMny":"3,000,000","finalMny":"3,000,000","brandsaQc":"0","adResult":{"success":true,"body":null,"message":"성공","returnCode":null,"detailMessage":null,"detailMessageMap":null}}]}
        $responseJson = $response->getBody();
        $responseObject = json_decode($responseJson);
        if ($responseObject->success != true) {
            dump($responseObject);
//            throw new \Exception("create failed.");
        }
    }
}