<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePasswordRequest
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class ChangePasswordRequest
{
    /**
     * @var string The doctor old password
     *
     * @Assert\NotBlank
     */
    private $oldPassword;

    /**
     * @var string The doctor new password
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage="The new password must be at least {{ limit }} characters long",
     * )
     */
    private $newPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
        return $this;
    }

}