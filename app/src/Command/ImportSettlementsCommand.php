<?php

namespace App\Command;

use App\Service\SettlementService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this
            ->addOption('coa-download', null, InputOption::VALUE_NONE, 'Download only Coat of arms file locally')
            ->addOption('delete-settlements', null, InputOption::VALUE_NONE, 'Delete all settlements')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            if ($input->getOption('coa-download')) {
                $message = $this->storeCoatOfArmsLocally();
            } elseif ($input->getOption('delete-settlements')) {
                $message = $this->deleteAllSettlements();
            } else {
                $message = $this->downloadSettlementData();
            }

            $io->success($message);
            $this->settlementLogger->info($message);

            return Command::SUCCESS;
        } catch (Throwable $t) {
            $io->error($t->getMessage());
            $this->settlementLogger->error($t->getMessage());

            return Command::FAILURE;
        }
    }

    private function downloadSettlementData(): string
    {
        $districtUrls = $this->settlementService->loadCurrentSettlements()->getAllDistricts();

        foreach ($districtUrls as $district => $districtUrl) {
            echo $district . PHP_EOL;
            $settlementUrls = $this->settlementService->getSettlementsForDistrict($districtUrl);

            foreach ($settlementUrls as $settlementUrl) {
                $this->settlementService->saveSettlement($district, $settlementUrl);
            }
            $this->em->flush();
        }
        $this->settlementService->setParentsOnSettlements();
        $this->em->flush();

        return 'Settlement data imported successfully';
    }

    private function storeCoatOfArmsLocally(): string
    {
        $count = $this->settlementService->downloadCoatOfArmsLocally();

        return sprintf('Coat of arms downloaded successfully for %s', $count);
    }

    private function deleteAllSettlements(): string
    {
        $this->settlementService->deleteAllSettlements();

        return 'All settlements deleted';
    }
}
