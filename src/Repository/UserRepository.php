<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function findOneByUsername(string $username): User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
