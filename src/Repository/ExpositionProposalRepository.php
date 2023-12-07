<?php

namespace App\Repository;

use App\Entity\ExpositionProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpositionProposal>
 *
 * @method ExpositionProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpositionProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpositionProposal[]    findAll()
 * @method ExpositionProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpositionProposalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpositionProposal::class);
    }

    // ^ check if an user has already made a proposal

    public function checkIfUserHasExistingProposal(?int $userId = null,  ?int $areaId = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb ->select('ep.id')
        ->from('App\Entity\ExpositionProposal', 'ep')
        ->where('ep.id =  :userId')
        ->andWhere('a.id = :areaId')
        ->setParameter('userId', $userId)
        ->setParameter('areaId', $areaId);

        $query = $qb->getQuery();
        $query->getResult();
        return $query !== null;

    }

    // ^ Get the number of proposal per Exposition

    // public function countProposalsPerExposition(?int $areaId = null) {
    //     $em = $this->getEntityManager();
    //     $qb = $em->createQueryBuilder();

    //     $qb ->select('COUNT(ep.id) as proposalCount')
    //     ->from('App\Entity\ExpositionProposal', 'ep')
    //     ->leftJoin('ep.area', 'a')
    //     ->where('a.id = :areaId')
    //     ->setParameter('areaId', $areaId)
    //     ->groupBy('a.id');

    //     $query = $qb->getQuery();
    //     return $query->getResult();
    // }

//    /**
//     * @return ExpositionProposal[] Returns an array of ExpositionProposal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExpositionProposal
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
