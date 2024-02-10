<?php

namespace App\Command;

use App\Entity\Area;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MarkEventsAsArchivedCommand extends Command 
{
    private $entityManager;
    private $areaRepository;
    
    public function __construct(EntityManagerInterface $entityManager, AreaRepository $areaRepository) {

        parent::__construct();

        $this->entityManager = $entityManager;
        $this->areaRepository = $areaRepository;

    }

    protected function configure()
    {
        $this->setName('app:mark-event-as-archived')
            ->setDescription('Mark event as archived');
    }

    protected  function execute(InputInterface $input, OutputInterface $output) {

        $currentDate = new \DateTime();


        $areas = $this->areaRepository->findAll();

        foreach ($areas as $area) {
            $endDate= $area->getEndDate();
           
            // dump($endDate);die;
            // Compare dates
            // use a DateTime because the endDate is a string
            if ($endDate <= $currentDate) {
                $area->setStatus("ARCHIVED");
            }
        
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}


// php bin/console MarkEventsAsArchivedCommand
// php bin/console app:mark-event-as-archived