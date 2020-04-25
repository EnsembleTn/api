<?php

namespace App\Repository;

use App\Entity\SMSVerification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class SMSVerificationRepository
 *
 * @method SMSVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method SMSVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method SMSVerification[]    findAll()
 * @method SMSVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class SMSVerificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SMSVerification::class);
    }
}
