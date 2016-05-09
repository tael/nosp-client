<?php

namespace Tael\Nosp\Data;

class PriceRequest
{
    public $saleunitId;
    public $unitId;
    public $unittypeCd;
    public $paymentCd;
    public $adprodCd;
    public $bizcatNo;
    public $currencyCd;
    public $startDttm;
    public $endDttm;
    public $targetCd;
    public $detailMny;


    /**
     * PriceRequest constructor.
     */
    public function __construct(
        $saleunitId,
        $unitId,
        $unittypeCd,
        $paymentCd,
        $adprodCd,
        $bizcatNo,
        $currencyCd,
        $startDttm,
        $endDttm,
        $targetCd,
        $detailMny
    ) {

        $this->saleunitId = $saleunitId;
        $this->unitId = $unitId;
        $this->unittypeCd = $unittypeCd;
        $this->paymentCd = $paymentCd;
        $this->adprodCd = $adprodCd;
        $this->bizcatNo = $bizcatNo;
        $this->currencyCd = $currencyCd;
        $this->startDttm = $startDttm;
        $this->endDttm = $endDttm;
        $this->targetCd = $targetCd;
        $this->detailMny = $detailMny;
    }
}