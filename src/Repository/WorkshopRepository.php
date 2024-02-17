<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 *
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

        // ^ search (keyword)
        public function searchByKeyword(string $keyword) { 

            $qb = $this->createQueryBuilder('a')
            ->where('a.name LIKE :keyword')
            ->andWhere('a.status IN (:statuses)')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->setParameter('statuses', ['OPEN', 'CLOSED', 'PENDING'])
            ->getQuery();
    
            return $qb->getResult();
        }

        // ^ search (period)
        public function searchByPeriod(string $period)
        {
            $now = new \DateTime();
            
            // Déterminer la date de fin en fonction de la période spécifiée
            switch ($period) {
                case 'week':
                    $endDate = clone $now;
                    $endDate->modify('+7 days');
                    break;
                case 'days':
                    $endDate = clone $now;
                    $endDate->modify('+30 days');
                    break;
                case 'months':
                    $endDate = clone $now;
                    $endDate->modify('+3 months');
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid period: $period");
            }
            
            $qb = $this->createQueryBuilder('w')
                ->where('w.startDate BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $now)
                ->setParameter('endDate', $endDate)
                ->andWhere('w.status IN (:statuses)')
                ->setParameter('statuses', ['OPEN', 'CLOSED', 'PENDING'])
                ->orderBy('w.startDate', 'ASC');
    
            return $qb->getQuery()->getResult();
        }
    

//    /**
//     * @return Workshop[] Returns an array of Workshop objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workshop
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
