<?php

namespace App\Repository;

use App\Entity\Hospedaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Hospedaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hospedaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hospedaje[]    findAll()
 * @method Hospedaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HospedajeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Hospedaje::class);
    }

    // /**
    //  * @return Hospedaje[] Returns an array of Hospedaje objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hospedaje
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
