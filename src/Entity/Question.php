<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Question
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    //question types

    const TYPE_YES_OR_NO = 1;
    const TYPE_YES_OR_NO_NEUTRAL = 2;
    const TYPE_YES_OR_NO_NOT_APPLICABLE = 3;
    const TYPE_NORMAL = 4;

    //question categories

    const CATEGORY_GENERAL = 1;
    const CATEGORY_ANTECEDENT = 2;
    const CATEGORY_SYMPTOMS = 3;


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $frValue;

    /**
     * @ORM\Column(type="text")
     */
    private $arValue;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrValue(): ?string
    {
        return $this->frValue;
    }

    public function setFrValue(string $frValue): self
    {
        $this->frValue = $frValue;

        return $this;
    }

    public function getArValue(): ?string
    {
        return $this->arValue;
    }

    public function setArValue(string $arValue): self
    {
        $this->arValue = $arValue;

        return $this;
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

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }
}
