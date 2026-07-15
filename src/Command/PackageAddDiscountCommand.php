<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\PackageStatus;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:package:add-discount',
    description: 'Apply a 30-minute discount step to all available packages (runs every 30 min, 17:00–21:00).',
)]
class PackageAddDiscountCommand extends Command
{
    public function __construct(
        private readonly PackageRepository $packageRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $packages = $this->packageRepository->findBy(['status' => PackageStatus::AVAILABLE->value]);

        if (empty($packages)) {
            $io->info('No available packages found.');
            return Command::SUCCESS;
        }

        $updated = 0;

        foreach ($packages as $package) {
            $decrement = $package->getDecrement();

            if ($decrement === null || $decrement <= 0.0) {
                continue;
            }

            $base = $package->getDiscountedPrice() ?? $package->getPrice();

            $newPrice = $base - ($base * $decrement / 100);
            $newPrice = max(0.0, round($newPrice, 2));

            $package->setDiscountedPrice($newPrice);
            $updated++;
        }

        $this->entityManager->flush();

        $io->success(sprintf('Applied discount step to %d package(s).', $updated));

        return Command::SUCCESS;
    }
}
