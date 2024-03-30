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
    private $entityManager; // EntityManager instance for managing entities in the db
    private $areaRepository; // Repository for accessing Area entities in the database
    private $workshopRepository; // Same for Workshop
    
    public function __construct(EntityManagerInterface $entityManager, AreaRepository $areaRepository, WorkshopRepository $workshopRepository) {

        parent::__construct();
        $this->entityManager = $entityManager;
        $this->areaRepository = $areaRepository;
        $this->workshopRepository = $workshopRepository;

    }
    protected function configure()
    {
        $this->setName('app:mark-event-as-archived')  // Set the name of the command
            ->setDescription('Mark event as archived'); // Set the description of the command
    }
    protected  function execute(InputInterface $input, OutputInterface $output) {
        $currentDate = new \DateTime(); // Get the current date and time
        $areas = $this->areaRepository->findAll();
        $workshops = $this->workshopRepository->findAll();

        // Iterate through each Area entity
        foreach ($areas as $area) {
            $endDate= $area->getEndDate();
            // Check if the end date is before or equal to the current date
            if ($endDate <= $currentDate) {
                $area->setStatus("ARCHIVED"); // Set the status of the area to "ARCHIVED"
            }
        
        }
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