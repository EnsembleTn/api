<?php

namespace App\Entity;

use App\Entity\Interfaces\Uploadable;
use App\Entity\Traits\ObjectMetaDataTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Informer
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="informer")
 * @ORM\Entity(repositoryClass="App\Repository\InformerRepository")
 */
class Informer implements Uploadable
{
    use TimestampableTrait;
    use ObjectMetaDataTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guid;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      exactMessage="The phone number should have exactly {{ limit }} characters"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage="The zip code should have exactly {{ limit }} characters"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $culpableFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $culpableLastName;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private $culpableAddress;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SMSVerification", mappedBy="informer")
     */
    private $smsVerifications;

    public function __construct()
    {
        $this->smsVerifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
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

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCulpableFirstName(): ?string
    {
        return $this->culpableFirstName;
    }

    public function setCulpableFirstName(string $culpableFirstName): self
    {
        $this->culpableFirstName = $culpableFirstName;

        return $this;
    }

    public function getCulpableLastName(): ?string
    {
        return $this->culpableLastName;
    }

    public function setCulpableLastName(string $culpableLastName): self
    {
        $this->culpableLastName = $culpableLastName;

        return $this;
    }

    public function getCulpableAddress(): ?string
    {
        return $this->culpableAddress;
    }

    public function setCulpableAddress(string $culpableAddress): self
    {
        $this->culpableAddress = $culpableAddress;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getUploadPath(): string
    {
        return sprintf('%s/%s/', strtolower($this->getClass()), date('Ymd'));
    }

    public function __toString()
    {
        return (string)$this->firstName;
    }

    /**
     * @return Collection|SMSVerification[]
     */
    public function getSmsVerifications(): Collection
    {
        return $this->smsVerifications;
    }

    public function addSmsVerification(SMSVerification $smsVerification): self
    {
        if (!$this->smsVerifications->contains($smsVerification)) {
            $this->smsVerifications[] = $smsVerification;
            $smsVerification->setInformer($this);
        }

        return $this;
    }

    public function removeSmsVerification(SMSVerification $smsVerification): self
    {
        if ($this->smsVerifications->contains($smsVerification)) {
            $this->smsVerifications->removeElement($smsVerification);
            // set the owning side to null (unless already changed)
            if ($smsVerification->getInformer() === $this) {
                $smsVerification->setInformer(null);
            }
        }

        return $this;
    }
}
