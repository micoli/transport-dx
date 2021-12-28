<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Drift\DBAL\Connection;
use Drift\DBAL\Credentials;
use Drift\DBAL\Driver\Mysql\MysqlDriver;
use React\EventLoop\LoopInterface;

final class MysqlConnection
{
    private string $url;
    private LoopInterface $loop;

    public function __construct(
        LoopInterface $loop,
        string $url
    ) {
        $this->url = $url;
        $this->loop = $loop;
    }

    public function getConnection(): Connection
    {
        return Connection::createConnected(
            new MysqlDriver($this->loop),
            new Credentials(
                '127.0.0.1',
                '3306',
                'root',
                'root',
                'test'
            ),
            new MySQLPlatform()
        );

    }
}
