<?php

namespace App\Repository;

use App\Entity\Estadocivil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Estadocivil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estadocivil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estadocivil[]    findAll()
 * @method Estadocivil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadocivilRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Estadocivil::class);
    }

    // /**
    //  * @return Estadocivil[] Returns an array of Estadocivil objects
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
    public function findOneBySomeField($value): ?Estadocivil
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
