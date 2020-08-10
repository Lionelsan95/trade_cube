<?php

namespace App\Repository;

use App\Entity\CronTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CronTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronTask[]    findAll()
 * @method CronTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CronTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CronTask::class);
    }

    // /**
    //  * @return CronTask[] Returns an array of CronTask objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CronTask
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
