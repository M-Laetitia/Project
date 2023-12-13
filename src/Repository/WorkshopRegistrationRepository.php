<?php

namespace App\Repository;

use App\Entity\WorkshopRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkshopRegistration>
 *
 * @method WorkshopRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkshopRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkshopRegistration[]    findAll()
 * @method WorkshopRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkshopRegistration::class);
    }

    // ^ get registration per timeslot
    public function getRegistrationPerTimeslot(?int $timeslotId = null) 
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('wr')
            ->from('App\Entity\WorkshopRegistration', 'wr')
            ->where('wr.timeslot = :timeslotId')
            ->setParameters('timeslotId', $timeslotId);

        $query = $qb->getQuery();
        return $query->getResult();
        

    }

//    /**
//     * @return WorkshopRegistration[] Returns an array of WorkshopRegistration objects
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

//    public function findOneBySomeField($value): ?WorkshopRegistration
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
