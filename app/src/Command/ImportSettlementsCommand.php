<?php

namespace App\Command;

use App\Service\SettlementService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
    protected LoggerInterface $settlementLogger;
    protected EntityManagerInterface $em;

    #[Required]
    public function loadDependencies(
        SettlementService $settlementService,
        LoggerInterface $settlementLogger,
        EntityManagerInterface $em,
    ): void {
        $this->settlementService = $settlementService;
        $this->settlementLogger = $settlementLogger;
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $districtUrls = $this->settlementService->getAllDistricts();

            foreach ($districtUrls as $district => $districtUrl) {
                $settlementUrls = $this->settlementService->getSettlementsForDistrict($districtUrl);

                foreach ($settlementUrls as $settlementUrl) {
                    $this->settlementService->saveSettlement($district, $settlementUrl);
                }
                $this->em->flush();
            }

            $io->success('Data imported successfully');
            $this->settlementLogger->info('Data imported successfully');

            return Command::SUCCESS;
        } catch (Throwable $t) {
            dd($t);
            $io->error($t->getMessage());
            $this->settlementLogger->error($t->getMessage());

            return Command::FAILURE;
        }
    }
}
