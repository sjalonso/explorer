<?php

namespace App\Repository;

use App\Entity\EstadoVisa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadoVisa|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoVisa|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoVisa[]    findAll()
 * @method EstadoVisa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoVisaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadoVisa::class);
    }

    // /**
    //  * @return EstadoVisa[] Returns an array of EstadoVisa objects
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
    public function findOneBySomeField($value): ?EstadoVisa
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
