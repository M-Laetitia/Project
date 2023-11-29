<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'CleanExpiredPasswordResetRequests',
    description: 'Add a short description for your command',
)]
class CleanExpiredPasswordResetRequestsCommand extends Command
{
    private $entityManager;
    private $resetPasswordRequestRepository;

    public function __construct(EntityManagerInterface $entityManager, ResetPasswordRequestRepository $resetPasswordRequestRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        //get the current date
        $currentDate = new \DateTime();


        // add the logic in order to "clean" the expiredRequests
        $expiredRequests = $this->resetPasswordRequestRepository->findExpiredRequests();

        
        foreach ($expiredRequests as $expiredRequest) {
            // Compare  dates
            if ($expiredRequest->getExpiresAt() <= $currentDate) {
                $this->entityManager->remove($expiredRequest);
            }
        }

        // delete
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}


// php bin/console CleanExpiredPasswordResetRequests