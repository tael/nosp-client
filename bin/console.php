#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$e = new Dotenv\Dotenv(__DIR__ . '/..');
$e->load();


$app = new \Tael\Nosp\Command\App();
$app->run();
