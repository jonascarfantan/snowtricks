<?php

namespace App\Repository;

use App\Entity\PublicMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicMessage[]    findAll()
 * @method PublicMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicMessage::class);
    }

    // /**
    //  * @return PublicMessage[] Returns an array of PublicMessage objects
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

    /*
    public function findOneBySomeField($value): ?PublicMessage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
