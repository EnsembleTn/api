<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SMSVerification
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="sms_verification")
 * @ORM\Entity(repositoryClass="App\Repository\SMSVerificationRepository")
 */
class SMSVerification
{
    // status

    const STATUS_UNUSED = 0;
    const STATUS_USED = 1;

    // pin code constraints

    const PIN_CODE_MAX_LENGTH = 6;

    // types

    const TYPE_PATIENT = 1;
    const TYPE_INFORMER = 2;

    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $pinCode;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="sMSVerifications")
     */
    private $patient;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Informer", inversedBy="smsVerifications")
     */
    private $informer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPinCode(): ?int
    {
        return $this->pinCode;
    }

    public function setPinCode(int $pinCode): self
    {
        $this->pinCode = $pinCode;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getInformer(): ?Informer
    {
        return $this->informer;
    }

    public function setInformer(?Informer $informer): self
    {
        $this->informer = $informer;

        return $this;
    }

    public static function getTypes()
    {
        return [
            'PATIENT' => self::TYPE_PATIENT,
            'INFORMER' => self::TYPE_INFORMER
        ];
    }
}
