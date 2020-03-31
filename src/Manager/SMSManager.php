<?php

namespace App\Manager;

use App\Entity\VerificationSMS;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SMSManager
 *
 * @author Karray Ghassen <ghassen.karray@epfl.ch>
 */
class SMSManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SMSManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * create a new verification sms entry
     *
     * @param string $code
     * @param int $phone
     */
    public function newSMS(string $code, int $phone): void
    {
        $verificationSms = new VerificationSMS();
        $verificationSms->setPhoneNumber($phone);
        $verificationSms->setVerificationCode($code);

        $this->em->persist($verificationSms);
        $this->em->flush();
    }

    public function getByPhoneNumber(int $number): VerificationSMS
    {
        $sms = $this->em->getRepository(VerificationSMS::class)->findOneBy([
            "phoneNumber"=>$number
            ]);

        if($sms) {
            return $sms ;
        } else {
            throw new \Exception("No SMS found for this phone number");
        }
    }

    public function remove(VerificationSMS $sms): void
    {
        $this->em->remove($sms);
        $this->em->flush();
    }

}
