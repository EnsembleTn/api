<?php

namespace App\Manager;

use App\Entity\Informer;
use App\Entity\Patient;
use App\Entity\SMSVerification;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SMSVerificationManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class SMSVerificationManager
{
    /**
     * TTL for sending Verification SMS
     */
    const RETRY_TTL = 900; // 15 minutes

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SMSVerificationManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Save sms verification
     *
     * @param SMSVerification $SMSVerification
     */
    public function save(SMSVerification $SMSVerification): void
    {
        $this->em->persist($SMSVerification);
        $this->em->flush();
    }

    public function markAsUsed(SMSVerification $smsVerification, $object)
    {
        $smsVerification->setStatus(SMSVerification::STATUS_USED);

        if ($object instanceof Patient)
            $smsVerification->setPatient($object);
        elseif ($object instanceof Informer)
            $smsVerification->setInformer($object);

        $this->em->flush();
    }

    /**
     * @param int $phoneNumber
     * @param int $type
     * @return string|null
     */
    public function canSendVerificationSend(int $phoneNumber, int $type): ?string
    {
        if (!$smsVerification = $this->getLastSMSVerification($phoneNumber, $type))
            return null;

        if ($smsVerification->getCreatedAt()->getTimestamp() + self::RETRY_TTL > time()) {

            return date('H:i:s', $smsVerification->getCreatedAt()->getTimestamp() + self::RETRY_TTL - (time() + 3600)); // adding 3600s to fix timezone;
        }

        return null;
    }

    public function getLastSMSVerification(int $phoneNumber, int $type): ?SMSVerification
    {
        /** @var SMSVerification[] $smsVerification */
        $smsVerification = $this->em->getRepository(SMSVerification::class)->findBy([
            'phoneNumber' => $phoneNumber,
            'status' => SMSVerification::STATUS_UNUSED,
            'type' => $type
        ], ['createdAt' => 'DESC'], 1);

        return count($smsVerification) ? $smsVerification[0] : null;
    }

}
