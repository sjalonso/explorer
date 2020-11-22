<?php

namespace App\Repository;

use App\Entity\EstadoReserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadoReserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoReserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoReserva[]    findAll()
 * @method EstadoReserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoReservaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadoReserva::class);
    }

    // /**
    //  * @return EstadoReserva[] Returns an array of EstadoReserva objects
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
    public function findOneBySomeField($value): ?EstadoReserva
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
