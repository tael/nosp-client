<?php

namespace Tael\Nosp\Data;

class FashionPriceRequest extends PriceRequest
{
    public function __construct(\DateTime $startDttm, \DateTime $endDttm)
    {
        parent::__construct('1254A_GT1', '1254A', 'UTC1', 'PM3', 'P398', '10041', 'KRW',
            $startDttm->format('YmdHis'), $endDttm->format('YmdHis'), '', '0');
    }
}