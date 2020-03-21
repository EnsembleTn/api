<?php

namespace App\Action\Question;

use App\Action\BaseAction;
use App\Manager\QuestionManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Get
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Get extends BaseAction
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Get constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get questions list
     *
     * Get all questions
     *
     * @Rest\Get("/api/v1/question")
     *
     * @SWG\Response(response=200, description="Questions resources get success")
     *
     * @SWG\Tag(name="Questions")
     *
     * @Rest\View(serializerGroups={"question"})
     * @param Request $request
     * @param QuestionManager $qm
     * @return View
     */
    public function __invoke(Request $request, QuestionManager $qm)
    {

        $questions = $qm->getAll();

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Questions resources get success',
            [
                'questions' => $questions
            ]
        );
    }
}
