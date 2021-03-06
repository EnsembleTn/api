<?php

namespace App\EventSubscriber;

use App\ApiEvents\PatientEvents;
use App\Entity\Doctor;
use App\Event\PatientEvent;
use App\Manager\DoctorManager;
use App\Service\TTSMSing;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PatientSubscriber
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientSubscriber implements EventSubscriberInterface
{
    const DOCTOR_NOTIFICATION_SMS_CONTENT = <<<DOCTOR_NOTIFICATION_SMS_CONTENT
L'équipe maabaadhna vous informe que vous avez un nouveau dossier d'un patient à traiter sur la plateforme. 
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PatientSubscriber constructor.
     *
     * @param DoctorManager $doctorManager
     * @param TTSMSing $TTSMSing
     * @param LoggerInterface $logger
     */
    public function __construct(DoctorManager $doctorManager, TTSMSing $TTSMSing, LoggerInterface $logger)
    {
        $this->doctorManager = $doctorManager;
        $this->TTSMSing = $TTSMSing;
        $this->logger = $logger;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            PatientEvents::PATIENT_NEW => 'onPatientNew',
            PatientEvents::PATIENT_EMERGENCY_CASE => 'onPatientEmergencyCase',
        );
    }

    /**
     * Actions to perform after new patient form is submitted successfully
     *
     * @param PatientEvent $patientEvent
     */
    public function onPatientNew(PatientEvent $patientEvent)
    {
        foreach ($this->doctorManager->getRandomDoctorsByRole(Doctor::ROLE_DOCTOR, 7) as $doctor) {
            $this->TTSMSing->send($doctor->getPhoneNumber(), self::DOCTOR_NOTIFICATION_SMS_CONTENT);

            $this->logger->alert("Notification SMS sent to {$doctor->getFullname()} , phone Number : {$doctor->getPhoneNumber()}", ['DOCTOR_NOTIFICATION']);
        }
    }

    /**
     * Actions to perform after patient case is treated as an emergency
     *
     * @param PatientEvent $patientEvent
     */
    public function onPatientEmergencyCase(PatientEvent $patientEvent)
    {
        $patient = $patientEvent->getPatient();

        foreach ($this->doctorManager->getRandomDoctorsByRole(Doctor::ROLE_EMERGENCY_DOCTOR, 7) as $doctor) {
            if (in_array($patient->getCity(), $this->doctorManager->getEmergencyDoctorControlledCities($doctor)))
                $this->TTSMSing->send($doctor->getPhoneNumber(), self::DOCTOR_NOTIFICATION_SMS_CONTENT);

            $this->logger->alert("Notification SMS sent to {$doctor->getFullname()} , phone Number : {$doctor->getPhoneNumber()}", ['EMERGENCY_DOCTOR_NOTIFICATION']);
        }
    }
}