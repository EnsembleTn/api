<?php

namespace App\Repository;

use App\Entity\Informer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class InformerRepository
 *
 * @method Informer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Informer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Informer[]    findAll()
 * @method Informer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class InformerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Informer::class);
    }
}
