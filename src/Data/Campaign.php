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

    public function getStartDateTime()
    {
        return new \DateTime($this->campstartYmdt);
    }

    public function getEndDateTime()
    {
        return new \DateTime($this->campendYmdt);
    }

}