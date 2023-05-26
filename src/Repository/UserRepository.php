<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
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

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function findUserByEmail($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getProjectById($value){

        $query = $this->getEntityManager()->createQuery('
            SELECT
                projects.name AS ProjectName,
                projects.description AS ProjectDescription,
                tasks.title AS TaskTitle,
                tasks.description AS TaskDescription,
                tasks.start_date ,
                tasks.due_date,
                priority.name AS Priority,
                status.status,
                CONCAT(user.firstname, \' \', user.lastname) AS Fullname
              
            FROM
                App\Entity\Tasks tasks
            JOIN tasks.project_id projects
            JOIN tasks.priority_id priority
            JOIN tasks.status_id status
            JOIN tasks.user_id user
            WHERE
                user.email = :email
        ');

        $query->setParameter('email', $value);

        return $query->getResult();
    }

    public function getProjectUser($value)
    {
        return $this->createQueryBuilder( 'SELECT
                projects.name AS ProjectName,
                projects.description AS ProjectDescription,
                tasks.title AS TaskTitle,
                tasks.description AS TaskDescription,
                tasks.start_date ,
                tasks.due_date,
                priority.name AS Priority,
                status.status,
                CONCAT(user.firstname, \' \', user.lastname) AS Fullname
              
            FROM
                App\Entity\Tasks tasks
            JOIN tasks.project_id projects
            JOIN tasks.priority_id priority
            JOIN tasks.status_id status
            JOIN tasks.user_id user
            WHERE
                user.email = :email)')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ;
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