<?php

namespace App\Repository;

use App\Entity\EstadoPaquete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadoPaquete|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoPaquete|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoPaquete[]    findAll()
 * @method EstadoPaquete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoPaqueteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadoPaquete::class);
    }

    // /**
    //  * @return EstadoPaquete[] Returns an array of EstadoPaquete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadoPaquete
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
