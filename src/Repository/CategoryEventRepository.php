<?php

namespace App\Repository;

use App\Entity\CategoryEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryEvent>
 *
 * @method CategoryEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryEvent[]    findAll()
 * @method CategoryEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEvent::class);
    }

//    /**
//     * @return CategoryEvent[] Returns an array of CategoryEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryEvent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
