<?php

declare(strict_types=1);

namespace App\UserInterface\Cli;

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

    public function __construct(
        LoopInterface $loop,
        SmtpServer $smtpServer,
    ) {
        parent::__construct();
        $this->loop = $loop;
        $this->smtpServer = $smtpServer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->smtpServer->listen(new Server('0.0.0.0:8025', $this->loop), $this->loop);

        $output->writeln('<info>System Online http://0.0.0.0:8081</info>');

        $this->loop->run();

        return Command::SUCCESS;
    }
}
