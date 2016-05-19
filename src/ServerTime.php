<?php

namespace Tael\Nosp;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;

class ServerTime
{
    private $client;

    /**
     * ServerTime constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function gmtToLocal($time)
    {
        $new_date = new DateTime($time);
        $new_date->setTimezone(new DateTimeZone('Asia/Seoul'));
        return $new_date;
    }

    /**
     * @param $serverUrl
     * @return DateTime
     */
    public function getServerDateTime($serverUrl)
    {
        $response = $this->client->get($serverUrl);
        $date = @$response->getHeader('date')[0];
        $serverDate = $this->gmtToLocal($date);
        return $serverDate;
    }

}