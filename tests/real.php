<?php
// TODO: change to parameters
$campaignId = "1138289";
require __DIR__ . '/bootstrap.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Logger;

$client = new \Tael\Nosp\MobileFashionBookingClient(
    getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'),
    $campaignId);

// campaignId_pid.log
$logFile = __DIR__ . '/logs/' . $campaignId . '_' . getmypid() . '.log';
$logger = new Logger('NOSP');
$logger->pushHandler(
    new StreamHandler($logFile, Logger::DEBUG)
);

try {
    $client->waitOpenTime();
    $logger->addDebug("START: " . (new DateTime())->format('Ymd H:i:s'));
    $client->repeat();
} catch (Tael\Nosp\CreateFailException $e) {
    $logger->addDebug("create fail: " . $e);
} finally {
    $logger->addDebug("DONE: " . (new DateTime())->format('Ymd H:i:s'));
}
