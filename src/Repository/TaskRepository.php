<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
    * @return Task[] Returns an array of Task objects
    */
    public function findNotCompletedByAdmin(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->orWhere('t.user = 3')
            ->andWhere('t.isDone = 0')
            ->setParameter('user', $user)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findNotCompletedByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.user = :user')
        ->setParameter('user', $user)
        ->andWhere('t.isDone = 0')
        ->orderBy('t.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    /**
    * @return Task[] Returns an array of Task objects
    */
    public function findCompletedByAdmin(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->orWhere('t.user = 3')
            ->andWhere('t.isDone = 1')
            ->setParameter('user', $user)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findCompletedByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.user = :user')
        ->setParameter('user', $user)
        ->andWhere('t.isDone = 1')
        ->orderBy('t.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
}
