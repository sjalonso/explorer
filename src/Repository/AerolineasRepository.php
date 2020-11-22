<?php

namespace App\Repository;

use App\Entity\Aerolineas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Aerolineas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aerolineas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aerolineas[]    findAll()
 * @method Aerolineas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AerolineasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Aerolineas::class);
    }

    // /**
    //  * @return Aerolineas[] Returns an array of Aerolineas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aerolineas
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
