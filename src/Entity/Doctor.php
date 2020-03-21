<?php

namespace App\Entity;

use App\Entity\Traits\ObjectMetaDataTrait;
use App\Entity\Traits\SoftDeleteableTrait;
use App\Entity\Traits\TimestampableTrait;
use DateTime;
use DateTimeInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Doctor
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="doctor")
 * @ORM\Entity(repositoryClass="App\Repository\DoctorRepository")
 *
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email address is already used.",
 * )
 * @UniqueEntity(
 *     fields={"phoneNumber"},
 *     message="This phone number is already used.",
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Doctor implements UserInterface
{

    //doctor categories

    const CATEGORY_SENIOR = 1;
    const CATEGORY_JUNIOR = 2;

    // <editor-fold defaultstate="collapsed" desc="traits">

    use TimestampableTrait;
    use SoftDeleteableTrait;
    use ObjectMetaDataTrait;

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="attributes">

    /**
     * @var int The doctor Id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Global unique identifier
     *
     * @ORM\Column(name="guid", type="string", length=255, nullable=false)
     */
    private $guid;

    /**
     * @var string The doctor email
     *
     * @ORM\Column(type="string", length=190, unique=true)
     *
     * @Assert\Email
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @var string The doctor encoded password
     *
     * @ORM\Column(type="text")
     */
    private $password;

    /**
     * @var string The doctor plain password
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(
     *      min = 8,
     *      minMessage="The password must be at least {{ limit }} characters long",
     * )
     */
    private $plainPassword;

    /**
     * @var boolean The doctor account status
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var string The token used for account validation
     *
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @var DateTime The last datetime at which the doctor requested for a new password
     *t
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @var string The doctor first name
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      minMessage="The firstname must be at least {{ limit }} characters long",
     * )
     */
    private $firstName;

    /**
     * @var string The doctor last name
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      minMessage="The lastname must be at least {{ limit }} characters long",
     * )
     */
    private $lastName;

    /**
     * @var string The doctor address
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private $address;


    /**
     * @var int The doctor phone number
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
     * @var array The doctor roles
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = ['ROLE_DOCTOR'];

    /**
     * @ORM\Column(type="text")
     */
    private $region;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="relations">

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="methods">

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Returns the username used to authenticate the doctor.
     *
     * @return string|null The username
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns the encoded password used to authenticate the doctor.
     *
     * @return string|null The password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * Removes sensitive data from the doctor.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Returns checking whether the doctor account is active or not ?
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $status)
    {
        $this->active = $status;
        return $this;
    }

    /**
     * Returns confirmation token used for registration or password resetting
     *
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function getPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
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

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * Checks whether the password reset request has expired or not ?
     *
     * @param int $ttl Requests older than this many seconds will be considered expired
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * Returns the roles granted to the doctor.
     *
     * @return array The doctor roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_DOCTOR';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        $this->roles[] = $role;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }


    // </editor-fold>
}
