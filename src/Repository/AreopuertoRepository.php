<?php

namespace App\Repository;

use App\Entity\Areopuerto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Areopuerto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Areopuerto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Areopuerto[]    findAll()
 * @method Areopuerto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreopuertoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Areopuerto::class);
    }

    // /**
    //  * @return Areopuerto[] Returns an array of Areopuerto objects
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
    public function findOneBySomeField($value): ?Areopuerto
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
