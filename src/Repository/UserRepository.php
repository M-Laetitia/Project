<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //^ get users whith the role artist only

    public function findArtistUsers(?int $userId = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();


        $qb ->select('u')
            ->from('App\Entity\User', 'u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_ARTIST"%');

        // Ajouter une condition pour filtrer par ID si un ID est fourni
        if ($userId !== null) {
            $qb->andWhere('u.id = :userId')
                ->setParameter('userId', $userId);
        }

        $query = $qb->getQuery();
        return $query->getResult();

    }

    //^ get users filtered by role

    public function findUsersbyRole(?string $role = null, ?int $userId = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();


        $qb ->select('u')
            ->from('App\Entity\User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        // Ajouter une condition pour filtrer par ID si un ID est fourni
        if ($userId !== null) {
            $qb->andWhere('u.id = :userId')
                ->setParameter('userId', $userId);
        }

        $query = $qb->getQuery();
        return $query->getResult();
        // return $qb->getQuery()->getResult();
        // $users = $userRepository->findUsersByRoleAndId('ROLE_ARTIST');
        // or
        // $usersById = $userRepository->findUsersByRoleAndId('ROLE_ARTIST', 1);

    }



//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
