<?php

namespace App\Repository;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Manager\DoctorManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class PatientRepository
 *
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientRepository extends ServiceEntityRepository
{
    /**
     * @var DoctorManager
     */
    private $dm;

    public function __construct(ManagerRegistry $registry, DoctorManager $dm)
    {
        parent::__construct($registry, Patient::class);

        $this->dm = $dm;
    }

    public function findAllCustom(Doctor $doctor)
    {
        return $doctor->isEmergencyDoctor() ?
            $this->findBy([
                'flag' => [Patient::FLAG_SUSPECT, Patient::FLAG_URGENT],
                'city' => $this->dm->getEmergencyDoctorControlledCities($doctor)],
                ['createdAt' => 'ASC']) :
            $this->findBy(['denounced' => 0], ['createdAt' => 'ASC']);
    }

    public function first(Doctor $doctor)
    {
        if ($doctor->isEmergencyDoctor()) {
            $arrayResult = $this->findBy([
                'emergencyStatus' => Patient::STATUS_ON_HOLD,
                'flag' => [Patient::FLAG_SUSPECT, Patient::FLAG_URGENT],
                'city' => $this->dm->getEmergencyDoctorControlledCities($doctor)],
                ['createdAt' => 'ASC'], 1);
        } else {
            $arrayResult = $this->findBy(['status' => Patient::STATUS_ON_HOLD, 'flag' => null], ['createdAt' => 'ASC'], 1);
        }

        return $arrayResult ? $arrayResult[0] : null;
    }
}
