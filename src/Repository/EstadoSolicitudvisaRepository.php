<?php

namespace App\Repository;

use App\Entity\EstadoSolicitudvisa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadoSolicitudvisa|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoSolicitudvisa|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoSolicitudvisa[]    findAll()
 * @method EstadoSolicitudvisa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoSolicitudvisaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadoSolicitudvisa::class);
    }

    // /**
    //  * @return EstadoSolicitudvisa[] Returns an array of EstadoSolicitudvisa objects
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
    public function findOneBySomeField($value): ?EstadoSolicitudvisa
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
