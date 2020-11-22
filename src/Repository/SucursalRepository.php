<?php

namespace App\Repository;

use App\Entity\Sucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sucursal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sucursal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sucursal[]    findAll()
 * @method Sucursal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SucursalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sucursal::class);
    }

    // /**
    //  * @return Sucursal[] Returns an array of Sucursal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sucursal
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
