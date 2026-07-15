<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\PackageStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:package:cleanup',
    description: 'Delete all packages that are available or reserved (runs nightly at 1 AM).',
)]
class PackageCleanupCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $deleted = $this->entityManager->createQueryBuilder()
            ->delete(\App\Entity\Package::class, 'p')
            ->where('p.status IN (:statuses)')
            ->setParameter('statuses', [
                PackageStatus::AVAILABLE->value,
                PackageStatus::RESERVED->value,
            ])
            ->getQuery()
            ->execute();

        $io->success(sprintf('Deleted %d package(s) with status available or reserved.', $deleted));

        return Command::SUCCESS;
    }
}
