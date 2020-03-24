<?php

namespace App\Entity;

use App\Entity\Traits\ObjectMetaDataTrait;
use App\Entity\Traits\SoftDeleteableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Validator\constraints\CollectionSameItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Patient
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="patient")
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 * @UniqueEntity(
 *     fields={"phoneNumber"},
 *     message="This phoneNumber address is already used.",
 * )
 * @CollectionSameItem(
 *     collection="responses",
 *     errorPath="question",
 *     variable="question",
 *     message="duplicate response for question with id =%variable% "
 * )
 *
 */
class Patient
{
    // <editor-fold defaultstate="collapsed" desc="traits">

    use TimestampableTrait;
    use ObjectMetaDataTrait;

    // </editor-fold>

    //patient status

    const STATUS_ON_HOLD = 'ON_HOLD';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_CLOSED = 'CLOSED';

    //patient status

    const FLAG_STABLE = 'STABLE';
    const FLAG_SUSPECT = 'SUSPECT';
    const FLAG_URGENT = 'URGENT';


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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", length=4)
     *
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage="The zip code should have exactly {{ limit }} characters"
     * )
     */
    private $zipCode;

    /**
     * @var int The patient phone number
     *
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
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $emergencyStatus;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $flag;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="patient", orphanRemoval=true, cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $responses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
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

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(?int $zipCode): self
    {
        $this->zipCode = $zipCode;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public static function getStatusesList($manageable = false)
    {
        return $manageable ?  [self::STATUS_CLOSED] : [self::STATUS_ON_HOLD, self::STATUS_IN_PROGRESS, self::STATUS_CLOSED] ;
    }

    public function getEmergencyStatus(): ?string
    {
        return $this->emergencyStatus;
    }

    public function setEmergencyStatus(string $emergencyStatus): self
    {
        $this->emergencyStatus = $emergencyStatus;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public static function getFlagsList()
    {
        return [
            self::FLAG_STABLE,
            self::FLAG_SUSPECT,
            self::FLAG_URGENT,
        ];
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function getGroupedResponses()
    {

        $general = [];
        $antecedent = [];
        $symptoms = [];

        foreach ($this->responses as $response) {
            switch ($response->getQuestion()->getCategory()) {
                case Question::CATEGORY_GENERAL :
                    $general[] = [
                        "question" => $response->getQuestion(),
                        "response" => $response
                    ];
                    break;
                case Question::CATEGORY_ANTECEDENT :
                    $antecedent[] = [
                        "question" => $response->getQuestion(),
                        "response" => $response
                    ];
                    break;
                case Question::CATEGORY_SYMPTOMS :
                    $symptoms[] = [
                        "question" => $response->getQuestion(),
                        "response" => $response
                    ];
            }
        }

        return [
            "CATEGORY_GENERAL" => $general,
            "CATEGORY_ANTECEDENT" => $antecedent,
            "CATEGORY_SYMPTOMS" => $symptoms,
        ];
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setPatient($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getPatient() === $this) {
                $response->setPatient(null);
            }
        }

        return $this;
    }
}
