<?php

namespace App\Manager;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Util\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class PatientManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PatientManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * Save patient
     *
     * @param Patient $patient
     * @throws Exception
     */
    public function save(Patient $patient): void
    {
        // generate GUID
        $patient->setGuid(Tools::generateGUID('PAT', 8));

        // set status ON_HOLD
        $patient->setStatus(Patient::STATUS_ON_HOLD);

        $this->em->persist($patient);
        $this->em->flush();
    }

    /**
     * Load patients list
     * @param bool $sorted
     * @param Doctor $doctor
     * @return array|object[]
     */
    public function getAll(Doctor $doctor, $sorted = true)
    {
        $patients = $this->em->getRepository(Patient::class)->findAllCustom($doctor);

        if ($sorted) {
            $onHold = [];
            $inProgress = [];
            $closed = [];

            $typeOfStatusToCallMethod = $doctor->isEmergencyDoctor() ? 'getEmergencyStatus' : 'getStatus';

            foreach ($patients as $patient) {


                switch (call_user_func([$patient, $typeOfStatusToCallMethod])) {
                    case Patient::STATUS_ON_HOLD :
                        $onHold[] = $patient;
                        break;
                    case Patient::STATUS_IN_PROGRESS :
                        $inProgress[] = $patient;
                        break;
                    case Patient::STATUS_CLOSED :
                        $closed[] = $patient;
                        break;
                }
            }

            return [
                "ON_HOLD" => [
                    'count' => count($onHold),
                    'patients' => $onHold
                ],
                "IN_PROGRESS" => [
                    'count' => count($inProgress),
                    'patients' => $inProgress
                ],
                "CLOSED" => [
                    'count' => count($closed),
                    'patients' => $closed
                ]
            ];
        }

        return $patients;
    }

    /**
     * Load patient by guid
     *
     * @param string $guid
     * @return object|null
     */
    public function getByGuid(string $guid)
    {
        return $this->em->getRepository(Patient::class)->findOneBy([
            'guid' => $guid
        ]);
    }

    public function update(Patient $patient)
    {
        if ($patient->getFlag() && $patient->getEmergencyStatus() == null) {
            //once the flag is set the patient case is closed for doctors and opened for emergency doctors
            $patient->setStatus(Patient::STATUS_CLOSED);
            $patient->setEmergencyStatus(Patient::STATUS_ON_HOLD);
        }

        $this->em->persist($patient);
        $this->em->flush();
    }

    /**
     * get first patient in queue depending on doctor role
     * @param Doctor $doctor
     * @return Patient|null
     */
    public function treat(Doctor $doctor): ?Patient
    {
        return $this->em->getRepository(Patient::class)->first($doctor);
    }
}
