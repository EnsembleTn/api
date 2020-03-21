<?php

namespace App\Event;

use App\Entity\Doctor;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class DoctorEvent
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class DoctorEvent extends Event
{
    /**
     * @var Doctor
     */
    protected $doctor;

    /**
     * DoctorEvent constructor.
     *
     * @param Doctor $doctor
     */
    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
    }

    /**
     * @return Doctor
     */
    public function getDoctor(): Doctor
    {
        return $this->doctor;
    }
}