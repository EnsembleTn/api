<?php

namespace App\Event;

use App\Entity\Patient;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class PatientEvent
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientEvent extends Event
{
    /**
     * @var Patient
     */
    protected $patient;

    /**
     * PatientEvent constructor.
     *
     * @param Patient $patient
     */
    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    /**
     * @return Patient
     */
    public function getPatient(): Patient
    {
        return $this->patient;
    }
}