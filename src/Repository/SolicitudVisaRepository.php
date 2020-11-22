<?php

namespace App\Repository;

use App\Entity\SolicitudVisa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SolicitudVisa|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudVisa|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudVisa[]    findAll()
 * @method SolicitudVisa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudVisaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SolicitudVisa::class);
    }

     /**
      * @return SolicitudVisa[] Returns an array of SolicitudVisa objects
      */

    public function findBySucursal($value)
    {
        return $this->createQueryBuilder('s')
            ->join('s.Cliente', 'c')
            ->join('c.sucursal', 'suc')
            ->andWhere('suc.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySucursalAndUser($sucursal, $user)
    {
        return $this->createQueryBuilder('s')
            ->join('s.Cliente', 'c')
            ->join('s.user', 'us')
            ->join('c.sucursal', 'suc')
            ->andWhere('suc.id = :val')
            ->andWhere('us.id = :us')
            ->setParameter('val', $sucursal)
            ->setParameter('us', $user)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?SolicitudVisa
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
