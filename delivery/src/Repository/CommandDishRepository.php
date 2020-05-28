<?php

namespace App\Repository;

use App\Entity\CommandDish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandDish|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandDish|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandDish[]    findAll()
 * @method CommandDish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandDishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandDish::class);
    }

    // /**
    //  * @return CommandDish[] Returns an array of CommandDish objects
    //  */

    public function findCommandDish($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.dish', 'd')
            ->andWhere('d.restorer = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }


    public function findCommandDishAdmin($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.dish', 'd')
            ->andWhere('d.restorer = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }



    /*
    public function findOneBySomeField($value): ?CommandDish
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
