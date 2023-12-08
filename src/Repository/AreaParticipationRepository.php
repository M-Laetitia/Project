<?php

namespace App\Repository;

use App\Entity\AreaParticipation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AreaParticipation>
 *
 * @method AreaParticipation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AreaParticipation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AreaParticipation[]    findAll()
 * @method AreaParticipation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AreaParticipation::class);
    }

    //^ check if an user has already a participation 

    public function checkIfUserHasExistingParticipation(?int $userId = null,  ?int $areaId = null) 
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('ap.id')
        ->from('App\Entity\ExpositionProposal', 'ap')
        ->where('ap.id =  :userId')
        ->andWhere('ap.id = :areaId')
        ->setParameter('userId', $userId)
        ->setParameter('areaId', $areaId);

        $query = $qb->getQuery();
        $query->getResult();
        return $query !== null;
        
    }

//    /**
//     * @return AreaParticipation[] Returns an array of AreaParticipation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AreaParticipation
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
