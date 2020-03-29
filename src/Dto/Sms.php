<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sms
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Sms
{
    /**
     * @var string content
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 620,
     *      minMessage="The sms content should have at least {{ limit }} characters",
     *      maxMessage="The sms content should have at most {{ limit }} characters"
     * )
     */
    private $content;

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}