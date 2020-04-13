<?php

namespace App\ApiEvents;

/**
 * Class PatientEvents
 * 
 * this class contains all events related to patient
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
final class PatientEvents
{
    /**
     * The PATIENT_NEW event occurs when a new patient form is submitted successfully.
     *
     * @Event("App\Event\PatientEvent")
     */
    const PATIENT_NEW = 'patient.new';
}