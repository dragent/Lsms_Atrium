<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
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

    public function findByRole(string $role,array $order): array
    {
        $role = mb_strtoupper($role);
        
        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->OrderBy("u.".$order["column"],$order["order"])
            ->setParameter('role', '"'.$role.'"')
            ->getQuery()
            ->getResult();
    }
    
    public function findLSMSConnected(array $order): array
    {
        $role = mb_strtoupper("ROLE_LSMS");
        
        return $this->createQueryBuilder('u')
            ->where("u.inService = true")
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->OrderBy("u.".$order["column"],$order["order"])
            ->setParameter('role', '"'.$role.'"')
            ->getQuery()
            ->getResult();
    }
}
