<?php

namespace App\Manager;

use App\Dto\ChangePasswordRequest;
use App\Dto\ResetPasswordRequest;
use App\Entity\Doctor;
use App\Util\Tools;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DoctorManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class DoctorManager
{
    /**
     * TTL for password request
     */
    const RETRY_TTL = 7200;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DoctorManager constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $em
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * Doctor registration business logic
     *
     * @param Doctor $doctor
     * @throws Exception
     */
    public function registerDoctor(Doctor $doctor): void
    {
        // encode password
        $this->encodePassword($doctor);

        // generate confirmation token
        $doctor->setConfirmationToken(Tools::generateToken());

        // generate GUID
        $doctor->setGuid(Tools::generateGUID('DCT', 12));

        // persist doctor in DB
        $this->em->persist($doctor);
        $this->em->flush();
    }

    /**
     * Get Current doctor from provided jwt token
     *
     * @return Doctor|null
     */
    public function getCurrentDoctor(): ?Doctor
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof TokenInterface) {

            /** @var Doctor $doctor */
            $doctor = $token->getUser();
            return $doctor;

        } else {
            return null;
        }
    }

    /**
     * @param Doctor|null $doctor
     */
    public function updateDoctor(Doctor $doctor): void
    {
        $this->em->merge($doctor);
        $this->em->flush();
    }

    /**
     * Load doctor by confirmation token
     *
     * @param string $confirmationToken
     * @return Doctor
     */
    public function getDoctorByConfirmationToken(string $confirmationToken): ?Doctor
    {
        $doctor = $this->em->getRepository(Doctor::class)->findOneBy([
            'confirmationToken' => $confirmationToken
        ]);

        if (!$doctor instanceof Doctor)
            return null;

        return $doctor;
    }

    /**
     * Load doctor by email
     *
     * @param string $email
     * @return Doctor
     */
    public function getDoctorByEmail(string $email): ?Doctor
    {
        $doctor = $this->em->getRepository(Doctor::class)->findOneBy([
            'email' => $email
        ]);

        if (!$doctor instanceof Doctor)
            return null;

        return $doctor;
    }

    /**
     * ConfirmRegistration doctor account
     *
     * @param Doctor $doctor
     */
    public function confirmAccount(Doctor $doctor): void
    {
        $doctor->setConfirmationToken(null);
        $doctor->setActive(true);

        $this->em->merge($doctor);
        $this->em->flush();
    }

    /**
     * Change doctor password
     *
     * @param Doctor $doctor
     * @param ChangePasswordRequest $changePasswordRequest
     * @return bool
     */
    public function changePassword(Doctor $doctor, ChangePasswordRequest $changePasswordRequest): ?bool
    {
        if (!$this->checkPassword($doctor, $changePasswordRequest->getOldPassword())) {
            return false;
        }

        if ($this->checkPassword($doctor, $changePasswordRequest->getNewPassword()) == true) {
            return null;
        }

        $doctor->setPlainPassword($changePasswordRequest->getNewPassword());
        $this->encodePassword($doctor);

        $this->em->merge($doctor);
        $this->em->flush();

        return true;
    }

    /**
     * Doctor request for new password
     *
     * @param Doctor $doctor
     * @return bool
     * @throws Exception
     */
    public function requestPassword(Doctor $doctor): bool
    {
        if (!$doctor->isPasswordRequestNonExpired(self::RETRY_TTL)) {
            $doctor->setConfirmationToken(Tools::generateToken());
            $doctor->setPasswordRequestedAt(new DateTime());

            $this->em->merge($doctor);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function resetPassword(Doctor $doctor, ResetPasswordRequest $resetPasswordRequest): void
    {
        $doctor->setPlainPassword($resetPasswordRequest->getNewPassword());
        $this->encodePassword($doctor);

        $doctor->setConfirmationToken(null);

        $this->em->merge($doctor);
        $this->em->flush();
    }

    /**
     * Encode plain password
     *
     * @param Doctor $doctor
     */
    private function encodePassword(Doctor $doctor): void
    {
        $doctor->setPassword($this->passwordEncoder->encodePassword($doctor, $doctor->getPlainPassword()));

        // erase sensitive data
    }

    /**
     * Check doctor password
     *
     * @param $doctor
     * @param string $password
     * @return bool
     */
    private function checkPassword(Doctor $doctor, string $password): bool
    {
        return $this->passwordEncoder->isPasswordValid($doctor, $password);
    }

}
