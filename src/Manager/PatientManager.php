<?php

namespace App\Manager;

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
        $patient->setGuid(Tools::generateGUID('PAT', 12));

        // set status ON_HOLD
        $patient->setStatus(Patient::STATUS_ON_HOLD);

        $this->em->persist($patient);
        $this->em->flush();
    }

    /**
     * Load patients list
     * @param bool $sorted
     * @return array|object[]
     */
    public function getAll($sorted = true)
    {
        $patients = $this->em->getRepository(Patient::class)->findAll();

        if ($sorted) {
            $onHold = [];
            $inProgress = [];
            $closed = [];

            foreach ($patients as $patient) {
                switch ($patient->getStatus()) {
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
        $this->em->persist($patient);
        $this->em->flush();
    }
}
