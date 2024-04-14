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
        // $query->getSingleScalarResult();


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


    // ^ search event (keyword)
    public function searchByKeyword(string $keyword) {  // Takes a keyword parameter of type string.
        $qb = $this->createQueryBuilder('a')
        ->where('a.name LIKE :keyword') // The keyword is inserted into the query using a parameter named :keyword.
        ->andWhere('a.status IN (:statuses)')
        ->setParameter('keyword', '%'.$keyword.'%') // The :keyword parameter is set with the searched keyword, with % to match parts of the event name.
        ->setParameter('statuses', ['OPEN', 'CLOSED', 'PENDING'])
        ->getQuery();

        return $qb->getResult();
    }


        // // ^ search event (keyword)
        // // Prend un paramètre keyword de type string.
        // public function searchByKeyword(string $keyword) {  
        //     $qb = $this->createQueryBuilder('a')
        //     // Le keyword est inséré dans la requête en utilisant le paramètre nommé:keyword
        //     ->where('a.name LIKE :keyword') 
        //     // Le paramètre:keyword est défini avec le keyword recherché
        //     ->setParameter('keyword', '%'.$keyword.'%') 
        //     ->getQuery();
        //     return $qb->getResult();
        // }
  

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
        
        $qb = $this->createQueryBuilder('e')
            ->where('e.startDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $now)
            ->setParameter('endDate', $endDate)
            ->andWhere('e.status IN (:statuses)')
            ->setParameter('statuses', ['OPEN', 'CLOSED', 'PENDING'])
            ->orderBy('e.startDate', 'ASC');

        return $qb->getQuery()->getResult();
    }

    //^ Get all past content ordered by date DESC
    public function getAllPastContent() {
        $em = $this->getEntityManager();

        // Query for areas
        $areaQb = $em->createQueryBuilder();
        $areaQb->select('a')
                ->from('App\Entity\Area', 'a')
                ->where('a.status = :status')
                ->setParameter('status', 'ARCHIVED')
                ->orderBy('a.startDate', 'DESC');
    
        // Query for workshops
        $workshopQb = $em->createQueryBuilder();
        $workshopQb->select('w')
                    ->from('App\Entity\Workshop', 'w')
                    ->where('w.status = :status')
                    ->setParameter('status', 'ARCHIVED')
                    ->orderBy('w.startDate', 'DESC');
    
        // Execute queries
        $areaResults = $areaQb->getQuery()->getResult();
        $workshopResults = $workshopQb->getQuery()->getResult();
    
        // Merge and sort results
        $allPastContent = array_merge($areaResults, $workshopResults);
        usort($allPastContent, function($a, $b) {
            return $b->getStartDate() <=> $a->getStartDate();
        });
    
        return $allPastContent;
    }


    //^ Get ongoing event/expo
    public function getCurrentExpoAreas($type)
    {
        $em = $this->getEntityManager();

        $currentDate = new \DateTime();

        $query = $em->createQuery(
            'SELECT a FROM App\Entity\Area a
            WHERE a.type = :type
            AND a.startDate <= :currentDate
            AND a.endDate >= :currentDate'
            )
        ->setParameter('type', $type)
        ->setParameter('currentDate', $currentDate);

        return $query->getResult();
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
