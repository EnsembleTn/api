<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResetPasswordRequest
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class ResetPasswordRequest
{

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