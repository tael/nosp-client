<?php
$program = $argv[0];
echo "[{$program}]" . '[INFO] : ' . $argc . " parameters received.\n\n";
if ($argc != 2) {
    exit("argument 1 required\n\n");
}
$campaignId = $argv[1];
if (!is_numeric($campaignId)) {
    var_dump($argv);
    exit("campaign must be integer, input is : " . $campaignId . "\n\n");
}
exit("campaign id :" . $campaignId . "\n\n");
