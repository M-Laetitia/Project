<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateSubscriptionStatusCommand extends Command 
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository) {

        parent::__construct();

        $this->entityManager = $entityManager;
        $this->subscriptionRepository = $subscriptionRepository;

    }

    protected function configure()
    {
        $this->setName('app:update-subscription-status')
            ->setDescription('Updat subscription status');
    }

    protected  function execute(InputInterface $input, OutputInterface $output) {

        $currentDate = new \DateTime();


        $subscription = $this->subscriptionRepository->findAll();

        foreach ($subscription as $subscription) {
            $endDate= $subscription->getEndDate();
           
            // dump($endDate);die;

             // Compare dat
            // Compare dates
            // use a DateTime because the endDate is a string
            if ($endDate <= $currentDate) {
                $subscription->setIsActive("0");
            }
        
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}


// php bin/console UpdateSubscriptionStatusCommand
// php bin/console app:update-subscription-status