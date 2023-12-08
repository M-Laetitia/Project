<?php

namespace App\Repository;

use App\Entity\AreaCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AreaCategory>
 *
 * @method AreaCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AreaCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AreaCategory[]    findAll()
 * @method AreaCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AreaCategory::class);
    }

//    /**
//     * @return AreaCategory[] Returns an array of AreaCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AreaCategory
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
