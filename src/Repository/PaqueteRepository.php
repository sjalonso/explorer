<?php

namespace App\Repository;

use App\Entity\Paquete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Paquete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paquete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paquete[]    findAll()
 * @method Paquete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaqueteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Paquete::class);
    }

    // /**
    //  * @return Paquete[] Returns an array of Paquete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findCountLikeCodigopaquete($value)
    {
        $value = $value."%";
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.codigopaquete like :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

}
