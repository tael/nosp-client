<?php

require __DIR__ . '/bootstrap.php';

class Test
{
    public function test1()
    {
        $second = new DateInterval('PT2S');

        $startTime = new DateTime();
        $startTime->sub($second);

        $destination = new DateTime();
        $destination->add($second);

        $waiter = new \Tael\Nosp\TimeWaiter($startTime);
// sleep 4 sec
        $waiter->waitUntil($destination);
    }

    public function test2()
    {
        $second = new DateInterval('PT1S');
        $nosp = new \Tael\Nosp\ServerTime(new \GuzzleHttp\Client());
        $serverTime = $nosp->getServerDateTime('nosp.da.naver.com');
        $serverTime->sub($second); // 서버시간에서 1초 빼서.

        $curTime = new DateTime();
        $curTime->setTime(18, 01, 00);

        $waiter = new \Tael\Nosp\TimeWaiter($serverTime);
        $waiter->waitUntil($curTime);
        echo "OK";
        
    }
}

$t = new Test();
//$t->test1();
$t->test2();
