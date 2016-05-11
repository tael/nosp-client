<?php

namespace Tael\Nosp\Data;

class Campaign
{
    public $bizcatNm;
    public $brandNm;
    public $campId;
    public $campNm;
    public $campPeriod;
    public $campPeriodForExcel;
    public $campendYmdt;
    public $campendYmdtStr;
    public $campstartYmdt;
    public $campstartYmdtStr;
    public $ctrtMny;
    public $lcatNm;
    public $mntgstatCd;
    public $needUserConfirmMonitoringYN;
    public $reguserNm;
    public $scatNm;
    public $sponsorNm;
    public $statCd;
    public $svcMny;

    /**
     * Campaign constructor.
     * @param $bizcatNm
     * @param $brandNm
     * @param $campId
     * @param $campNm
     * @param $campPeriod
     * @param $campPeriodForExcel
     * @param $campendYmdt
     * @param $campendYmdtStr
     * @param $campstartYmdt
     * @param $campstartYmdtStr
     * @param $ctrtMny
     * @param $lcatNm
     * @param $mntgstatCd
     * @param $needUserConfirmMonitoringYN
     * @param $reguserNm
     * @param $scatNm
     * @param $sponsorNm
     * @param $statCd
     * @param $svcMny
     */
    public function __construct(
        $bizcatNm,
        $brandNm,
        $campId,
        $campNm,
        $campPeriod,
        $campPeriodForExcel,
        $campendYmdt,
        $campendYmdtStr,
        $campstartYmdt,
        $campstartYmdtStr,
        $ctrtMny,
        $lcatNm,
        $mntgstatCd,
        $needUserConfirmMonitoringYN,
        $reguserNm,
        $scatNm,
        $sponsorNm,
        $statCd,
        $svcMny
    ) {
        $this->bizcatNm = $bizcatNm;
        $this->brandNm = $brandNm;
        $this->campId = $campId;
        $this->campNm = $campNm;
        $this->campPeriod = $campPeriod;
        $this->campPeriodForExcel = $campPeriodForExcel;
        $this->campendYmdt = $campendYmdt;
        $this->campendYmdtStr = $campendYmdtStr;
        $this->campstartYmdt = $campstartYmdt;
        $this->campstartYmdtStr = $campstartYmdtStr;
        $this->ctrtMny = $ctrtMny;
        $this->lcatNm = $lcatNm;
        $this->mntgstatCd = $mntgstatCd;
        $this->needUserConfirmMonitoringYN = $needUserConfirmMonitoringYN;
        $this->reguserNm = $reguserNm;
        $this->scatNm = $scatNm;
        $this->sponsorNm = $sponsorNm;
        $this->statCd = $statCd;
        $this->svcMny = $svcMny;
    }


    public function getStartDateTime()
    {
        return new \DateTime($this->campstartYmdt);
    }

    public function getEndDateTime()
    {
        return new \DateTime($this->campendYmdt);
    }

}