<?php

namespace App\Repository;

use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    // /**
    //  * @return Reserva[] Returns an array of Reserva objects
    //  */
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

    public function findBySucursalAndPaquete($sucursal, $paquete)
    {
        return $this->createQueryBuilder('s')
            ->join('s.Cliente', 'c')
            ->join('c.sucursal', 'suc')
            ->join('s.paquete', 'p')
            ->andWhere('suc.id = :sucursal')
            ->andWhere('p.id = :paquete')
            ->setParameter('sucursal', $sucursal)
            ->setParameter('paquete', $paquete)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBySucursalPaqueteAndUser($sucursal, $paquete, $user)
    {
        return $this->createQueryBuilder('s')
            ->join('s.Cliente', 'c')
            ->join('c.sucursal', 'suc')
            ->join('s.paquete', 'p')
            ->join('s.user', 'us')
            ->andWhere('suc.id = :sucursal')
            ->andWhere('p.id = :paquete')
            ->andWhere('us.id = :us')
            ->setParameter('sucursal', $sucursal)
            ->setParameter('paquete', $paquete)
            ->setParameter('us', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByPaquete($paquete)
    {
        return $this->createQueryBuilder('s')
            ->join('s.Cliente', 'c')
            ->join('s.paquete', 'p')
            ->andWhere('p.id = :paquete')
            ->setParameter('paquete', $paquete)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Reserva
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
