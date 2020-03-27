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
     * @param bool $sorted
     * @return Question[]|object[]
     */
    public function getAll($sorted = true)
    {
        $questions = $this->em->getRepository(Question::class)->findAll();

        if ($sorted) {
            $general = [];
            $antecedent = [];
            $symptoms = [];

            foreach ($questions as $question) {
                switch ($question->getCategory()) {
                    case Question::CATEGORY_GENERAL :
                        $general[] = $question;
                        break;
                    case Question::CATEGORY_ANTECEDENT :
                        $antecedent[] = $question;
                        break;
                    case Question::CATEGORY_SYMPTOMS :
                        $symptoms[] = $question;
                }
            }

            return [
                "CATEGORY_GENERAL" => $general,
                "CATEGORY_ANTECEDENT" => $antecedent,
                "CATEGORY_SYMPTOMS" => $symptoms,
            ];
        }

        return $questions;
    }
}
