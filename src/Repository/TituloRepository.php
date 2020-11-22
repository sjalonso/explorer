<?php

namespace App\Repository;

use App\Entity\Titulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Titulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Titulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Titulo[]    findAll()
 * @method Titulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TituloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Titulo::class);
    }

    // /**
    //  * @return Titulo[] Returns an array of Titulo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Titulo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
