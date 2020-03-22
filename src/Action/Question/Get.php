<?php

namespace App\Action\Question;

use App\Action\BaseAction;
use App\Manager\QuestionManager;
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
     * Get questions list
     *
     * Get all questions <br>
     * Field **type** : <br><br> 1 : TYPE_YES_OR_NO (oui,non) <br> 2 : TYPE_YES_OR_NO_NEUTRAL (oui, non, je ne sais pas) <br> 3 : TYPE_YES_OR_NO_NOT_APPLICABLE (oui, non, non applicable) <br> 4 : TYPE_NORMAL ( text input )
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
