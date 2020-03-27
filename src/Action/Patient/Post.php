<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\ApiEvents\GenericEvents;
use App\Entity\Patient;
use App\Event\FileUploadEvent;
use App\Form\PatientType;
use App\Manager\PatientManager;
use App\Manager\QuestionManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Response as QuestionResponse;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
     * @param EventDispatcherInterface $dispatcher
     * @param ParameterBagInterface $parameters
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, PatientManager $pm, QuestionManager $qm, EventDispatcherInterface $dispatcher, ParameterBagInterface $parameters)
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

        // dispatch file upload event
        $dispatcher->dispatch(new FileUploadEvent(
            $patient,
            $form->get('audio')->getData(),
            $parameters->get('upload_files_to_server') === 'true' ? true : false
        ), GenericEvents::FILE_UPLOAD);


        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Patient resource add success'
        );
    }
}
