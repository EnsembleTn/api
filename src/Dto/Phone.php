<?php

namespace App\Dto;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Phone
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Phone
{
    /**
     * @var int phone number
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      exactMessage="The phone number should have exactly {{ limit }} characters"
     * )
     */
    private $number;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotBlank
     */
    private $type;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}