<?php
require __DIR__ . '/bootstrap.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Logger;

//validate console parameters
$param = validateParameters($argc, $argv);
$campaignId = $param[1];

$logger = getLogger($campaignId);
$logger->info("START campaign : $campaignId");

try {
// campaign id validate
    $client = new \Tael\Nosp\MobileFashionBookingClient(
        getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'),
        $campaignId);
    $client->waitOpenTime();
    $logger->debug("START: " . (new DateTime())->format('Ymd H:i:s'));
    $client->repeat();
} catch (Tael\Nosp\CreateFailException $e) {
    $logger->debug("create fail: " . $e);
} finally {
    $logger->debug("DONE: " . (new DateTime())->format('Ymd H:i:s'));
}

/**
 * @param $campaignId
 * @return Logger
 */
function getLogger($campaignId)
{
    $pid = getmypid();
    $ppid = posix_getppid();
    $date = date("Ymd");
// {date}_{ppid}_{pid}_{campaignId}.log
    $logFile = __DIR__ . "/logs/{$date}_{$ppid}_{$pid}_{$campaignId}.log";
    $logger = new Logger('NOSP');
    $logger->pushHandler(
        new StreamHandler($logFile, Logger::DEBUG)
    );
    return $logger;
}

function validateParameters($argumentCount, $argumentVar)
{
    if ($argumentCount != 2) {
        exit("argument 1 required\n\n");
    }
    if (!is_numeric($argumentVar[1])) {
        exit("campaign must be integer, input is : " . $argumentVar[1] . "\n\n");
    }
    return $argumentVar;
}
