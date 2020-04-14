<?php

namespace App\Action\Question;

use App\Action\BaseAction;
use App\Manager\QuestionManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
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
     * Field **type** : <br><br> 1 : TYPE_YES_OR_NO (oui,non) <br> 2 : TYPE_NORMAL (text input )<br> 3 : TYPE_NUMBER (numeric input )<br>
     * Field **category** : <br><br> 1 : CATEGORY_GENERAL <br> 2 : CATEGORY_ANTECEDENT <br> 3 : CATEGORY_SYMPTOMS
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
    public function __invoke(Request $request, QuestionManager $qm, LoggerInterface $logger)
    {

        $logger->info('TESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSST');

        $questions = $qm->getAll(true);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Questions resources get success',
            [
                'questions' => $questions
            ]
        );
    }
}
