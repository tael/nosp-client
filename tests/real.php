<?php
require __DIR__ . '/bootstrap.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Logger;

//validate console parameters
$param = validateParameters($argc, $argv);
$campaignId = $param[1];

$needWait = false;

try {
    $logger = getLogger($campaignId);
    $logger->info("START campaign : $campaignId");
// campaign id validate
    $client = new \Tael\Nosp\MobileFashionBookingClient(
        getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'),
        $campaignId);
    if ($needWait) {
        $client->waitOpenTime();
    }
    $logger->debug("wait done, starting repeat()");
    $client->repeat();
    $logger->debug(sprintf("RESULT: success : (%s)", $campaignId));

} catch (Tael\Nosp\CreateFailException $e) {
    $logger->debug("create fail: " . $e);
} catch (Tael\Nosp\NeedMoreMoneyException $e) {
    //실패 (사유 : 업종 서비스금액 제외 구매 가능 금액 초과)
    $logger->debug(sprintf("RESULT: fail : need more money : (%s) %s", $campaignId, $e));
//    $logger->debug("need more money or already succeed: (" . $campaignId . ") " . $e);
} catch (\Tael\Nosp\InventoryNotEnoughException $e) {
    //실패 (사유 : 가용 인벤토리 확보 실패)
    $logger->debug(sprintf("RESULT: fail : no more inventory : (%s) %s", $campaignId, $e));
//    $logger->debug("fail too late, no more inventory: (" . $campaignId . ") " . $e);
} catch (\Exception $e) {
    $logger->critical("Unhandled exception raised: " . $e);
} finally {
    $logger->debug("all done");
}

/**
 * @param $campaignId
 * @return Logger
 */
function getLogger($campaignId)
{
    $pid = getmypid();
    $date = date("Ymd");
// {date}_{ppid}_{pid}_{campaignId}.log
    $logFile = __DIR__ . "/logs/{$date}_{$pid}_{$campaignId}.log";
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
