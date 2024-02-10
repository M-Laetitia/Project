<?php

namespace App\Command;

use App\Entity\Area;
use App\Repository\AreaRepository;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MarkEventsAsArchivedCommand extends Command 
{
    private $entityManager;
    private $areaRepository;
    private $workshopRepository;
    
    public function __construct(EntityManagerInterface $entityManager, AreaRepository $areaRepository, WorkshopRepository $workshopRepository) {

        parent::__construct();

        $this->entityManager = $entityManager;
        $this->areaRepository = $areaRepository;
        $this->workshopRepository = $workshopRepository;

    }

    protected function configure()
    {
        $this->setName('app:mark-event-as-archived')
            ->setDescription('Mark event as archived');
    }

    protected  function execute(InputInterface $input, OutputInterface $output) {

        $currentDate = new \DateTime();


        $areas = $this->areaRepository->findAll();
        $workshops = $this->workshopRepository->findAll();

        // foreach ($areas as $area) {
        //     $endDate= $area->getEndDate();
        //     if ($endDate <= $currentDate) {
        //         $area->setStatus("ARCHIVED");
        //     }
        
        // }

        foreach ($workshops as $workshop) {
            $endDate= $workshop->getEndDate();
            if ($endDate <= $currentDate) {
                $workshop->setStatus("ARCHIVED");
            }        
        }


        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}


// php bin/console MarkEventsAsArchivedCommand
// php bin/console app:mark-event-as-archived