<?php

namespace App\Repository;

use App\Entity\MaterielEntree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterielEntree|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterielEntree|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterielEntree[]    findAll()
 * @method MaterielEntree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielEntreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterielEntree::class);
    }

    // /**
    //  * @return MaterielEntree[] Returns an array of MaterielEntree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterielEntree
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
