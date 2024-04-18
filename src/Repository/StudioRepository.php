<?php

namespace App\Repository;

use App\Entity\Studio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Studio>
 *
 * @method Studio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studio[]    findAll()
 * @method Studio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studio::class);
    }

    public function findOngoingTimeslotsPerStudio() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $startOfWeek = new \DateTime('this week');
        $startOfWeek->setTime(0, 0, 0);

        $qb->select('s', 'ts')
        ->from('App\Entity\Studio', 's')
        ->leftJoin('s.timeslots', 'ts', 'WITH', 'ts.date > :startOfWeek')
        ->setParameter('startOfWeek', $startOfWeek);

        $query = $qb->getQuery();
        return $query->getResult();
    }

//    /**
//     * @return Studio[] Returns an array of Studio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Studio
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
