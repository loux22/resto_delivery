<?php

namespace App\Repository;

use App\Entity\Restorer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restorer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restorer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restorer[]    findAll()
 * @method Restorer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestorerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restorer::class);
    }

    // /**
    //  * @return Restorer[] Returns an array of Restorer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Restorer
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
