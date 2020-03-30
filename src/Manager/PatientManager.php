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
     * TTL for submitting cases
     */
    const RETRY_TTL = 21600; // 6 hours

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
    public function getAll(Doctor $doctor = null, $sorted = true)
    {
        $patients = $doctor ? $this->em->getRepository(Patient::class)->findAllCustom($doctor) : $this->em->getRepository(Patient::class)->findAll();

        if ($sorted) {
            $onHold = [];
            $inProgress = [];
            $closed = [];

            $typeOfStatusToCallMethod = $doctor && $doctor->isEmergencyDoctor() ? 'getEmergencyStatus' : 'getStatus';

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

    /**
     * Load patients by phoneNumber
     *
     * @param int $phoneNumber
     * @param string $orderBy
     * @return object[]|null
     */
    public function getByPhoneNUmber(int $phoneNumber, $orderBy = 'ASC')
    {
        return $this->em->getRepository(Patient::class)->findBy([
            'phoneNumber' => $phoneNumber
        ], ['createdAt' => 'DESC']);
    }

    public function update(Patient $patient)
    {
        if ($patient->getFlag() && $patient->getEmergencyStatus() == null) {
            //once the flag is set the patient case is closed for doctors and opened for emergency doctors
            $patient->setStatus(Patient::STATUS_CLOSED);
            if ($patient->getFlag() != Patient::FLAG_STABLE)
                $patient->setEmergencyStatus(Patient::STATUS_ON_HOLD);
        }

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

    /**
     * @param Patient $patient
     * @return string|null
     */
    public function canSubmit(Patient $patient): ?string
    {
        if (!$patients = $this->getByPhoneNUmber($patient->getPhoneNumber(), 'DESC'))
            return null;

        $reversedPatientsArray = array_reverse($patients);

        if (($lastCase = array_pop($reversedPatientsArray))->getCreatedAt()->getTimestamp() + self::RETRY_TTL > time()) {

            return date('H:i:s', $lastCase->getCreatedAt()->getTimestamp() + self::RETRY_TTL - (time() + 3600)); // adding 3600s to fix timezone;
        }

        return null;
    }

    public function revertAll()
    {
        foreach ($this->getAll(null, false) as $patient) {
            $patient
                ->setStatus(Patient::STATUS_ON_HOLD)
                ->setEmergencyStatus(null)
                ->setFlag(null)
                ->setDoctor(null)
                ->setDenounced(0)
                ->setMedicalStatus(null)
                ->setTestPositive(false);

            $this->update($patient);
        }

        $this->em->flush();
        $this->em->clear();
    }
}
