<?php
namespace Tael\Nosp;

// TODO: json 을 클래스로 잘!?
class AdInput implements \JsonSerializable
{
//"saleunitId": "1241B_GT1",
    public $saleunitId = "1241B_GT1";
//"unitId": "1241B",
    public $unitId = "1241B";
//"unitDesc": "M_메인_리빙푸드_컨텐츠형광고",
    public $unitDesc = "M_메인_리빙푸드_컨텐츠형광고";
//"paymentCd": "PM3",
    public $paymentCd = "PM3";
//"adtypeCd": "LA2",
    public $adtypeCd = "LA2";
//"adprodexpssCd": "APTX01",
    public $adprodexpssCd = "APTX01";
//"adprodCd": "P404",
    public $adprodCd = "P404";
//"adprodNm": "[모바일]메인_리빙푸드_컨텐츠형",
    public $adprodNm = "[모바일]메인_리빙푸드_컨텐츠형";
//"startDttm": "20160711000000",
    public $startDttm = "20160711000000";
//"endDttm": "20160717235959",
    public $endDttm = "20160717235959";
//"targetCd": "",
    public $targetCd = "";
//"freqCd": "",
    public $freqCd = "";
//"freqVal": "",
    public $freqVal = "";
// 변경
//"detailMny": "3000000",
    public $detailMny = "3000000";
//"priceMny": "5000000",
    public $priceMny = "5000000";
//"finalMny": "3000000",
    public $finalMny = "3000000";

//"incPct": "1",
    public $incPct = "1";
//"incMny": "0",
    public $incMny = "0";
//"decPct": "0.6",
    public $decPct = "0.6";
//"decMny": "0",
    public $decMny = "0";

// 집행금액 아이디
//"executePriceId": "11009087",
    public $executePriceId = "11009087";

// 전체 수량
//"totalInv": "15",
    public $totalInv = "15";
// 남은 수량
//"availInv": "13",
    public $availInv = "13";
// 구매 수량
//"buyQty": "1",
    public $buyQty = "1";
//"tokenId": "",
    public $tokenId = "";
//"brandsaNo": "0",
    public $brandsaNo = "0";
//"brandsaQc": "0",
    public $brandsaQc = "0";
//"brandsaKwdGrpId": "",
    public $brandsaKwdGrpId = "";
//"bidId": "",
    public $bidId = "";
//"videolivetokenId": "",
    public $videolivetokenId = "";
//"parentUnitId": ""
    public $parentUnitId = "";

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this;
    }
}