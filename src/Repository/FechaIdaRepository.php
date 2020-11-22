<?php

namespace App\Repository;

use App\Entity\FechaIda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FechaIda|null find($id, $lockMode = null, $lockVersion = null)
 * @method FechaIda|null findOneBy(array $criteria, array $orderBy = null)
 * @method FechaIda[]    findAll()
 * @method FechaIda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FechaIdaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FechaIda::class);
    }

    // /**
    //  * @return FechaIda[] Returns an array of FechaIda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FechaIda
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
