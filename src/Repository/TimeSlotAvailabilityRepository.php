<?php

namespace App\Repository;

use App\Entity\TimeSlotAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSlotAvailability>
 *
 * @method TimeSlotAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlotAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlotAvailability[]    findAll()
 * @method TimeSlotAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSlotAvailability::class);
    }



    // SELECT * 
    //     FROM time_slot_availability ta
    //     WHERE ta.id NOT IN (
    //         SELECT timeslot.time_slot_availability_id
    //         FROM timeslot
    //         WHERE timeslot.studio_id  = '2'
    //         AND timeslot.end_date >= '2024-02-14 14:39:32'
    //         AND timeslot.start_date <= '2024-02-14 15:39:40'
    //     );


    public function findAvailableTimeSlots($studioId, $date )
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('ta')
        ->from('App\Entity\TimeSlotAvailability', 'ta')
        ->where($qb->expr()->notIn(
            'ta.id',
            $em->createQueryBuilder()
                ->select('IDENTITY(ts.timeSlotAvailability)') // permet d'obtenir l'ID de l'entité TimeSlotAvailability liée à chaque entité TimeSlot, et permet de filtrer les TimeSlotAvailability en fonction des ID obtenus dans la sous-requête NOT IN.
                ->from('App\Entity\TimeSlot', 'ts')
                ->where('ts.studio = :studio')
                ->andWhere('ts.date >= :date')
                ->getDQL() //  retourne la chaîne de requête DQL générée par le qb. Cette chaîne de requête DQL est utilisée dans la sous-requête notIn() pour filtrer les TimeSlotAvailability. Cela permet de créer une requête DQL plus complexe et de l'utiliser comme condition dans une autre requête DQL.
        ))
        ->setParameter('studio', $studioId)
        ->setParameter('date', $date);
   

        $query = $qb->getQuery();
        return $query->getResult();
    }


//    /**
//     * @return TimeSlotAvailability[] Returns an array of TimeSlotAvailability objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TimeSlotAvailability
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
