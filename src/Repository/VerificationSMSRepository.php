<?php

namespace App\Repository;

use App\Entity\VerificationSMS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VerificationSMS|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerificationSMS|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerificationSMS[]    findAll()
 * @method VerificationSMS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerificationSMSRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerificationSMS::class);
    }

    // /**
    //  * @return VerificationSMS[] Returns an array of VerificationSMS objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VerificationSMS
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
