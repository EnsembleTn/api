<?php

namespace App\Manager;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class QuestionManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class QuestionManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * QuestionManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * Load questions list
     */
    public function getAll()
    {
        return $this->em->getRepository(Question::class)->findAll();
    }
}
