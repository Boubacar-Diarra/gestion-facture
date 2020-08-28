<?php

namespace App\Repository;

use App\Entity\InfoPaiementEspece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoPaiementEspece|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoPaiementEspece|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoPaiementEspece[]    findAll()
 * @method InfoPaiementEspece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoPaiementEspeceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoPaiementEspece::class);
    }

    // /**
    //  * @return InfoPaiementEspece[] Returns an array of InfoPaiementEspece objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoPaiementEspece
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
