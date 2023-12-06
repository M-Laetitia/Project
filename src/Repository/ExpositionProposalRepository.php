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
