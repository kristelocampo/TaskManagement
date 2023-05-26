<?php

namespace App\Repository;

use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tasks>
 *
 * @method Tasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasks[]    findAll()
 * @method Tasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasks::class);
    }

    public function save(Tasks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tasks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getAll()
    {
        return $this->createQueryBuilder('t')
            ->select('proj.name', 'proj.description', 't.title', 't.description', 't.start_date', 't.due_date', 'prio.name', 'stat.status')
            ->join('t.project', 'proj')
            ->join('t.priority', 'prio')
            ->join('t.status', 'stat')
            ->join('t.user', 'u')
            ->andWhere('t')
            ->getQuery()
            ;

    }

    public function getTasksWithDetails()
    {
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
        ');

        return $query->getResult();
    }

//    /**
//     * @return Tasks[] Returns an array of Tasks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tasks
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
