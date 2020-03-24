<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Manager\PatientManager;
use App\Manager\QuestionManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Response as QuestionResponse;

/**
 * Class Post
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Post extends BaseAction
{
    /**
     * Post Patient
     *
     * Save new Patient Resource
     *
     * @Rest\Post("/api/v1/patient")
     *
     * @SWG\Parameter(
     *     name="patient",
     *     in="body",
     *     required=true,
     *     @Model(type=PatientType::class)
     * )
     *
     * @SWG\Response(response=200, description="Patient resource add success")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=403, description="Patient with phone number %phone number% can submit again in : %remaining time%")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View()
     * @param Request $request
     * @param PatientManager $pm
     * @param QuestionManager $qm
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, PatientManager $pm, QuestionManager $qm)
    {
        $patient = new Patient();

        // attach responses to patient
        $questions = $qm->getAll(false);
        foreach ($questions as $question) {
            $response = new QuestionResponse();
            $response->setQuestion($question);

            $patient->addResponse($response);
        }

        $form = $this->createForm(PatientType::class, $patient);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        if ($timeToSubmitAgain = $pm->canSubmit($patient)) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                "Patient with phone number {$patient->getPhoneNumber()} can submit again in : {$timeToSubmitAgain}"
            );
        }

        $pm->save($patient);

        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Patient resource add success'
        );
    }
}
