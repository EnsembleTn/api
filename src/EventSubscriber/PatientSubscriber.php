<?php

namespace App\EventSubscriber;

use App\ApiEvents\PatientEvents;
use App\Entity\Doctor;
use App\Event\PatientEvent;
use App\Manager\DoctorManager;
use App\Service\TTSMSing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PatientSubscriber
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientSubscriber implements EventSubscriberInterface
{
    const DOCTOR_NOTIFICATION_SMS_CONTENT = <<<DOCTOR_NOTIFICATION_SMS_CONTENT
L’équipe maabaadhna vous informe que vous avez un nouveau dossier d’un patient à traiter sur la plateforme. 
Notre devise est la rapidité de la prise en charge pour ne pas encombrer le Samu. 
Nous vous remercions pour votre collaboration et votre dévouement!
DOCTOR_NOTIFICATION_SMS_CONTENT;


    /**
     * @var DoctorManager
     */
    private $doctorManager;

    /**
     * @var TTSMSing
     */
    private $TTSMSing;

    /**
     * PatientSubscriber constructor.
     *
     * @param DoctorManager $doctorManager
     * @param TTSMSing $TTSMSing
     */
    public function __construct(DoctorManager $doctorManager, TTSMSing $TTSMSing)
    {
        $this->doctorManager = $doctorManager;
        $this->TTSMSing = $TTSMSing;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            PatientEvents::PATIENT_NEW => 'onPatientNew',
        );
    }

    /**
     * Actions to perform after new patient form is submitted successfully
     *
     * @param PatientEvent $patientEvent
     */
    public function onPatientNew(PatientEvent $patientEvent)
    {
        $patient = $patientEvent->getPatient();

        foreach ($this->doctorManager->getDoctorsByRole(Doctor::ROLE_DOCTOR) as $doctor) {
            $this->TTSMSing->send($doctor->getPhoneNumber(),
                self::DOCTOR_NOTIFICATION_SMS_CONTENT
            );
        }
    }
}