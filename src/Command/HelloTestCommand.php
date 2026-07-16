<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test:hello',
    description: 'Prints a hello message (runs every 5 minutes, for testing the scheduler).',
)]
class HelloTestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success(sprintf('Hello from the scheduler! (%s)', (new \DateTimeImmutable())->format('Y-m-d H:i:s')));

        return Command::SUCCESS;
    }
}