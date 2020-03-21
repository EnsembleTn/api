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
    public function savePatient(Patient $patient): void
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
     */
    public function getAll()
    {
        return $this->em->getRepository(Patient::class)->findAll();
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
