<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\ApiEvents\GenericEvents;
use App\ApiEvents\PatientEvents;
use App\Entity\Patient;
use App\Event\FileUploadEvent;
use App\Event\PatientEvent;
use App\Form\PatientType;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class AddPatient
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Add extends BaseAction
{
    /**
     * Post Patient
     *
     * Add new Patient Resource <br>
     *
     * **This endpoint is used only by doctors**
     *
     * @Rest\Post("/api/v1/secured/add-patient")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     *
     * @SWG\Parameter(
     *     name="patient",
     *     in="body",
     *     required=true,
     *     @Model(type=PatientType::class),
     * )
     *
     * @SWG\Response(response=200, description="Patient resource add success")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View(serializerGroups={"patient"})
     * @param Request $request
     * @param PatientManager $pm
     * @param DoctorManager $dm
     * @param EventDispatcherInterface $dispatcher
     * @param ParameterBagInterface $parameters
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, PatientManager $pm, DoctorManager $dm, EventDispatcherInterface $dispatcher, ParameterBagInterface $parameters)
    {
        if ($dm->getCurrentDoctor()->isEmergencyDoctor()) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                'Emergency doctor can\'t access this endpoint.'
            );
        }

        $patient = new Patient();

        $form = $this->createForm(
            PatientType::class, $patient,
            [
                'allow_extra_fields' => true,
                'validation_groups' => ['add-by-doctor']
            ]
        );
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        $pm->save($patient, true);

        // dispatch file upload event
        $dispatcher->dispatch(new FileUploadEvent(
            $patient,
            $form->get('audio')->getData(),
            $parameters->get('upload_files_to_server') === 'true'
        ), GenericEvents::FILE_UPLOAD);

        // notify emergency doctor
        if ($patient->getFlag() != Patient::FLAG_STABLE)
            // dispatch new patient event
            $dispatcher->dispatch(new PatientEvent($patient), PatientEvents::PATIENT_EMERGENCY_CASE);

        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Patient resource add success',
            [
                'patient' => $patient
            ]
        );
    }
}
