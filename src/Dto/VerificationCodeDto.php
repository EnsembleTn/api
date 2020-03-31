<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class VerificationCodeDto
 *
 * @author Karray Ghassen <ghassen.karray@epfl.ch>
 */
class VerificationCodeDto
{
    /**
     * @var string content
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      max = 6,
     *      exactMessage="The verification code should have exactly {{ limit }} characters"
     * )
     */
    private $code;

    /**
     * @var int number
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      exactMessage="The verification code should have exactly {{ limit }} characters"
     * )
     */
    private $number;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $content
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}