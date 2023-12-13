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
    // ! gérer le cas où le timeslotId*  est nul !
    public function getRegistrationPerTimeslot(?int $timeslotId = null) 
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('COUNT(wr.id) as nbRegistration')
            ->from('App\Entity\WorkshopRegistration', 'wr')
            ->where('wr.timeslot = :timeslotId')
            ->setParameter('timeslotId', $timeslotId);
            // ->groupBy('wr.id');

        $query = $qb->getQuery();
        // return $query->getResult();
        // getSingleScalarResult est utilisé car la requête DQL est construite pour renvoyer un seul résultat scalaire, qui est le nombre de réservations pour un timeslot donné (et non pas une collection d'entités)
        //Un résultat scalaire est un résultat unique qui n'est pas une entité ou un objet complexe, mais plutôt une valeur simple.
        return $query->getSingleScalarResult();
        

        // return $this->createQueryBuilder('wr')
        // ->select('COUNT(wr.id) as nbRegistrations')
        // ->andWhere('wr.timeslot = :timeslotId')
        // ->setParameter('timeslotId', $timeslotId)
        // ->getQuery()
        // ->getSingleScalarResult();

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
