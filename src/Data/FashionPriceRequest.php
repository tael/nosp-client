<?php

namespace Tael\Nosp\Data;

class FashionPriceRequest extends PriceRequest
{
    public function __construct($startDttm, $endDttm)
    {
        $startDttm = '20160725000000';
        $endDttm = '20160731235959';

        parent::__construct('1254A_GT1', '1254A', 'UTC1', 'PM3', 'P398', '10041', 'KRW',
            $startDttm, $endDttm, '', '0');
    }
}