<?php

namespace Tael\Nosp;

class TimeWaiter
{
    /**
     * @var \DateTime
     */
    private $startTime;
    private $diffInterval;


    /**
     * TimeWaiter constructor.
     * @param \DateTime $startTime
     */
    public function __construct(\DateTime $startTime)
    {
        $this->diffInterval = $startTime->diff(new \DateTime());
        $this->startTime = $startTime;
    }

    public function waitUntil(\DateTime $destinationTime)
    {
        while (1) {
            $currentDateTime = new \DateTime();
//            dump($currentDateTime->format('s'));
            $currentDateTime->sub($this->diffInterval);
//            dump($currentDateTime->format('s'));
//            dump($currentDateTime);
            if ($currentDateTime == $destinationTime) {
//                dump($destinationTime);
//                echo "END";
                break;
            }
//            dump($this->diffInterval);
        }

//        bool time_sleep_until ( float $timestamp )

    }
}