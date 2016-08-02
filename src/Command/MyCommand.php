<?php

namespace Tael\Nosp\Command;

use DateTime;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tael\Nosp\CreateFailException;
use Tael\Nosp\MobileFashionBookingClient;

class MyCommand extends Command
{
    private $campaignId;

    protected function configure()
    {
        $this->setName('nosp-client');
        $this->addArgument('campaign-id', InputArgument::REQUIRED, 'The campaign ID to Execute.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->campaignId = $input->getArgument('campaign-id');

        $output->writeln([
            'NOSP Client',
            '============',
        ]);
        $client = $this->createClient();
        $logger = $this->createLogger();
        $this->ex($client, $logger);
        $output->writeln('Campaign ID: ' . $this->campaignId);
    }

    private function ex(MobileFashionBookingClient $client, Logger $logger)
    {
        try {
            $client->waitOpenTime();
            $logger->addDebug("START: " . (new DateTime())->format('Ymd H:i:s'));
            $client->repeat();
        } catch (CreateFailException $e) {
            $logger->addDebug("create fail: " . $e);
        } finally {
            $logger->addDebug("DONE: " . (new DateTime())->format('Ymd H:i:s'));
        }
    }

    /**
     * @return Logger
     */
    protected function createLogger()
    {
        // campaignId_pid.log
        $logFile = __DIR__ . '/logs/' . $this->campaignId . '_' . getmypid() . '.log';
        $logger = new Logger('NOSP');
        $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
        return $logger;
    }

    /**
     * @return MobileFashionBookingClient
     */
    protected function createClient()
    {
        $client = new MobileFashionBookingClient(getenv('NOSP_ID'), getenv('NOSP_PW'), getenv('NOSP_SECRET'),
            $this->campaignId);
        return $client;
    }
}