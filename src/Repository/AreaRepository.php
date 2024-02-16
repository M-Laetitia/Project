<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Area>
 *
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Area::class);
    }


    public function countProposalsPerExpo(?int $expositionId = null)
    {
        $qb = $this->createQueryBuilder('a')
        ->select('COUNT(ep.id) as counts')
        ->leftJoin('a.expositionProposals', 'ep')
        ->andWhere('a.id = :expositionId')
        ->setParameter('expositionId', $expositionId)
        ->groupBy('a.id')
        ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function countParticipationPerExpo(?int $expositionId = null) {
        // $em = $this->getEntityManager();
        // $qb = $em->createQueryBuilder();
         
        // $qb ->select('COUNT(a.id) as counts')
        // ->leftJoin('a.areaParticipation', 'ap')
        // ->from('App\Entity\Area', 'a')
        // ->where('a.id = :expositionId')
        // ->setParameter('expositionId', $expositionId)
        // ->groupBy('a.id')
        // ->getQuery();

        // $query = $qb->getQuery();
        // $query->getResult();


        $qb = $this->createQueryBuilder('a')
        ->select('COUNT(ap.id) as counts')
        ->leftJoin('a.areaParticipations', 'ap')
        ->andWhere('a.id = :expositionId')
        ->setParameter('expositionId', $expositionId)
        ->groupBy('a.id')
        ->getQuery();

        return $qb->getSingleScalarResult();

        // return $qb->getSingleScalarResult();
        // return $query !== null;
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
        

//    /**
//     * @return Area[] Returns an array of Area objects
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

//    public function findOneBySomeField($value): ?Area
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
