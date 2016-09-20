<?php

namespace Tael\Nosp\Data;

class FashionAdInput extends AdInput
{
//[M_메인_패션뷰티_컨텐츠형광고] 고유정보, 안바뀜
    public $saleunitId = "1254A_GT1";
    public $unitId = "1254A";
    public $unitDesc = "M_메인_패션뷰티_컨텐츠형광고";

//    public $adprodCd = "P398"; // before
    public $adprodCd = "P491";
//
//    public $adprodNm = "[모바일]메인_패션뷰티_컨텐츠형"; // before
    public $adprodNm = "[모바일]메인_컨텐츠형(신)";

    public $paymentCd = "PM3";
    public $adtypeCd = "LA2";
    public $detailMny = "5000000";
    public $priceMny = "5000000";
    public $finalMny = "5000000";
    public $totalInv = "8";
    public $availInv = "7";

    /**
     * FashionAdInput constructor.
     */
    public function __construct(\DateTime $startDateTime, \DateTime $endDateTime, $executePriceId)
    {
        $this->startDttm = $startDateTime->format('YmdHis');
        $this->endDttm = $endDateTime->format('YmdHis');
        $this->executePriceId = $executePriceId;
    }
}