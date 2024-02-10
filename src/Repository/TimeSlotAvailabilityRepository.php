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
