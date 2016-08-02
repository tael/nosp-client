<?php
require_once __DIR__.'./bootstrap.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Logger;

// 로거 채널 생성
$log = new Logger('name');

// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$log->pushHandler(new StreamHandler('logs/your.log', Logger::DEBUG));

// add records to the log
$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
$log->addDebug('Debug log');
$log->addError('Error log');

