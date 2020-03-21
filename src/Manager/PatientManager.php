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
        $patient->setGuid(Tools::generateGUID('DCT', 12));

        $this->em->persist($patient);
        $this->em->flush();
    }
}
