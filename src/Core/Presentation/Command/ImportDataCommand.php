<?php

declare(strict_types=1);

namespace App\Core\Presentation\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use App\Core\Domain\Service\DataImporterService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:data',
    description: 'Import scout data from an excel file',
    aliases: ['app:data:import']
)]
class ImportDataCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private DataImporterService $dataImportService,
        private LoggerInterface     $logger,
    )
    {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a new group')
            ->addUsage('php bin/console app:import:data  [options] <xlsxFileToImport>')
            ->addArgument('fileToImport', InputArgument::REQUIRED, 'XLSX File to import')
            ->addOption('overwrite', 'o', InputArgument::OPTIONAL, 'Overwrite existing data', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fileToImport = $input->getArgument('fileToImport');
            $overwrite = $input->getOption('overwrite');
            if (null === $overwrite || false === $overwrite) {
                $overwrite = false;
            } else {
                $overwrite = (bool)preg_match('/^=?((y{1}(es)?)|(s{1}[iì]?)|1|true|on)$/iu', $overwrite);
            }

            $this->io->info('File to import set to: ' . $fileToImport . ', overwrite set to: ' . ($overwrite ? 'true' : 'false'));
            $this->dataImportService->setOverwrite($overwrite);
            $this->dataImportService->setLogger($this->logger);
            $this->dataImportService->import($fileToImport);
            $this->io->info('Import complete for file: ' . $fileToImport);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->io->error($e->getMessage() . ', stacktrace: ' . $e->getTraceAsString());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
