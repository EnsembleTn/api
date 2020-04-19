<?php

namespace App\Entity;

use App\Entity\Interfaces\Uploadable;
use App\Entity\Traits\ObjectMetaDataTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Validator\constraints\CollectionSameItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Patient
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="patient")
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 * @CollectionSameItem(
 *     collection="responses",
 *     errorPath="question",
 *     variable="question",
 *     message="duplicate response for question with id =%variable% "
 * )
 */
class Patient implements Uploadable
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

    //patient gender

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    //patient medical status

    const MEDICAL_STATUS_TO_BE_TESTED = 'TO_BE_TESTED';
    const MEDICAL_STATUS_NOT_TO_BE_TESTED = 'NOT_TO_BE_TESTED';
    const MEDICAL_STATUS_TESTED = 'TESTED';

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
     *
     * @Assert\NotBlank(groups={"add-by-doctor"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(groups={"add-by-doctor"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="integer", length=4, nullable=true)
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
     * @Assert\NotBlank(groups={"add-by-doctor"})
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      exactMessage="The phone number should have exactly {{ limit }} characters",
     *      groups={"add-by-doctor"}
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     *
     * @Assert\NotBlank(groups={"add-by-doctor"})
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $doctorSms;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $emergencyDoctorSms;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $medicalStatus;

    /**
     * @ORM\Column(type="boolean")
     */
    private $testPositive = false;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $emergencyStatus;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $flag;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $denounced = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Doctor", inversedBy="doctorPatients")
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     */
    private $doctor;

    /**
     * @ORM\ManyToOne(targetEntity="Doctor", inversedBy="emergencyDoctorPatients")
     * @ORM\JoinColumn(name="emergency_doctor_id", referencedColumnName="id")
     */
    private $emergencyDoctor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="patient", orphanRemoval=true, cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $responses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

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

    public function getFullname()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public static function getGendersList()
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
        ];
    }

    public function getDoctorSms(): ?string
    {
        return $this->doctorSms;
    }

    public function setDoctorSms(string $sms): self
    {
        $this->doctorSms = $sms;

        return $this;
    }

    public function getEmergencyDoctorSms(): ?string
    {
        return $this->emergencyDoctorSms;
    }

    public function setEmergencySms(string $sms): self
    {
        $this->emergencyDoctorSms = $sms;

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

    public function getMedicalStatus(): ?string
    {
        return $this->medicalStatus;
    }

    public function setMedicalStatus(?string $medicalStatus): self
    {
        $this->medicalStatus = $medicalStatus;

        return $this;
    }

    public static function getMedicalStatusesList()
    {
        return [
            self::MEDICAL_STATUS_NOT_TO_BE_TESTED,
            self::MEDICAL_STATUS_TO_BE_TESTED,
            self::MEDICAL_STATUS_TESTED,
        ];
    }

    public function isTestPositive(): bool
    {
        return $this->testPositive;
    }

    public function isTestPositiveAsString(): string
    {
        return $this->testPositive ? 'YES' : 'NO';
    }

    public function setTestPositive(bool $testPositive): self
    {
        $this->testPositive = $testPositive;

        return $this;
    }


    public static function getStatusesList($manageable = false)
    {
        return $manageable ? [self::STATUS_CLOSED] : [self::STATUS_ON_HOLD, self::STATUS_IN_PROGRESS, self::STATUS_CLOSED];
    }

    public function getEmergencyStatus(): ?string
    {
        return $this->emergencyStatus;
    }

    public function setEmergencyStatus(?string $emergencyStatus): self
    {
        $this->emergencyStatus = $emergencyStatus;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(?string $flag): self
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
     * @return bool
     */
    public function isDenounced(): bool
    {
        return $this->denounced == 1;
    }

    public function isDenouncedAsString(): string
    {
        return $this->denounced ? 'YES' : 'NO';
    }

    /**
     * @param int $denounced
     * @return Patient
     */
    public function setDenounced($denounced): self
    {
        $this->denounced = $denounced;

        return $this;
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

    /**
     * @return Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * @param Doctor $doctor
     * @return Patient
     */
    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * @return Doctor
     */
    public function getEmergencyDoctor()
    {
        return $this->emergencyDoctor;
    }

    /**
     * @param Doctor $emergencyDoctor
     * @return Patient
     */
    public function setEmergencyDoctor(?Doctor $emergencyDoctor): self
    {
        $this->emergencyDoctor = $emergencyDoctor;

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
        return $this->getFullname();
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

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
}
