<?php

declare(strict_types=1);

namespace App\Core\Presentation\Command;

use App\Core\Domain\Command\ImportData;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:group:create',
    description: 'Crete a new group.',
    aliases: ['app:create:group']
)]
class ImportDataCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private MessageBusInterface $messageBus,
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
        $this->setHelp('This command allows you to create a new group');
        $this->addArgument('fileToImport', InputArgument::REQUIRED, 'XLSX File to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fileToImport = $input->getArgument('fileToImport');
            $this->io->info('File to import set to: ' . $fileToImport);
            $this->messageBus->dispatch(new ImportData($fileToImport));
            $this->io->info('Dispatched ImportData command');
        } catch (ExceptionInterface $e) {
            $this->logger->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
