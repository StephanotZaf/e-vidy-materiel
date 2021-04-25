<?php

namespace App\Repository;

use App\Entity\SousType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousType[]    findAll()
 * @method SousType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousType::class);
    }

    // /**
    //  * @return SousType[] Returns an array of SousType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SousType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMaterielByMarque($marque_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT materiel.*,sous_type.*,categorie.* FROM categorie 
                INNER JOIN materiel ON materiel.categorie_id=categorie.id 
                INNER JOIN sous_type ON sous_type.id = categorie.sous_type_id 
                WHERE sous_type.id= :marque_id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['marque_id' => $marque_id]);

        return $stmt->fetchAllAssociative();
    }
}
