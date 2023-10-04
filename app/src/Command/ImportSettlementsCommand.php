<?php

namespace App\Command;

use App\Service\SettlementService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

#[AsCommand(
    name: 'app:import-settlements',
    description: 'Seed data from external data source',
)]
class ImportSettlementsCommand extends Command
{
    protected SettlementService $settlementService;

    #[Required]
    public function loadDependencies(SettlementService $settlementService): void
    {
        $this->settlementService = $settlementService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $districtUrls = $this->settlementService->getAllDistricts();

            $io->success('Data imported successfully');

            return Command::SUCCESS;
        } catch (Throwable $t) {
            echo $t->getMessage() . PHP_EOL;

            return Command::FAILURE;
        }
    }
}
