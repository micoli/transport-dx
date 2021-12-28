<?php

declare(strict_types=1);

namespace App\UserInterface\Cli;

use App\Infrastructure\Http\HttpServer;
use App\Infrastructure\Persistence\MysqlConnection;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Drift\DBAL\Connection;
use Drift\DBAL\Credentials;
use Drift\DBAL\Driver\Mysql\MysqlDriver;
use Micoli\Smtp\Server\Server as SmtpServer;
use React\EventLoop\LoopInterface;
use React\Socket\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LoopRunnerCommand extends Command
{
    protected static $defaultName = 'loop:runner';
    private LoopInterface $loop;
    private SmtpServer $smtpServer;
    private HttpServer $httpServer;
    private MysqlConnection $mysqlConnection;

    public function __construct(
        LoopInterface $loop,
        SmtpServer $smtpServer,
        HttpServer $httpServer,
        MysqlConnection $mysqlConnection
    ) {
        parent::__construct();
        $this->loop = $loop;
        $this->smtpServer = $smtpServer;
        $this->httpServer = $httpServer;
        $this->mysqlConnection = $mysqlConnection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mysqlConnection->getConnection($this->loop);

        $smtpTcpServer = new Server('0.0.0.0:8035', $this->loop);
        $this->smtpServer->listen($smtpTcpServer, $this->loop);

        $httpTcpServer = new Server('0.0.0.0:8081', $this->loop);
        $this->httpServer->listen($httpTcpServer);

        $output->writeln(sprintf('<info>System Online %s</info>', json_encode([$smtpTcpServer->getAddress(), $httpTcpServer->getAddress()])));

        $this->loop->run();

        return Command::SUCCESS;
    }
}
