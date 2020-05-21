<?php

namespace App\Repository;

use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class);
    }

    // /**
    //  * @return Dish[] Returns an array of Dish objects
    //  */
    
    public function searchDish($value, $restorer)
    {
        return $this->createQueryBuilder('d')
            ->where('d.name LIKE :dish')
            ->setParameter('dish', $value.'%')
            ->andWhere('d.restorer = :restorer')
            ->setParameter('restorer', $restorer)
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    
    
}
