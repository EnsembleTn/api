<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    const TYPE_NORMAL = 2;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="question", orphanRemoval=true)
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

    public function getTypeAsString(): string
    {
        $type = '';

        switch ($this->type) {
            case 1 :
                return 'TYPE_YES_OR_NO';
                break;
            case 2 :
                return 'TYPE_NORMAL';
                break;
        }

        return $type;
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

    public function getCategoryAsString(): string
    {
        $category = '';

        switch ($this->category) {
            case 1 :
                return 'CATEGORY_GENERAL';
                break;
            case 2 :
                return 'CATEGORY_ANTECEDENT';
                break;
            case 3 :
                return 'CATEGORY_SYMPTOMS';
                break;
        }

        return $category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setQuestion($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getQuestion() === $this) {
                $response->setQuestion(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
