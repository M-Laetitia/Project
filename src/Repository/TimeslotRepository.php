<?php

namespace App\Repository;

use App\Entity\Timeslot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timeslot>
 *
 * @method Timeslot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timeslot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timeslot[]    findAll()
 * @method Timeslot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeslotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timeslot::class);
    }

    public function findExistingTimeSlots(TimeSlot $timeSlot, ?int $studioId = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('ts')
        ->from('App\Entity\Timeslot', 'ts')
        ->innerJoin('ts.studio', 's')
        ->Where('ts.startDate < :endDate')
        ->andWhere('ts.endDate > :startDate')
        ->andWhere('s.id = :studioId')
        ->setParameter('startDate', $timeSlot->getStartDate())
        ->setParameter('endDate', $timeSlot->getEndDate())
        ->setParameter('studioId', $studioId);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findOngoingTimeslots() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $startOfWeek = new \DateTime('this week');
        $startOfWeek->setTime(0, 0, 0);
        $qb->select('ts')
        ->from('App\Entity\Timeslot', 'ts')
        ->where('ts.date > :startOfWeek')
        ->setParameter('startOfWeek', $startOfWeek);

        $query = $qb->getQuery();
        return $query->getResult();
    }

 



    // ^ find timeslots by studio

    // public function findTimeSlotsPerStudio(?int $studioId = null) 
    // {
    //     $em = $this->getEntityManager();
    //     $qb = $em->createQueryBuilder();

    //     $qb->select('ts')
    //     ->from('App\Entity\Timeslot', 'ts')
    //     ->leftJoin('ts.studio', 's')
    //     ->Where('ts.studio = :studioId')
    //     ->setParameter('studioId', $studioId);

    //     $query = $qb->getQuery();
    //     return $query->getResult();
    // }


//    /**
//     * @return Timeslot[] Returns an array of Timeslot objects
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

//    public function findOneBySomeField($value): ?Timeslot
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
