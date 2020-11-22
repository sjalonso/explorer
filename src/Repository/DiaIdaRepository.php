<?php

namespace App\Repository;

use App\Entity\DiaIda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiaIda|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiaIda|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiaIda[]    findAll()
 * @method DiaIda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiaIdaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiaIda::class);
    }

    // /**
    //  * @return DiaIda[] Returns an array of DiaIda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiaIda
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
