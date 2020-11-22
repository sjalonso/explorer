<?php

namespace App\Repository;

use App\Entity\DiaVuelta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiaVuelta|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiaVuelta|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiaVuelta[]    findAll()
 * @method DiaVuelta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiaVueltaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiaVuelta::class);
    }

    // /**
    //  * @return DiaVuelta[] Returns an array of DiaVuelta objects
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
    public function findOneBySomeField($value): ?DiaVuelta
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
