<?php

namespace App\Permissions;

use App\Entity\Patient;
use Exception;

/**
 * Trait DoctorPermissions
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
trait DoctorPermissions
{
    /**
     * @param Patient $patient
     * @throws Exception
     */
    public function canUpdatePatient(Patient $patient)
    {
        if ($this->getCurrentDoctor()->isEmergencyDoctor()) {
            if (!$patient->getFlag() or $patient->getFlag() == Patient::FLAG_STABLE)
                throw new Exception('Emergency doctor can\'t update unflagged or flagged with STABLE patient.');
            if ($patient->getEmergencyStatus() == Patient::STATUS_CLOSED)
                throw new Exception('Closed patient case can\'t be updated anymore.');
            if ($patient->getEmergencyStatus() == Patient::STATUS_ON_HOLD)
                throw new Exception('Patient case status was returned to ON_HOLD by the cron due to inactivity.', 403);
        } else {
            if ($patient->getStatus() == Patient::STATUS_CLOSED)
                throw new Exception('Closed patient case can\'t be updated anymore.');
            if ($patient->getStatus() == Patient::STATUS_ON_HOLD)
                throw new Exception('Patient case status was returned to ON_HOLD by the cron due to inactivity.', 403);
            if ($patient->isDenounced())
                throw new Exception('Denounced patient can\'t be updated.');
        }
    }

    /**
     * @param Patient $patient
     * @throws Exception
     */
    public function canDenounce(Patient $patient)
    {
        if ($this->getCurrentDoctor()->isEmergencyDoctor())
            throw new Exception('Emergency Doctor can\'t denounce patients');
        else {
            if ($patient->isDenounced())
                throw new Exception('This patient is already denounced');
            if ($patient->getStatus() != Patient::STATUS_IN_PROGRESS)
                throw new Exception('Patient case status field must be IN_PROGRESS so that patient can be denounced.');
        }
    }

    /**
     * @param Patient $patient
     * @throws Exception
     */
    public function canSendSms(Patient $patient)
    {
        if ($this->getCurrentDoctor()->isEmergencyDoctor() && !$patient->getFlag())
            throw new Exception('Emergency Doctor can\'t send sms to untreated patient');
    }

    /**
     * @param Patient $patient
     * @throws Exception
     */
    public function canUpdatePatientMedicalStatus(Patient $patient)
    {
        if ($patient->getMedicalStatus() == Patient::MEDICAL_STATUS_TESTED)
            throw new Exception('Patient is already tested.');
    }

    /**
     * @param Patient $patient
     * @throws Exception
     */
    public function canUpdatePatientTestResult(Patient $patient)
    {
        if (!$this->getCurrentDoctor()->isEmergencyDoctor())
            throw new Exception('Only Emergency Doctor can update patient testPositive field');
        else {
            if ($patient->getMedicalStatus() != Patient::MEDICAL_STATUS_TESTED)
                throw new Exception('Patient should be tested first to set the result.');
        }
    }
}