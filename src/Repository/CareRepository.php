<?php

namespace App\Repository;

use App\Entity\Care;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Care>
 */
class CareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Care::class);
    }

    //    /**
    //     * @return Care[] Returns an array of Care objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Care
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAll():array
    {
        return $this->createQueryBuilder("c")
            ->leftJoin("c.category","cg")
            ->orderBy("cg.position","ASC")
            ->orderBy("c.slug","ASC")
            ->getQuery()
            ->getResult();
    }
    public function deleteCare( int $id)
    {
        $this->createQueryBuilder("q")  
            ->delete("App\Entity\Care","c")
            ->where("c.category = :id")
            ->setParameter("id",$id)
            ->getQuery()
            ->execute();
    }
}