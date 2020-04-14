<?php

namespace App\Repository;

use App\Entity\Doctor;
use App\Util\Tools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DoctorRepository
 *
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[]    findAll()
 * @method Doctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class DoctorRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * DoctorRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    /**
     * Loading doctor
     *
     * @param string $username
     * @return mixed|UserInterface|null
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('doctor')
            ->where('doctor.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $role
     * @param int $limit
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByRole(string $role, int $limit)
    {
        $total = $totalRowsTable = $this->createQueryBuilder('doctor')
            ->select('count(doctor.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomIds = Tools::UniqueRandomNumbersWithinRange(1, $total, $limit);

        return $this->createQueryBuilder('doctor')
            ->where('doctor.id IN (:ids)')
            ->andWhere('doctor.roles LIKE :roles')
            ->setParameter('ids', $randomIds)
            ->setParameter('roles', '%"' . $role . '"%')
            ->getQuery()
            ->getResult();
    }
}
